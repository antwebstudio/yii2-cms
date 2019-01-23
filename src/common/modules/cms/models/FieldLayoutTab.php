<?php

namespace common\modules\cms\models;

use Yii;

/**
 * This is the model class for table "cms_field_layout_tab".
 *
 * @property integer $id
 * @property integer $layout_id
 * @property string $name
 * @property integer $sequence
 * @property string $created_date
 * @property string $last_updated
 *
 * @property CmsFieldLayoutField[] $cmsFieldLayoutFields
 * @property CmsFieldLayout $layout
 */
class FieldLayoutTab extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_field_layout_tab}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['layout_id', 'name'], 'required'],
            [['layout_id', 'sequence'], 'integer'],
            [['created_date', 'last_updated'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['layout_id'], 'exist', 'skipOnError' => true, 'targetClass' => FieldLayout::className(), 'targetAttribute' => ['layout_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'layout_id' => 'Layout ID',
            'name' => 'Name',
            'sequence' => 'Sequence',
            'created_date' => 'Created Date',
            'last_updated' => 'Last Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFieldLayoutFields()
    {
        return $this->hasMany(FieldLayoutField::className(), ['tab_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFieldLayout()
    {
        return $this->hasOne(FieldLayout::className(), ['id' => 'layout_id']);
    }
}
