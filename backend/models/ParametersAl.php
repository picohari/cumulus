<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "parameters_al".
 *
 * @property integer $parameters_id
 * @property integer $device_id
 * @property integer $user_id
 * @property integer $mem_cnt
 * @property integer $c1_cnt
 * @property integer $c2_cnt
 * @property integer $t1_hour
 * @property integer $t1_min
 * @property integer $t2_hour
 * @property integer $t2_min
 * @property integer $sludge_days
 * @property integer $sludge_sec
 * @property integer $air_on
 * @property integer $air_off
 *
 * @property Device $device
 */
class ParametersAl extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'parameters_al';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device_id', 'user_id'], 'required'],
            [['device_id', 'user_id', 'mem_cnt', 'c1_cnt', 'c2_cnt', 't1_hour', 't1_min', 't2_hour', 't2_min', 'sludge_days', 'sludge_sec', 'air_on', 'air_off'], 'integer'],
            [['device_id', 'user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Device::className(), 'targetAttribute' => ['device_id' => 'device_id', 'user_id' => 'user_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'parameters_id' => Yii::t('app', 'Parameters ID'),
            'device_id' => Yii::t('app', 'Device ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'mem_cnt' => Yii::t('app', 'MEM Number'),
            'c1_cnt' => Yii::t('app', 'C1'),
            'c2_cnt' => Yii::t('app', 'C2'),
            't1_hour' => Yii::t('app', 'T1 Hour'),
            't1_min' => Yii::t('app', 'T1 Min'),
            't2_hour' => Yii::t('app', 'T2 Hour'),
            't2_min' => Yii::t('app', 'T2 Min'),
            'sludge_days' => Yii::t('app', 'Sludge Days'),
            'sludge_sec' => Yii::t('app', 'Sludge Sec'),
            'air_on' => Yii::t('app', 'Air On'),
            'air_off' => Yii::t('app', 'Air Off'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevice()
    {
        return $this->hasOne(Device::className(), ['device_id' => 'device_id', 'user_id' => 'user_id']);
    }

    /**
     * @inheritdoc
     * @return ParametersAlQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ParametersAlQuery(get_called_class());
    }
}
