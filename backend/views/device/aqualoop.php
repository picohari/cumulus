<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Device */

$this->title = $model->name;
$this->blocks['content-header'] = "AQUALOOP: " . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Devices', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJs('
    var server = "'.Yii::$app->params['mqttServer'].'";
    var uuid = "'.$model->uuid.'";
    ',
    \yii\web\View::POS_HEAD, null
);

?>


<!-- Bootstrap core CSS -->
<!-- link href="css/bootstrap.css" rel="stylesheet" -->
<!-- jQuery -->
<!-- script type="text/javascript" src="js/jquery-1.11.2.min.js"></script -->
<!-- Sparkline -->
<script type="text/javascript" src="js/jquery.sparkline.min.js"></script>
<!-- jgPlot -->
<link class="include" rel="stylesheet" type="text/css" href="dist/jquery.jqplot.min.css" />

<script type="text/javascript" src="js/jquery.jqplot.min.js"></script>
<script type="text/javascript" src="js/jqplot.canvasTextRenderer.min.js"></script>
<script type="text/javascript" src="js/jqplot.canvasAxisLabelRenderer.min.js"></script>
<script type="text/javascript" src="js/jqplot.dateAxisRenderer.min.js"></script>

<!-- socket.io for communication -->
<!-- script type="text/javascript" src="http://localhost:3000/socket.io/socket.io.js"></script -->

