<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use ant\widgets\TinyMce;
use trntv\filekit\widget\Upload;

?>

<?php $form = ActiveForm::begin() ?>
	<?= $form->field($model, 'image')->widget(Upload::class, [
		'url' => ['http://localhost/ant/directus/public/ant-web/files'],
	]) ?>

	<?= $form->field($model, 'content')->widget(TinyMce::class, ['clientOptions' => ['height' => 500]]) ?>
	
	<?= Html::submitButton('Create', ['class' => 'btn btn-primary']) ?>
<?php ActiveForm::end() ?>