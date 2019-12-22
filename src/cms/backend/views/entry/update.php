<?php
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
?>
<div class="cms-entry-update">
    <h1><?= Yii::t('app', 'Update {model}', ['model' => Yii::t('cms', 'Entry')]) ?></h1>
	<?= $this->render('_form', ['model' => $model]) ?>
</div>
