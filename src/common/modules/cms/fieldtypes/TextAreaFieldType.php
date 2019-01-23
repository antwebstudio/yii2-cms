<?php

namespace common\modules\cms\fieldtypes;

class TextAreaFieldType extends \common\modules\cms\components\FieldType {
	protected $_columnType = 'text';
	
	public function getName() {
		return 'Multiline Text';
	}
	
	public function input($form, $model) {
		return $form->field($model, $this->field->handle)->textarea();
	}
}