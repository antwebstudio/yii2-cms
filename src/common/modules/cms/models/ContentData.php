<?php

namespace common\modules\cms\models;

use Yii;

/**
 * This is the model class for table "cms_content_data".
 *
 * @property integer $id
 * @property integer $app_id
 * @property integer $type_id
 * @property integer $last_updated_by
 * @property string $data
 * @property string $name
 * @property integer $sequence
 * @property string $created_date
 * @property string $last_updated
 * @property integer $created_by
 * @property string $slug
 *
 * @property BusinessHour[] $businessHours
 * @property CmsCategory[] $cmsCategories
 * @property CmsEntryType $type
 * @property CmsContentLang[] $cmsContentLangs
 * @property CmsEntry[] $cmsEntries
 * @property CmsMatrixContent[] $cmsMatrixContents
 * @property CmsRelation[] $cmsRelations
 * @property CmsStructureContent[] $cmsStructureContents
 */
class ContentData extends \yii\db\ActiveRecord
{
	public function behaviors() {
		return [
			[
				'class' => \common\behaviors\SerializeBehavior::className(),
				'attributes' => ['data'],
				'serializeMethod' => \common\behaviors\SerializeBehavior::METHOD_JSON,
			],
		];
	}
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_content_data}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id', 'type_id', 'last_updated_by', 'sequence', 'created_by'], 'integer'],
            [['type_id', 'name'], 'required'],
            //[['data'], 'string'],
            [['created_date', 'last_updated'], 'safe'],
            [['name', 'slug'], 'string', 'max' => 255],
            [['type_id'], 'exist', 'skipOnError' => true, 'targetClass' => EntryType::className(), 'targetAttribute' => ['type_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_id' => 'App ID',
            'type_id' => 'Type ID',
            'last_updated_by' => 'Last Updated By',
            'data' => 'Data',
            'name' => 'Name',
            'sequence' => 'Sequence',
            'created_date' => 'Created Date',
            'last_updated' => 'Last Updated',
            'created_by' => 'Created By',
            'slug' => 'Slug',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBusinessHours()
    {
        return $this->hasMany(BusinessHour::className(), ['model_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCmsCategories()
    {
        return $this->hasMany(CmsCategory::className(), ['content_uid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntryType()
    {
        return $this->hasOne(EntryType::className(), ['id' => 'type_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCmsContentLangs()
    {
        return $this->hasMany(CmsContentLang::className(), ['app_content_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEntry()
    {
		$contentType = $this->getEntryType()->one()->content_type;
        return $this->hasOne($contentType::className(), ['content_uid' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCmsMatrixContents()
    {
        return $this->hasMany(CmsMatrixContent::className(), ['model_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getToRelations()
    {
        return $this->hasMany(Relation::className(), ['source_id' => 'id']);
    }
	
	public function getFromRelations() {
		return $this->hasMany(Relation::className(), ['target_id' => 'id']);
	}
	
	public function getRelatedToContentData() {
		return $this->hasMany(ContentData::className(), ['id' => 'target_id'])
			->via('toRelations');
			
		/*return $this->hasMany(ContentData::className(), ['id' => 'target_id'])
			->viaTable(Relation::tableName(), ['source_id' => 'id']);*/
	}
	
	public function getRelatedFromContentData() {
		return $this->hasMany(ContentData::className(), ['id' => 'source_id'])
			->via('fromRelations');
			
		/*return $this->hasMany(ContentData::className(), ['id' => 'source_id'])
			->viaTable(Relation::tableName(), ['target_id' => 'id']);*/
	}

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCmsStructureContents()
    {
        return $this->hasMany(CmsStructureContent::className(), ['content_id' => 'id']);
    }
}
