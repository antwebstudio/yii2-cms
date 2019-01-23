<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\ckeditor\CKEditor;
use common\modules\category\models\Category;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use trntv\filekit\widget\Upload;
use yii\web\JsExpression;
/* @var $this yii\web\View */
/* @var $model common\modules\article\models\Article */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="article-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php if ($model->hasErrors()): ?>
        <?= \yii\bootstrap\Alert::widget([
            'options' => ['class' => 'alert alert-danger'],
            'body' => $form->errorSummary($model). "\n" .' WARNING ! PDF will be missing if submit error.',
        ]) ?>
    <?php endif; ?>
    <?php if (in_array('title', $formModelAttributeToBeShow)): ?>
        <?= $form->field($model, 'title')->textInput() ?>
    <?php endif ?>

    <?php if (in_array('subtitle', $formModelAttributeToBeShow)): ?>
        <?= $form->field($model, 'subtitle')->textInput(['maxlength' => true]) ?>
    <?php endif ?>

    <?php if (in_array('slug', $formModelAttributeToBeShow)): ?>
        <?= $form->field($model, 'slug')->textInput(['maxlength' => true]) ?>
    <?php endif ?>

    <?php if (in_array('body', $formModelAttributeToBeShow)): ?>
        <?= $form->field($model, 'body')->widget(CKEditor::className(), [
                'options' =>
                [
                    'maxlength' => true,
                    'rows' => 6
                ],
                'preset' => 'full'
        ]) ?>
    <?php endif ?>

    <?php if (in_array('view', $formModelAttributeToBeShow)): ?>
        <?= $form->field($model, 'view')->textInput(['maxlength' => true, 'disabled' => true]) ?>
    <?php endif ?>

    <?php if (in_array('category_id', $formModelAttributeToBeShow)): ?>
        <?= $form->field($model, 'categories')->widget(Select2::classname(), [
                'data' => ArrayHelper::map($categories, 'id', 'title'),
                'maintainOrder' => true,
                'options' => [
                    'placeholder' => '', 'multiple' => true
                ],
                'pluginOptions' => ['allowClear' => true],
            ]);
        ?>
    <?php endif ?> 
    <?php if (in_array('thumbnail', $formModelAttributeToBeShow)): ?>
        <?= $form->field($model, 'thumbnail')->widget(
                Upload::classname(),
                [
                    'url' => ['/article/article/avatar-upload'],
                ])->label('Thumbnail')
        ?>
    <?php endif ?>
    <?php if (in_array('attachment', $formModelAttributeToBeShow)): ?>
        <?= $form->field($modelAttachment, 'attachment')->widget(
			Upload::classname(),
			[
				'acceptFileTypes' => new JsExpression('/(\.|\/)(doc|pdf|txt)$/i'),
				'url' => ['/article/article/avatar-upload'],
			])->label('PDF')
        ?>
    <?php endif ?>

    <?php if (in_array('access_type', $formModelAttributeToBeShow)): ?>
                <?= $form->field($model, 'access_type')->widget(Select2::classname(), [
                'data' => ['Public' => 'Public', 'Private' => 'Private'], 
                'maintainOrder' => true,
                'options' => [
                    'placeholder' => '', 'multiple' => false,
                    'prompt' => ''
                ],
                'pluginOptions' => ['allowClear' => true],
            ]);
        ?>
    <?php endif ?>
    <?php if (in_array('status', $formModelAttributeToBeShow)): ?>
        <?= $form->field($model, 'status')->textInput() ?>
    <?php endif ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
