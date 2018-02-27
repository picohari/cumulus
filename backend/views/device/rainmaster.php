<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Device */

$this->title = $model->name;
$this->blocks['content-header'] = "RAINMASTER: " . $model->name;
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

<!--script type="text/javascript">
    $(document).ready(function() {
        /* Connect to MQTT server */
        MQTTconnect();
    });
</script-->



<div class="device-status">
<div class="box">


<div class="box-header with-border">
        <h2 class="box-title">RM-Eco Status: <?= Html::encode($this->title) ?></h2>
        <?php if ($site != "none") {
            echo "<p>Location: ";
            echo Html::a(Html::encode($model->subscription->site->name), ['location/view', 'site_id' => $model->subscription->site->site_id]);
            echo "</p>";
        } ?>
</div>

<div class="box-body">
        <div class="panel panel-default">
          <div class="panel-body">
                <table class="table table-striped">
                    <!-- Entrace door -->
                    <tr>
                    <td width="40%" style="vertical-align:middle;"><h3>Valve Clearwater</h3><small id="value1">(no value recieved)</small></td>
                    <td style="vertical-align:middle;"></td>
                    <td width="30%" style="vertical-align:middle;"><h4>&nbsp;<span id="label1" class="label">Unknown</span></h4></td>
                    </tr>
                    <!-- Back door -->
                    <tr>
                    <td width="40%" style="vertical-align:middle;"><h3>Floatswitch BR</h3><small id="value2">(no value recieved)</small></td>
                    <td style="vertical-align:middle;"></td>
                    <td width="30%" style="vertical-align:middle;"><h4>&nbsp;<span id="label2" class="label">Unknown</span></h4></td>
                    </tr>
                    <!-- Kitchen door -->
                    <tr>
                    <td width="40%" style="vertical-align:middle;"><h3>Floatswitch CL</h3><small id="value3">(no value recieved)</small></td>
                    <td style="vertical-align:middle;"></td>
                    <td width="30%" style="vertical-align:middle;"><h4>&nbsp;<span id="label3" class="label">Unknown</span></h4></td>
                    </tr>
                    <!-- Living room temperature -->
                    <tr>
                    <td width="40%" style="vertical-align:middle;"><h3>Pump RPM</h3><small id="livingTempSensor">(no value recieved)</small></td>
                    <td style="vertical-align:middle;"><div id="livingTempChart" style="height:80px; width:180px;"></div></td>
                    <td width="30%" style="vertical-align:middle;"><h4>&nbsp;<span id="livingTempLabel" class="label">Unknown</span></h4></td>
                    </tr>
                    <!-- Basement temperature -->
                    <tr>
                    <td width="40%" style="vertical-align:middle;"><h3>Temperature CL</h3><small id="basementTempSensor">(no value recieved)</small></td>
                    <td style="vertical-align:middle;"><span class="basementTempSparkline"></span></td>
                    <td width="30%" style="vertical-align:middle;"><h4>&nbsp;<span id="basementTempLabel" class="label">Unknown</span></h4></td>
                    </tr>
                </table>
          </div>
        </div>
        
        <div class="panel panel-default">
            <div class="panel-body">
              <div class="row">
                <div class="col-md-6"><h5 class="h5"><b> Latest MQTT message:  </b> <span id="message">no message recieved</span></h5></div>
                <div class="col-md-6"><h5 class="h5"><b> Status: </b>  <span id='status'></span></h5></div>
              </div>
            </div>
        </div>

</div>
</div>
</div>

        
<div class="device-view">
<div class="box">


<div class="box-header with-border">
        <h2 class="box-title">RAINMASTER details: <?= Html::encode($this->title) ?></h2>
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
        ],
    ]) ?>

</div>
</div>
</div>