<!-- MQTT Websocket -->
<script type="text/javascript" src="js/mqttws31.js"></script>
<script type="text/javascript">

    var livingTemp = new Array();
    var basementTemp = new Array();

    var host = server;
    var port = 15675;
    var topic = uuid + '/#';
    var useTLS = false;
    var cleansession = true;
    var mqtt;
    var reconnectTimeout = 2000;

    var offlineTimer;

    function MQTTconnect() {
    if (typeof path == "undefined") {
        path = '/ws';
    }
    mqtt = new Paho.MQTT.Client(
            host,
            port,
            path,
            "AL-MS-CU_" + Math.floor((1 + Math.random()) * 0x100000).toString(16)
    );
        var options = {
            userName : "device",    // Diese Option auch über YII übergeben
            password : "device",    // PW verschlüsseln ?
            mqttVersion: 3,
            timeout: 3,
            useSSL: useTLS,
            cleanSession: cleansession,
            onSuccess: onConnect,
            onFailure: function (message) {
                $('#status').html("Connection failed: " + message.errorMessage + "Retrying...");
                setTimeout(MQTTconnect, reconnectTimeout);
            }
        };

        mqtt.onConnectionLost = onConnectionLost;
        mqtt.onMessageArrived = onMessageArrived;
        console.log("Host: "+ host + ", Port: " + port + ", Path: " + path + " TLS: " + useTLS);
        console.log("Topic: "+ topic);
        mqtt.connect(options);
    };

    function onConnect() {
        $('#status').html('Connected to ' + host + ':' + port + path);
        mqtt.subscribe(topic, {qos: 0});
        $('#topic').html(topic);
    };

    function onConnectionLost(response) {
        setTimeout(MQTTconnect, reconnectTimeout);
        //$('#status').html("Connection lost: " + response.errorMessage + ". Reconnecting...");
        console.log("Connection lost: " + response.errorMessage + ". Reconnecting...");

    };

    function checkDeviceOnline() {
        $('#online_state').text('Offline');
        $('#online_state').removeClass('badge btn-success').addClass('badge btn-danger');

        clearInterval(offlineTimer);
    };

    function onMessageArrived(message) {
        var topic = message.destinationName;
        var payload = message.payloadString;
        //console.log("Topic: " + topic + ", Message payload: " + payload);
        $('#message').html(topic + ', ' + payload);
        var message = topic.split('/');
        var area = message[1];
        var state = message[2];

        var timestamp = Math.round((new Date()).getTime() / 1000);

        $('#message-ts').html(Date());

        switch (area) {
            case 'online_status':
                if (payload == 'online') {
                    $('#online_state').text('Online');
                    $('#online_state').removeClass('badge btn-danger').addClass('badge btn-success');

                    clearInterval(offlineTimer);
                    offlineTimer = setInterval(function () {
                        //console.log("Offline ?");
                        checkDeviceOnline();
                    }, 5000);

                    //$('#online_state').removeClass('label-danger').addClass('label-success');
                } else {
                    $('#online_state').removeClass('badge btn-success').addClass('badge btn-danger');

                }
                break;
            case 'front': 
                $('#value1').html('(Switch value: ' + payload + ')');
                if (payload == 'true') {
                    $('#label1').text('Closed');
                    $('#label1').removeClass('label-success').addClass('label-success');
                } else {
                    $('#label1').text('Open');
                    $('#label1').removeClass('label-success').addClass('label-danger');
                }
                break;
            case 'back':
                $('#value2').html('(Switch value: ' + payload + ')');
                if (payload == 'true') {
                    $('#label2').text('Closed');
                    $('#label2').removeClass('label-danger').addClass('label-success');
                } else {
                    $('#label2').text('Open');
                    $('#label2').removeClass('label-success').addClass('label-danger');
                }
                break;
            case 'kitchen':
                $('#value3').html('(Switch value: ' + payload + ')');
                if (payload == 'true') {
                    $('#label3').text('Closed');
                    $('#label3').removeClass('label-danger').addClass('label-success');
                } else {
                    $('#label3').text('Open');
                    $('#label3').removeClass('label-success').addClass('label-danger');
                }
                break;
            case 'living':
                    $('#livingTempSensor').html('(Sensor value: ' + payload + ')');
                    $('#livingTempLabel').text(payload + ' RPM');
                    $('#livingTempLabel').removeClass('').addClass('label-default');

                var entry = new Array();
                entry.push(timestamp);
                entry.push(parseInt(payload));
                livingTemp.push(entry);
                // Show only 20 values
                if (livingTemp.length >= 20) {
                    livingTemp.shift()
                }

                var livingTempPlot = $.jqplot ('livingTempChart', [livingTemp], {
                    axesDefaults: {
                        labelRenderer: $.jqplot.CanvasAxisLabelRenderer,
                        tickOptions: {
                            showMark: false,
                            showGridline: false,
                            show: false,
                            showLabel: false,
                        }
                      },
                    grid: {
                        gridLineColor: '#FFFFFF',
                        borderWidth: 0,
                        shadow: false,
                    },
                    seriesDefaults: {
                        rendererOptions: {
                            smooth: true
                        },
                        showMarker: false,
                        lineWidth: 2,
                      },
                      axes: {
                        xaxis: {
                          renderer:$.jqplot.DateAxisRenderer,
                          tickOptions:{
                            formatString:'%T'
                          },
                          pad: 0
                        },
                        yaxis: {
                        }
                    }
                });
                break;
            case 'basement':
                $('#basementTempSensor').html('(Sensor value: ' + payload + ')');
                if (payload >= 25) {
                        $('#basementTempLabel').text(payload + ' °C - too hot');
                        $('#basementTempLabel').removeClass('label-warning label-success label-info label-primary').addClass('label-danger');
                } else if (payload >= 21) {
                        $('#basementTempLabel').text(payload + ' °C - hot');
                        $('#basementTempLabel').removeClass('label-danger label-success label-info label-primary').addClass('label-warning');
                } else if (payload >= 18) {
                        $('#basementTempLabel').text(payload + ' °C - normal');
                        $('#basementTempLabel').removeClass('label-danger label-warning label-info label-primary').addClass('label-success');
                } else if (payload >= 15) {
                        $('#basementTempLabel').text(payload + ' °C - low');
                        $('#basementTempLabel').removeClass('label-danger label-warning label-success label-primary').addClass('label-info');
                } else if (payload <= 12) {
                        $('#basementTempLabel').text(payload + ' °C - too low');
                        $('#basementTempLabel').removeClass('label-danger label-warning label-success label-info').addClass('label-primary');
                basementTemp.push(parseInt(payload));
                if (basementTemp.length >= 20) {
                    basementTemp.shift()
                }

                $('.basementTempSparkline').sparkline(basementTemp, {
                    type: 'line',
                    width: '160',
                    height: '40'});
                }
                break;
            default: console.log('Error: Data do not match the MQTT topic.'); break;
        }
    };
    $(document).ready(function() {
        MQTTconnect();

        offlineTimer = setInterval(function () {
            //console.log("Offline ?");
            checkDeviceOnline();
        }, 5000);  

    });
</script>


<div class="device-status">
<div class="box">


