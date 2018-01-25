<?php

namespace backend\controllers;

use Yii;
use Yii\app;
use yii\web\Controller;

//use backend\models\OpenTSDBDataSource;
use backend\models\OpenTSDBService;
use yii\data\ArrayDataProvider;

use backend\models\Device;



/**
 * Site controller
 */
class TimeseriesController extends Controller
{



    public function actionIndex()
    {

        $opentsdb = new OpenTSDBService();
        //$queryurl = $opentsdb->_build_url("&m=", 1);

        $provider_metrics = new ArrayDataProvider([
            'allModels' => $opentsdb->getMetricsList('alms', '1000'),
            ]);
        
        $metrics = $provider_metrics->getModels();



        $provider_data = new ArrayDataProvider([
            'allModels' => $opentsdb->getMetricsData("2017/10/15-00:00:01", "2018/02/01-00:00:00", "none", "false", "alms.temperature", ""),
            'pagination' => [
                'pageSize' => 10,
                ],
            ]);
        
        $data = $provider_data->getModels();



        $tsdb_version = $opentsdb->getVersion();

        //$tsdb_metrics = $opentsdb->getMetricsList('net', '1000');


        return $this->render('index', [
            'tsdb_version' => $tsdb_version,
            'data' => $data,
            'metrics' => $metrics,
        ]);
    }




    public function actionGetdata($from, $to, $device_id, $metric)
    {

        //$from = Yii::$app->getRequest()->getQueryParam('from');
        //$to = Yii::$app->getRequest()->getQueryParam('to');

        Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

/*
        // Problem: Liefert nicht alle, sondern nur die ersten 10... Abhilfe mit "pagination"
        $provider_data = new ArrayDataProvider([
            'allModels' => $opentsdb->getMetricsData("2017/10/15-00:00:01", "2018/02/01-00:00:00", "none", "false", "alms.temperature", ""),
        ]);
        
        $data = $provider_data->getModels();
*/

        // Suche Device mit gelieferten ID
        $device = Device::findOne(['device_id' => $device_id, 'user_id' => Yii::$app->user->id]);

        // Falls kein Device gefunden, abbrechen
        if($device == null) return; 

        // Entfernen der ":" aus der Geräte-ID 
        $tsdbdevice = str_replace(":", "", $device->uuid);

        // Liefert alle Einträge in der DB
        $opentsdb = new OpenTSDBService();
        $data = $opentsdb->getMetricsData($from, 
                                          $to, 
                                          "none", 
                                          "false", 
                                          "alms." . $metric, 
                                          "host=" . $tsdbdevice . ",user=" . Yii::$app->user->id);

        if(count($data) > 0 ) {

            return array('status' => true, 
                         'data'   => array(
                            'tuples' => $data, 
                            'metric' => $metric, 
                            'uuid' => $device->uuid, 
                            'rows'=> count($data)
                        ));

        } else {

            return array('status' => false,'data'=> 'No rows found!');
        }

    }





    public function actionGetmetrics($device_id)
    {

        //$from = Yii::$app->getRequest()->getQueryParam('from');
        //$to = Yii::$app->getRequest()->getQueryParam('to');

        Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

/*
        // Problem: Liefert nicht alle, sondern nur die ersten 10... Abhilfe mit "pagination"
        $provider_data = new ArrayDataProvider([
            'allModels' => $opentsdb->getMetricsData("2017/10/15-00:00:01", "2018/02/01-00:00:00", "none", "false", "alms.temperature", ""),
        ]);
        
        $data = $provider_data->getModels();
*/

        // Suche Device mit gelieferten ID
        $device = Device::findOne(['device_id' => $device_id, 'user_id' => Yii::$app->user->id]);

        // Falls kein Device gefunden, abbrechen
        if($device == null) return; 


        // Abhängig vom Product des Devices, wird auf eine spezifische Darstellung umgeleitet.
        switch ($device->product_id) {
            // AQUALOOP
            case 10 : 
                return array('status' => true, 
                                        'entities'   => array(
                                            array(  'type'          => "temperature",
                                                    'active'        => true,
                                                    'description'   => "Temperatur BR",
                                                    'style'         => "lines",
                                                    'metric'        => "temperature"
                                                ),
                                            array(  'type'          => "pressure",
                                                    'active'        => true,
                                                    'description'   => "Druck Saugpumpe",
                                                    'style'         => "lines",
                                                    'metric'        => "pressure"
                                                ),
                                        ));
                break;


            case 20 :
                return array('status' => true, 
                                         'entity'   => array(
                                            'metrics' => ['pressure', 'volume', 'rpm', 'temperature'], 
                                        ));
                break;

            //default: throw new NotFoundHttpException('The requested page does not exist.');
            default: return $this->redirect(['index']);
        }



    }





}
