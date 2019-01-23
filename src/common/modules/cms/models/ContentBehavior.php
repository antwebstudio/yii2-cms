<?php

namespace common\modules\cms\models;

use Yii;

/**
 * This is the model class for table "cms_content_behavior".
 *
 * @property integer $id
 * @property integer $content_type_id
 * @property string $name
 * @property string $class
 * @property string $settings
 * @property string $created_date
 * @property string $last_updated
 * @property integer $apply_for_admin
 *
 * @property CmsEntryType $contentType
 */
class ContentBehavior extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_content_behavior}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content_type_id', 'name', 'class'], 'required'],
            [['content_type_id', 'apply_for_admin'], 'integer'],
            [['settings'], 'string'],
            [['created_date', 'last_updated'], 'safe'],
            [['name'], 'string', 'max' => 100],
            [['class'], 'string', 'max' => 255],
            [['content_type_id', 'name'], 'unique', 'targetAttribute' => ['content_type_id', 'name'], 'message' => 'The combination of Content Type ID and Name has already been taken.'],
            [['content_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => CmsEntryType::className(), 'targetAttribute' => ['content_type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'content_type_id' => 'Content Type ID',
            'name' => 'Name',
            'class' => 'Class',
            'settings' => 'Settings',
            'created_date' => 'Created Date',
            'last_updated' => 'Last Updated',
            'apply_for_admin' => 'Apply For Admin',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContentType()
    {
        return $this->hasOne(CmsEntryType::className(), ['id' => 'content_type_id']);
    }
}
