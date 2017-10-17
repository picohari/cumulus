<?php

namespace backend\models;

use Yii;
use common\models\User;
use yii\data\ActiveDataProvider;


/**
 * This is the model class for table "subscription".
 *
 * @property integer $subscription_id
 * @property integer $user_id
 * @property integer $site_id
 * @property integer $device_id
 * @property integer $mailing_use
 * @property integer $billing_use
 *
 * @property Location $site
 * @property Device $device
 * @property User $user
 */
class Subscription extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'subscription';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'site_id'], 'required'],
            [['user_id', 'site_id', 'device_id', 'mailing_use', 'billing_use'], 'integer'],
            [['site_id'], 'exist', 'skipOnError' => true, 'targetClass' => Location::className(), 'targetAttribute' => ['site_id' => 'site_id']],
            [['device_id'], 'exist', 'skipOnError' => true, 'targetClass' => Device::className(), 'targetAttribute' => ['device_id' => 'device_id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'subscription_id' => Yii::t('app', 'Subscription ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'site_id' => Yii::t('app', 'Site ID'),
            'device_id' => Yii::t('app', 'Device ID'),
            'mailing_use' => Yii::t('app', 'Mailing Use'),
            'billing_use' => Yii::t('app', 'Billing Use'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSite()
    {
        return $this->hasOne(Location::className(), ['site_id' => 'site_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevice()
    {
        return $this->hasOne(Device::className(), ['device_id' => 'device_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return SubscriptionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SubscriptionQuery(get_called_class());
    }

}
