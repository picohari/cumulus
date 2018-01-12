<?php

namespace backend\models;

use Yii;

/**
 * This is the ActiveQuery class for [[Location]].
 *
 * @see Location
 */
class LocationQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Location[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Location|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    public function owned()
    {
        return $this->andWhere(['user_id' => Yii::$app->user->id]);
    }

}
