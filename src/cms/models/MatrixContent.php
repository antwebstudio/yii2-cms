<?php

namespace ant\cms\models;

use Yii;

/**
 * This is the model class for table "cms_matrix_content".
 *
 * @property integer $id
 * @property integer $model_id
 * @property integer $field_id
 * @property integer $sequence
 * @property string $created_date
 * @property string $last_updated
 *
 * @property CmsContentData $model
 */
class MatrixContent extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_matrix_content}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_id', 'field_id'], 'required'],
            [['model_id', 'field_id', 'sequence'], 'integer'],
            [['created_date', 'last_updated'], 'safe'],
            [['model_id'], 'exist', 'skipOnError' => true, 'targetClass' => CmsContentData::className(), 'targetAttribute' => ['model_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model_id' => 'Model ID',
            'field_id' => 'Field ID',
            'sequence' => 'Sequence',
            'created_date' => 'Created Date',
            'last_updated' => 'Last Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModel()
    {
        return $this->hasOne(CmsContentData::className(), ['id' => 'model_id']);
    }
}
