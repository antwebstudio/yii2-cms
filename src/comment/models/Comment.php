<?php

namespace ant\comment\models;

use Yii;
use ant\user\models\User;
use ant\models\ModelClass;

/**
 * This is the model class for table "comment".
 *
 * @property integer $id
 * @property string $title
 * @property string $body
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
			[['author_name'], 'required'],
            [['model_class_id', 'model_id', 'body'], 'required'],
            [['body'], 'string'],
            [['id', 'status', 'created_by', 'updated_by'], 'integer'],
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
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
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
	
	public function getCreatedAt() {
		return $this->created_at;
	}
	
	public function getModel() {
		$class = ModelClass::getClassName($this->model_class_id);
		return $this->hasOne($class, ['id' => 'model_id']);
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
