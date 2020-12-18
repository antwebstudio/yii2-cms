<?php
use yii\grid\GridView;
use yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use bs\Flatpickr\FlatpickrWidget;
use ant\language\widgets\LanguageSelector;

if ($model->isNewRecord && !isset($model->status)) $model->status = true;
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

	<div class="row">
		<div class="col-md-9">

			<?= $form->field($model, 'name')->textInput() ?>
			
			<?php foreach ($model->entryType->getFields() as $handle => $field): ?>
				<?= $field->fieldType->backendInput($form, $model) ?>
			<?php endforeach ?>
		</div>
		<div class="col-md-3">
			<div class="sticky-top position-sticky">
				<?= $form->field($model, 'created_date')->widget(FlatpickrWidget::class, [
					'clientOptions' => ['enableTime' => true],
				])->label('Date') ?>

				<?= $form->field($model, 'status')->widget(\kartik\switchinput\SwitchInput::class) ?>

				<?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
			</div>
		</div>
<?php ActiveForm::end() ?>