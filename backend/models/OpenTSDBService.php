<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ArrayDataProvider;

use yii\httpclient\Client;


//$settings = Yii::$app->settings;


/**
 * UserSettingsSearch represents the model behind the search form about `app\models\UserSettings`.
 */
class OpenTSDBService extends Model
{


    /**
     * The OpenTSDB datasource config from conf/sw_datasource.json,
     * found in $sw['sw_config.config']['datasource']['OpenTSDB']
     *
     * @var array
     */
    private $_opentsdb_config;

    /**
     * The OpenTSDB server to query against
     * @var string
     */
    private $_opentsdb_host;

    /**
     * The URL for the OpenTSDB API query including a printf-format
     * template string.
     * e.g. http://opentsdb.example.com:4242/q?start=%s&end=%s%s&s
     * @var string
     */
    private $_opentsdb_base_url;




    /**
     * Format for dates in OpenTSDB API queries
     */
    const OPENTSDB_DATE_FORMAT = 'Y/m/d-H:i:s';

    /**
     * Holds the first timestamp received in the data returned from OpenTSDB
     *
     * @var string
     */
    protected $start_timestamp;

    /**
     * Holds the last timestamp received in the data returned from OpenTSDB
     *
     * @var string
     */
    protected $end_timestamp;

    /**
     * Data start time, post-downsampling
     *
     * @var string
     */
    public $start_time;

    /**
     * Data end time, post-downsampling
     *
     * @var string
     */
    public $end_time;



    /**
     * OpenTSDB API query start time in OPENTSDB_DATE_FORMAT
     * @var string
     */
    private $_query_start;

    /**
     * OpenTSDB API query end time in OPENTSDB_DATE_FORMAT
     * @var string
     */
    private $_query_end;



    /**
     * The generated OpenTSDB API query URL
     *
     * @var string
     */
    public $opentsdb_query_url;

    /**
     * The search string for the OpenTSDB API query
     * e.g. '&m=sum:proc.stat.cpu_used{host=server1.example.com}
     * @var string
     */
    private $_search_key;    

    /**
     * Container for the data returned by OpenTSDB
     *
     * @var array
     */
    public $opentsdb_data = array();


    public function __construct($_opentsdb_host = null) {

    	// Get configuration from params.php
        $this->_opentsdb_config = Yii::$app->params['opentsdbServer']['datasource']['OpenTSDB'];
        
        // If no OpenTSDB host is passed to the constructor,
        // look for one in the config

        if ($_opentsdb_host) {
            $this->_opentsdb_host = $_opentsdb_host;
        } elseif (in_array('url', $this->_opentsdb_config) && is_array($this->_opentsdb_config['url'])) {
            $this->_opentsdb_host = $this->_opentsdb_config['url'][array_rand($this->_opentsdb_config['url'])];
        } else {
            throw new InvalidParamException('No OpenTSDB Host found in the datasource config');
        }

        /*
        // Check config for the trim setting, the time to be trimmed from the
        // end of the query time range to account for the data population lag
        // in OpenTSDB
        if (in_array('trim', $this->_opentsdb_config)) {
            $this->opentsdb_query_trim = $this->_opentsdb_config['trim'];
        }
        */

        $this->_opentsdb_base_url = 'http://' . $this->_opentsdb_host;

    }

    private function requestJSON($url){

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$array = (array)json_decode(curl_exec($ch), true);		// AS stdClass Object - Siehe SensorsController für Test...
		//$json = json_decode(curl_exec($ch));					// AS Array
		curl_close($ch);

		return $array;
    }

    public function getVersion()
    {

    	$json = $this->requestJSON($this->_opentsdb_base_url .'/api/version');

		if(!empty($json)) {
			return $json;
		} else {
			return null;
		}

    }

    public function getAggregators()
    {

    	$json = $this->requestJSON($this->_opentsdb_base_url .'/api/aggregators');

		if(!empty($json)) {
			return $json;
		} else {
			return null;
		}

    }

    public function getConfig()
    {

    	$json = $this->requestJSON($this->_opentsdb_base_url .'/api/config');

		if(!empty($json)) {
			return $json;
		} else {
			return null;
		}

    }

    public function getMetricsList($query, $max)
    {

    	$json = $this->requestJSON($this->_opentsdb_base_url .'/api/suggest?type=metrics&q='. $query . '&max=' . $max);

		if(!empty($json)) {
			return $json;
		} else {
			return null;
		}

    }

    public function getMetricsData($start, $end, $aggregator, $rate, $metric, $qtags)
    {

    	//   http://192.168.1.107:4242/api/query?start=2017/10/15-00:00:01&end=2017/10/15-14:40:18&m=sum:rate:proc.net.packets{host=*}
    	$json = $this->requestJSON($this->_opentsdb_base_url.'/api/query?arrays=true&start='.$start.'&end='.$end.'&m='.$aggregator.':'.$rate.':'.$metric.'{'.$qtags.'}');

		if(!empty($json)) {

            if ( isset($json['error']) )
                return null;
            else
    		    return $json['0']['dps'];
			//return $json;
		} else {
			return null;
		}

    }










}