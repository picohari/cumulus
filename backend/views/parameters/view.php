<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\ParametersAl */

$this->title = "Parameters of AL-MS: " . $model->device->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Device'), 'url' => ['device/aqualoop', 'device_id' => $model->device_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php /*
<?php $this->registerJsFile('js/dhtmlx-gantt/dhtmlxgantt.js', ['position' => \yii\web\View::POS_HEAD]); ?>
<?php $this->registerCssFile('js/dhtmlx-gantt/dhtmlxgantt.css', ['position' => \yii\web\View::POS_END]); ?>
*/ ?>


<?php //$this->registerJsFile('js/jquery-ui/jquery-ui.js',   [yii\web\JqueryAsset::className()]); ?>
<?php $this->registerJsFile('js/jquery-ui/jquery-ui.js',   ['position' => \yii\web\View::POS_BEGIN]); ?>
<?php $this->registerCssFile('js/jquery-ui/jquery-ui.css', ['position' => \yii\web\View::POS_END]); ?>


<?php $this->registerJsFile('js/jQRangeSlider/jQRangeSlider.js', ['position' => \yii\web\View::POS_END]); ?>

<?php $this->registerJsFile('js/jQRangeSlider/jQRangeSliderMouseTouch.js', ['position' => \yii\web\View::POS_END]); ?>
<?php $this->registerJsFile('js/jQRangeSlider/jQRangeSliderDraggable.js', ['position' => \yii\web\View::POS_END]); ?>

<?php $this->registerJsFile('js/jQRangeSlider/jQRangeSliderBar.js', ['position' => \yii\web\View::POS_END]); ?>
<?php $this->registerJsFile('js/jQRangeSlider/jQRangeSliderHandle.js', ['position' => \yii\web\View::POS_END]); ?>
<?php $this->registerJsFile('js/jQRangeSlider/jQRangeSliderLabel.js', ['position' => \yii\web\View::POS_END]); ?>

<?php $this->registerJsFile('js/jQRangeSlider/jQDateRangeSliderHandle.js', ['position' => \yii\web\View::POS_END]); ?>
<?php $this->registerJsFile('js/jQRangeSlider/jQDateRangeSlider.js', ['position' => \yii\web\View::POS_END]); ?>

<?php $this->registerJsFile('js/jQRangeSlider/jQRuler.js', ['position' => \yii\web\View::POS_END]); ?>

<?php $this->registerCssFile('js/jQRangeSlider/css/iThing.css', ['position' => \yii\web\View::POS_END]); ?>




<?php /*

<?php $this->registerJsFile('js/jQRangeSlider/jQEditRangeSlider.js', ['position' => \yii\web\View::POS_END]); ?>
<?php $this->registerJsFile('js/jQRangeSlider/jQEditRangeSliderLabel.js', ['position' => \yii\web\View::POS_END]); ?>


*/ ?>



<div class="device-parameters">

