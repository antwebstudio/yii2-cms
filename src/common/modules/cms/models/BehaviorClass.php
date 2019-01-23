<?php

namespace common\modules\cms\models;

use Yii;

/**
 * This is the model class for table "behavior_class".
 *
 * @property integer $id
 * @property string $class_name
 *
 * @property BehaviorSubscription[] $behaviorSubscriptions
 */
class BehaviorClass extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%behavior_class}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['class_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'class_name' => 'Class Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBehaviorSubscriptions()
    {
        return $this->hasMany(BehaviorSubscription::className(), ['class_id' => 'id']);
    }
}
