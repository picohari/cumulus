<?php

namespace backend\models;

use Yii;
use common\models\User;

/**
 * This is the model class for table "device".
 *
 * @property integer $device_id
 * @property integer $user_id
 * @property integer $product_id
 * @property string $uuid
 * @property string $name
 * @property string $version
 * @property string $status
 * @property string $description
 *
 * @property Product $product
 * @property User $user
 * @property Subscription[] $subscriptions
 */
class Device extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'device';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            /*[['user_id', 'product_id'], 'required'],*/
            [['user_id', 'product_id', 'contact_id'], 'integer'],
            [['uuid', 'name', 'version', 'status', 'description'], 'string', 'max' => 45],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'product_id']],
            [['user_id'],    'exist', 'skipOnError' => true, 'targetClass' => User::className(),    'targetAttribute' => ['user_id' => 'id']],
            [['contact_id'], 'exist', 'skipOnError' => true, 'targetClass' => Contact::className(), 'targetAttribute' => ['contact_id' => 'contact_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'device_id' => Yii::t('app', 'Device ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'product_id' => Yii::t('app', 'Product ID'),
            'contact_id' => Yii::t('app', 'Contact ID'),
            'uuid' => Yii::t('app', 'UUID'),
            'name' => Yii::t('app', 'Name'),
            'version' => Yii::t('app', 'Version'),
            'status' => Yii::t('app', 'Status'),
            'description' => Yii::t('app', 'Description'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['product_id' => 'product_id']);
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
    public function getContact()
    {
        return $this->hasOne(Contact::className(), ['contact_id' => 'contact_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubscriptions()
    {
        return $this->hasMany(Subscription::className(), ['device_id' => 'device_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubscription()
    {
        return $this->hasOne(Subscription::className(), ['device_id' => 'device_id']);
    }

    /**
     * @inheritdoc
     * @return DeviceQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DeviceQuery(get_called_class());
    }


}
