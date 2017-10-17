<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[FeaturesDm]].
 *
 * @see FeaturesDm
 */
class FeaturesDmQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return FeaturesDm[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return FeaturesDm|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
