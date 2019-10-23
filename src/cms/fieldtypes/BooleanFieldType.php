<?php

namespace ant\cms\fieldtypes;

class BooleanFieldType extends \ant\cms\components\FieldType {
	protected $_columnType = 'tinyint(1)';
	
	public function getName() {
		return 'Boolean';
	}
	
	public function input($form, $model) {
		return $form->field($model, $this->field->handle)->widget(\kartik\switchinput\SwitchInput::classname(), [
			//'type' => \kartik\switchinput\SwitchInput::CHECKBOX
		]);
	}
}