<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ParametersAl;

/**
 * ParametersAlSearch represents the model behind the search form about `backend\models\ParametersAl`.
 */
class ParametersAlSearch extends ParametersAl
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parameters_id', 'device_id', 'user_id', 'mem_cnt', 'c1_cnt', 'c2_cnt', 't1_hour', 't1_min', 't2_hour', 't2_min', 'sludge_days', 'sludge_sec', 'air_on', 'air_off'], 'integer'],
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
        $query = ParametersAl::find();

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
            'parameters_id' => $this->parameters_id,
            'device_id' => $this->device_id,
            'user_id' => $this->user_id,
            'mem_cnt' => $this->mem_cnt,
            'c1_cnt' => $this->c1_cnt,
            'c2_cnt' => $this->c2_cnt,
            't1_hour' => $this->t1_hour,
            't1_min' => $this->t1_min,
            't2_hour' => $this->t2_hour,
            't2_min' => $this->t2_min,
            'sludge_days' => $this->sludge_days,
            'sludge_sec' => $this->sludge_sec,
            'air_on' => $this->air_on,
            'air_off' => $this->air_off,
        ]);

        return $dataProvider;
    }
}
