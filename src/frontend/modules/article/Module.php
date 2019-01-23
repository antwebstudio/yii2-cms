<?php

namespace frontend\modules\article;

/**
 * article module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'frontend\modules\article\controllers';
    public $formModelArticleAttributeToBeShow = null;
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
            ],
        ],
    ];

    public $landingUrl;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
