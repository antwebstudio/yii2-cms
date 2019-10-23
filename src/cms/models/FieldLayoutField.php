<?php

namespace ant\cms\models;

use Yii;

/**
 * This is the model class for table "cms_field_layout_field".
 *
 * @property integer $id
 * @property integer $layout_id
 * @property integer $tab_id
 * @property integer $field_id
 * @property integer $required
 * @property integer $sequence
 * @property string $created_date
 * @property string $last_updated
 *
 * @property CmsField $field
 * @property CmsFieldLayout $layout
 * @property CmsFieldLayoutTab $tab
 */
class FieldLayoutField extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_field_layout_field}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['layout_id', 'tab_id', 'field_id'], 'required'],
            [['layout_id', 'tab_id', 'field_id', 'required', 'sequence'], 'integer'],
            [['created_date', 'last_updated'], 'safe'],
            [['layout_id', 'field_id'], 'unique', 'targetAttribute' => ['layout_id', 'field_id'], 'message' => 'The combination of Layout ID and Field ID has already been taken.'],
            [['field_id'], 'exist', 'skipOnError' => true, 'targetClass' => Field::className(), 'targetAttribute' => ['field_id' => 'id']],
            [['layout_id'], 'exist', 'skipOnError' => true, 'targetClass' => FieldLayout::className(), 'targetAttribute' => ['layout_id' => 'id']],
            [['tab_id'], 'exist', 'skipOnError' => true, 'targetClass' => FieldLayoutTab::className(), 'targetAttribute' => ['tab_id' => 'id']],
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
            'tab_id' => 'Tab ID',
            'field_id' => 'Field ID',
            'required' => 'Required',
            'sequence' => 'Sequence',
            'created_date' => 'Created Date',
            'last_updated' => 'Last Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getField()
    {
        return $this->hasOne(Field::className(), ['id' => 'field_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLayout()
    {
        return $this->hasOne(FieldLayout::className(), ['id' => 'layout_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTab()
    {
        return $this->hasOne(FieldLayoutTab::className(), ['id' => 'tab_id']);
    }
}
