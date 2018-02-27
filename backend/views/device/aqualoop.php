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
    var device_id = "'.$model->device_id.'";
    ',
    \yii\web\View::POS_HEAD, null
);

?>

<!-- Bootstrap core CSS -->
<!-- link href="css/bootstrap.css" rel="stylesheet" -->
<!-- jQuery -->
<!-- script type="text/javascript" src="js/jquery-1.11.2.min.js"></script -->
<script type="text/javascript" src="js/jquery-ui/jquery-ui.min.js"></script>
<link   type="text/css" href="js/jquery-ui/jquery-ui.min.css" rel="stylesheet">

<!-- jQuery Sparkline -->
<script type="text/javascript" src="js/ext/jquery-extensions.js"></script>
<!-- jQuery Moment -->
<script type="text/javascript" src="js/ext/moment.min.js"></script>
<!-- jQuery Sparkline -->
<script type="text/javascript" src="js/jquery-sparkline/jquery.sparkline.min.js"></script>

<!-- jQuery jgPlot -->
<!--
<link class="include" rel="stylesheet" type="text/css" href="js/jquery-jqplot/jquery.jqplot.min.css" />
<script type="text/javascript" src="js/jquery-jqplot/jquery.jqplot.min.js"></script>
<script type="text/javascript" src="js/jquery-jqplot/plugins/jqplot.canvasTextRenderer.min.js"></script>
<script type="text/javascript" src="js/jquery-jqplot/plugins/jqplot.canvasAxisLabelRenderer.min.js"></script>
<script type="text/javascript" src="js/jquery-jqplot/plugins/jqplot.dateAxisRenderer.min.js"></script>
<script type="text/javascript" src="js/jquery-jqplot/plugins/jqplot.cursor.min.js"></script>
<script type="text/javascript" src="js/jquery-jqplot/plugins/jqplot.highlighter.min.js"></script>
-->

<!-- flot -->
<script type="text/javascript" src="js/flot/jquery.flot.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.crosshair.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.time.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.selection.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.resize.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.axislabels.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.canvas.js"></script>
<!--script type="text/javascript" src="js/ext/curvedLines.js"></script -->

<!-- socket.io for communication -->
<!-- script type="text/javascript" src="http://localhost:3000/socket.io/socket.io.js"></script -->

<!-- MQTT Websocket -->
<script type="text/javascript" src="js/mqtt/mqttws31.js"></script>

<!-- Application specific sources -->
<script type="text/javascript" src="js/init.js"></script>
<script type="text/javascript" src="js/functions.js"></script>
<script type="text/javascript" src="js/entities.js"></script>
<script type="text/javascript" src="js/wui.js"></script>
<script type="text/javascript" src="js/entity.js"></script>
<script type="text/javascript" src="js/options.js"></script>

<!--
<script type="text/javascript" src="js/graph.js"></script>
-->


<script type="text/javascript" src="js/mqtt.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        /* Connect to MQTT server */
        MQTTconnect();
    });
</script>

<style type="text/css">
    table.jqplot-table-legend, table.jqplot-cursor-legend {border: none;}
    .btn-controls {width: 85px !important;}
    .jqplot-chart { width: 100%; height: 300px;}
</style>

