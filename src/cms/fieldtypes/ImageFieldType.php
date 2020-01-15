<?php

namespace ant\cms\fieldtypes;

use ant\file\widgets\Upload;
use ant\file\models\FileStorageItem;

class ImageFieldType extends FileFieldType {
	
	protected $_createColumn = false;
	//protected $_columnType = 'varchar(255)';
	
	public function getName() {
		return 'Image';
	}
	
	public function input($form, $model) {
		$uploadWidgetId = $this->field->handle.'-upload';
		//$modal = \ant\widgets\Modal::begin();
		//$modalHtml = \ant\widgets\Modal::end();
		ob_start();
		$modal = \yii\bootstrap4\Modal::begin();
		\yii\bootstrap4\Modal::end();
		
		$modalHtml = ob_get_contents();
		ob_end_clean();
		
		return $form->field($model, $this->field->handle)->widget(
            Upload::classname(),
            [
				'id' => $uploadWidgetId,
				'form' => $form,
				'fields' => $this->getAttachmentFields(),
				'maxNumberOfFiles' => $this->maxFile,
				'clientOptions' => [
					'buttons' => $this->getButtons($modal),
				],
				'multiple' => true, // If remove will cause error in AttachmentBehavior which currently not support non-multiple
                'url' => ['image-upload'],
            ]
        ).$modalHtml;
	}
	
	protected function getButtons($modal) {
		$uploadWidgetId = $this->field->handle.'-upload';
		
		return [
			'remove' => [
				'class' => 'uploader-btn glyphicon glyphicon-remove-circle remove fas fa-times-circle',
			],
			'edit' => [
				'class' => 'uploader-btn glyphicon glyphicon-pencil edit fas fa-edit',
				'data-toggle' => 'modal',
				'data-target' => '#'.$modal->id,
				'events' => [
					'click' => new \yii\web\JsExpression('
						function() {
							var index = $(this).data("index");
							var $form = $("#widget-'.$uploadWidgetId.'-" + index);
							//$(".upload-custom-field-group").hide();
							$form.show();
							$("#'.$modal->id.' .modal-body").append($form);
						}
					'),
				]
			],
		];
	}
	
	protected function getAttachmentFields() {
		return [
			'caption' => [
				'name' => 'caption',
				'label' => 'Caption',
				'class' => 'form-control',
			],
		];
	}
}