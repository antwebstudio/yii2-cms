<?php
namespace ant\comment\controllers;

use Yii;
use ant\comment\models\Comment;

/**
 * CommentController for the `comment` module
 */
class CommentController extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
			'access' => [
				'class' => \ant\rbac\ModelAccessControl::className(),
			],
            'verbs' => [
                'class' => \yii\filters\VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
	
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render($this->action->id);
    }
	
	public function actionCreate() {
		$model = $this->module->getFormModel('comment');
		
		if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(Yii::$app->request->referrer);
		}
		throw new \Exception(print_r($model->errors, 1));
		
		return $this->render($this->action->id, [
			'model' => $model,
		]);
	}
	
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(Yii::$app->request->referrer);
        }
		throw new \Exception(print_r($model->errors, 1));
		
		return $this->render($this->action->id, [
			'model' => $model,
		]);
    }
	
	public function actionCreateAnnonymomus() {
		$model = new Comment;
		
		if ($model->load(\Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(\yii\helpers\Url::back());
		}
		
		return $this->render($this->action->id, [
			'model' => $model,
		]);
		
	}
	
	public function actionDeleteAnnonymomus() {
		$model = new Comment;
		
		if ($model->load(\Yii::$app->request->post()) && $model->save()) {
			return $this->redirect(\yii\helpers\Url::back());
		}
		
		return $this->render($this->action->id, [
			'model' => $model,
		]);
		
	}

    /**
     * Deletes an existing Comment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        
        if (!Yii::$app->user->can('admin')) {
            $this->checkAccess('delete', $model);
        }
		
		$model->delete();

        return $this->redirect(\Yii::$app->request->referrer);
    }
	
    protected function findModel($id)
    {
        if (($model = Comment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
