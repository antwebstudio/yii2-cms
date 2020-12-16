<?php
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use bs\Flatpickr\FlatpickrWidget;
use ant\language\widgets\LanguageSelector;

?>

<?php $form = ActiveForm::begin(); ?>

	<?php if (Yii::$app->getModule('translatemanager')): ?>
		<?php $language = Yii::$app->request->post('language', Yii::$app->request->get('language', Yii::$app->language)) ?>
		
		<div class="row">
			<div class="col">
				<div class="text-right">
					Language: <?= LanguageSelector::widget([]) ?>
				</div>
			</div>
		</div>
		<?= Html::hiddenInput('language', $language) ?>
	<?php endif ?>
	
	<?= $form->field($model, 'name')->textInput() ?>
	
	<?php foreach ($model->entryType->getFields() as $handle => $field): ?>
		<?= $field->fieldType->backendInput($form, $model) ?>
	<?php endforeach ?>
	
	<?= $form->field($model, 'created_date')->widget(FlatpickrWidget::class, [
		'clientOptions' => ['enableTime' => true],
	])->label('Date') ?>
	
	<?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>

	<?= Html::submitButton('Save and create', ['name' => 'submit', 'value' => 'save-and-create', 'class' => 'btn btn-default']) ?>
<?php ActiveForm::end() ?>