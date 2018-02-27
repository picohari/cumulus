<?php

namespace backend\controllers;

use Yii;
use Yii\app;
use yii\web\Controller;


use common\models\Entity;
use yii\data\ArrayDataProvider;

use common\definitions\EntityDefinition;
use common\definitions\PropertyDefinition;

/**
 * Site controller
 */
class CapabilitiesController extends Controller
{

    public function actionIndex()
    {


    }

    public function actionList($section = NULL)
    {

        Yii::$app->response->format = \yii\web\Response:: FORMAT_JSON;

        $capabilities = array();

/*
        if (is_null($section) || $section == 'configuration') {
            $configuration = array(
                'precision' => View\View::PRECISION,
                'database' => Util\Configuration::read('db.driver'),
                'debug' => Util\Configuration::read('debug'),
                'devmode' => Util\Configuration::read('devmode')
            );

            if ($commit = Util\Debug::getCurrentCommit()) {
                $configuration['commit'] = $commit;
            }

            $capabilities['configuration'] = $configuration;
        }
*/
/*  
        // db statistics - only if specifically requested
        if ($section == 'database') {
            $conn = $this->em->getConnection(); // get DBAL connection from EntityManager

            // estimate InnoDB tables to avoid performance penalty
            $rows = $this->sqlCount($conn, 'data');
            $size = $this->dbSize($conn, 'data');

            $capabilities['database'] = array(
                'data' => array(
                    'rows' => $rows,
                    'size' => $size
                )
            );

            // aggregation table size
            if (Util\Configuration::read('aggregation')) {
                $agg_rows = $this->sqlCount($conn, 'aggregate');
                $agg_size = $this->dbSize($conn, 'aggregate');

                $capabilities['database']['aggregation'] = array(
                    'rows' => $agg_rows,
                    'size' => $agg_size,
                    'ratio' => ($agg_rows) ? $rows/$agg_rows : 0
                );
            }
        }
        if (is_null($section) || $section == 'formats') {
            $capabilities['formats'] = array_keys(Router::$viewMapping);
        }

        if (is_null($section) || $section == 'contexts') {
            $capabilities['contexts'] = array_keys(Router::$controllerMapping);
        }
*/

        if (is_null($section) || $section == 'definitions') {
            // unresolved artifact from Symfony migration
            if (!is_null($section)) { // only caching when we don't request dynamic informations
                $this->view->setCaching('expires', time()+2*7*24*60*60); // cache for 2 weeks
            }

            $capabilities['definitions']['entities'] = EntityDefinition::get();
            $capabilities['definitions']['properties'] = PropertyDefinition::get();
        }

        if (count($capabilities) == 0) {
            throw new \Exception('Invalid capability identifier: \'' . $section . '\'');
        }

        return array('capabilities' => $capabilities);

    }





}
