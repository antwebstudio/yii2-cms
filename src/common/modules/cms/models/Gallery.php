<?php

namespace common\modules\cms\models;

use Yii;

/**
 * This is the model class for table "cms_gallery".
 *
 * @property integer $id
 * @property integer $content_id
 * @property string $created_date
 * @property string $last_updated
 */
class Gallery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_gallery}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content_id'], 'required'],
            [['content_id'], 'integer'],
            [['created_date', 'last_updated'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content_id' => 'Content ID',
            'created_date' => 'Created Date',
            'last_updated' => 'Last Updated',
        ];
    }
}
