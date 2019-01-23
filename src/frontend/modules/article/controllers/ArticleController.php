<?php

namespace frontend\modules\article\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\ForbiddenHttpException;
use trntv\filekit\actions\DeleteAction;
use trntv\filekit\actions\UploadAction;
use common\modules\user\models\User;
use common\modules\article\models\Article;
use common\modules\article\models\ArticleCategory;
use common\modules\article\models\ArticleSearch;
use common\modules\article\models\ArticleAttachment;
use common\modules\subscription\models\Subscription;
use common\modules\category\models\CategoryMap;
use common\modules\category\models\Category;
use common\modules\category\models\CategoryType;

/**
 * ArticleController implements the CRUD actions for Article model.
 */
class ArticleController extends Controller
{
	public $enableCsrfValidation = false;
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
            'access' =>
            [
                'class' => \common\rbac\ModelAccessControl::className(),
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

    public function init()
    {
        // echo "<pre>";
        // print_r($this->uniqueid);
        // echo "</pre>";
        // die;
        // throw new \yii\web\ForbiddenHttpException(Yii::t('yii', 'test'));
        parent::init();
    }

    protected function checkSubscribe() {
        $model = new Subscription();    
        $url = '/casey/member/index';
        $param = [];
        if(Yii::$app->user->can(\common\rbac\Permission::of('index', get_class($model)
        )->name)) {
            return false;
        } else {
            \Yii::$app->getSession()->setFlash('error', 'Article requires subscription to see');
            return $this->redirect([$url, $param]);
        }
    }

    public function actionIndex($categoryId = null, $type = 'article'){
        // Yii::$app->user->isGuest || 
        //         !Subscription::find()
        //         ->andWhere(['owned_by' => Yii::$app->user->id])
        //         ->andWhere(['>', 'expire_date', date('Y-m-d H:i:s')])

        $needSubscriptionOrGuest = false; // upper query used to check guest or got subscription
        $validateSubscribed = true;
        if($validateSubscribed) {
            $redirect = $this->checkSubscribe();
            if ($redirect) {
                return $redirect;
            }
        }

        if($needSubscriptionOrGuest) {
            $public = true;
        } else {
            $public = false;
        }
        if ($public) {
            $search = [
                'ArticleSearch' => [
                    'access_type' => 'public'
                ]
            ];
            $message = 'You are not subscribed member. So only public articles will be shown.';
        } else {
            $search = [];
            $message = null;
        }
        if (isset($this->module->articleIndexSearch)) {
            $search = $this->module->articleIndexSearch;
        }
        
        $searchModel = new ArticleSearch();
        $searchModel->categoryId = $categoryId;
        $searchModel->categoryType = $type;
        $dataProvider = $searchModel->search(ArrayHelper::merge(Yii::$app->request->queryParams, $search));

        $this->view->params['articleCategoryNav'] = $this->getArticleCategory($type);

        // category id is not set when articles from all categories is listed.
        $category = Category::findOne($categoryId);
        if (isset($category)) {
            $this->checkCategoryTypeAccess($category->type);
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'message' => $message,
         ]);
    }

    protected function checkAccess($model, $throwException = true) {
        foreach ($model->getCategories()->all() as $category) {
            if ($this->checkCategoryTypeAccess($category->type, false)) {
                return true;
            }
        }
        if ($throwException) throw new \yii\web\ForbiddenHttpException('Permission Denied. ');
    }
    
    protected function checkCategoryTypeAccess($type, $throwException = true) {
        $permission = \common\rbac\Permission::of('index', Category::className())->type($type);

        if (!\Yii::$app->user->can($permission->name)) {
            if ($throwException) throw new \yii\web\ForbiddenHttpException('Permission Denied. '.(YII_DEBUG ? '('.$permission->name.')' : ''));
            return false;
        }
        return true;
    }

    protected function getArticleCategory($type = 'article') {
        $this->checkCategoryTypeAccess($type);
        /*$articleCategory = Category::find()
        ->andWhere(['type' => 'article'])
        ->select('id')
        ->asArray()
        ->all();
        $ids = array_map('current',$articleCategory);
        //throw new \Exception(print_r($ids,1));

        $categoryType = CategoryType::find()
        ->andWhere(['type' => 'article']) //maybe can article personal etc
        ->andWhere(['model' => Article::className()])
        ->select('id')
        ->asArray()
        ->all();

        $typeId = array_map('current',$categoryType);
        //throw new \Exception(print_r($typeId,1));
        $categoryMap = CategoryMap::find()
        ->andWhere(['category_id' => $ids])
        ->andWhere(['type_id' => $typeId])
        ->all();

        $categoryIds = [];
        foreach ($categoryMap as $key => $map) {
            $categoryIds[] = $map->category_id;
        }*/

        return Category::find()->andWhere(['type' => $type])->all();
    }

    public function actionCompanyIndex()
    {
        $searchModel = new ArticleSearch();
        $dataProvider = $searchModel->search(ArrayHelper::merge(Yii::$app->request->queryParams, [
                'ArticleSearch' => [
                    'author_id' => Yii::$app->user->id,
                ]
            ]) 
        );

        return $this->render('company-index', [
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
        $model = Article::findOne($id);
        $this->checkAccess($model);

        if (strtolower($model->access_type) != 'public') {
            if (Yii::$app->user->isGuest || 
                    !Subscription::find()
                    ->andWhere(['owned_by' => Yii::$app->user->id])
                    ->andWhere(['>', 'expire_date', date('Y-m-d H:i:s')])
                    ->one()
                ) {
                throw new ForbiddenHttpException(Yii::t('yii', 'You are not allowed to perform this action. This article is not public'));
            }
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Article model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $formModelAttributeToBeShow = null;
        if (isset(Yii::$app->getModule('article')->formModelArticleAttributeToBeShow)) {
            $formModelAttributeToBeShow = Yii::$app->getModule('article')->formModelArticleAttributeToBeShow;
        } else {
            $formModelAttributeToBeShow = Yii::$app->getModule('article')->defaultFormArticleModelAttributeToBeShow;
        }
        $model = Yii::createObject(Yii::$app->getModule('article')->model['default']['model']['article']);
        $modelAttachment = new ArticleAttachment ();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $modelAttachment->article_id = $model->id;
            $modelAttachment->load(Yii::$app->request->post());
            $modelAttachment->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'modelAttachment' => $modelAttachment,
                'formModelAttributeToBeShow' => $formModelAttributeToBeShow
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
        $formModelAttributeToBeShow = null;
        if (isset(Yii::$app->getModule('article')->formModelArticleAttributeToBeShow)) {
            $formModelAttributeToBeShow = Yii::$app->getModule('article')->formModelArticleAttributeToBeShow;
        } else {
            $formModelAttributeToBeShow = Yii::$app->getModule('article')->defaultFormArticleModelAttributeToBeShow;
        }
        $modelAttachment = ArticleAttachment::find()->andWhere(['article_id' => $id])->one();
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'modelAttachment' => $modelAttachment,
                'formModelAttributeToBeShow' => $formModelAttributeToBeShow,
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
        if (($model = Article::findOne($id)) !== null) {
            if ($model->author_id == Yii::$app->user->identity->id) {
                return $model;
            }
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        throw new NotFoundHttpException('The Article is not created by you.');
    }
}
