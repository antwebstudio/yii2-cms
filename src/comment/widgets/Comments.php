<?php
namespace ant\comment\widgets;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use ant\helpers\TemplateHelper;
use ant\models\ModelClass;
use ant\comment\models\Comment;

class Comments extends \yii\base\Widget
{

	protected $comments;

	public $model;
	
	public $layout = '{comments} {form}';
	
	public $commentLayout = '{header} {title} {body} {form} {meta} <hr/>';
	
	public $commentView;

	/**
	 * @var array the options for the container tag of the widget
	 */
	public $options = [];
	
	public $formOptions = [];

	/**
	 * @var array|\Closure the options for the comment-containers or a closure retuning an array
	 * of options (format: function($model))
	 */
	public $editLinkOptions = ['class' => 'btn btn-sm'];
	
	public $deleteLinkOptions = ['class' => 'btn btn-sm'];
	
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
		//$this->loadComments();

		//prepare options
		Html::addCssClass($this->options, 'widget-comments');
		Html::addCssClass($this->commentOptions, 'comment');
	}

	/**
	 * Loads the comments
	 */
	/*protected function loadComments()
	{
		$this->comments = $this->model->getComments()->all();
	}*/

	/**
	 * @inheritdoc
	 */
	public function run()
	{
		echo Html::beginTag('div', $this->options);
		
		echo TemplateHelper::renderTemplate($this->layout, [
			'comments' => [$this, 'renderComments'],
			'form' => [$this, 'renderForm'],
		], []);

		echo Html::endTag('div');
	}
	
	public function renderComments() {
		if (isset($this->commentView)) {
			return \yii\widgets\ListView::widget([
				'layout' => '{items} {pager}',
				'viewParams' => ['widget' => $this],
				'itemView' => $this->commentView,
				'dataProvider' => new \yii\data\ActiveDataProvider(['query' => $this->model->getComments()]),
			]);
		} else {
			echo Html::beginTag('div', []);

			if ($this->model->getComments()->count() === 0) {
				echo Html::tag('span', Yii::t('app', 'No comments yet!'), ['class' => 'no-comments']);
			} else {
				foreach ($this->model->getComments()->all() as $comment) {
					if ($this->view === null) {
						echo $this->renderComment($comment);
					} else {
						echo $this->render($this->view, ['model' => $comment, 'options' => $this->commentOptions]);
					}
				}
			}

			echo Html::endTag('div');
		}
	}
	
	public function renderForm() {
		$comment = new Comment;
		$comment->model_class_id = ModelClass::getClassId($this->model);
		$comment->model_id = $this->model->id;
		return $this->renderCommentForm($comment);
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
	
	public function renderCommentTitle($comment) {
		if (!empty($comment->title)) {
			$titleOptions = ['data-comment-title' => '', 'class'=>'comment-title'];
			if (false) Html::addCssClass($titleOptions, 'media-heading');
			return Html::tag($this->commentTitleTag, $this->renderAttribute($comment, 'title'), $titleOptions);
		}
	}
	
	public function renderCommentContent($comment) {
		return Html::tag('div', $this->renderAttribute($comment, 'body'), ['data-comment-content' => '', 'class'=>'comment-content']);
	}
	
	public function renderCommentMeta($comment) {
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
	
	public function renderCommentHeader($comment) {
		return Html::tag('div', TemplateHelper::renderTemplate('{update} {delete}', [
			'update' => function($model) {
				if ($model->created_by == Yii::$app->user->id) {
					$options = $this->editLinkOptions;
					$options['data-comment-edit'] = '';
					return Html::a('Edit', 'javascript:;', $options);
				}
			}, 
			'delete' => function($model) {				
				if ($model->created_by == Yii::$app->user->id) {
					return Html::a('Delete', ['/comment/comment/delete', 'id' => $model->id], array_merge([
						'data-method' => 'post', 
						'data-comment-delete' => '',
						'data-confirm' => 'Are you sure to delete this comment? ',
					], $this->deleteLinkOptions));
				}
			},
		], [$comment]), ['class' => 'pull-right', 'data-comment-buttons' => '']);
	}
	
	public function renderCommentForm($comment) {
		$this->getView()->registerJs('
			(function($) {
				$("[data-comment-id]").each(function(event) {
					var $content = $(this).find("[data-comment-content]");
					var $title = $(this).find("[data-comment-title]");
					var $form = $(this).find("[data-comment-form]");
					var $buttons = $(this).find("[data-comment-buttons]");
					
					$form.hide();
					
					$(this).find("[data-comment-edit]").click(function() {
						if ($content.is(":visible")) {
							$content.hide();
							$title.hide();
							$form.show();
							$buttons.hide();
						} else {
							$content.show();
							$title.show();
							$form.hide();
							$buttons.show();
						}
					});
					
					$(this).find("[data-comment-cancel]").click(function() {
						$content.show();
						$title.show();
						$form.hide();
						$buttons.show();
					});
						
				});
			})(jQuery);
		');
		
		$formOptions = $this->formOptions;
		$formOptions['action'] = $comment->isNewRecord ? ['/comment/comment/create'] : ['/comment/comment/update', 'id' => $comment->id];
		
		return Html::tag('div', $this->render('comment-form', [
			'model' => $comment,
			'formOptions' => $formOptions,
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

		$html .= TemplateHelper::renderTemplate($this->commentLayout, [
			'title' => [$this, 'renderCommentTitle'],
			'body' => [$this, 'renderCommentContent'],
			'meta' => [$this, 'renderCommentMeta'],
			'form' => [$this, 'renderCommentForm'],
			'header' => [$this, 'renderCommentHeader'],
		], [$comment]);

		$html .= Html::endTag('div');
		
		$html .= Html::endTag('div');
		
		return $html;
	}

}
