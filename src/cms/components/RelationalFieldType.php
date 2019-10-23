<?php
namespace ant\cms\components;

use ant\cms\models\Relation;

abstract class RelationalFieldType extends FieldType {
	protected $_createColumn = false;
	
	public function events() {
		return [
			'afterContentFind' => [$this, 'afterContentFind'],
			'beforeContentUpdate' => [$this, 'beforeContentUpdate'],
			'afterContentUpdate' => [$this, 'afterContentSave'],
			'afterContentInsert' => [$this, 'afterContentSave'],
		];
	}
	
	public function afterContentSave($event) {
		
		$model = $event->model;
		$value = (array) $model->getFieldValue($this->field->handle);
	
		// @TODO should check only hasStructure, hasStructure should return 1 if and only if structureId is set.
		//if ($this->model->hasStructure() && $this->model->getStructureId() && true) { // @TODO add a setting for this
			//$value = ArrayHelper::merge($value, $this->getParentsIds($value));
		//}
		
		$relation = Relation::findAll([
			'source_id' => $model->content_uid,
			'field_id' => $this->field->id,
		]);
	
		$new = $value;
		if (isset($relation)) {
			foreach ($relation as $r) {
				if (!in_array($r->target_id, $value)) {
					$r->delete();
				} else {
					$new = array_diff($new, array($r->target_id));
				}
			}
		}
		
		$new = array_unique($new);
		
		foreach ($new as $relatedContentId) {
			if ($relatedContentId) {
				$relation = new Relation;
				$relation->source_id = $model->content_uid;
				$relation->field_id = $this->field->id;
				$relation->target_id = $relatedContentId;
				
				if (!$relation->save()) throw new Exception('Failed to insert new relation records. (Target ID: '.$relatedContentId.')'.CHtml::errorSummary($relation));
			}
		}
		
		// Update also parent last_updated date
		//if (isset($this->field->settings->{self::SETTING_UPDATE_RELATED_LAST_UPDATED}) && $this->field->settings->{self::SETTING_UPDATE_RELATED_LAST_UPDATED}) {
		//	Content::model()->updateByPk($this->value, array('last_updated' => new CDbExpression('NOW()	')));
		//}
	}
}