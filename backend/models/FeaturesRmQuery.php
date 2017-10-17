<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[FeaturesRm]].
 *
 * @see FeaturesRm
 */
class FeaturesRmQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return FeaturesRm[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return FeaturesRm|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
