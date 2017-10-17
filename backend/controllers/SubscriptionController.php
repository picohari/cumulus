<?php

namespace backend\controllers;

use Yii;
use backend\models\Subscription;
use backend\models\SubscriptionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SubscriptionController implements the CRUD actions for Subscription model.
 */
class SubscriptionController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Subscription models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SubscriptionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Subscription model.
     * @param integer $subscription_id
     * @param integer $user_id
     * @param integer $site_id
     * @return mixed
     */
    public function actionView($subscription_id, $user_id, $site_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($subscription_id, $user_id, $site_id),
        ]);
    }

    /**
     * Creates a new Subscription model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Subscription();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'subscription_id' => $model->subscription_id, 'user_id' => $model->user_id, 'site_id' => $model->site_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Subscription model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $subscription_id
     * @param integer $user_id
     * @param integer $site_id
     * @return mixed
     */
    public function actionUpdate($subscription_id, $user_id, $site_id)
    {
        $model = $this->findModel($subscription_id, $user_id, $site_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'subscription_id' => $model->subscription_id, 'user_id' => $model->user_id, 'site_id' => $model->site_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Subscription model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $subscription_id
     * @param integer $user_id
     * @param integer $site_id
     * @return mixed
     */
    public function actionDelete($subscription_id, $user_id, $site_id)
    {
        $this->findModel($subscription_id, $user_id, $site_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Subscription model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $subscription_id
     * @param integer $user_id
     * @param integer $site_id
     * @return Subscription the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($subscription_id, $user_id, $site_id)
    {
        if (($model = Subscription::findOne(['subscription_id' => $subscription_id, 'user_id' => $user_id, 'site_id' => $site_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
