<?php

namespace ant\cms\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use ant\cms\models\Entry;

/**
 * EntrySearch represents the model behind the search form of `ant\cms\models\Entry`.
 */
class EntrySearch extends Entry
{
	public $q;
	public $type;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'section_id', 'content_uid'], 'integer'],
            [['q', 'created_date', 'last_updated', 'published_date', 'expire_date'], 'safe'],
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
        $query = Entry::find();
		
		$query->joinWith('contentData data');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        /*if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }*/

        // grid filtering conditions
		if (isset($this->type)) {
			$query->type($this->type);
		}
		
		if (isset($this->q)) {
			$query->andFilterWhereQueryString($this->q);
		}
		
        $query->andFilterWhere([
            'id' => $this->id,
            'section_id' => $this->section_id,
            'created_date' => $this->created_date,
            'last_updated' => $this->last_updated,
            'content_uid' => $this->content_uid,
            'published_date' => $this->published_date,
            'expire_date' => $this->expire_date,
        ]);

        return $dataProvider;
    }
}
