<?php

namespace ant\cms\behaviors;

use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\base\Event;
use ant\cms\models\ContentData;
use ant\cms\models\EntryType;
use ant\cms\models\Relation;
use ant\cms\events\FieldEvent;

class CmsFieldsBehavior extends \yii\base\Behavior {
	public $contentRelation = 'contentData';
	public $appId = 1;
	
	protected $_entryType;
	protected $_content;
	protected $_data = [];
	protected $_processValue = false;
	
	public function events() {
		return [
			ActiveRecord::EVENT_BEFORE_VALIDATE => 'beforeValidate',
			ActiveRecord::EVENT_BEFORE_INSERT => 'beforeInsert',
			ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeUpdate',
			ActiveRecord::EVENT_AFTER_INSERT => 'afterInsert',
			ActiveRecord::EVENT_AFTER_UPDATE => 'afterUpdate',
			ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
			ActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
		];
	}
	
	public function afterDelete() {
		if (!$this->content->delete()) throw new \Exception('Failed to delete content data. ');
	}
	
	public function afterFind() {
		$this->owner->entryType->attachEntryTypeBehaviors($this->owner);
		$this->_data = $this->content->data;
		
		$this->triggerFieldsEvent('afterContentFind');
	}
	
	public function beforeInsert($event) {
		$this->triggerFieldsEvent('beforeContentInsert');
	}
	
	public function beforeUpdate($event) {
		$this->triggerFieldsEvent('beforeContentUpdate');
	}
	
	public function afterInsert($event) {
		$this->triggerFieldsEvent('afterContentInsert');
	}
	
	public function afterUpdate($event) {
		$this->triggerFieldsEvent('afterContentUpdate');
	}
	
	public function beforeValidate() {
		$this->triggerFieldsEvent('beforeContentValidate');
		
		$this->content->data = $this->_data;
		
		if (!$this->content->save()) throw new \Exception(Html::errorSummary($this->content));
		
		$this->owner->content_uid = $this->content->id;
	}
    
	public function getName() {
		return $this->content->name;
	}
	
	public function processValue() {
		$this->_processValue = true;
		return $this;
	}
	
	public function setName($value) {
		$this->content->name = $value;
	}
	
	public function setEntryType($handleOrId) {
		if (isset($this->_entryType)) throw new \Exception('Entry type is already set. ');
		
		$this->_entryType = is_object($handleOrId) ? $handleOrId : EntryType::findOne($handleOrId);
		
		$this->_entryType->attachEntryTypeBehaviors($this->owner);
		
		/*if ($this->_entryType instanceof EntryType) {
			$this->_entryType->setModel($this->owner);
		}*/
	}
    
	public function getEntryType() {
		$entryType = $this->_entryType;
		
		if ($this->_entryType instanceof EntryType) {
			return $this->_entryType;
		} else if (is_integer($this->_entryType)) {
			$this->_entryType = EntryType::findOne($this->_entryType);
		} else if (isset($this->_entryType)) {
			$this->_entryType = EntryType::find()->where(['handle' => $this->_entryType])->one();
		} else if (isset($this->content->type_id)) {
			$this->_entryType = EntryType::findOne($this->content->type_id);
		} else {
			throw new \Exception('Entry type is not set. ');
		}
		
		if (!isset($this->_entryType)) throw new \Exception('Entry type "'.$entryType.'" is not exist. ');
		
		//$this->_entryType->setModel($this->owner);
		
		return $this->_entryType;
	}
	
	public function hasCustomField($handle) {
		return $this->entryType->hasField($handle) || isset($this->_data[$handle]);
	}
	
	public function getCustomField($handle) {
		return $this->entryType->getField($handle);
	}
	
	public function getFieldValue($handle) {
		$fields = $this->entryType->getFields();
		
		if ($this->content->hasAttribute($handle) || $fields[$handle]->fieldType->createColumn) {
			$return = $this->content->{$handle};
		} else if (isset($this->_data[$fields[$handle]->id])) {
			$return = $this->_data[$fields[$handle]->id];
		} else {
			return null;
		}
		
		if ($this->_processValue) {
			$return = $fields[$handle]->fieldType->prepareValue($return);
		}
		
		return $return;
	}
	
	public function setFieldValue($handle, $value) {
		$fields = $this->entryType->getFields();
		
		if ($this->content->hasAttribute($handle) || $fields[$handle]->fieldType->createColumn) {
			$this->content->{$handle} = $value;
		} else {
			$this->_data[$fields[$handle]->id] = $value;
		}
	}
	
