<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use backend\models\Contact;

/**
 * ContactSearch represents the model behind the search form about `backend\models\Contact`.
 */
class ContactSearch extends Contact
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contact_id', 'user_id'], 'integer'],
            [['name', 'email', 'phone', 'message'], 'safe'],
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
        $query = Contact::find();

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
            'contact_id' => $this->contact_id,
            'user_id' => $this->user_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
              ->andFilterWhere(['like', 'email', $this->email])
              ->andFilterWhere(['like', 'phone', $this->phone])
              ->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }




    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function getUnusedAlContacts($device_id)
    {

        /*
        Find all contacts NOT already assigned to a specific device

        select *
        from  contact
        where  not exists (
                 select null
                 from   features_al
                 where  features_al.contact_id = contact.contact_id);
        */

        $subQuery = (new \yii\db\Query)
                        ->select(null)
                        ->from('features_al')
                        ->where('features_al.contact_id = contact.contact_id')
                        ->andWhere(['features_al.device_id' => $device_id]);

        $query = (new \yii\db\Query())
                        ->select('*')
                        ->from('contact')
                        ->where(['not exists', $subQuery]);

        //$query  =  Contact::findBySql($sql);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'key' => 'contact_id',
        ]);

/*
        if ($this->validate()) {
            return $dataProvider;
        }
*/
        return $dataProvider;
    }


    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function getUnusedRmContacts($device_id)
    {

        /*
        Find all contacts NOT already assigned to a specific device

        select *
        from  contact
        where  not exists (
                 select null
                 from   features_al
                 where  features_al.contact_id = contact.contact_id);
        */

        $subQuery = (new \yii\db\Query)
                        ->select(null)
                        ->from('features_rm')
                        ->where('features_rm.contact_id = contact.contact_id')
                        ->andWhere(['features_rm.device_id' => $device_id]);

        $query = (new \yii\db\Query())
                        ->select('*')
                        ->from('contact')
                        ->where(['not exists', $subQuery]);

        //$query  =  Contact::findBySql($sql);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'key' => 'contact_id',
        ]);

/*
        if ($this->validate()) {
            return $dataProvider;
        }
*/
        return $dataProvider;
    }



}
