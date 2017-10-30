<?php

namespace backend\controllers;

use Yii;
use backend\models\Device;
use backend\models\FeaturesAl;
use backend\models\FeaturesRm;
use backend\models\ContactSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SubscriptionController implements the CRUD actions for Subscription model.
 */
class AlertingController extends Controller
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
    public function actionIndex($device_id)
    {
        $device = Device::findOne(['user_id' => Yii::$app->user->id, 'device_id' => $device_id]);

        switch ($device->product_id) {

            case 10:    /* AQUALOOP */
                $searchModel = new FeaturesAl();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                $dataProvider->query->where(['features_al.device_id' => $device_id]);
                break;

            case 20:    /* RAINMASTER */
                $searchModel = new FeaturesRm();
                $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                $dataProvider->query->where(['features_rm.device_id' => $device_id]);
                break;
                

            default:
                return $this->redirect(['index']);

        }

        $post = Yii::$app->request->post();

        $contact = 0;
        $count = 0;
        
        if ($post) {

            foreach($post as $keys => $values) {

                // Problem 1: Die contact_id der POST Parameter werden als 1[power_fail], 2[power_fail], 25[power_fail] usw. codiert.
                // YII2 liefert aber zwei nicht brauchbare POST Variablen, die aussortiert werden:
                if ( $keys == "_csrf-backend" || $keys == "Device" ) { 
                    $count++;
                    continue;
                }
                
                // Suche Contact mit der ID aus $keys
                switch ($device->product_id) {
                    case 10: $contact = FeaturesAl::findOne(['contact_id' => $keys, 'device_id' => $device_id]); break;
                    case 20: $contact = FeaturesRm::findOne(['contact_id' => $keys, 'device_id' => $device_id]); break;
                }
                
                // Da features_XX Model für alle gleich ist, können wir hier auf eine Fallunterscheidung verzichten
                $contact->power_fail      = ( $values["power_fail"]      == 1 ? 1 : null);
                $contact->pump_fail       = ( $values['pump_fail']       == 1 ? 1 : null);
                $contact->connection_lost = ( $values['connection_lost'] == 1 ? 1 : null);
                
                $contact->save();

            }

        }

    
        //$searchModel = new SubscriptionSearch();
        //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'device' => $device,
            'searchModel'  => $searchModel,
            'dataProvider' => $dataProvider,
            'count' => $count,
            'contact' => $contact,
            'post' => $post,
            //'keys' => $keys["power_fail"],
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
    public function actionCreate($device_id)
    {
        $device = Device::findOne(['user_id' => Yii::$app->user->id, 'device_id' => $device_id]);

        switch ($device->product_id) {

            /* AQUALOOP */
            case 10: $model = new FeaturesAl(); break;

            /* RAINMASTER */
            case 20: $model = new FeaturesRm(); break;
                

            /* Sollte nicht vorkommen, da im Device IMMER das Produkt vermerkt ist ...  Es kommt eine Fehlermeldung, da kein device_id in URL angegeben wurde */
            default: 
                return $this->redirect(['index']);

        }

        $post = Yii::$app->request->post();

        /* Wir wissen ja, welches Device wir verknüpfen wollen, daher laden wir die $_POST Parameter in das Modell für den feature_XX Eintrag */
        if ($model->load( Yii::$app->request->post() ) ) {

            switch ($device->product_id) {

                /* AQUALOOP */
                case 10:
                    $model->device_id  = $post['FeaturesAl']['device_id'];
                    $model->contact_id = $post['FeaturesAl']['contact_id'];
                    $model->save();
                    break;

                /* RAINMASTER */
                case 20:
                    $model->device_id  = $post['FeaturesRm']['device_id'];
                    $model->contact_id = $post['FeaturesRm']['contact_id'];
                    $model->save();
                    break;

            }

            return $this->redirect(['index', 'device_id' => $device->device_id]);

        } else {

            return $this->render('create', [
                'device' => $device,
                'model'  => $model,
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
