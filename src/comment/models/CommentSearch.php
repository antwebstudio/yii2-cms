<?php

namespace ant\comment\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use ant\comment\models\Comment;

/**
 * CommentSearch represents the model behind the search form of `ant\comment\models\Comment`.
 */
class CommentSearch extends Comment
{
	public $created_at_range;
	public $created_at_start;
	public $created_at_end;
	
	public function behaviors() {
		return [
			[
				'class' => \ant\behaviors\DateTimeRangeBehavior::className(),
				'attribute' => 'created_at_range',
				'dateStartAttribute' => 'created_at_start',
				'dateEndAttribute' => 'created_at_end',
			],
		];
	}
	
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
			[['created_at_range'], 'safe'],
            [['id', 'model_id', 'model_class_id', 'status', 'created_by', 'updated_by'], 'integer'],
            [['author_name', 'title', 'body', 'created_at', 'updated_at'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
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
        $query = Comment::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
		
		$query->andFilterWhere(['between', 'date(created_at)', $this->created_at_start, $this->created_at_end]);

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'model_id' => $this->model_id,
            'model_class_id' => $this->model_class_id,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'created_by' => $this->created_by,
            'updated_at' => $this->updated_at,
            'updated_by' => $this->updated_by,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
			->andFilterWhere(['like', 'author_name', $this->author_name])
            ->andFilterWhere(['like', 'body', $this->body]);

        return $dataProvider;
    }
}
