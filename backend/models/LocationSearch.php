<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Location;

/**
 * LocationSearch represents the model behind the search form about `backend\models\Location`.
 */
class LocationSearch extends Location
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['site_id', 'user_id'], 'integer'],
            [['name', 'first_address', 'last_address', 'post_code', 'city', 'country_code', 'site_description'], 'safe'],
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
        $query = Location::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'user_id' => $this->user_id,
            'site_id' => $this->site_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
              ->andFilterWhere(['like', 'first_address', $this->first_address])
              ->andFilterWhere(['like', 'last_address', $this->last_address])
              ->andFilterWhere(['like', 'post_code', $this->post_code])
              ->andFilterWhere(['like', 'city', $this->city])
              ->andFilterWhere(['like', 'country_code', $this->country_code])
              ->andFilterWhere(['like', 'site_description', $this->site_description]);

        return $dataProvider;
    }
}
