<?php

namespace backend\modules\article\controllers;

use Yii;
use common\modules\article\models\Article;
use common\modules\article\models\ArticleSearch;
use common\modules\article\models\ArticleAttachment;
use common\modules\category\models\Category;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use trntv\filekit\actions\DeleteAction;
use trntv\filekit\actions\UploadAction;
use yii\filters\VerbFilter;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actions(){
        return  [
            'avatar-upload' =>
            [
                'class' => UploadAction::className(),
                'deleteRoute' => 'avatar-delete',
            ],
            'avatar-delete' => [
                'class' => DeleteAction::className()
            ],
        ];
    }

    /**
     * Lists all Article models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

        ]);
    }

    /**
     * Displays a single Article model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($type = 'article')
    {
        $formModelAttributeToBeShow = null;
        if (isset(Yii::$app->getModule('article')->formModelArticleAttributeToBeShow)) {
            $formModelAttributeToBeShow = Yii::$app->getModule('article')->formModelArticleAttributeToBeShow;
        } else {
            $formModelAttributeToBeShow = Yii::$app->getModule('article')->defaultFormArticleModelAttributeToBeShow;
        }
        $model = Yii::createObject(Yii::$app->getModule('article')->model['default']['model']['article']);
        $modelAttachment = new ArticleAttachment();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $modelAttachment->article_id = $model->id;
            $modelAttachment->load(Yii::$app->request->post());
            $modelAttachment->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
                'modelAttachment' => $modelAttachment,
                'formModelAttributeToBeShow' => $formModelAttributeToBeShow,
                'categories' => Category::find()->andWhere(['type' => $type])->all(),
                'type' => $type,
            ]);
        }
    }

    /**
     * Updates an existing Article model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {   
        $category = Category::findOne($id);
        $formModelAttributeToBeShow = null;
        if (isset(Yii::$app->getModule('article')->formModelArticleAttributeToBeShow)) {
            $formModelAttributeToBeShow = Yii::$app->getModule('article')->formModelArticleAttributeToBeShow;
        } else {
            $formModelAttributeToBeShow = Yii::$app->getModule('article')->defaultFormArticleModelAttributeToBeShow;
        }
        $modelAttachment = ArticleAttachment::find()->andWhere(['article_id' => $id])->one();
		if (!isset($modelAttachment)) $modelAttachment = new ArticleAttachment ();
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $modelAttachment->article_id = $model->id;
            $modelAttachment->load(Yii::$app->request->post());
            $modelAttachment->save();
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
                'modelAttachment' => $modelAttachment,
                'formModelAttributeToBeShow' => $formModelAttributeToBeShow,
                'categories' => Category::find()->andWhere(['type' => $category->type])->all(),
            ]);
        }
    }

    /**
     * Deletes an existing Article model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Article model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Article the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        $modelClass = Yii::$app->getModule('article')->model['default']['model']['article']['class'];
        if (($model = $modelClass::findOne($id)) !== null) {
            isset(Yii::$app->getModule('article')->model['default']['model']['article']['scenario']) ? $model->scenario = Yii::$app->getModule('article')->model['default']['model']['article']['scenario'] : 'default';
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
