<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\DeviceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Devices');
$this->blocks['content-header'] = "Device list";

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="device-index">

<div class="box">

<div class="box-header with-border">
    <h2 class="box-title"><?= Html::encode($this->title) ?></h2>
</div>

<div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Device'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            //'device_id',
            'product.name',
            //'uuid',
            'name',
            'subscription.site.name',
            //'contact.name',
            // 'version',
            // 'status',
            // 'description',

            ['class' => 'yii\grid\ActionColumn',

             'urlCreator' => function( $action, $model, $key, $index ) {
                    if ($action == "view") {
                        if ($model->product_id == "10")
                            return Url::to(['device/aqualoop',   'device_id' => $model->device_id]);
                        if ($model->product_id == "20")
                            return Url::to(['device/rainmaster', 'device_id' => $model->device_id]);
                    }
                    if ($action == "update") {
                        return Url::to(['device/update', 'device_id' => $model->device_id]);
                    }
                    if ($action == "delete") {
                        return Url::to(['device/delete', 'device_id' => $model->device_id]);
                    }
                },

             'controller' => 'device'

            ],
        ],
    ]); ?>
</div>

<div class="box-body">
    <div class="col-md-6">
        <h4>Tip!</h4>
        <p>Get more devices at <?= Html::a('INTEWA Store', 'http://de.intewa-store.com/') ?>.</p>
    </div>
</div>

</div>


</div>
