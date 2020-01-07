<?php
use yii\widgets\ListView;

$dataProvider = $searchModel->search([]);

?>

<?= ListView::widget([
	'dataProvider' => $dataProvider,
	'itemView' => '_article',
]) ?>