<div class="box-header with-border">
        <h2 class="box-title">AL-MS Status: <?= Html::encode($this->title) ?>  <span id="online_state" class="btn-danger badge">Offline</span></h2>

        <?php if ($site != "none") {
            echo '<p>Location: ';
            echo Html::a(Html::encode($model->subscription->site->name), ['location/view', 'site_id' => $model->subscription->site->site_id]);
            echo '</p>';
        } ?>
</div>

<div class="box-body">
        <div class="panel panel-default">
          <div class="panel-body">
                <table class="table table-striped">
                    <!-- Entrace door -->
                    <tr>
                    <td width="40%" style="vertical-align:middle;"><h4>Valve Clearwater</h4><small id="value1">(no value recieved)</small></td>
                    <td style="vertical-align:middle;"></td>
                    <td width="30%" style="vertical-align:middle;"><h4>&nbsp;<span id="label1" class="label">Unknown</span></h4></td>
                    </tr>
                    <!-- Back door -->
                    <tr>
                    <td width="40%" style="vertical-align:middle;"><h4>Floatswitch BR</h4><small id="value2">(no value recieved)</small></td>
                    <td style="vertical-align:middle;"></td>
                    <td width="30%" style="vertical-align:middle;"><h4>&nbsp;<span id="label2" class="label">Unknown</span></h4></td>
                    </tr>
                    <!-- Kitchen door -->
                    <tr>
                    <td width="40%" style="vertical-align:middle;"><h4>Floatswitch CL</h4><small id="value3">(no value recieved)</small></td>
                    <td style="vertical-align:middle;"></td>
                    <td width="30%" style="vertical-align:middle;"><h4>&nbsp;<span id="label3" class="label">Unknown</span></h4></td>
                    </tr>
                    <!-- Living room temperature -->
                    <tr>
                    <td width="40%" style="vertical-align:middle;"><h4>Pump RPM</h4><small id="livingTempSensor">(no value recieved)</small></td>
                    <td style="vertical-align:middle;"><div id="livingTempChart" style="height:80px; width:180px;"></div></td>
                    <td width="30%" style="vertical-align:middle;"><h4>&nbsp;<span id="livingTempLabel" class="label">Unknown</span></h4></td>
                    </tr>
                    <!-- Basement temperature -->
                    <tr>
                    <td width="40%" style="vertical-align:middle;"><h4>Temperature CL</h4><small id="basementTempSensor">(no value recieved)</small></td>
                    <td style="vertical-align:middle;"><span class="basementTempSparkline"></span></td>
                    <td width="30%" style="vertical-align:middle;"><h4>&nbsp;<span id="basementTempLabel" class="label">Unknown</span></h4></td>
                    </tr>
                </table>
          </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-body">
              <div class="row">
                <div class="col-md-6">
                    <h5 class="h5"><b> Latest MQTT message:  </b> <span id="message">no message recieved</span></h5>
                    <h5 class="h5"><b> Latest MQTT error:  </b> <span id="message">no error received</span></h5>
                </div>
                <div class="col-md-6">
                    <h5 class="h5"><b> Status: </b>  <span id='status'></span></h5>
                    <h5 class="h5"><b> Last updated:  </b> <span id="message-ts">-</span></h5>
                </div>
              </div>
            </div>
        </div>

</div>
</div>
</div>

        
<div class="device-view">
<div class="box">


<div class="box-header with-border">
        <h2 class="box-title">AQUALOOP details: <?= Html::encode($this->title) ?></h2>
</div>

<div class="box-body">

    <div class="row">
        <div class="col-md-6">
            <div class="float-left">
                <p>
                    <?= Html::a('Update',   ['update',         'device_id' => $model->device_id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Alerting', ['alerting/index', 'device_id' => $model->device_id], ['class' => 'btn btn-warning']) ?>
                </p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="float-right">
                <p class="pull-right">
                    <?= Html::a('Delete', ['delete', 'device_id' => $model->device_id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you REALLY sure you want to delete this Device?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </p>
            </div>
        </div>
    </div>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'device_id',
            'uuid',
            //'product_id',
            [
                'label' => "INTEWA Product",
                'value' => $model->product->name,
            ],
            'name',
            'status',
            'subscription.site.name',
            'contact.name',
            [
                'label' => Html::activeLabel($model, 'created_ts'),
                'value' => date("D, j. F Y H:i:s T", $model->created_ts),
            ],
        ],
    ]) ?>

</div>
</div>
</div>
