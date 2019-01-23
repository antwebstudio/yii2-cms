<?php

namespace common\modules\cms\models;

use Yii;

/**
 * This is the model class for table "cms_entry_type".
 *
 * @property integer $id
 * @property integer $app_id
 * @property integer $section_id
 * @property integer $field_layout_id
 * @property string $name
 * @property string $handle
 * @property integer $has_title_field
 * @property string $title_label
 * @property string $title_format
 * @property integer $sequence
 * @property string $created_date
 * @property string $last_updated
 * @property string $content_type
 * @property string $permission_role_id
 * @property integer $permission_default
 * @property integer $structure_id
 * @property integer $is_single
 * @property integer $max_entry_per_user
 * @property string $default_values
 * @property string $tostring_template
 */
class EntryType extends \yii\db\ActiveRecord
{
    //protected $_fieldIds; // Should not be set be default, else will break the code for $this->createFieldLayoutField
    //protected $_fields; // Should be not set be default, else will break the code for $this->getFields
    
	public function behaviors() {
		return [
			['class' => \common\modules\cms\behaviors\EntryTypeBehavior::className()],
		];
	}
	
    public static function tableName()
    {
        return '{{%cms_entry_type}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id', 'section_id', 'field_layout_id', 'has_title_field', 'sequence', 'permission_default', 'structure_id', 'is_single', 'max_entry_per_user'], 'integer'],
            //[['section_id', 'name', 'handle', 'content_type', 'permission_default'], 'required'],
            [['created_date', 'last_updated'], 'safe'],
            [['permission_role_id', 'default_values'/*, 'tostring_template'*/], 'string'],
            [['name', 'handle', 'title_label', 'title_format'], 'string', 'max' => 255],
            [['content_type'], 'string', 'max' => 50],
            [['app_id', 'name', 'section_id'], 'unique', 'targetAttribute' => ['app_id', 'name', 'section_id'], 'message' => 'The combination of App ID, Section ID and Name has already been taken.'],
            [['app_id', 'handle', 'section_id'], 'unique', 'targetAttribute' => ['app_id', 'handle', 'section_id'], 'message' => 'The combination of App ID, Section ID and Handle has already been taken.'],
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
            'section_id' => 'Section ID',
            'field_layout_id' => 'Field Layout ID',
            'name' => 'Name',
            'handle' => 'Handle',
            'has_title_field' => 'Has Title Field',
            'title_label' => 'Title Label',
            'title_format' => 'Title Format',
            'sequence' => 'Sequence',
            'created_date' => 'Created Date',
            'last_updated' => 'Last Updated',
            'content_type' => 'Content Type',
            'permission_role_id' => 'Permission Role ID',
            'permission_default' => 'Permission Default',
            'structure_id' => 'Structure ID',
            'is_single' => 'Is Single',
            'max_entry_per_user' => 'Max Entry Per User',
            'default_values' => 'Default Values',
            'tostring_template' => 'Tostring Template',
        ];
    }
	
	public function getFieldLayout() {
		return $this->hasOne(FieldLayout::className(), ['id' => 'field_layout_id']);
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCmsContentBehaviors()
    {
        return $this->hasMany(CmsContentBehavior::className(), ['content_type_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCmsContentDatas()
    {
        return $this->hasMany(ContentData::className(), ['type_id' => 'id']);
    }
}
