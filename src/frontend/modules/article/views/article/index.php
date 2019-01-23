<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\BaseStringHelper;
use yii\widgets\LinkSorter;
$controllerClassName = $this->context->className();
$this->blocks['content-header'] = '';
/* @var $this yii\web\View */
/* @var $searchModel common\modules\article\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Articles');
$this->params['breadcrumbs'][] = $this->title;
?>
<style type="text/css">

/* styles for '...' */ 
.block-with-text {
  /* hide text if it more than N lines  */
  overflow: hidden;
  /* for set '...' in absolute position */
  position: relative; 
  /* use this value to count block height */
  line-height: 1.2em;
  /* max-height = line-height (1.2) * lines max number (3) */
  max-height: 4.6em; 
  /* fix problem when last visible word doesn't adjoin right side  */
  text-align: justify;  
  /* place for '...' */
  margin-right: -1em;
  padding-right: 1em;
}
/* create the ... */
.block-with-text:before {
  /* points in the end */
  content: '...';
  /* absolute position */
  position: absolute;
  /* set position to right bottom corner of block */
  right: 0;
  bottom: 0;
}
/* hide ... if we have text, which is less than or equal to max lines */
.block-with-text:after {
  /* points in the end */
  content: '';
  /* absolute position */
  position: absolute;
  /* set position to right bottom corner of text */
  right: 0;
  /* set width and height */
  width: 1em;
  height: 1em;
  margin-top: 0.2em;
  /* bg color = bg color under block */
  background: white;
}
</style>
<div class="article-index">
	<?php
        $form = ActiveForm::begin(['id' => 'contact-form',
			'layout' => 'inline',
			'options' => [
				'enctype' => 'multipart/form-data',
                'data-pjax' => '',
            ],
        ]);
    ?>
		<?= $form->field($searchModel, 'searchStringArticleIndex')->textInput([
			'placeholder' => 'TYPE YOUR KEY WORDS HERE'
		]); ?>
		<?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>

    <div class="articleGroup">
		<?= $message ?>
		
        <?= \yii\widgets\ListView::widget([
			'dataProvider' => $dataProvider,
			'itemOptions' => ['class' => 'item'],
			'itemView' => '_article',
			'viewParams' => [
			],
			'options' => [
			  'class' => 'inline-block-child',
			],
			// 'pager' => [
			//   'class' => \kop\y2sp\ScrollPager::className(),
			//   'overflowContainer' => '.article-index',
			//   'container' => '.articleGroup',
			//   'triggerOffset' => 3,
			//   'eventOnScroll' => YII_DEBUG ? new \yii\web\JsExpression(
			// 	"function(){ console.log('scrolled'); 

			//   }") : '',
			//   'triggerText' => '',
			//   'noneLeftTemplate' => '',
			// ],  
			'layout' => "\n{items} {pager}",
			'emptyText' => ''
		]) ?>
		</ul>
		<span class="pagination"></span>
    </div>

    <?php ActiveForm::end(); ?>

</div>
