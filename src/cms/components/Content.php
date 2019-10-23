<?php
namespace ant\cms\components;

use ant\cms\models\ContentData;
use ant\cms\models\Category;
use ant\cms\models\Entry;

// This is different with old cms Content class, old cms Content class is renamed to ContentData
class Content extends \yii\base\Model {
	
	public static function findByUid($uid) {
		$content = ContentData::find()->joinWith('entryType')->andWhere([ContentData::tableName().'.id' => $uid])->one();
		$className = $content->entryType->content_type;
		
		if (strpos($className, '\\') === false) {
			$className = '\ant\cms\models\\'.$className;
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