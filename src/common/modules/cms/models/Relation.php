<?php

namespace common\modules\cms\models;

use Yii;

/**
 * This is the model class for table "cms_relation".
 *
 * @property integer $id
 * @property integer $field_id
 * @property integer $source_id
 * @property integer $target_id
 * @property integer $sequence
 * @property string $created_date
 * @property string $last_updated
 *
 * @property CmsContentData $source
 */
class Relation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_relation}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['field_id', 'source_id', 'target_id'], 'required'],
            [['field_id', 'source_id', 'target_id', 'sequence'], 'integer'],
            [['created_date', 'last_updated'], 'safe'],
            [['source_id'], 'exist', 'skipOnError' => true, 'targetClass' => ContentData::className(), 'targetAttribute' => ['source_id' => 'id']],
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
            'source_id' => 'Source ID',
            'target_id' => 'Target ID',
            'sequence' => 'Sequence',
            'created_date' => 'Created Date',
            'last_updated' => 'Last Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSource()
    {
        return $this->hasOne(ContentData::className(), ['id' => 'source_id']);
    }
}
