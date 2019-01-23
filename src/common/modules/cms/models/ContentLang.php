<?php

namespace common\modules\cms\models;

use Yii;

/**
 * This is the model class for table "cms_content_lang".
 *
 * @property integer $id
 * @property integer $app_content_id
 * @property string $lang_id
 * @property string $data
 * @property string $name
 * @property string $slug
 * @property integer $last_updated_by
 * @property string $created_date
 * @property string $last_updated
 *
 * @property CmsContentData $appContent
 */
class ContentLang extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_content_lang}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_content_id', 'lang_id'], 'required'],
            [['app_content_id', 'last_updated_by'], 'integer'],
            [['data'], 'string'],
            [['created_date', 'last_updated'], 'safe'],
            [['lang_id'], 'string', 'max' => 6],
            [['name', 'slug'], 'string', 'max' => 255],
            [['app_content_id'], 'exist', 'skipOnError' => true, 'targetClass' => CmsContentData::className(), 'targetAttribute' => ['app_content_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_content_id' => 'App Content ID',
            'lang_id' => 'Lang ID',
            'data' => 'Data',
            'name' => 'Name',
            'slug' => 'Slug',
            'last_updated_by' => 'Last Updated By',
            'created_date' => 'Created Date',
            'last_updated' => 'Last Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAppContent()
    {
        return $this->hasOne(CmsContentData::className(), ['id' => 'app_content_id']);
    }
}
