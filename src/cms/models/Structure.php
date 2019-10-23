<?php

namespace ant\cms\models;

use Yii;

/**
 * This is the model class for table "cms_structure".
 *
 * @property integer $id
 * @property string $created_date
 * @property string $last_updated
 *
 * @property CmsStructureContent[] $cmsStructureContents
 */
class Structure extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_structure}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_date', 'last_updated'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_date' => 'Created Date',
            'last_updated' => 'Last Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCmsStructureContents()
    {
        return $this->hasMany(CmsStructureContent::className(), ['structure_id' => 'id']);
    }
}
