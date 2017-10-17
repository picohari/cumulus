<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */


$this->title = 'Alerting';

$this->params['breadcrumbs'][] = ['label' => $device->name, 'url' => ['device/index', 'device_id' => $device->device_id]];
$this->params['breadcrumbs'][] = $this->title;

$device_id = $device->device_id;
?>



<div class="site-index">

<h1><?= Html::encode($this->title) ?> for <?= Html::encode($device->name) ?></h1>

<!-- p>Your name is: <?= Html::encode(Yii::$app->user->identity->username) ?></p -->
<p>INTEWA Product: <b><?= Html::encode($device->product->name) ?></b> (Device ID: <?= Html::encode($device->device_id) ?>)</p>

<?php $form = ActiveForm::begin(); ?>

<div class="row">
    <div class="col-md-6">
        <div class="float-left">
            <p>
                <!-- ?= Html::a('Save',          ['alerting/index',  'device_id' => $device->device_id], ['class' => 'btn btn-primary']) ? -->
                <?= Html::a('Add Contact',   ['alerting/create', 'device_id' => $device->device_id], ['class' => 'btn btn-success']) ?>

            </p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="float-right">
            <!-- p>
                <?= Html::a('Delete', ['delete', 'device_id' => $device->device_id], [
                    'class' => 'btn btn-danger pull-right',
                    'data' => [
                        'confirm' => 'Are you REALLY sure you want to delete this Device?',
                        'method' => 'post',
                    ],
                ]) ?>
            </p -->
        </div>
    </div>
</div>


<?= Html::activeHiddenInput($device, 'device_id', ['value' => $device->device_id]) ?>


 <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,

        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'device_id',
            'contact.name',
            'contact.email',
            [
                'label' => "Power</br>failure",
                'encodeLabel' => false,
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions'  => ['class' => 'text-center'],
                'format' => 'raw',
                    'value' => function($dataProvider) {
                    	// Trick: Wir legen die Felder doppelt an, einmal als "hidden" u einmal sichtbar. Damit wird beim POST eine nicht-angewählter Paramter mit 0[..] = 0 gesendet
                    	echo   Html::hiddenInput($dataProvider->contact_id . "[power_fail]", $value="0", $options = ['id' => 'alert_powerfail']);
						return Html::checkbox(   $dataProvider->contact_id . "[power_fail]", $checked = ($dataProvider->power_fail != 1 ? false : true), $options = ['id' => 'alert_powerfail']);
		
                    },
            ],
            [
                'label' => "Connection</br>lost",
                'encodeLabel' => false,
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions'  => ['class' => 'text-center'],
                'format' => 'raw',
                    'value' => function($dataProvider) {
                    	echo   Html::hiddenInput($dataProvider->contact_id . "[connection_lost]", $value="0", $options = ['id' => 'alert_connectionlost']);
						return Html::checkbox(   $dataProvider->contact_id . "[connection_lost]", $checked = ($dataProvider->connection_lost != 1 ? false : true), $options = ['id' => 'alert_connectionlost']);
                    },
            ],
            [
                'label' => "Pump</br>failure",
                'encodeLabel' => false,
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions'  => ['class' => 'text-center'],
                'format' => 'raw',
                    'value' => function($dataProvider) {
                    	echo   Html::hiddenInput($dataProvider->contact_id . "[pump_fail]", $value="0", $options = ['id' => 'alert_pumpfail']);
						return Html::checkbox(   $dataProvider->contact_id . "[pump_fail]", $checked = ($dataProvider->pump_fail != 1 ? false : true), $options = ['id' => 'alert_pumpfail']);
                    },
            ],
            [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{update}&nbsp;&nbsp;&nbsp;{delete}',
            'urlCreator' => function($action, $dataProvider) {
                    if ($action == "update") {
                        return Url::to(['contact/update', 'contact_id' => $dataProvider->contact_id]);
                    }
                    if ($action == "delete") {
                        return Url::to(['contact/delete', 'contact_id' => $dataProvider->contact_id, 'device_id' => $dataProvider->device_id]);
                    }
                },

            'controller' => 'device'

            ],
        ],
    ]); ?>


<div class="row">
    <div class="col-md-6">
        <div class="float-left">
            <p>
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
            </p>
        </div>
    </div>
</div>

<?php ActiveForm::end(); ?>


<div class="row">
    <div class="col-md-6">
        <div class="float-left">
            <div class="callout">
                <h4>Notifying your contacts:</h4>
                <p>Device specific notifications can be be assigned to multiple contacts. Add a new contact and select the type of notification you want to send to this contact.</p>
                <p>As soon as an event occurs, a message with detailed reporting is sent to the corresponding contacts.</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">

        <div class="box box-solid">
            <div class="box-header with-border">
              <i class="fa fa-check-square-o"></i><h3 class="box-title">Alerting description</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <dl class="dl-horizontal">
                    <dt>Power fail</dt>
                    <dd>Means a power loss or disconnection was detected. </dd>

                    <dt>Connection lost</dt>
                    <dd>The internet connection at this site is not reliable. </dd>
                    
                    <dt>Pump fail</dt>
                    <dd>A defective pump operation was detected. </dd>
                    
                    <dt>More errors</dt>
                    <dd>Can be added at any time. </dd>
                </dl>
            </div>
            <!-- /.box-body -->
        </div>

    </div>
</div>








</div>


<?php //echo '<pre>'; print_r($count); '</pre>' ?>
<?php //echo '<pre>'; print_r($keys); '</pre>' ?>
<?php //echo '<pre>'; print_r($contact); '</pre>' ?>
<?php //echo '<pre>'; print_r($post); '</pre>' ?>
