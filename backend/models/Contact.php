<?php

namespace backend\models;

use Yii;
use common\models\User;


/**
 * This is the model class for table "contact".
 *
 * @property integer $contact_id
 * @property integer $user_id
 * @property string $Name
 * @property string $email
 * @property string $phone
 * @property string $message
 *
 * @property User $user
 * @property FeaturesAl[] $featuresAls
 * @property FeaturesDm[] $featuresDms
 * @property FeaturesRm[] $featuresRms
 */
class Contact extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contact';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            /*[['contact_id', 'user_id'], 'required'],*/
            [['contact_id', 'user_id'], 'integer'],
            [['name', 'email', 'phone', 'message'], 'string', 'max' => 45],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'contact_id' => Yii::t('app', 'Contact ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'name' => Yii::t('app', 'Contact Name'),
            'email' => Yii::t('app', 'E-Mail'),
            'phone' => Yii::t('app', 'Phone'),
            'message' => Yii::t('app', 'Message'),
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
    public function getDevices()
    {
        return $this->hasMany(Device::className(), ['contact_id' => 'contact_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeaturesAls()
    {
        return $this->hasMany(FeaturesAl::className(), ['contact_id' => 'contact_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeaturesDms()
    {
        return $this->hasMany(FeaturesDm::className(), ['contact_id' => 'contact_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFeaturesRms()
    {
        return $this->hasMany(FeaturesRm::className(), ['contact_id' => 'contact_id']);
    }

    /**
     * @inheritdoc
     * @return ContactQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ContactQuery(get_called_class());
    }
}
