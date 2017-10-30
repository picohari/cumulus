<?php

namespace backend\models;

/**
 * This is the ActiveQuery class for [[ParametersAl]].
 *
 * @see ParametersAl
 */
class ParametersAlQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return ParametersAl[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return ParametersAl|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