	public function getRelatedTo($type) {
		if ($type instanceof EntryType) {
			$entryType = $type;
			$className = $type->content_type;
		} else if (class_exists($type)) {
			$entryType = null;
			$className = $type;
		} else {
			$entryType = EntryType::findOne(['handle' => $type]);
			$className = $entryType->content_type;
		}
		if (strpos($className, '\\') === false) {
			$className = '\ant\cms\models\\'.$className;
		}
		
		$query = $this->owner->hasMany($className, ['content_uid' => 'target_id'])
			->via('toRelations');
			
		if (isset($entryType)) {
			$query->alias('related')
				->leftJoin(ContentData::tableName().' AS related_content', 'related_content.id = related.content_uid')
				->where(['related_content.type_id' => $entryType->id])
			;
		}
		
		return $query;
			
		/*return $this->hasMany($contentType::className(), ['content_uid' => 'id'])
			->viaTable(ContentData::tableName(), ['id' => 'target_id'])
			->viaTable(Relation::tableName(), ['source_id' => 'content_uid']);
			
		return $this->hasMany($contentType::className(), ['content_uid' => 'id'])
				->via('relatedToContentData')->indexBy('content_uid');*/
	}
	
	public function getRelatedFrom($type) {
		if ($type instanceof EntryType) {
			$entryType = $type;
			$className = $type->content_type;
		} else if (class_exists($type)) {
			$entryType = null;
			$className = $type;
		} else {
			$entryType = EntryType::findOne(['handle' => $type]);
			$className = $entryType->content_type;
		}
		if (strpos($className, '\\') === false) {
			$className = '\ant\cms\models\\'.$className;
		}
		
		/*return $this->hasMany($className, ['content_uid' => 'source_id'])
			->via('fromRelations');*/
			
		$query = $this->owner->hasMany($className, ['content_uid' => 'source_id'])
			->via('fromRelations');
			
		if (isset($entryType)) {
			$query->alias('related')
				->leftJoin(ContentData::tableName().' AS related_content', 'related_content.id = related.content_uid')
				->where(['related_content.type_id' => $entryType->id]);
		}
		
		return $query;
	}
	
	public function getToRelations() {
		return $this->owner->hasMany(Relation::className(), ['source_id' => 'content_uid']);
	}
	
	public function getFromRelations() {
		return $this->owner->hasMany(Relation::className(), ['target_id' => 'content_uid']);
	}
	
	public function _getRelatedTo($entryType = null) {
		//return $this->owner->getContentData()->one()->getRelatedToContentData()->one()->getEntry();
		if (isset($entryType)) {
			$entryType = EntryType::findOne(['handle' => $entryType]);
			$contentType = $entryType->content_type;
			
			// Entry > ContentData > Relation > ContentData > Entry
			
			//throw new \Exception($entryType->handle.'-'.$contentType);
			
			/*return $this->owner->getContentData()->one()->hasMany($contentType::className(), ['content_uid' => 'id'])
				->viaTable(ContentData::tableName(), ['id' => 'target_id'])
				->viaTable(\ant\cms\models\Relation::tableName(), ['source_id' => 'id'])
				->one();*/
				
			return $this->owner->hasMany($contentType::className(), ['content_uid' => 'id'])
				->via('relatedToContentData');
				
			return $this->owner->getContentData()->one()->hasMany(ContentData::className(), ['id' => 'target_id'])
				->viaTable(\ant\cms\models\Relation::tableName(), ['source_id' => 'id'])
				->one()->getEntry();
		} else {
			return $this->owner->getContentData()->one()->getRelatedToContentData()->one()->getEntry();
		}
	}
	
	public function _getRelatedFrom() {
		//return $this->owner->getContentData()->one()->getRelatedFromContentData()->one()->getEntry();
		return $this->owner->getContentData()->one()->getRelatedFromContentData()->one()->getEntry();
	}
	
	protected function triggerFieldsEvent($name) {
		$fields = $this->entryType->getFields();
		foreach ($fields as $field) {
			$field->trigger($name, new FieldEvent(['model' => $this->owner]));
		}
	}
	
	protected function getContent() {
		if (!isset($this->_content)) {			
			if (isset($this->owner->{$this->contentRelation})) {
				// Content is created
				$this->_content = $this->owner->{$this->contentRelation};
			} else {
				// Content is not created
				if (!isset($this->entryType)) throw new \Exception('Entry type is not yet set. ');
				
				$this->_content = new ContentData;
				if ($this->_content->hasAttribute('app_id')) $this->_content->app_id = $this->appId;
				$this->_content->type_id = $this->entryType->id;
			}
		}
		return $this->_content;
	}
}