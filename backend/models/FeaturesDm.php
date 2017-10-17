<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "features_dm".
 *
 * @property integer $feature_id
 * @property integer $contact_id
 * @property integer $device_id
 * @property integer $power_fail
 * @property integer $pump_fail
 * @property integer $connection_lost
 *
 * @property Contact $contact
 */
class FeaturesDm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'features_dm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['contact_id'], 'required'],
            [['feature_id', 'contact_id', 'device_id', 'power_fail', 'pump_fail', 'connection_lost'], 'integer'],
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
            'contact_id' => Yii::t('app', 'Contact ID'),
            'device_id' => Yii::t('app', 'Device ID'),
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
     * @inheritdoc
     * @return FeaturesDmQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FeaturesDmQuery(get_called_class());
    }
}
