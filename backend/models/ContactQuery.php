<?php

namespace backend\models;

use Yii;

/**
 * This is the ActiveQuery class for [[Contact]].
 *
 * @see Contact
 */
class ContactQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return Contact[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Contact|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    // FIXME: Rename this function to better comprehension
    public function owned()
    {
        return $this->andWhere(['user_id' => Yii::$app->user->id]);
    }

}
