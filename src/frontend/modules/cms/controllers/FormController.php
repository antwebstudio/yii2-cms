<?php

namespace frontend\modules\cms\controllers;

class FormController extends \yii\web\Controller
{
	public $layout = '//main';
	
    public function actionIndex()
    {
        return $this->render('index');
    }
	
	public function actionRegister() {
		$model = new \common\models\ContactForm;
		$model->created_ip = \Yii::$app->request->userIp;
		if ($model->load(\Yii::$app->request->post()) && $model->save()) {
			\Yii::$app->session->setFlash('success', \Yii::t('app', 'Form successfully submitted'));
			return $this->redirect(['register']);
		}
		return $this->render('register', ['model' => $model]);
	}

}