<div class="box">

    <div class="box-header with-border">
        <h2 class="box-title"><?= Html::encode($this->title) ?></h2>
    </div>

    <div class="box-body">

        <div class="row">
            <div class="col-md-12">
                <div class="float-left">

                <h5>Device function parameters</h5>
                <p>
                    Some parameters of the device can be modified. <br/>Here is a list of all parameters that can be changed:
                </p>


                </div>
            </div>

            <div class="col-md-12">
                <div class="float-right">

                    <p class="pull-right">
                        <?= Html::a('Back to Device', ['device/aqualoop', 'device_id' => $model->device_id], ['class' => 'btn btn-success']) ?>
                    </p>

                </div>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-12">
                <div class="box-body">
                    <h4>T1/T2 Cycles</h4>
                    <p>Starting times for C1/C2 suction cycles.</p>
                </div>
            </div>
        </div>
        
        <!--
        <div class="row">
            <div class="col-md-4">
                <div class="form-horizontal">
                    <div class="form-group">
                        <span class="col-sm-6 control-label" id="basic-addon3">T1 hours: </span>
                        <div class="col-sm-6"><?= Html::activeInput('text', $model, 't1_hour', ['class' => "form-control"]) ?></div>
                    </div>
                </div>
                <div class="form-horizontal">
                    <div class="form-group">
                        <span class="col-sm-6 control-label" id="basic-addon3">T2 hours: </span>
                        <div class="col-sm-6"><?= Html::activeInput('text', $model, 't2_hour', ['class' => "form-control"]) ?></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-horizontal">
                    <div class="form-group">
                        <span class="col-sm-6 control-label" id="basic-addon3">T1 minute: </span>
                        <div class="col-sm-6"><?= Html::activeInput('text', $model, 't1_min', ['class' => "form-control"]) ?></div>
                    </div>
                </div>
                <div class="form-horizontal">
                    <div class="form-group">
                        <span class="col-sm-6 control-label" id="basic-addon3">T2 minute: </span>
                        <div class="col-sm-6"><?= Html::activeInput('text', $model, 't2_min', ['class' => "form-control"]) ?></div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-horizontal">
                    <div class="form-group">
                        <span class="col-sm-6 control-label" id="basic-addon3">Cycles C1: </span>
                        <div class="col-sm-6"><?= Html::activeInput('text', $model, 'c1_cnt', ['class' => "form-control"]) ?></div>
                    </div>
                </div>
                <div class="form-horizontal">
                    <div class="form-group">
                        <span class="col-sm-6 control-label" id="basic-addon3">Cycles C2: </span>
                        <div class="col-sm-6"><?= Html::activeInput('text', $model, 'c2_cnt', ['class' => "form-control"]) ?></div>
                    </div>
                </div>
            </div>
        </div>
        -->
        
        <!--
        <div class="row">
            <div class="col-md-12">

                <div id="gantt_here" style="width:100%; height:130px;"></div>

                <script type="text/javascript">
                    var tasks =  {
                        data:[
                            {id:1, text:"Cycle C1", start_date:"01-04-2013 7:15", duration:12,order:10, progress:0.4, open: true},
                            {id:2, text:"Cycle C2", start_date:"01-04-2013 15:30", duration:24,order:10, progress:0.4, open: true},
                        ],

                    };

                    //var scale_day = 0;

                    //gantt.templates.date_scale = function(date) {
                        //var d = gantt.date.date_to_str("%F %d");
                        //return "<strong>Day " + (scale_day++) + "</strong>";
                    //};

                    //gantt.config.scale_height = 44;
                    gantt.config.duration_unit = "minute";
                    gantt.config.duration_step = 15;

                    gantt.config.scale_unit = "hour";
                    gantt.config.date_scale = "%H:%i";
                    gantt.config.time_step = 1;
                    gantt.config.step = 1;

                    gantt.config.min_column_width = 40;

                    gantt.config.subscales = [
                        //{unit:"hour", step:2, date:"%H:%i"},
                        //{unit:"minute", step:15, date : "%i"}
                    ];

                    gantt.config.grid_width = 0;

                    gantt.init("gantt_here");

                    gantt.parse(tasks);

                </script>

            </div>
        </div>
        -->

        <div class="row">
            <div class="col-md-12">
                <div class="box-body">
                    <b>Cycle C1</b>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div id="cycles-slider-c1"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box-body">
                    <b>&nbsp;</b>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box-body">
                    <b>Cycle C1</b>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div id="cycles-slider-c2"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box-body">
                    <b>&nbsp;</b>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box-body">
                    <div class="form-group pull-right">
                        <a onclick="updateTIMER()" class="btn btn-primary">Save</a>        
                    </div>
                </div>
            </div>
        </div>

        <script>

            $( function() {

                var min2 = new Date("2018-01-01T00:00:00Z"),
                    max2 = new Date("2018-01-02T06:00:00Z");

                function addZero(val) {
                    if (val < 10) {
                        return "0" + val;
                    }

                    return val;
                }

                $("#cycles-slider-c1").dateRangeSlider({

                    arrows: false,

                    bounds: {
                        "min": min2,
                        "max": max2
                    },

                    range: {
                        min: {
                            hours: 1
                        }
                    },

                    step: {
                        minutes: 5
                    },

                    scales: [{
                        first: function (value) {
                            return value;
                        },
                        end: function (value) {
                            return value;
                        },
                        next: function (value) {
                            var next = new Date(value);
                            return new Date(next.setHours(value.getHours() + 1));

                        },
                        label: function (value) {
                            return value.getHours();
                        },
                        format: function (tickContainer, tickStart, tickEnd) {
                            tickContainer.addClass("myCustomClass");
                        }
                    }],

                    formatter: function (val) {
                        var h = val.getHours(),
                            m = val.getMinutes();

                        return addZero(h) + ':' + addZero(m);
                    },

                    defaultValues: {
                        min: new Date("2018-01-01T05:30:00Z"),
                        max: new Date("2018-01-01T10:00:00Z")
                    }
                });

                $("#cycles-slider-c2").dateRangeSlider({

                    arrows: false,

                    bounds: {
                        "min": min2,
                        "max": max2
                    },

                    range: {
                        min: {
                            hours: 1
                        }
                    },

                    step: {
                        minutes: 5
                    },

                    scales: [{
                        first: function (value) {
                            return value;
                        },
                        end: function (value) {
                            return value;
                        },
                        next: function (value) {
                            var next = new Date(value);
                            return new Date(next.setHours(value.getHours() + 1));

                        },
                        label: function (value) {
                            return value.getHours();
                        },
                        format: function (tickContainer, tickStart, tickEnd) {
                            tickContainer.addClass("myCustomClass");
                        }
                    }],

                    formatter: function (val) {
                        var h = val.getHours(),
                            m = val.getMinutes();

                        return addZero(h) + ':' + addZero(m);
                    },

                    defaultValues: {
                        min: new Date("2018-01-01T16:30:00Z"),
                        max: new Date("2018-01-01T21:45:00Z")
                    }
                });
            });

        </script>





        <hr>

        <div class="row">
            <div class="col-md-12">
                <div class="box-body">
                    <h4>Aeration</h4>
                    <p>Cycles of air ventilation.</p>
                </div>
            </div>
        </div>

        <!--
        <div class="row">

            <div class="col-md-6">
                <div class="form-horizontal">
                    <div class="form-group">
                        <span class="col-sm-6 control-label" id="basic-addon3">Air ON: </span>
                        <div class="col-sm-6"><?= Html::activeInput('text', $model, 'air_on', ['class' => "form-control"]) ?></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-horizontal">
                    <div class="form-group">
                        <span class="col-sm-6 control-label" id="basic-addon3">Air OFF: </span>
                        <div class="col-sm-6"><?= Html::activeInput('text', $model, 'air_off', ['class' => "form-control"]) ?></div>
                    </div>
                </div>
            </div>

        </div>
        -->

        <div class="row">
            <div class="col-md-12">
                <div class="box-body">
                    <b>AIR Compressor OFF/ON time</b>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div id="slider-air-on"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box-body">
                    <b>&nbsp;</b>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box-body">
                    <div class="form-group pull-right">
                        <a onclick="updateAIR()" class="btn btn-primary">Save</a>        
                    </div>
                </div>
            </div>
        </div>

        <script>

            $( function() {

                $("#slider-air-on").rangeSlider(
                {

                    arrows: false,

                    range:  {min: 10, max: 40},

                    scales: [
                        // Primary scale
                        {
                            first: function(val){ return val; },
                            next: function(val){ return val + 10; },
                            stop: function(val){ return false; },
                            label: function(val){ return val; },
                            format: function(tickContainer, tickStart, tickEnd){ 
                                    tickContainer.addClass("myCustomClass");
                                }
                        },
                        // Secondary scale
                        {
                            first: function(val){ return val; },
                            next: function(val){
                              if (val % 10 === 9){
                                return val + 2;
                              }
                              return val + 1;
                            },
                            stop: function(val){ return false; },
                            label: function(){ return null; }
                        }
                    ],
                    
                });

            });

        </script>


        <hr>

        <div class="row">
            <div class="col-md-12">
                <div class="box-body">
                    <b>Sludge pump</b>
                    <p>Cycles of sludge pump.</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-horizontal">
                    <div class="form-group">
                        <span class="col-sm-6 control-label" id="basic-addon3">Sludge Pump days: </span>
                        <div class="col-sm-6"><?= Html::activeInput('text', $model, 'sludge_days', ['class' => "form-control"]) ?></div>
                    </div>
                </div>
                <div class="form-horizontal">
                    <div class="form-group">
                        <span class="col-sm-6 control-label" id="basic-addon3">Sludge Pump seconds: </span>
                        <div class="col-sm-6"><?= Html::activeInput('text', $model, 'sludge_sec', ['class' => "form-control"]) ?></div>
                    </div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box-body">
                    <div class="form-group pull-right">
                        <a onclick="updateSP()"  class="btn btn-primary">Save</a>        
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="row">
            <div class="col-md-12">
                <div class="box-body">
                    <b>Suction overflow</b>
                    <p>Description here... .</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-horizontal">
                    <div class="form-group">
                        <span class="col-sm-6 control-label" id="basic-addon3">Suction Overflow: </span>
                        <div class="col-sm-6"><?php //Html::activeInput('text', $model, 'suction_overflow', ['class' => "form-control"]) ?></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box-body">
                    <div class="form-group pull-right">
                        <a onclick="updateSO()"  class="btn btn-primary">Save</a>        
                    </div>
                </div>
            </div>            
        </div>

        <hr>

        <div class="row">
            <div class="col-md-12">
                <div class="box-body">
                    <b>Membranes setup</b>
                    <p>Number of installed membranes.</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="form-horizontal">
                    <div class="form-group">
                        <span class="col-sm-6 control-label" id="basic-addon3">Membranes number: </span>
                        <div class="col-sm-6"><div id="mem-num-slider"><div id="custom-handle" class="ui-slider-handle"></div></div></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div>&nbsp;</div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="box-body">
                    <div class="form-group pull-right">
                        <a onclick="updateMEM()"  class="btn btn-primary">Save</a>        
                    </div>
                </div>
            </div>            
        </div>

        <style>
            #mem-num-slider {
                margin-top: 12px;
            }

            #custom-handle {
                width: 3em;
                height: 1.6em;
                top: 50%;
                margin-top: -.8em;
                text-align: center;
                line-height: 1.6em;
            }
        </style>

        <script>
        $( function() {
            var handle = $( "#custom-handle" );

            $( "#mem-num-slider" ).slider({

                min: 1,
                max: 6,
                step: 1,
                create: function() {
                        handle.text( $( this ).slider( "value" ) );
                },
                slide: function( event, ui ) {
                        handle.text( ui.value );
                }
            });
        });
        </script>


    </div>


    <div class="box-body">
        <div class="callout callout-danger tip-block col-md-6">
            <h4>ToDo!</h4>
            <p>Paramters are stored inside device memory. <br>
               No need to put them into database here... </p>
        </div>
    </div>



</div>
</div>












<!-- div class="parameters-al-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'device_id' => $model->device_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'device_id' => $model->device_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'parameters_id',
            'device_id',
            'user_id',
            'mem_cnt',
            'c1_cnt',
            'c2_cnt',
            't1_hour',
            't1_min',
            't2_hour',
            't2_min',
            'sludge_days',
            'sludge_sec',
            'air_on',
            'air_off',
        ],
    ]) ?>

</div -->
