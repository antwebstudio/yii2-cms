<?php

namespace common\modules\cms\fieldtypes;

class PlainTextFieldType extends \common\modules\cms\components\FieldType {
	protected $_columnType = 'varchar(255)';
	
	public function getName() {
		return 'Plain Text';
	}
	
	public function input($form, $model) {
		return $form->field($model, $this->field->handle)->textInput();
	}
}