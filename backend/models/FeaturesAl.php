<?php

namespace backend\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "features_al".
 *
 * @property integer $feature_id
 * @property integer $power_fail
 * @property integer $pump_fail
 * @property integer $connection_lost
 * @property integer $contact_id
 *
 * @property Contact $contact
 */
class FeaturesAl extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'features_al';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contact_id'], 'required'],
            [['contact_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contact::className(), 'targetAttribute' => ['contact_id' => 'contact_id']],
            [['feature_id', 'power_fail', 'pump_fail', 'connection_lost', 'contact_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'feature_id' => Yii::t('app', 'Feature ID'),
            'contact_id' => Yii::t('app', 'Contact ID'),
            'power_fail' => Yii::t('app', 'Power Fail'),
            'pump_fail' => Yii::t('app', 'Pump Fail'),
            'connection_lost' => Yii::t('app', 'Connection Lost'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContact()
    {
        return $this->hasOne(Contact::className(), ['contact_id' => 'contact_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevices()
    {
        return $this->hasMany(Device::className(), ['device_id' => 'device_id']);
    }

    /**
     * @inheritdoc
     * @return FeaturesAlQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FeaturesAlQuery(get_called_class());
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

/*
        $query = (new \yii\db\Query())
            ->select('*')
            ->from('features_al')
            ->leftJoin('contact', 'contact.contact_id = features_al.contact_id')
            ->where(['features_al.device_id' => $this->device_id])
            ->all();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'key' => 'contact_id',
        ]);
*/
     
        $query = FeaturesAl::find()
            ->leftJoin('contact', 'contact.contact_id = features_al.contact_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

/*
        // grid filtering conditions
        $query->andFilterWhere([
            'contact_id' => $this->contact_id,
            'device_id' => $this->device_id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
              ->andFilterWhere(['like', 'email', $this->email])
              ->andFilterWhere(['like', 'phone', $this->phone])
              ->andFilterWhere(['like', 'message', $this->message]);
*/
        return $dataProvider;
    }

}
