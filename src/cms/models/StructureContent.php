<?php

namespace ant\cms\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "cms_structure_content".
 *
 * @property integer $id
 * @property integer $structure_id
 * @property integer $content_uid
 * @property integer $root
 * @property integer $left
 * @property integer $right
 * @property integer $level
 * @property string $created_date
 * @property string $last_updated
 *
 * @property CmsContentData $content
 * @property CmsStructure $structure
 */
class StructureContent extends \yii\db\ActiveRecord
{
	public function behaviors() {
		return ArrayHelper::merge(parent::behaviors(), [
			[
				'class' => \ant\behaviors\NestedSetsBehavior::className(),
				'treeAttribute' => 'root',
				'leftAttribute' => 'left',
				'rightAttribute' => 'right',
				'depthAttribute' => 'level',
			],
		]);
	}
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_structure_content}}';
    }
	
    /**
     * @inheritdoc
     */
    public static function find()
    {
        return new \kartik\tree\models\TreeQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //[['structure_id', 'root', 'left', 'right', 'level'], 'required'],
            [['structure_id', 'content_uid', 'root', 'left', 'right', 'level'], 'integer'],
            [['created_date', 'last_updated'], 'safe'],
            [['content_uid'], 'exist', 'skipOnError' => true, 'targetClass' => ContentData::className(), 'targetAttribute' => ['content_uid' => 'id']],
            [['structure_id'], 'exist', 'skipOnError' => true, 'targetClass' => Structure::className(), 'targetAttribute' => ['structure_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'structure_id' => 'Structure ID',
            'content_uid' => 'Content ID',
            'root' => 'Root',
            'left' => 'Left',
            'right' => 'Right',
            'level' => 'Level',
            'created_date' => 'Created Date',
            'last_updated' => 'Last Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContent()
    {
        return $this->hasOne(ContentData::className(), ['id' => 'content_uid']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStructure()
    {
        return $this->hasOne(Structure::className(), ['id' => 'structure_id']);
    }
}
