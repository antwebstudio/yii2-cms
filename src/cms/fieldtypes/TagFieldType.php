<?php
namespace ant\cms\fieldtypes;

use yii\helpers\ArrayHelper;

class TagFieldType extends \ant\cms\components\RelationalFieldType {
	public $category_type_id;
	
	public function getName() {
		return 'Tag';
	}
	
	public function rules() {
		return [[$this->field->handle.'Value'], 'safe'];
	}
	
	public function entryBehaviors() {
		return [
			[
				'class' => \ant\tag\behaviors\TaggableBehavior::class,
				'relation' => $this->field->handle,
				'attribute' => $this->field->handle.'Value',
				//'modelClassId' => \ant\models\ModelClass::getClassId(get_class($this)),
			],
		];
	}
	
	public function input($form, $model) {
		return $form->field($model, $this->field->handle.'Value')->widget(\kartik\select2\Select2::className(), [
			'data' => array_combine($model->tagsValue, $model->tagsValue),
			'options' => ['placeholder' => 'Add a tag ...', 'multiple' => true],
			
			'pluginOptions' => [
				'tags' => true,
			],
		]);
	}
}