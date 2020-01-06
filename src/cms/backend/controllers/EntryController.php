<?php

namespace ant\cms\backend\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use trntv\filekit\actions\DeleteAction;
use trntv\filekit\actions\UploadAction;
use Intervention\Image\ImageManagerStatic;
use ant\cms\models\Entry;
use ant\cms\models\Category;
use ant\cms\models\EntryType;
use ant\cms\components\Content;

/**
 * Default controller for the `cms` module
 */
class EntryController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'delete' => ['post']
                ]
            ]
        ];
    }
	
    public function actions()
    {
        return [
            'file-upload' => [
                'class' => UploadAction::className(),
                'deleteRoute' => 'file-delete',
            ],
            'image-upload' => [
                'class' => UploadAction::className(),
                'deleteRoute' => 'file-delete',
                'on afterSave' => function ($event) {
					ini_set('memory_limit','256M');
                    /* @var $file \League\Flysystem\File */
					$sizeConfig = ['fit', 'width' => 800];
					
                    $file = $event->file;
                    $img = ImageManagerStatic::make($file->read());
					
					if (isset($sizeConfig['width']) && !isset($sizeConfig['height'])) {
						$sizeConfig['height'] = (int) ($img->height() / $img->width() * $sizeConfig['width']);
					}
					
					$method = array_shift($sizeConfig);
					call_user_func_array([$img, $method], $sizeConfig);
					$file->put($img->encode());
                }
            ],
            'file-delete' => [
                'class' => DeleteAction::className()
            ],
        ];
    }
	
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex($type)
    {
		$entryType = EntryType::findOne(['handle' => $type]);
		$className = $entryType->content_type;
		
		if (strpos($className, '\\') === false) $className = '\ant\cms\models\\'.$className;
		
		$dataProvider = new ActiveDataProvider([
			'query' => $className::find()->type($type),
			'key' => 'content_uid',
		]);
		
        return $this->render('index', [
			'entryType' => $entryType,
			'dataProvider' => $dataProvider,
		]);
    }
	
	// Note: $id here is content_uid
	public function actionUpdate($id) {
		$model = Content::findByUid($id);
		
		if ($model->load(\Yii::$app->request->post())) {
			if ($model->save()) {
				return $this->redirect(['index', 'type' => $model->entryType->handle]);
			}
		}
		
        return $this->render('update', [
			'model' => $model,
		]);
	}
	
	public function actionCreate($type) {
		$entryType = EntryType::findOne(['handle' => $type]);
		$className = $entryType->content_type;
		
		$model = new $className(['entryType' => $entryType]);
		//$model->setEntryType($entryType);
		
		if ($model->load(\Yii::$app->request->post())) {
			if ($model->save()) {
				return $this->redirect(['index', 'type' => $model->entryType->handle]);
			}
		}
		
        return $this->render('update', [
			'model' => $model,
		]);
	}
	
	public function actionDelete($id) {
		$model = Content::findByUid($id);
		
		if ($model->delete()) {
			Yii::$app->session->setFlash('success', 'Sucessfully deleted. ');
				return $this->redirect(['index', 'type' => $model->entryType->handle]);
		} else {
			Yii::$app->session->setFlash('error', 'Failed to delete. ');
				return $this->redirect(['index', 'type' => $model->entryType->handle]);
		}
		
	}
}
