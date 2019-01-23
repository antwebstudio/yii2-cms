<?php

namespace common\modules\cms\models;

use Yii;

/**
 * This is the model class for table "cms_tag".
 *
 * @property integer $id
 * @property integer $field_id
 * @property string $name
 * @property integer $counter
 * @property string $created_date
 * @property string $last_updated
 */
class Tag extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_tag}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['field_id', 'name', 'counter'], 'required'],
            [['field_id', 'counter'], 'integer'],
            [['created_date', 'last_updated'], 'safe'],
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'field_id' => 'Field ID',
            'name' => 'Name',
            'counter' => 'Counter',
            'created_date' => 'Created Date',
            'last_updated' => 'Last Updated',
        ];
    }
}
