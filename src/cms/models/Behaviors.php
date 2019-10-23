<?php

namespace ant\cms\models;

use Yii;

/**
 * This is the model class for table "behaviors".
 *
 * @property integer $id
 * @property string $behavior_name
 *
 * @property BehaviorAttachment[] $behaviorAttachments
 */
class Behaviors extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%behaviors}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['behavior_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'behavior_name' => 'Behavior Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBehaviorAttachments()
    {
        return $this->hasMany(BehaviorAttachment::className(), ['behavior_id' => 'id']);
    }
}
