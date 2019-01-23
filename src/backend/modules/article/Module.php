<?php

namespace backend\modules\article;

/**
 * article module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'backend\modules\article\controllers';
    public $formModelArticleAttributeToBeShow = null;
    public $formModelArticleCategoryAttributeToBeShow = null;
    public $defaultFormArticleCategoryModelAttributeToBeShow = [
        'title',
        'subtitle',
        'slug',
        'body',
        'parent_id',
        'thumbnail',
        'icon',
    ];
    public $defaultFormArticleModelAttributeToBeShow = [
        'title',
        'subtitle',
        'slug',
        'body',
        'view',
        'category_id',
        'thumbnail',
        'access_type',
    ];
    public $model = [
        'default' => [
            'model' => [
                'article' => [   
                    'class' => 'common\modules\article\models\Article',
                    'status' => 0,
                ],
                'articleCategory' => [   
                    'class' => 'common\modules\article\models\ArticleCategory',
                    'status' => 0,
                ],
            ],
        ],
    ];
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
