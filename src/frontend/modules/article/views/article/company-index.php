<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel ant\article\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Articles');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php // Html::a(Yii::t('app', 'Create Article'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'slug',
            'title',
            'subtitle',
            //'body:ntext',
            // 'view',
            // 'category_id',
            // 'thumbnail_base_url:url',
            // 'thumbnail_path',
            // 'author_id',
            // 'updater_id',
            // 'status',
            // 'published_at',
            // 'created_at',
            // 'updated_at',
            // 'access_type',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
