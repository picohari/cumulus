<?php

namespace backend\controllers;

use Yii;
use backend\models\Location;
use backend\models\LocationSearch;
use backend\models\DeviceSearch;
use backend\models\Subscription;
use backend\models\SubscriptionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LocationController implements the CRUD actions for Location model.
 */
class LocationController extends Controller
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
     * Lists all Location models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LocationSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Location model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($site_id)
    {
        
        /*
        $searchDevice = new SubscriptionSearch();
        $searchDevice->site_id = $id;
        $dataProvider = $searchDevice->search(Yii::$app->request->queryParams);
        */

        $user_id = Yii::$app->user->id;

        $searchModel = new DeviceSearch();
        $dataProvider = $searchModel->getSiteDevices($site_id);

        return $this->render('view', [
            'model' => $this->findModel($site_id, $user_id),
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Creates a new Location model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Location();

        if ($model->load(Yii::$app->request->post()) ) {

            $model->user_id = Yii::$app->user->id;
            $model->save();

/*
            $subscription = new Subscription();
            $subscription->user_id = Yii::$app->user->id;
            $subscription->site_id = $model->site_id;
            $subscription->device_id = null;
            $subscription->save();
*/
            return $this->redirect(['view', 'site_id' => $model->site_id]);

        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Location model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($site_id)
    {
        $model = $this->findModel($site_id, Yii::$app->user->id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'site_id' => $model->site_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Location model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($site_id)
    {
        $this->findModel($site_id, Yii::$app->user->id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Location model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Location the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($site_id, $user_id)
    {
        if (($model = Location::findOne(['site_id' => $site_id,  'user_id' => $user_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
