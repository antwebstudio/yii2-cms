<?php

namespace ant\cms\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "cms_entry".
 *
 * @property integer $id
 * @property integer $section_id
 * @property string $created_date
 * @property string $last_updated
 * @property integer $content_uid
 * @property string $published_date
 * @property string $expire_date
 *
 * @property AppContent $content
 */
class Entry extends \ant\cms\components\ContentActiveRecord
{
    /**
     * @inheritdoc
     */

    
    public static function tableName()
    {
        return '{{%cms_entry}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [[/*'section_id', */'content_uid'], 'required'],
            [['section_id', 'content_uid'], 'integer'],
            [['created_date', 'last_updated', 'published_date', 'expire_date'], 'safe'],
            [['content_uid'], 'exist', 'skipOnError' => true, 'targetClass' => ContentData::className(), 'targetAttribute' => ['content_uid' => 'id']],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'section_id' => 'Section ID',
            'created_date' => 'Created Date',
            'last_updated' => 'Last Updated',
            'content_uid' => 'Content ID',
            'published_date' => 'Published Date',
            'expire_date' => 'Expire Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContentData()
    {
        return $this->hasOne(ContentData::className(), ['id' => 'content_uid']);
    }
	
	public function getUrl() {
		return \yii\helpers\Url::to(['/cms/entry/view', 'uid' => $this->content_uid]);
	}
}
