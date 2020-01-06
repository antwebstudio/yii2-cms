<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$url = $model->isNewRecord ? ['/comment/comment/create'] : ['/comment/comment/update', 'id' => $model->id];
?>

<?php $form = ActiveForm::begin($formOptions) ?>
	<?= $form->errorSummary($model) ?>
	
	<?php if ($model->isNewRecord): ?>
		<?= $form->field($model, 'model_class_id')->hiddenInput()->label(false) ?>
		
		<?= $form->field($model, 'model_id')->hiddenInput()->label(false) ?>
	<?php endif ?>
	
	<?= $form->field($model, 'title')->textInput() ?>
	
	<?= $form->field($model, 'body')->textArea() ?>
	
	<?= Html::submitButton($model->isNewRecord ? 'Comment' : 'Save', ['class' => 'btn btn-primary']) ?>
	
	<?php if (!$model->isNewRecord): ?>
		&nbsp; &nbsp;
		<a class="btn-link" href="javascript:;" data-comment-cancel>Cancel</a>
	<?php endif ?>
	
<?php ActiveForm::end() ?>