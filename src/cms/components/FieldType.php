<?php

namespace ant\cms\components;

use Yii;
use ant\cms\models\ContentData;

abstract class FieldType extends \yii\base\Component {
	public $useValueFromContent = false;
	protected $_field;
	protected $_model;
	protected $_columnType;
	protected $_createColumn = true;
	protected $_multiLingual = false;
	
	public function __construct($options) {
		$this->_field = $options['field'];
		unset($options['field']);
		parent::__construct($options);
	}
	
	public function getIsSupported() {
		return true;
	}
	
	public function events() {
		return [
			'beforeContentValidate' => [$this, 'beforeContentValidate'],
			'afterContentFind' => [$this, 'afterContentFind'],
			'beforeContentInsert' => [$this, 'beforeContentInsert'],
			'beforeContentUpdate' => [$this, 'beforeContentUpdate'],
			'afterContentUpdate' => [$this, 'afterContentUpdate'],
			'afterContentInsert' => [$this, 'afterContentInsert'],
		];
	}
	
	public function behaviors() {
		return [
			[
				'class' => \ant\behaviors\EventHandlerBehavior::className(),
				'events' => $this->events(),
			],
		];
	}
	
	public function rules() {
		$rules = [[$this->field->handle], 'safe'];
		if ($this->field->isRequired) {
			$rules = [[$this->field->handle], 'required'];;
		}
		return $rules;
	}
	
	public function init() {
		/*foreach ($this->events() as $event => $handler) {
            $this->on($event, is_string($handler) ? [$this, $handler] : $handler);
        }*/
		
		/*if (isset($this->model) && !$this->model->isReturnObjectValue) {
			if ($this->model->getFieldByHandle($this->field->handle) && !isset($this->value)) {
				// $TODO add multilanguage support for default value
				//$this->model->setFieldValue($this->field->handle, $this->defaultValue, $this->isMultiLingualField);
				
				// currently default value do not have multilanguage support hence always pass false
				$this->model->setFieldValue($this->field->handle, $this->defaultValue, false);
			}
		}*/
	}
	
	public function attachFieldTypeBehaviors($entry) {
		$entry->attachBehaviors($this->entryBehaviors());
	}
	
	public function entryBehaviors() {
		return [];
	}
	
	public function prepareValue($value) {
		//$value = $model->{$this->field->handle};
		return $value;
	}
	
	public function createOrDropColumn($isNewAddedField, $columnSqlType = null, $columnName = null, $tableName = null) {
		if (!isset($columnSqlType)) $columnSqlType = $this->getColumnType();
		if (!isset($tableName)) $tableName = ContentData::tableName();
		if (!isset($columnName)) $columnName = $this->field->handle;
		
		if ($isNewAddedField == 1 || $isNewAddedField == 0) {
			// If column is not exist, create the column
			$existingColumns = Yii::$app->db->schema->getTableSchema($tableName, true)->columns;
			if (!isset($existingColumns[$columnName])) {
				Yii::$app->db->createCommand()->addColumn($tableName, $columnName, $columnSqlType)->execute();
			}
			
			// LangTable field
			if ($this->isMultiLingualField) {
				$existingColumns = Yii::$app->db->schema->getTableSchema($tableName.'_lang', true)->columns;
				if (!isset($existingColumns[$columnName])) {
					Yii::$app->db->createCommand()->addColumn($tableName.'_lang', $columnName, $columnSqlType)->execute();
				}
			}
		} else if (!$this->field->inUse) {
			Yii::$app->db->createCommand()->dropColumn($tableName, $columnName)->execute();
			if ($this->isMultiLingualField) {
				Yii::$app->db->createCommand()->dropColumn($tableName.'_lang', $columnName)->execute();
			}
		}
	}
	
	public function afterContentFind($event) {
		
	}
	
	public function afterContentInsert($event) {
		
	}
	
	public function afterContentUpdate($event) {
		
	}
	
	public function beforeContentUpdate($event) {
		
	}
	
	public function beforeContentInsert($event) {
		
	}

	public function beforeContentValidate($event) {
		 
	}
	
	public function afterContentValidate($event) {
		 
	}
	
	// @param isNewAddedField (0 = old field, 1 = new field, -1 = deleted field)
	public function afterEntryTypeSave($isNewAddedField) {
		/*
		// For testing purpose
		switch ($isNewAddedField) {
			case 0:
				break;
			case 1:
				throw new Exception('add field: '.$this->field->name);
			case -1:
				throw new Exception('removed field: '.$this->field->name);
			default:
				throw new Exception('Unknown valued for isNewAddedField');
		}
		*/
		
		if ($this->createColumn) {
			$this->createOrDropColumn($isNewAddedField, $this->columnType);
		}
	}
	
	public function getAdminSettingsPanel() {
		return array();
	}
	
	protected function getField() {
		return $this->_field;
	}
	
	protected function getSetting($name, $defaultValue = null) {
		return isset($this->field->settings->{$name}) ? $this->field->settings->{$name} : $defaultValue;
	}
	
	public function setModel($value) {
		$this->_model = $value;
		if ($this->hasParent()) {
			$this->field->parent->setModel($this->_model);
		}
	}
	
	protected function getModel() {
		//if (!isset($this->_model)) throw new Exception('Model is not set. ');
		return $this->field->model;
	}
	
	protected function hasModel() {
		return isset($this->_model);
	}
	
	protected function hasParent() {
		return isset($this->field->parent);
	}
	
	public function getCustomFieldRule() {
		return false;
	}
	
	public function getAttribute() {
		return $this->field->handle;
	}
	
	public function input($form, $model) {
		return '<p>Field type '.$this->name.' input not supported yet. </p>';
	}
	
	public function backendInput($form, $model) {
		return $this->input($form, $model);
	}
	
	protected function getCreateColumn() {
		return $this->_createColumn;
	}
	
	public function getColumnType() {
		if (isset($this->_columnType)) {
			return $this->_columnType;
		} else {
			throw new \Exception('Column type for field '.$this->field->type.' is not set.');
		}
	}
	
	protected function setValue($value) {
		$this->model->{$this->field->handle} = $value;
	}
	
	protected function getValue() {
		if ($this->hasParent()) {
			$parentValue = $this->model->{$this->field->parent->handle};

			return $parentValue;
		} else {
			if (isset($model)) {
				return $model->{$this->field->handle};
			}
			return $this->model->{$this->field->handle};
		}
	}
	
	protected function getIsMultiLingualField() {
		return $this->_multiLingual;
	}
	
	protected function getDefaultValue() {
		$entryType = $this->model->entryType->handle;
		$fieldName = $this->field->handle;
		$defaultValue = $this->model->entryType->getDefaultValue($this->field->id);
		if (isset($defaultValue)) {
			return $defaultValue;
		}
		if (isset(Yii::app()->params['entryDefaultValue'][$entryType][$fieldName])) {
			if (is_callable(Yii::app()->params['entryDefaultValue'][$entryType][$fieldName])) {
				return call_user_func_array(Yii::app()->params['entryDefaultValue'][$entryType][$fieldName], array($this));
			}
			return Yii::app()->params['entryDefaultValue'][$entryType][$fieldName];
		}
	}
	
	protected function getAppId() {
		return isset($this->model->app_id) ? $this->model->app_id : Yii::app()->controller->appId;
	}
	
	abstract public function getName();
}