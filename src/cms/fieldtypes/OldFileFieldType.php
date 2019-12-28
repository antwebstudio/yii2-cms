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
	
	public function prepareValue($value) {
		if (is_array($value) && isset($value[0])) {
			$return = [];
			foreach ($value as $v) {
				$return[] = $this->prepareValue($v);
			}
			return $return;
		}
		
		if (isset($value['id'])) $value = $value['id'];
		
		if (is_array($value)){
			// Check if recorded needed is loaded and stored in $this->_file (should be loaded on afterContentFind), if not, load from db.
			$model = [];
			$loadFromDb = false;
			foreach ($value as $v) {
				if (isset($this->_file[$v])) {
					$model[] = $this->_file[$v];
				} else {
					$loadFromDb = true;
				}
			}
			if ($loadFromDb) $model = FileStorageItem::findAll($value);
		
			//if (!isset($model)) throw new \Exception('File IDs: '.implode(', ', $value).' not found. ');
		
			return $model;
		} else {
			$model = FileStorageItem::findOne($value);
		
			//if (!isset($model)) throw new \Exception('File ID: '.$value.' not found. ');
		
			return $model;
		}
	}
	
	protected function getWidgetParams($value) {
		if (is_array($value) && isset($value[0])) {
			$return = [];
			foreach ($value as $v) {
				$return[] = $this->getWidgetParams($v);
			}
			return $return;
		} else {
			if (is_numeric($value)) {
				$file = FileStorageItem::findOne($value);
				
				if (isset($file)) {
					$this->_file[$file->id] = $file;
					return [
						'id' => $file->id,
						'name' => $file->name,
						'size' => $file->size,
						'type' => $file->type,
						'path' => $file->path,
						'base_url' => $file->base_url,
					];
				}
			} else if (is_numeric(base64_decode($value))) {
				// To support old cms, once all old cms migration is done, this can be removed.
				return $value;
			} else if (is_array($value)) {
				throw new \Exception('Wrong value saved. It is already in params. '.print_r($value,1));
			} else if (strlen(trim($value))) {
				$filename = $value;
				$filename = 'form.pdf';
				$path = \Yii::getAlias('@storage/web/source');
				$file = File::create($path.'/'.$this->uploadPath.'/'.$filename);
				
				return [
					'name' => $value,
					//'size' => $file->size,
					'type' => $file->mimeType,
					'path' => $value,
					'base_url' => '',
				];
			} else {
				return '';
			}
		}
	}
	
	protected function getIdsFromParams($params) {
		if (isset($params[0])) {
			$return = [];
			foreach ($params as $p) {
				$return[] = $this->getIdsFromParams($p);
			}
			return $return;
		} else if (isset($params['path'])) {
			$file = FileStorageItem::findOne(['path' => $params['path']]);
			if (!isset($file)) {
				throw new \Exception('Failed to save '.$this->className().'： '. $params['path']);
				//\Yii::$app->fileStorage->save($params['path']);
			}
			return $file->id;
		} else {
			return '';
		}
	}
	
	public function afterContentFind($event) {
		$field = $this->field;
		$model = $event->model;
		
		$model->{$field->handle} = $this->getWidgetParams($model->{$field->handle});
	}
	
	public function beforeContentValidate($event) {
		$field = $this->field;
		$model = $event->model;
		
		$model->{$field->handle} = $this->getIdsFromParams($model->{$field->handle});
	}
	
	public function afterContentInsert($event) {
		$this->afterContentUpdate($event);
	}
	
	public function afterContentUpdate($event) {
		$field = $this->field;
		$model = $event->model;
		
		$model->{$field->handle} = $this->getWidgetParams($model->{$field->handle});
	}
	
	public function input($form, $model) {
		return $form->field($model, $this->field->handle)->widget(
            Upload::classname(),
            [
				'maxNumberOfFiles' => $this->maxFile,
                'url' => ['file-upload'],
            ]
        );
	}
}