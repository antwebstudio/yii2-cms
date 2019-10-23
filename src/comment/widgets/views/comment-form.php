<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<?php $form = ActiveForm::begin() ?>
	<?= $form->errorSummary($model) ?>
	
	<?= $form->field($model, 'id')->hiddenInput()->label(false) ?>
	
	<?= $form->field($model, 'title')->textInput() ?>
	
	<?= $form->field($model, 'body')->textArea() ?>
	
	<?= Html::submitButton($model->isNewRecord ? 'Comment' : 'Save', ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end() ?>