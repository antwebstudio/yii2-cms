<?php

namespace backend\modules\cms\controllers;

use yii\web\Controller;
use ant\models\ContactForm;

/**
 * Default controller for the `cms` module
 */
class FormController extends Controller
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
	
	public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
	
	protected function findModel($id)
    {
        if (($model = ContactForm::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
