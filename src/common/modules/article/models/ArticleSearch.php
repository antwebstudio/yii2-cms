<?php

namespace common\modules\article\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\article\models\Article;
use common\modules\category\models\Category;
use common\modules\category\models\CategoryMap;
use yii\helpers\ArrayHelper;

/**
 * ArticleSearch represents the model behind the search form about `common\modules\article\models\Article`.
 */
class ArticleSearch extends Article
{
    /**
     * @inheritdoc
     */
    public $searchStringArticleIndex;
    public $categoryId;
    public $categoryType;

    public function rules()
    {
        return [
            [['id', 'author_id', 'updater_id', 'status', 'published_at', 'created_at',
             'updated_at'], 'integer'],
            [['slug', 'title', 'subtitle', 'body', 'view', 'thumbnail_base_url',
             'thumbnail_path', 'access_type', 'searchStringArticleIndex'], 'safe'],
            [['categories', 'categoryId', 'categoryType'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $this->load($params);
        $query = Article::find()->alias('article');
		
		if (isset($this->categoryId) || isset($this->categoryType)) {
			$query->joinWith('categories category');
		}
		
		if (isset($this->categoryId)) {
			$query->filterByCategoryId($this->categoryId);
		}

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        
        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if ($this->searchStringArticleIndex != null) {
            $query->andFilterWhere(['or', 
				['like', 'article.title' , $this->searchStringArticleIndex],
				['like', 'article.body' , $this->searchStringArticleIndex],
			]);
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'author_id' => $this->author_id,
            'updater_id' => $this->updater_id,
            'status' => $this->status,
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            //'category.type' => $this->categoryType,
        ]);

        $query->andFilterWhere(['like', 'article.title', $this->title])
            ->andFilterWhere(['like', 'subtitle', $this->subtitle])
            ->andFilterWhere(['like', 'article.body', $this->body])
            ->andFilterWhere(['like', 'view', $this->view])
            ->andFilterWhere(['like', 'thumbnail_base_url', $this->thumbnail_base_url])
            ->andFilterWhere(['like', 'thumbnail_path', $this->thumbnail_path])
			//->andFilterWhere(['like', 'article.slug', $this->slug])
            ->andFilterWhere(['like', 'access_type', $this->access_type]);

        return $dataProvider;
    }
}
