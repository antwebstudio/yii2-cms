<?php

namespace frontend\modules\cms\controllers;

use ant\cms\models\Entry;
use ant\cms\components\Content;

class EntryController extends \yii\web\Controller
{
    public function actionIndex($type)
    {
        return $this->render('index', [
			'type' => $type
		]);
    }
	
	public function actionView($uid) {
		
		return $this->render('view', [
			'entry' => Content::findByUid($uid),
		]);
	}

}
