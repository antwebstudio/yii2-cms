<?php

namespace ant\author\models;

use Yii;

/**
 * This is the model class for table "{{%author}}".
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $short_description
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Author extends \yii\db\ActiveRecord
{
	public $picture;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%author}}';
    }
	
	public function behaviors() {
		return [
			\ant\behaviors\TimestampBehavior::class,
			[
				'class' => 'ant\file\behaviors\AttachmentBehavior',
				'attribute' => 'picture',
			],
		];
	}

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['picture', 'created_at', 'updated_at'], 'safe'],
            [['name', 'short_description'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'short_description' => 'Short Description',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
