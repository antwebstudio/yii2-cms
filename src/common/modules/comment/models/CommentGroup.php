<?php

namespace common\modules\comment\models;

use Yii;

/**
 * This is the model class for table "comment_group".
 *
 * @property integer $id
 * @property string $model_class
 * @property string $foreign_pk
 *
 * @property Comment[] $comments
 */
class CommentGroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment_group';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model_class', 'foreign_pk'], 'required'],
            [['model_class', 'foreign_pk'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model_class' => 'Model Class',
            'foreign_pk' => 'Foreign Pk',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['group_id' => 'id']);
    }

	/**
	 * @inheritdoc
	 *
	 * @return CommentQuery
	 */
	public static function find()
	{
		return new \common\modules\comment\models\query\CommentGroupQuery(get_called_class());
	}
}
