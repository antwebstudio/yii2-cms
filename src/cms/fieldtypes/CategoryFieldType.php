<?php
namespace ant\cms\fieldtypes;

use yii\helpers\ArrayHelper;
//use ant\cms\models\Category;
use ant\category\models\Category;

class CategoryFieldType extends \ant\cms\components\RelationalFieldType {
	public $category_type_id;
	
	public function getName() {
		return 'Category';
	}
	
	public function rules() {
		return [[$this->field->handle.'_ids'], 'safe'];
	}
	
	public function entryBehaviors() {
		return [
			[
				'class' => 'ant\category\behaviors\CategorizableBehavior',
				'type' => [$this->field->handle],
			]
		];
	}
	
	public function input($form, $model) {
		$options = [
			'prompt' => '',
			'multiple' => true,
		];
		//$items = isset($field->setting['items']) ? array_combine($field->setting['items'], $field->setting['items']) : [];
		
		return $form->field($model, $this->field->handle.'_ids')->widget(
            \kartik\select2\Select2::classname(),
            [
				'maintainOrder' => true,
				'pluginOptions' => ['allowClear' => true],
				//'data' => ArrayHelper::map(Category::find()->type($this->category_type_id)->all(), 'content_uid', 'contentData.name'),
				'data' => ArrayHelper::map(Category::find()->typeOf('default')->all(), 'id', 'title'),
				'options' => $options,
            ]
        );
	}
}