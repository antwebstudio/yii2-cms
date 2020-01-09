<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel ant\comment\models\CommentSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Comments';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comment-index">

    <?php Pjax::begin() ?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
			'author_name',
            //'model_id',
            //'model_class_id',
            //'title',
            'body:ntext',
			[
				'format' => 'raw',
				'attribute' => 'model',
				'value' => function($model) {
					return Html::a($model->model->name, Yii::$app->urlManagerFrontEnd->createUrl($model->model->route), ['target' => '_blank', 'data-pjax' => '0']);
				}
			],
            //'status',
            [
				'attribute' => 'created_at',
				'filter' => \kartik\daterange\DateRangePicker::widget([
					'model' => $searchModel,
					'attribute' => 'created_at_range',
					'convertFormat' => true,
					'hideInput' => true,
					'pluginOptions' => [
						'locale' => [
							'format' => 'Y-m-d'
						],
						'allowClear' => true
					],
				]),
			],
			
            //'created_by',
            //'updated_at',
            //'updated_by',

            [
				'class' => 'ant\grid\ActionColumn',
				'template' => '{delete}',
			],
        ],
    ]); ?>

    <?php Pjax::end() ?>

</div>
