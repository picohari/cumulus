<?php

namespace backend\models;

use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "features_rm".
 *
 * @property integer $feature_id
 * @property integer $power_fail
 * @property integer $pump_fail
 * @property integer $connection_lost
 * @property integer $contact_id
 *
 * @property Contact $contact
 */
class FeaturesRm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'features_rm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contact_id'], 'required'],
            [['feature_id', 'power_fail', 'pump_fail', 'connection_lost', 'contact_id'], 'integer'],
            [['contact_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contact::className(), 'targetAttribute' => ['contact_id' => 'contact_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'feature_id' => Yii::t('app', 'Feature ID'),
            'power_fail' => Yii::t('app', 'Power Fail'),
            'pump_fail' => Yii::t('app', 'Pump Fail'),
            'connection_lost' => Yii::t('app', 'Connection Lost'),
            'contact_id' => Yii::t('app', 'Contact ID'),
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
     * @return FeaturesRmQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FeaturesRmQuery(get_called_class());
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
        $query = FeaturesRm::find()
            ->leftJoin('contact', 'features_rm.contact_id = contact.contact_id');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            //return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'device_id' => $this->device_id,
        ]);

        return $dataProvider;
    }
}
