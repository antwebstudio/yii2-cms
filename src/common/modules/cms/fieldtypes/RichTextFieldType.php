<?php

namespace common\modules\cms\fieldtypes;

class RichTextFieldType extends \common\modules\cms\components\FieldType {
	protected $_columnType = 'mediumtext';
	
	public function getName() {
		return 'Rich Text';
	}
	
	public function input($form, $model) {
		$language = strtolower(\Yii::$app->language);
		if ($language == 'zh-cn' || $language == 'zh-tw') $language = str_replace('-', '_', $language);
		if ($language == 'en-us') $language = null;
		
		return $form->field($model, $this->field->handle)->widget(
			\yii\imperavi\Widget::className(),
			[
				'plugins' => ['fullscreen', 'fontcolor', 'video'],
				'options' => [
					'lang' => $language,
					'minHeight' => 400,
					'maxHeight' => 400,
					'buttonSource' => true,
					'convertDivs' => false,
					'removeEmptyTags' => false,
					'imageUpload' => \Yii::$app->urlManager->createUrl(['/file-storage/upload-imperavi'])
				]
			]
		);
	}
}