<?php

namespace frontend\modules\article\controllers;

use Yii;
use yii\web\Controller;

class DefaultController extends Controller
{
    public function actionIndex() {
        if (isset($this->module->landingUrl)) {
            $url = is_callable($this->module->landingUrl) ? call_user_func_array($this->module->landingUrl, []) : $this->module->landingUrl;
            return $this->redirect($url);
        }
        return $this->render($this->action->id);
    }
}