<?php

namespace ant\cms\backend\controllers;

use yii\web\Controller;
use ant\models\ContactForm;

/**
 * Default controller for the `cms` module
 */
class PageController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
		$searchModel = new ContactForm();
        $dataProvider = new \yii\data\ActiveDataProvider(['query' => $searchModel->find()]);
		//$searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->sort = [
            //'defaultOrder'=>['published_at'=>SORT_DESC]
        ];
        return $this->render('index', [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
    }
	
	public function actionDelete($id) {
		
	}
}
