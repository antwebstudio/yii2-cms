<?php

namespace ant\cms\models;

use Yii;

/**
 * This is the model class for table "cms_field".
 *
 * @property integer $id
 * @property integer $app_id
 * @property integer $group_id
 * @property string $name
 * @property string $handle
 * @property string $context
 * @property string $instructions
 * @property integer $translatable
 * @property string $type
 * @property string $settings
 * @property string $created_date
 * @property string $last_updated
 *
 * @property CmsFieldGroup $group
 * @property CmsFieldLayoutField[] $cmsFieldLayoutFields
 * @property CmsFieldLayout[] $layouts
 */
class Field extends \yii\db\ActiveRecord
{
	protected $_fieldType;
	protected $_model;
	
	public function setModel($model) {
		if (isset($this->_model)) throw new \Exception('Context is already set. ');
		$this->_model = $model;
	}
	
	public function getModel() {
		return $this->_model;
	}
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cms_field}}';
    }
	
	public static function createForEntryType($entryType, $handle, $fieldTypeClass, $fieldOptions = []) {
		$entryType = is_object($entryType) ? $entryType : EntryType::findOne($entryType);
		
		if (is_array($handle)) {
			$handle = key($handle);
			$label = current($handle);
		} else {
			$label = \ant\helpers\StringHelper::generateTitle($handle);
		}
		
		$field = self::findOne([
			'handle' => $handle,
			'type' => $fieldTypeClass,
		]);
		
		if (!isset($field)) {
			$field = new static;
			$field->attributes = [
				'name' => $label,
				'handle' => $handle,
				'type' => $fieldTypeClass,
			];
			if (!$field->save()) throw new \Exception(print_r($field->errors, 1));
		}
		
		// Add field to entry type
		$layoutField = new FieldLayoutField();
		$layoutField->layout_id = $entryType->field_layout_id;
		$layoutField->tab_id = $entryType->fieldLayout->tabs[0]->id;
		$layoutField->field_id = $field->id;
		
		if (!$layoutField->save()) throw new \Exception(print_r($layoutField->errors, 1));
	}
	
	public function events() {
		return [
			'beforeContentValidate' => [$this, 'eventHandler'],
			'afterContentValidate' => [$this, 'eventHandler'],
			'beforeContentFind' => [$this, 'eventHandler'],
			'afterContentFind' => [$this, 'eventHandler'],
			'beforeContentUpdate' => [$this, 'eventHandler'],
			'beforeContentInsert' => [$this, 'eventHandler'],
			'afterContentUpdate' => [$this, 'eventHandler'],
			'afterContentInsert' => [$this, 'eventHandler'],
		];
	}
	
	public function behaviors() {
		return [
			[
				'class' => \ant\behaviors\EventHandlerBehavior::className(),
				'events' => $this->events(),
			],
			[
				'class' => \ant\behaviors\SerializeBehavior::className(),
				'attributes' => ['settings'],
				'serializeMethod' => \ant\behaviors\SerializeBehavior::METHOD_JSON,
			],
		];
	}

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['app_id', 'group_id', 'translatable'], 'integer'],
            [['name', 'handle', 'type'], 'required'],
            [['instructions', 'settings'], 'string'],
            [['created_date', 'last_updated'], 'safe'],
            [['name', 'context'], 'string', 'max' => 255],
            [['handle'], 'string', 'max' => 58],
            [['type'], 'string', 'max' => 150],
            [['app_id', 'handle', 'context'], 'unique', 'targetAttribute' => ['app_id', 'handle', 'context'], 'message' => 'The combination of App ID, Handle and Context has already been taken.'],
            [['group_id'], 'exist', 'skipOnError' => true, 'targetClass' => FieldGroup::className(), 'targetAttribute' => ['group_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'app_id' => 'App ID',
            'group_id' => 'Group ID',
            'name' => 'Name',
            'handle' => 'Handle',
            'context' => 'Context',
            'instructions' => 'Instructions',
            'translatable' => 'Translatable',
            'type' => 'Type',
            'settings' => 'Settings',
            'created_date' => 'Created Date',
            'last_updated' => 'Last Updated',
        ];
    }
	
	public function eventHandler($event) {
		$field = $event->sender;
		$field->fieldType->trigger($event->name, $event);
	}
	
	public function attachFieldBehaviors($entry) {
		$this->fieldType->attachFieldTypeBehaviors($entry);
	}
	
	public function getIsRequired() {
		if (!isset($this->fieldLayoutFields)) throw new \Exception('Field with ID: '.$this->id.' do not have fieldLayoutFields. ');
		return $this->fieldLayoutFields->required;
	}
	
	public function getFieldType() {
		if (!isset($this->_fieldType)) {
			$className = $this->normalizeTypeClass($this->type);
			
			if (!isset($className)) throw new \Exception('Class name is null: '.$this->type);
			
			$this->_fieldType = new $className(['field' => $this]);
			try {
				\Yii::configure($this->_fieldType, (array) $this->settings);
			} catch (\Exception $ex) {
				throw new \Exception('Setting for field ID: '.$this->id.' have error: '.$ex->getMessage());
			}
		}
		//$this->_fieldType->setModel($this->_model);
		
		return $this->_fieldType;
	}
	
	// To handle Yii1 field type
	protected function normalizeTypeClass($class) {
		if (!class_exists($class)) {
			return '\ant\cms\fieldtypes\\'.$class.'FieldType';
		}
		return $class;
	}
	
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getGroup()
    {
        return $this->hasOne(CmsFieldGroup::className(), ['id' => 'group_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFieldLayoutFields()
    {
        return $this->hasOne(FieldLayoutField::className(), ['field_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLayouts()
    {
        return $this->hasMany(CmsFieldLayout::className(), ['id' => 'layout_id'])->viaTable('cms_field_layout_field', ['field_id' => 'id']);
    }
}
