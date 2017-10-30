<?php

namespace backend\controllers;

use Yii;
use Yii\app;
use yii\web\Controller;

//use backend\models\OpenTSDBDataSource;
use backend\models\OpenTSDBService;
use yii\data\ArrayDataProvider;


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
            'allModels' => $opentsdb->getMetricsList('net', '1000'),
            ]);
        
        $metrics = $provider_metrics->getModels();



        $provider_data = new ArrayDataProvider([
            'allModels' => $opentsdb->getMetricsData("2017/10/15-00:00:01", "2017/10/15-14:30:18", "none", "false", "proc.net.packets", ""),
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

}
