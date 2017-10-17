<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Subscription;

/**
 * SubscriptionSearch represents the model behind the search form about `backend\models\Subscription`.
 */
class SubscriptionSearch extends Subscription
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subscription_id', 'user_id', 'site_id', 'device_id', 'mailing_use', 'billing_use'], 'integer'],
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
        $query = Subscription::find();

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
            'subscription_id' => $this->subscription_id,
            'user_id' => $this->user_id,
            'site_id' => $this->site_id,
            'device_id' => $this->device_id,
            'mailing_use' => $this->mailing_use,
            'billing_use' => $this->billing_use,
        ]);

        return $dataProvider;
    }
}
