<?php

namespace ant\cms\backend\controllers;

use yii\web\Controller;
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
class EntryController extends Controller
{
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
                    $file = $event->file;
                    $img = ImageManagerStatic::make($file->read())->resize(800);
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
}
