<?php

namespace common\modules\cms\models;

use Yii;

/**
 * This is the model class for table "cms_search_index".
 *
 * @property integer $model_id
 * @property string $attribute
 * @property integer $weight
 * @property string $keywords
 */
class SearchIndex extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_search_index}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_id', 'attribute'], 'required'],
            [['model_id', 'weight'], 'integer'],
            [['keywords'], 'string'],
            [['attribute'], 'string', 'max' => 25],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'model_id' => 'Model ID',
            'attribute' => 'Attribute',
            'weight' => 'Weight',
            'keywords' => 'Keywords',
        ];
    }
}
