<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Data;

/**
 * DataSearch represents the model behind the search form of `app\models\Data`.
 */
class DataSearch extends Data
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'code', 'name', 'card', 'discount', 'age', 'born_year'], 'integer'],
            [['user_name', 'gender', 'born_date', 'email', 'phone', 'comment', 'subscribe'], 'safe'],
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
        $query = Data::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['card' => SORT_DESC]],
            'pagination' => [
                'pageSize' => 1000,
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            //'card' => $this->card,
            'discount' => $this->discount,
            'age' => $this->born_date,
            'born_year' => $this->born_date,
            'born_date' => $this->born_date,
        ]);

        $query->andFilterWhere(['like', 'user_name', $this->user_name])
            ->andFilterWhere(['like', 'card', $this->card])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'comment', $this->comment])
            ->andFilterWhere(['like', 'subscribe', $this->subscribe]);

        return $dataProvider;
    }
}
