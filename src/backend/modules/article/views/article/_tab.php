<?php 
use yii\bootstrap\Nav;
$items = [];
foreach (Yii::$app->getModule('category')->categoryTypeSelection as $key => $value) {
    $items[] = 
        [
            'label' => Yii::t('user', $value),
            'url' => ['/article/article/create', 'type' => $key],
            'active' => $key == $type,
        ];
}
?>
<?= Nav::widget([
    'options' => [
        'class' => 'nav-tabs',
        'style' => 'margin-bottom: 15px'
    ],
    'items' => $items
]) ?>