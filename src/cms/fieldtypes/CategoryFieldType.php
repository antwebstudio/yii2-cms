<?php

namespace ant\cms\fieldtypes;

use yii\helpers\ArrayHelper;
use ant\cms\models\Category;

class CategoryFieldType extends \ant\cms\components\RelationalFieldType {
	public $category_type_id;
	
	public function getName() {
		return 'Category';
	}
	
	public function input($form, $model) {
		$options = [];
		//$items = isset($field->setting['items']) ? array_combine($field->setting['items'], $field->setting['items']) : [];
		
		return $form->field($model, $this->field->handle)->widget(
            \kartik\select2\Select2::classname(),
            [
				'data' => ArrayHelper::map(Category::find()->type($this->category_type_id)->all(), 'content_uid', 'contentData.name'),
				'options' => $options,
            ]
        );
	}
}