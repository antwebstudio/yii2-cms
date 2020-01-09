<?php

namespace ant\cms\fieldtypes;

use ant\models\ModelClass;

class RichTextFieldType extends \ant\cms\components\FieldType {
	protected $_columnType = 'mediumtext';
	
	public function getName() {
		return 'Rich Text';
	}
	
	public function input($form, $model) {
		$language = strtolower(\Yii::$app->language);
		if ($language == 'zh-cn' || $language == 'zh-tw') $language = str_replace('-', '_', $language);
		if ($language == 'en-us') $language = null;
		
		return $form->field($model, $this->field->handle)->widget(\ant\widgets\TinyMce::className(), [
			'fileFinder' => [
				'url' => [
					'/file/elfinder/tinymce', 
					'model_id' => $model->id, 
					'model_class_id' => ModelClass::getClassId($model),
				],
			],
			//'plugins' => ['fullscreen', 'fontcolor', 'video'],
			'clientOptions' => [
				'relative_urls' => false,
				'lang' => $language,
				'minHeight' => 800,
				'buttonSource' => true,
				'convertDivs' => false,
				'removeEmptyTags' => false,
				//'imageUpload' => \Yii::$app->urlManager->createUrl(['/file-storage/upload-imperavi'])
			]
		]);
	}
}