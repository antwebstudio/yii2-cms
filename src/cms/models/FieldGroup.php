<?php

namespace ant\cms\models;

use Yii;

/**
 * This is the model class for table "cms_field_group".
 *
 * @property integer $id
 * @property integer $app_id
 * @property string $name
 * @property string $created_date
 * @property string $last_updated
 *
 * @property CmsField[] $cmsFields
 */
class FieldGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_field_group}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id'], 'integer'],
            [['name'], 'required'],
            [['created_date', 'last_updated'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['app_id', 'name'], 'unique', 'targetAttribute' => ['app_id', 'name'], 'message' => 'The combination of App ID and Name has already been taken.'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_id' => 'App ID',
            'name' => 'Name',
            'created_date' => 'Created Date',
            'last_updated' => 'Last Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCmsFields()
    {
        return $this->hasMany(CmsField::className(), ['group_id' => 'id']);
    }
}
