<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\rbac\Permission;
use common\modules\category\models\Category;
use yii\helpers\ArrayHelper;
$controllerClassName = $this->context->className();
/* @var $this yii\web\View */
/* @var $searchModel common\modules\article\models\ArticleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('app', 'Articles');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="article-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Article'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{summary}\n<div class=\"table-responsive\">{items}</div>\n{pager}",
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'id',
            // 'slug',
            'title',
            [
                'label' => 'Category',
                'attribute' => 'categories',
                'value' => function($model) {
                    return $model->getString();
                },
                'filter' => ArrayHelper::map(Category::findAll(['type' => 'article']), 'id', 'slug'),
            ],
            // 'subtitle',
            // 'body:ntext',
            [
                'label' => 'Updated At',
                'attribute' => 'updated_at',
                'value' => function($model) {
                    return date('Y-m-d H:i:s', $model->updated_at);
                }
            ],
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
            [
                'label' => 'Access Type',
                'attribute' => 'access_type',
                'value' => function ($model) {
                    return $model->access_type;
                }
            ],

            [
                'class' => 'yii\grid\ActionColumn',
                'visibleButtons' => [
                    'delete' => Yii::$app->user->can(common\rbac\Permission::of('delete', $controllerClassName
                        )->name),
                ],
            ],
        ],
    ]); ?>
</div>
