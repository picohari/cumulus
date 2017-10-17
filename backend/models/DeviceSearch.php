<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\Device;

/**
 * DeviceSearch represents the model behind the search form about `backend\models\Device`.
 */
class DeviceSearch extends Device
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device_id', 'user_id', 'product_id'], 'integer'],
            [['uuid', 'name', 'version', 'status', 'description'], 'safe'],
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
        $query = Device::find();

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
            'device_id' => $this->device_id,
            'user_id' => $this->user_id,
            'product_id' => $this->product_id,
        ]);

        $query->andFilterWhere(['like', 'uuid', $this->uuid])
              ->andFilterWhere(['like', 'name', $this->name])
              ->andFilterWhere(['like', 'version', $this->version])
              ->andFilterWhere(['like', 'status', $this->status])
              ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSiteDevices($site_id)
    {

        $query = Device::find()

        ->joinWith('subscription' , 'device.device_id = subscription.device_id')
        ->where(['subscription.site_id' => $site_id])
        ->andWhere(['device.user_id' => Yii::$app->user->id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'key' => 'device_id',
            'sort' => [
                'defaultOrder' => [
                    'name' => SORT_ASC,
                ]
            ],
        ]);

        
        if ($this->validate()) {
            return $dataProvider;
        }
        

        //return $dataProvider;
    }

}
