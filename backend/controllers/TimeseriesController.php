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

            // We must multiply timestamp of OpenTSDB from seconds to milliseconds
            foreach ($data as $index => $tuples) {
                $data[$index][0] *= 1000;
            }

            // find minimum
            $timestamp = NULL;
            $max = NULL;
            $min = NULL;
            $max_tuple = NULL;
            $min_tuple = NULL;

            foreach ($data as $index => $tuples) {
                if (is_null($max) || $data[$index][1] > $max) {
                    $max = $data[$index][1];
                    $max_tuple = $tuples;
                }
                if (is_null($min) || $data[$index][1] < $min) {
                    $min = $data[$index][1];
                    $min_tuple = $tuples;
                }
            }


            return array('status' => true, 
                         'data'   => array(
                            'tuples' => $data, 
                            'metric' => $metric, 
                            'uuid' => $device->uuid,
                            'min' => $min_tuple, 
                            'max' => $max_tuple, 
                            'rows'=> count($data)
                        ));

        } else {

            return array('status' => false,'data'=> 'No rows found!');
        }

    }





    public function actionGetmetrics($device_id, $metric)
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

                switch($metric) {
                    case 'temperature' :
                        return array('status' => true, 
                                                'entity'   =>  array(
                                                    //'uuid'          => "1",
                                                    'type'          => "temperature",
                                                    'active'        => true,
                                                    'color'         => "#ce93d8",
                                                    'description'   => "Temperature Bioreactor",
                                                    'fillstyle'     => 0,
                                                    'gap'           => 0,
                                                    'public'        => true,
                                                    'resolution'    => 1,
                                                    'style'         => "lines",
                                                    'title'         => "Temperature BR",
                                                    'yaxis'         => "auto"
                                                    ),
                                                );
                        break;

                    case 'current' :
                        return array('status' => true, 
                                                'entity'   =>  array(
                                                    //'uuid'          => "2",
                                                    'type'          => "current",
                                                    'active'        => true,
                                                    'color'         => "#ce93d8",
                                                    'description'   => "Current Suction Pump",
                                                    'fillstyle'     => 0,
                                                    'gap'           => 0,
                                                    'public'        => true,
                                                    'resolution'    => 1,
                                                    'style'         => "lines",
                                                    'title'         => "Current Pump",
                                                    'yaxis'         => "auto"
                                                    ),
                                                );
                        break;

                    case 'filllevel' :
                        return array('status' => true, 
                                                'entity'   =>  array(
                                                    //'uuid'          => "3",
                                                    'type'          => "filllevel",
                                                    'active'        => true,
                                                    'color'         => "#ce93d8",
                                                    'description'   => "Tanklevel Bioreactor",
                                                    'fillstyle'     => 0,
                                                    'gap'           => 0,
                                                    'public'        => true,
                                                    'resolution'    => 1,
                                                    'style'         => "lines",
                                                    'title'         => "Tanklevel BR",
                                                    'yaxis'         => "auto"
                                                    ),
                                                );
                        break;

                    case 'pressure' :
                        return array('status' => true, 
                                                'entity'   =>  array(
                                                    //'uuid'          => "4",
                                                    'type'          => "pressure",
                                                    'active'        => true,
                                                    'color'         => "#ce93d8",
                                                    'description'   => "Pressure Suction Pump",
                                                    'fillstyle'     => 0,
                                                    'gap'           => 0,
                                                    'public'        => true,
                                                    'resolution'    => 1,
                                                    'style'         => "lines",
                                                    'title'         => "Pressure Pump",
                                                    'yaxis'         => "auto"
                                                    ),
                                                );
                        break;
                }




                break;

            // RAINMASTER
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








    public function actionGetmetric($device_id)
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
                                        'entity'   =>  array(
                                                    'type'          => "temperature",
                                                    'description'   => "Temperatur Bioreactor",
                                                    'title'         => "Temperatur BR",
                                                    'active'        => true,
                                                    'style'         => "lines",
                                                    'uuid'          => "1234",

                                                ),
                                        );
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
