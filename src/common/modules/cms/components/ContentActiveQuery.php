<?php
namespace common\modules\cms\components;

use common\modules\cms\models\EntryType;

class ContentActiveQuery extends \yii\db\ActiveQuery {
	public function type($type) {
		$appId = env('APP_ID', isset(\Yii::$app->params['appId']) ? \Yii::$app->params['appId'] : null);
		if (!$appId) throw new \Exception('Either env("APP_ID") or Yii::$app->params["appId"] need to be set. ');
		
		if ($type instanceof EntryType) {
			$entryType = $type;
			$className = $type->content_type;
		} else if (class_exists($type)) {
			$entryType = null;
			$className = $type;
		} else if (is_numeric($type)) {
			$entryType = EntryType::findOne($type);
			$className = $entryType->content_type;
		} else {
			$entryType = EntryType::findOne(['app_id' => $appId, 'handle' => $type]);
			$className = $entryType->content_type;
		}
		
		return $this->joinWith(['contentData' => function($query) { $query->alias('contentData'); }])
			->andWhere(['contentData.type_id' => $entryType->id]);
	}
}