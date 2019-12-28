<?php
namespace ant\cms\behaviors;

use yii\db\ActiveRecord;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use ant\cms\models\Field;
use ant\cms\models\FieldLayout;
use ant\cms\models\FieldLayoutTab;
use ant\cms\models\FieldLayoutField;

class EntryTypeBehavior extends \yii\base\Behavior {
	protected $_fieldIds; // Should be null be default
	protected $_fields;
	protected $_attachedEntryTypeBehaviors = false;
	
	public function attachEntryTypeBehaviors($entry) {
		if (!$this->_attachedEntryTypeBehaviors) {
			$this->_attachedEntryTypeBehaviors = true;
			
			foreach ($this->getFields() as $field) {
				$field->attachFieldBehaviors($entry);
			}
		}
	}
	
	public function setModel($model) {
		// Should not setModel to entryType, as multiple instance of entry may use the same instance of entry type
		// This method is remained here as a reminder
		throw new \Exception('Not implemented. ');
	}
	public function events() {
		return [
			ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
			ActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
		];
	}
	
	public function beforeSave($event) {
		$this->ensureFieldLayout();
	}
	
	public function afterSave($event) {
		
		foreach ($this->getFields() as $field) {
			$field->fieldType->afterEntryTypeSave(1);
		}
	}
	
	public function getField($handle) {
		$fields = $this->getFields();
		return $fields[$handle];
	}
	
	public function hasField($handle) {
		$fields = $this->getFields();
		return isset($fields[$handle]);
	}

    public function getFields() {
        if (!isset($this->_fields)) {
			$fieldIds = $this->getFieldIds();
			
			if (isset($fieldIds)) {
				$this->_fields = Field::find()->where(['id' => $this->_fieldIds])->indexBy('handle')->all();
			} else {
				$this->_fieldIds = [];
				$this->_fields = [];
			}
			/*
			foreach ($this->_fields as $field) {
				$field->setModel($this->model);
			}*/
		}
		return $this->_fields;
    }
	
	public function getFieldIds() {
		if (!isset($this->_fieldIds)) {
			$fieldLayout = $this->owner->fieldLayout;
			
			if (isset($fieldLayout)) {
				$this->_fieldIds = [];
				$this->_fields = [];
				
				if (isset($fieldLayout->fields)) {
					// Please note that fieldLayoutField and field are different.
					foreach ($fieldLayout->fields as $field) {
						$this->_fieldIds[] = $field->id;
						$this->_fields[] = $field;
					}
				}
			} else {
				$this->_fieldIds = [];
			}
		}
		return $this->_fieldIds;
	}
    
    public function setFieldIds($fieldIds) {
        $this->_fieldIds = $fieldIds;
        $this->_fields = null; // Use unset() will cause unknown property in PHP 7, refer: https://bugs.php.net/bug.php?id=72194
    }
    
    // To make sure field_layout_id is set.
    protected function ensureFieldLayout() {
        if (!$this->hasFieldLayout()) {
			$fieldLayout = $this->createFieldLayout();
			$layoutTab = $this->createFieldLayoutTab();
			
			if (isset($this->_fieldIds)) {
				$this->addFieldToTab($layoutTab->id, $this->_fieldIds);
			}
		}
    }
	
	protected function hasFieldLayout() {
		return isset($this->owner->field_layout_id);
	}

	protected function createFieldLayout() {
		$fieldLayout = new FieldLayout();
		$fieldLayout->type = 'Entry';
		
		if ($fieldLayout->save()) {
			$this->owner->field_layout_id = $fieldLayout->id;
			return $fieldLayout;
		} else {
			$this->addError('Field layout cannot be created. ');
		}
	}

	protected function createFieldLayoutTab() {
		if ($this->hasFieldLayout()) {
			$tab = new FieldLayoutTab();
			$tab->layout_id = $this->owner->field_layout_id;
			$tab->name = $this->owner->className();
			
			if (!$tab->save()) throw new \Exception('Failed to create layout tab. ');

			return $tab;
		} else {
			throw new \Exception('Failed to create layout tab, no field layout. ');
		}
	}

	protected function addFieldToTab($tabId, $fields) {
		if ($this->hasFieldLayout()) {
			foreach ($fields as $i => $f) {
				$field = new FieldLayoutField();
				$field->layout_id = $this->owner->field_layout_id;
				$field->tab_id = $tabId;
				$field->field_id = $f instanceof Field ? $f->id : $f;
				$field->sequence = $i;
				
				if (!$field->save()) throw new \Exception('Field cannot be created. ' . Html::errorSummary($field));
			}
		} else {
			throw new Exception('No field layout, field cannot be created. ');
		}
	}
}