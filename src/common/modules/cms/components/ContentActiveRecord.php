<?php
namespace common\modules\cms\components;

use yii\helpers\ArrayHelper;
use common\modules\cms\models\Relation;
use common\modules\cms\models\ContentData;
use common\modules\cms\models\EntryType;

abstract class ContentActiveRecord extends \yii\db\ActiveRecord {
	public function behaviors() {
		return [
			[
				'class' => \common\modules\cms\behaviors\CmsFieldsBehavior::className(),
			],
		];
	}
	
	public function __isset($name) {
		try {
			if (parent::__isset($name)) return true;
			
			if ($this->hasCustomField($name)) {
				return true;
			}
		}  catch (\Exception $e) {

			// Fine, throw the exception
			throw $e;
		}
	}
	
	public function __get($name) {
		// Run through the BaseModel/CModel stuff first
		try {
			return parent::__get($name);
		} catch (\Exception $e) {
			// Is $name a field handle?
			if ($name != 'entryType' && $this->hasCustomField($name)) {
				return $this->getFieldValue($name);
			}

			// Fine, throw the exception
			throw $e;
		}
	}

	public function __set($name, $value) {
		// Run through the BaseModel/CModel stuff first
		try {
			return parent::__set($name, $value);
		} catch (\Exception $e) {
			// Is $name a field handle?
			if ($this->hasCustomField($name)) {
				return $this->setFieldValue($name, $value);
			}

			// Fine, throw the exception
			throw $e;
		}
	}
	
	public function rules() {
		$rules = [];
		foreach ($this->entryType->getFields() as $field) {
			$rule = $field->fieldType->rules();
			if (isset($rule)) $rules[] = $rule;
		}
		return ArrayHelper::merge($rules, [
			[['name'], 'safe'],
		]);
	}
	
	public static function find() {
		return new ContentActiveQuery(get_called_class());
	}
	
	public function getAttribute($name) {
		if ($this->hasMethod('isTreeNodeAttribute') && $this->isTreeNodeAttribute($name)) {
			return $this->getTreeNodeAttribute($name);
		}
		return parent::getAttribute($name);
	}
	
	public function setAttribute($name, $value) {
		if ($this->hasMethod('isTreeNodeAttribute') && $this->isTreeNodeAttribute($name)) {
			return $this->setTreeNodeAttribute($name);
		}
		return parent::setAttribute($name, $value);
	}
	
	/*
	public function getRelatedToContentData() {
		return $this->hasMany(ContentData::className(), ['id' => 'target_id'])
			->viaTable(Relation::tableName(), ['source_id' => 'content_uid']);
	}*/
}