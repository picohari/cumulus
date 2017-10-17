<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "country".
 *
 * @property string $country_code
 * @property string $name
 * @property string $continent
 *
 * @property Location[] $locations
 */
class Country extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_code'], 'required'],
            [['continent'], 'string'],
            [['country_code'], 'string', 'max' => 3],
            [['name'], 'string', 'max' => 45],
            [['country_code'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'country_code' => Yii::t('app', 'Country Code'),
            'name' => Yii::t('app', 'Country'),
            'continent' => Yii::t('app', 'Continent'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(Location::className(), ['country_code' => 'country_code']);
    }

    /**
     * @inheritdoc
     * @return CountryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CountryQuery(get_called_class());
    }
}
