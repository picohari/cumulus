<?php

namespace backend\controllers;

use Yii;
use backend\models\Device;
use backend\models\DeviceSearch;
use backend\models\Subscription;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DeviceController implements the CRUD actions for Device model.
 */
class DeviceController extends Controller
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
     * Lists all Device models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DeviceSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Device model.
     * @param integer $device_id
     * @param integer $user_id
     * @return mixed
     */
    public function actionAqualoop($device_id)
    {
        $user_id = Yii::$app->user->id;

        $subscription = Subscription::findOne(['user_id' => Yii::$app->user->id, 'device_id' => $device_id]);
        if (!$subscription) $subscription = "none";

        return $this->render('aqualoop', [
            'model' => $this->findModel($device_id, $user_id),
            'site'  => $subscription,
        ]);
    }

    /**
     * Displays a single Device model.
     * @param integer $device_id
     * @param integer $user_id
     * @return mixed
     */
    public function actionRainmaster($device_id)
    {
        $user_id = Yii::$app->user->id;

        $subscription = Subscription::findOne(['user_id' => Yii::$app->user->id, 'device_id' => $device_id]);
        if (!$subscription) $subscription = "none";

        return $this->render('rainmaster', [
            'model' => $this->findModel($device_id, $user_id),
            'site'  => $subscription,
        ]);
    }








    /**
     * Creates a new Device model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Device();

        if ($model->load(Yii::$app->request->post())) {

            $model->user_id = Yii::$app->user->id;
            $model->save();

            $post = Yii::$app->request->post("Device");

            // Zuerst wird für das Device eine Subscription angelegt, damit das Device einer Location zugeordnet werden kann.
            if($post['subscription'] != null) {
                $subscription = new Subscription();
                $subscription->user_id   = Yii::$app->user->id;
                $subscription->site_id   = $post['subscription'];
                $subscription->device_id = $model->device_id;
                $subscription->save();
            }


            // Abhängig vom Product des Devices, wird auf eine spezifische Darstellung umgeleitet.
            switch ($model->product_id) {
                case 10 : return $this->redirect(['aqualoop',   'device_id' => $model->device_id]); break;
                case 20 : return $this->redirect(['rainmaster', 'device_id' => $model->device_id]); break;

                //default: throw new NotFoundHttpException('The requested page does not exist.');
                default: return $this->redirect(['index']);
            }

            //return $this->redirect(['view', 'device_id' => $model->device_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }



    /**
     * Creates a new Device model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate_at($site_id)
    {
        $model = new Device();

        if ($model->load(Yii::$app->request->post())) {

            $model->user_id = Yii::$app->user->id;
            $model->save();

            $subscription = new Subscription();
            $subscription->user_id   = Yii::$app->user->id;
            $subscription->site_id   = $site_id;
            $subscription->device_id = $model->device_id;
            $subscription->save();

/*
            $subscription = new Subscription();
            $subscription = Subscription::find()->where(['user_id' => Yii::$app->user->id, 'site_id' => Yii::$app->request->post('site_id')])->one();

            $subscription->device_id = $device_id;
            $subscription->save();
*/

            // Abhängig vom Product des Devices, wird auf eine spezifische Darstellung umgeleitet.
            switch ($model->product_id) {
                case 10 : return $this->redirect(['aqualoop',   'device_id' => $model->device_id]); break;
                case 20 : return $this->redirect(['rainmaster', 'device_id' => $model->device_id]); break;

                //default: throw new NotFoundHttpException('The requested page does not exist.');
                default: return $this->redirect(['index']);
                
            }

            //return $this->redirect(['view', 'device_id' => $model->device_id]);
        } else {
            
            return $this->render('create_at', [
                'model' => $model,
                'site'  => $site_id,
            ]);
        }
    }



    /**
     * Updates an existing Device model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $device_id
     * @param integer $user_id
     * @return mixed
     */
    public function actionUpdate($device_id)
    {

        $model = $this->findModel($device_id, Yii::$app->user->id);

        $subscription = Subscription::findOne(['user_id' => Yii::$app->user->id, 'device_id' => $device_id]);

        if ($model->load(Yii::$app->request->post())) {

            $model->save();

            $post = Yii::$app->request->post("Device");

            if ($post['subscription'] != null) {

                $subscription = Subscription::findOne(['user_id' => Yii::$app->user->id, 'device_id' => $device_id]);
                
                // Falls eine Subscription (Location) zugewiesen ist, aktualisiere den Eintrag, sonst einen neuen Eintrag anlegen
                if ($subscription) {
                    $subscription->site_id   = $post['subscription'];
                    $subscription->save();
                } else {
                    $subscription = new Subscription();
                    $subscription->user_id   = Yii::$app->user->id;
                    $subscription->site_id   = $post['subscription'];
                    $subscription->device_id = $model->device_id;
                    $subscription->save();
                }

            } else {
                // Es wurde keine Location für dieses Device angegeben, daher löschen wir den Eintrag, falls es einen gibt
                $subscription = Subscription::findOne(['user_id' => Yii::$app->user->id, 'device_id' => $device_id]);
                if ($subscription) $subscription->delete();
            }






            // Abhängig vom Product des Devices, wird auf eine spezifische Darstellung umgeleitet.
            switch ($model->product_id) {
                case 10 : return $this->redirect(['aqualoop',   'device_id' => $model->device_id]); break;
                case 20 : return $this->redirect(['rainmaster', 'device_id' => $model->device_id]); break;

                //default: throw new NotFoundHttpException('The requested page does not exist.');
                default: return $this->redirect(['index']);
            }
            
        } else {
            
            // Create dummy Subscription, if Device has none
            if (!$subscription) $subscription = new Subscription();

            return $this->render('update', [
                'model' => $model,
                'subscription' => $subscription,
            ]);
        }
    }

    /**
     * Deletes an existing Device model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $device_id
     * @param integer $user_id
     * @return mixed
     */
    public function actionDelete($device_id)
    {
        $user_id = Yii::$app->user->id;

        // Falls es eine Subscription mit diesem Device gibt, muss diese als erste gelöscht werden, bevor das Device gelöscht wird
        $subscription = Subscription::findOne(['user_id' => $user_id, 'device_id' => $device_id]);
        if ($subscription) $subscription->delete();

        $this->findModel($device_id, $user_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Device model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $device_id
     * @param integer $user_id
     * @return Device the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($device_id, $user_id)
    {
        if (($model = Device::findOne(['device_id' => $device_id, 'user_id' => $user_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
