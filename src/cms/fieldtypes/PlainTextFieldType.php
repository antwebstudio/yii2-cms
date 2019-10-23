<?php

namespace ant\cms\fieldtypes;

class PlainTextFieldType extends \ant\cms\components\FieldType {
	protected $_columnType = 'varchar(255)';
	
	public function getName() {
		return 'Plain Text';
	}
	
	public function input($form, $model) {
		return $form->field($model, $this->field->handle)->textInput();
	}
}