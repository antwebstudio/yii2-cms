<?php
namespace common\modules\cms\components;

use common\modules\cms\models\ContentData;
use common\modules\cms\models\Category;
use common\modules\cms\models\Entry;

// This is different with old cms Content class, old cms Content class is renamed to ContentData
class Content extends \yii\base\Model {
	
	public static function findByUid($uid) {
		$content = ContentData::find()->joinWith('entryType')->andWhere([ContentData::tableName().'.id' => $uid])->one();
		$className = $content->entryType->content_type;
		
		if (strpos($className, '\\') === false) {
			$className = '\common\modules\cms\models\\'.$className;
		}
		return $className::findOne(['content_uid' => $uid]);
		throw new \Exception($content->id);
	}
	
	public static function create($entryTypeHandle) {
		
	}
	/*
	public static function load($entryTypeHandle) {
		
	}*/
}