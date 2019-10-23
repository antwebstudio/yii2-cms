<?php

namespace ant\comment\models;

use Yii;
use ant\user\models\User;

/**
 * This is the model class for table "comment".
 *
 * @property integer $id
 * @property integer $group_id
 * @property string $title
 * @property string $body
 * @property integer $is_published
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 *
 * @property User $createdBy
 * @property User $updatedBy
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%comment}}';
    }
	
	public function behaviors() {
		return [
			[
				'class' => \ant\behaviors\TimestampBehavior::className(),
			],
			[
				'class' => \yii\behaviors\BlameableBehavior::className(),
			],
		];
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['group_id', 'body', 'is_published'], 'required'],
            [['body'], 'string'],
            [['id', 'is_published', 'group_id', 'created_by', 'updated_by'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['updated_by' => 'id']],
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
            'title' => 'Title',
            'body' => 'Comment',
            'is_published' => 'Is Published',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }
	
	public function getCommentGroup() {
		return $this->hasOne(CommentGroup::className(), ['id' => 'group_id']);
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

	/**
	 * @inheritdoc
	 *
	 * @return CommentQuery
	 */
	public static function find()
	{
		return new \ant\comment\models\query\CommentQuery(get_called_class());
	}
}