<div class="device-plot">
    <div class="box">
        <div class="box-header with-border">
            <h2 class="box-title">Chart: <span id="title"></span></h2>
        </div>

        <div class="box-body">
            <div class="panel panel-default">
              <div class="panel-body">
                    <div id="plot">
                        <div id="flot"></div>
                        <div id="overlay"></div>
                        <div id="legend"></div>
                    </div>
                    
              </div>
            </div>
        </div>

        <div class="box-body">
            <div class="panel panel-default">
                <div class="panel-body">

                    <div id="entity-list">

                        <table class="table table-sm">
                            <thead>
                                <tr>
                                <th><i class="fa fa-fw fa-eye"></i></th>
                                <th></th>
                                <th>Title</th>
                                <th class="type">Type</th>
                                <th class="min">Min</th>
                                <th class="max">Max</th>
                                <th class="average">&empty;</th>
                                <th class="last">Latest</th>
                                <th class="consumption">Consumption</th>
                                <th class="cost">Cost</th>
                                <th class="total">Total</th>
                                <th class="ops">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                    </div>

                </div>
            </div>
        </div>

        <div class="box-body">
            <div class="panel panel-default">
              <div class="panel-body">
                <div id="aqualoop-controls" class="jqplot-controls"></div>

                    <div class="row form-group" id="controls">
                        <div class="col-md-6">
                            <div class="float-left">
                                <div class="btn-group">
                                    <button type="button" value="move-back"    class="btn btn-default btn-controls"><i class="fa fa-backward"></i><br/> Backward </button>
                                    <button type="button" value="move-forward" class="btn btn-default btn-controls"><i class="fa fa-forward"></i><br/> Forward </button>
                                    <button type="button" value="move-last"    class="btn btn-default btn-controls"><i class="fa fa-step-forward"></i><br/> Now </button>
                                    <button type="button" value="zoom-out"     class="btn btn-default btn-controls"><i class="fa fa-search-minus"></i><br/> Zoom out </button>
                                    <button type="button" value="zoom-in"      class="btn btn-default btn-controls"><i class="fa fa-search-plus"></i><br/> Zoom in </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="float-right">
                                <div class="pull-right">
                                <div class="btn-group">
                                    <button type="button" value="zoom-hour"    class="btn btn-default btn-controls"><i class="fa fa-arrows-h"></i><br/> Hour </button>
                                    <button type="button" value="zoom-day"     class="btn btn-default btn-controls"><i class="fa fa-arrows-h"></i><br/> Day </button>
                                    <button type="button" value="zoom-week"    class="btn btn-default btn-controls"><i class="fa fa-arrows-h"></i><br/> Week </button>
                                    <button type="button" value="zoom-month"   class="btn btn-default btn-controls"><i class="fa fa-arrows-h"></i><br/> Month </button>
                                    <button type="button" value="zoom-year"    class="btn btn-default btn-controls"><i class="fa fa-arrows-h"></i><br/> Year </button>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">

                        <div class="col-md-3">

                                <!--span class="align-middle">Refreshing every: </span-->
                                <!--input class="form-check-input" type="checkbox" value="" id="refresh">
                                <label class="form-check-label" for="refresh">Automatic refresh <span id="refresh-time"></span></label -->
                            <div class="form-group">
                                <label for="refresh-interval">Refreshing every:</label>
                                <select class="form-control align-middle" id="refresh-interval">
                                    <option value="undefined">off</option>
                                    <option value="5">5s</option>
                                    <option value="10">10s</option>
                                    <option value="30">30s</option>
                                    <option value="60">1m</option>
                                    <option value="300">5m</option>
                                    <option value="900">15m</option>
                                    <option value="1800">30m</option>
                                    <option value="3600">1h</option>
                                    <option value="7200">2h</option>
                                    <option value="86400">1d</option>
                                </select>
                            </div>
                        </div>

                    </div>

              </div>
            </div>
        </div> 

    </div>
</div>


<div class="device-status">
<div class="box">

<div class="box-header with-border">
        <h2 class="box-title">Status: <span id="online_state" class="btn-danger badge">Offline</span></h2>

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
        <h2 class="box-title">Details: <?= Html::encode($this->title) ?></h2>
</div>

<div class="box-body">

    <div class="row">
        <div class="col-md-6">
            <div class="float-left">
                <p>
                    <?= Html::a('Update',     ['update',           'device_id' => $model->device_id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Parameters', ['parameters/view',  'device_id' => $model->device_id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Alerting',   ['alerting/index',   'device_id' => $model->device_id], ['class' => 'btn btn-warning']) ?>
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
