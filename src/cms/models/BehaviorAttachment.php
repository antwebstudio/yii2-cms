<?php

namespace ant\cms\models;

use Yii;

/**
 * This is the model class for table "behavior_attachment".
 *
 * @property integer $id
 * @property integer $class_id
 * @property integer $behavior_id
 *
 * @property BehaviorClass $class
 * @property Behaviors $behavior
 */
class BehaviorAttachment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%behavior_attachment}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['class_id', 'behavior_id'], 'required'],
            [['class_id', 'behavior_id'], 'integer'],
            [['class_id'], 'exist', 'skipOnError' => true, 'targetClass' => BehaviorClass::className(), 'targetAttribute' => ['class_id' => 'id']],
            [['behavior_id'], 'exist', 'skipOnError' => true, 'targetClass' => Behaviors::className(), 'targetAttribute' => ['behavior_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'class_id' => 'Class ID',
            'behavior_id' => 'Behavior ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getClass()
    {
        return $this->hasOne(BehaviorClass::className(), ['id' => 'class_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttachedBehavior()
    {
        return $this->hasOne(Behaviors::className(), ['id' => 'behavior_id']);
    }
}
