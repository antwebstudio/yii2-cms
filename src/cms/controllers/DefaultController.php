<?php

namespace ant\cms\controllers;

use yii\web\Controller;
use ant\cms\models\EntrySearch;

/**
 * Default controller for the `cms` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render($this->action->id);
    }
	
	public function actionSearch($q, $type = null) {
		$searchModel = new EntrySearch;
		$searchModel->q = $q;
		$searchModel->type = $type;
		$dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
		
		return $this->render($this->action->id, [
			'searchModel' => $searchModel,
			'dataProvider' => $dataProvider,
		]);
	}
}
