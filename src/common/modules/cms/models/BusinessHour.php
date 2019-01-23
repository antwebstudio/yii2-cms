<?php

namespace common\modules\cms\models;

use Yii;

/**
 * This is the model class for table "business_hour".
 *
 * @property integer $id
 * @property integer $model_id
 * @property string $day
 * @property string $start_time
 * @property string $end_time
 * @property string $remark
 *
 * @property CmsContentData $model
 */
class BusinessHour extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%business_hour}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_id', 'day', 'start_time', 'end_time'], 'required'],
            [['model_id'], 'integer'],
            [['day', 'start_time', 'end_time'], 'string', 'max' => 25],
            [['remark'], 'string', 'max' => 255],
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
            'day' => 'Day',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'remark' => 'Remark',
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
