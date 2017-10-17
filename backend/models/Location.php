<?php

namespace backend\models;

use Yii;
use common\models\User;


/**
 * This is the model class for table "location".
 *
 * @property integer $site_id
 * @property string $name
 * @property string $first_address
 * @property string $last_address
 * @property string $post_code
 * @property string $city
 * @property string $country_code
 * @property string $site_description
 *
 * @property Country $countryCode
 * @property Subscription[] $subscriptions
 */
class Location extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'location';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_code'], 'required'],
            [['name', 'first_address', 'last_address', 'post_code', 'city', 'site_description'], 'string', 'max' => 45],
            [['country_code'], 'string', 'max' => 3],
            [['country_code'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_code' => 'country_code']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'site_id' => Yii::t('app', 'Site ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'name' => Yii::t('app', 'Location Name'),
            'first_address' => Yii::t('app', 'First Address'),
            'last_address' => Yii::t('app', 'Last Address'),
            'post_code' => Yii::t('app', 'Post Code'),
            'city' => Yii::t('app', 'City'),
            'country_code' => Yii::t('app', 'Country'),
            'site_description' => Yii::t('app', 'Site Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountryCode()
    {
        return $this->hasOne(Country::className(), ['country_code' => 'country_code']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriptions()
    {
        return $this->hasMany(Subscription::className(), ['site_id' => 'site_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriptionscount()
    {
        return $this->hasMany(Subscription::className(), ['site_id' => 'site_id'])->count();
    }

    /**
     * @inheritdoc
     * @return LocationQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LocationQuery(get_called_class());
    }
}
