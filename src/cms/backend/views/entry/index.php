<?php
use yii\grid\GridView;
use ant\grid\ActionColumn;
use yii\helpers\Html;
?>
<div class="cms-default-index">
    <h1><?= Yii::t('cms', 'Entry') ?></h1>
	<?= Html::a(Yii::t('cms', 'New {type}', ['type' => $entryType->name]), ['/cms/backend/entry/create', 'type' => $entryType->handle], [
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
			],
		],
	]) ?>
</div>
