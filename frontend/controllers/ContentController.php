<?php

namespace frontend\controllers;

use Yii;
use Yii\app;
use yii\web\Controller;

/**
 * Site controller
 */
class ContentController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'page' => [
                'class' => 'yii\web\ViewAction',
            ],
        ];
    }


}
