<?php
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use bs\Flatpickr\FlatpickrWidget;

?>

<?php $form = ActiveForm::begin(); ?>
	<?= $form->field($model, 'name')->textInput() ?>
	
	<?php foreach ($model->entryType->getFields() as $handle => $field): ?>
		<?= $field->fieldType->backendInput($form, $model) ?>
	<?php endforeach ?>
	
	<?= $form->field($model, 'created_date')->widget(FlatpickrWidget::class, [
		'clientOptions' => ['enableTime' => true],
	])->label('Date') ?>
	
	<?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end() ?>