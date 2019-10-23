<?php

namespace ant\cms\fieldtypes;

use trntv\filekit\widget\Upload;
use ant\file\models\FileStorageItem;

class ImageFieldType extends FileFieldType {
	
	protected $_createColumn = false;
	//protected $_columnType = 'varchar(255)';
	
	public function getName() {
		return 'Image';
	}
	
	public function input($form, $model) {
		return $form->field($model, $this->field->handle)->widget(
            Upload::classname(),
            [
                'url' => ['image-upload'],
            ]
        );
	}
}