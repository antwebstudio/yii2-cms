<?php

namespace ant\cms\fieldtypes;

use ant\file\widgets\Upload;
use ant\file\models\FileStorageItem;

class ImageFieldType extends FileFieldType {
	public $width;
	public $height;

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
					'done' => $this->getDoneHandler($modal),
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
							var cssClass = "upload-custom-field-group";
							var index = $(this).data("index");
							var $container = $("#'.$uploadWidgetId.'");
							var $form = $("#widget-'.$uploadWidgetId.'-" + index);
							var $modal = $("#'.$modal->id.' .modal-body");
							var $all = $("." + cssClass);

							$container.append($all);
							$all.hide();
							$form.show();
							$form.addClass(cssClass);
							$modal.append($form);
						}
					'),
				]
			],
		];
	}

	protected function getDoneHandler($modal) {
		$uploadWidgetId = $this->field->handle.'-upload';
		$modalId = $modal->id;
		$containerId = $modalId;

		if (isset($this->width) && isset($this->height)) {
			return new \yii\web\JsExpression('function() { 

				jQuery("#'.$modalId.'").on("shown.bs.modal", function(e) {
					var $image = $(this).find("img.cropper");
					var fieldId = $image.attr("field");
					var $field = $("#" + fieldId);
					var value = $image.attr("value");
					value = value != null && value.trim() != "" ? JSON.parse(value) : null;
					
					$image.cropper({
						data: value,
						aspectRatio: '.($this->width / $this->height).',
						minCropBoxWidth: '.$this->width.',
						minCropBoxHeight: '.$this->height.',
						crop: function(event) {
							$field.val(JSON.stringify(event.detail));
							
							//console.log(event.detail);
						}
					});
					var cropper = $image.data("cropper");

				});

			}');
		}

		return new \yii\web\JsExpression('function() {}');
	}
	
	protected function getAttachmentFields() {
		$width = $this->getSetting('width');
		$height = $this->getSetting('height');

		$fields = [	
			'caption' => [
				'name' => 'caption',
				'label' => 'Caption',
				'class' => 'form-control',
			],
		];
		if (isset($this->width) && isset($this->height)) {
			$fields['img'] = [
				'name' => 'cropper',
				'tag' => 'img',
				'class' => 'cropper',
				'style' => 'max-width: 100%',
			];
		}

		return $fields;
	}
}