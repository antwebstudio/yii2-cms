<?php

namespace common\modules\cms\models;

use Yii;
use yii\helpers\ArrayHelper;
use common\modules\cms\behaviors\CmsNestedSetsBehavior;

/**
 * This is the model class for table "cms_category".
 *
 * @property integer $id
 * @property integer $content_uid
 * @property string $created_date
 * @property string $last_updated
 *
 * @property CmsContentData $contentU
 */
class Category extends \common\modules\cms\components\ContentActiveRecord
{
	//public $nodeActivationErrors;
	
	/*use \kartik\tree\models\TreeTrait {
        isDisabled as parentIsDisabled; // note the alias
    }*/
	
	/*public function behaviors() {
		$module = \kartik\tree\TreeView::module();
        $settings = ['class' => CmsNestedSetsBehavior::className()] + $module->treeStructure;
        $return = empty($module->treeBehaviorName) ? [$settings] : [$module->treeBehaviorName => $settings];
		
		return ArrayHelper::merge(parent::behaviors(), $return, []);
	}*/
	
	public function behaviors() {
		return ArrayHelper::merge(parent::behaviors(), [
			['class' => \common\modules\cms\behaviors\CmsTreeViewModelBehavior::className()],
		]);
	}
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_category}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            [['content_uid'], 'required'],
            [['content_uid'], 'integer'],
            [['created_date', 'last_updated'], 'safe'],
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
            'content_uid' => 'Content Uid',
            'created_date' => 'Created Date',
            'last_updated' => 'Last Updated',
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
