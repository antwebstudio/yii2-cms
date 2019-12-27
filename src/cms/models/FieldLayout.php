<?php

namespace ant\cms\models;

use Yii;

/**
 * This is the model class for table "cms_field_layout".
 *
 * @property integer $id
 * @property string $type
 * @property string $created_date
 * @property string $last_updated
 *
 * @property CmsFieldLayoutField[] $cmsFieldLayoutFields
 * @property CmsField[] $fields
 * @property CmsFieldLayoutTab[] $cmsFieldLayoutTabs
 */
class FieldLayout extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_field_layout}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type'], 'required'],
            [['created_date', 'last_updated'], 'safe'],
            [['type'], 'string', 'max' => 150],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'created_date' => 'Created Date',
            'last_updated' => 'Last Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCmsFieldLayoutFields()
    {
        return $this->hasMany(FieldLayoutField::className(), ['layout_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFields()
    {
        return $this->hasMany(Field::className(), ['id' => 'field_id'])->viaTable(FieldLayoutField::tableName(), ['layout_id' => 'id']);
    }
	
	public function getTabs() {
        return $this->hasMany(FieldLayoutTab::className(), ['layout_id' => 'id']);
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCmsFieldLayoutTabs()
    {
		if (YII_DEBUG) throw new \Exception('DEPRECATED'); // 2019-12-27
        return $this->hasMany(FieldLayoutTab::className(), ['layout_id' => 'id']);
    }
}
