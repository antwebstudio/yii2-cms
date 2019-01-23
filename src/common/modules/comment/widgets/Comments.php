<?php
namespace common\modules\comment\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use common\helpers\TemplateHelper;

class Comments extends \yii\base\Widget
{

	protected $comments;

	public $model;
	
	public $layout = '{header} {title} {body} {form} {meta} <hr/>';

	/**
	 * @var array the options for the container tag of the widget
	 */
	public $options = [];

	/**
	 * @var array|\Closure the options for the comment-containers or a closure retuning an array
	 * of options (format: function($model))
	 */
	public $commentOptions = ['class' => 'media'];

	/**
	 * @var bool whether or not to render newest or oldest first (default: true)
	 */
	public $newestFirst = true;

	/**
	 * @var string optional view-alias which will be used to render comments if set.
	 * Within the view the comment-model is available via `$model` variable and the comment
	 * options via `$options`.
	 */
	public $view;

	/**
	 * @var string the title tag to use within a single comment
	 */
	public $commentTitleTag = 'h4';

	public $attributesSettings = [];

	/**
	 * @inheritdoc
	 *
	 * @throws \yii\base\InvalidConfigException
	 */
	public function init()
	{
		//assert proper model is set
		if (!isset($this->model)) {
			throw new InvalidConfigException('Setting the model property of type ActiveRecord is mandatory');
		}
		//ComponentConfig::hasBehavior($this->model, CommentsBehavior::className(), true);

		//load the comments
		$this->loadComments();

		//prepare options
		Html::addCssClass($this->options, 'widget-comments');
		Html::addCssClass($this->commentOptions, 'comment');
	}

	/**
	 * Loads the comments
	 */
	protected function loadComments()
	{
		$this->comments = $this->model->getComments()->all();
	}

	/**
	 * @inheritdoc
	 */
	public function run()
	{
		echo Html::beginTag('div', $this->options);

		if (count($this->comments) === 0) {
			echo Html::tag('span', Yii::t('app', 'No comments yet!'), ['class' => 'no-comments']);
		} else {
			foreach ($this->comments as $comment) {
				if ($this->view === null) {
					echo $this->renderComment($comment);
				} else {
					echo $this->render($this->view, ['model' => $comment, 'options' => $this->commentOptions]);
				}
			}
		}

		echo Html::endTag('div');
	}
	
	protected function renderAttribute($comment, $attribute) {
		if (isset($this->attributesSettings[$attribute])) {
			$attribute = ArrayHelper::merge($this->attributesSettings[$attribute], [
				'attribute' => $attribute,
			]);
		}
		return TemplateHelper::renderAttribute($comment, $attribute);
		return $comment->{$attribute};
		$author = $this->authorCallback === null ? $comment->created_by : call_user_func($this->authorCallback, $comment->created_by);
	}
	
	public function renderTitle($comment) {
		if (!empty($comment->title)) {
			$titleOptions = ['data-comment-title' => '', 'class'=>'comment-title'];
			if (false) Html::addCssClass($titleOptions, 'media-heading');
			return Html::tag($this->commentTitleTag, $this->renderAttribute($comment, 'title'), $titleOptions);
		}
	}
	
	public function renderContent($comment) {
		return Html::tag('div', $this->renderAttribute($comment, 'body'), ['data-comment-content' => '', 'class'=>'comment-content']);
	}
	
	public function renderMeta($comment) {
		$html = Html::beginTag('dl', ['class'=>'comment-meta']);
		
		$html .= Html::tag('dt', Yii::t('app', 'Created'));
		$html .= Html::tag('dd', Yii::$app->formatter->asDatetime($comment->created_at));
		
		if (!empty($comment->updated_at) && $comment->updated_at != $comment->created_at) {
			$html .= Html::tag('dt', Yii::t('app', 'Updated'));
			$html .= Html::tag('dd', Yii::$app->formatter->asDatetime($comment->updated_at));
		}
		
		if (!empty($comment->created_by)) {
			$html .= Html::tag('dt', Yii::t('app', 'Author'));
			$html .= Html::tag('dd', $this->renderAttribute($comment, 'created_by'));
		}
		
		$html .= Html::endTag('dl');
		
		return $html;
	}
	
	public function renderHeader($comment) {
		return Html::tag('div', TemplateHelper::renderTemplate('{update} {delete}', [
			'update' => function($model) {
				if ($model->created_by == Yii::$app->user->id) {
					return Html::a('Edit', 'javascript:;', ['data-comment-edit' => '']);
				}
			}, 
			'delete' => function($model) {				
				if ($model->created_by == Yii::$app->user->id) {
					return Html::a('Delete', ['/comment/comment/delete', 'id' => $model->id], [
						'data-method' => 'post', 
						'data-comment-delete' => '',
						'data-confirm' => 'Are you sure to delete this comment? ',
					]);
				}
			},
		], [$comment]), ['class' => 'pull-right']);
	}
	
	public function renderForm($comment) {
		$this->getView()->registerJs('
			(function($) {
				$("[data-comment-id]").each(function(event) {
					var $content = $(this).find("[data-comment-content]");
					var $title = $(this).find("[data-comment-title]");
					var $form = $(this).find("[data-comment-form]");
					
					$form.hide();
					
					$(this).find("[data-comment-edit]").click(function() {
						if ($content.is(":visible")) {
							$content.hide();
							$title.hide();
							$form.show();
						} else {
							$content.show();
							$title.show();
							$form.hide();
						}
					});
				});
			})(jQuery);
		');
		
		return Html::tag('div', $this->render('comment-form', [
			'model' => $comment,
		]), ['data-comment-form' => '']);
	}

	protected function renderComment($comment)
	{
		$html = '';
		
		if ($this->commentOptions instanceof \Closure) {
			$options = call_user_func($this->commentOptions, $comment);
		} else {
			$options = $this->commentOptions;
		}
		$options = ArrayHelper::merge($options, ['data-comment-id' => $comment->id]);
		Html::addCssClass($options, 'media');

		$wrapperOptions = ['class' => 'comment-wrapper'];
		Html::addCssClass($wrapperOptions, 'media-body');
		
		$html .= Html::beginTag('div', $options);

		// body
		$html .= Html::beginTag('div', $wrapperOptions);

		$html .= TemplateHelper::renderTemplate($this->layout, [
			'title' => [$this, 'renderTitle'],
			'body' => [$this, 'renderContent'],
			'meta' => [$this, 'renderMeta'],
			'form' => [$this, 'renderForm'],
			'header' => [$this, 'renderHeader'],
		], [$comment]);

		$html .= Html::endTag('div');
		
		$html .= Html::endTag('div');
		
		return $html;
	}

}
