<?php
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
?>
<div class="cms-default-index">
    <h1><?= Yii::t('cms', 'Entry') ?></h1>
	<?= Html::a(Yii::t('cms', 'New {type}', ['type' => $entryType->name]), ['/cms/entry/create', 'type' => $entryType->handle], [
		'class' => 'btn btn-primary',
	]) ?>
	<?= GridView::widget([
		'dataProvider' => $dataProvider,
		'columns' => [
			[
				'attribute' => 'contentData.name',
			],
			[
				'class' => ActionColumn::className(),
				// you may configure additional properties here
				/*'buttons' => [
					'update' => function ($url, $model, $key) {
						return Html::a('Update', $url);
					},
				],*/
			],
		],
	]) ?>
</div>
