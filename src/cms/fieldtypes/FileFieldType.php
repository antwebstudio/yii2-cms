<?php

namespace ant\cms\fieldtypes;

use trntv\filekit\widget\Upload;
use trntv\filekit\File;
use \ant\file\models\FileStorageItem;

class FileFieldType extends \ant\cms\components\FieldType {
	public $uploadPath;
	public $sourceFromDb;
	public $allowedMime;
	public $minFile = 0;
	public $maxFile = 3;
	
	protected $_file = [];
	protected $_createColumn = false;
	//protected $_columnType = 'varchar(255)';
	
	public function getName() {
		return 'File';
	}
	
	public function entryBehaviors() {
		return [
            [
                'class' => \ant\file\behaviors\AttachmentBehavior::className(),
                'attribute' => $this->field->handle,
                'type' => $this->field->handle,
            ],
		];
	}
	
	public function input($form, $model) {
		return $form->field($model, $this->field->handle)->widget(
            Upload::classname(),
            [
				'multiple' => true, // If remove will cause error in AttachmentBehavior which currently not support non-multiple
				'maxNumberOfFiles' => $this->maxFile,
                'url' => ['file-upload'],
            ]
        );
	}
}