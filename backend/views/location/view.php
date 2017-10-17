<?php

use yii\helpers\Html;
use yii\helpers\Url;

use yii\widgets\DetailView;
use yii\grid\GridView;

use backend\models\Country;

/* @var $this yii\web\View */
/* @var $model backend\models\Location */

$this->title = $model->name;
$this->blocks['content-header'] = "Location: " . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Locations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="location-devices">
<div class="box">

<div class="box-header with-border">
    <h2 class="box-title">Location devices</h2>
</div>

<div class="box-body">
    <p>
        <?= Html::a('Create Device', ['device/create_at', 'site_id' => $model->site_id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'tableOptions' => ['id' => "location-devices-table", 'class' => 'table table-striped table-hover'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            /*'device_id',*/
            /*'name',*/
            [
                'label' => "Name",
                'format' => 'raw',
                    'value' => function( $model ) {
                        if ($model->product_id == "10")
                            return Html::a($model->name, ['device/aqualoop',   'device_id' => $model->device_id], ['class' => 'profile-link']);
                        if ($model->product_id == "20")
                            return Html::a($model->name, ['device/rainmaster', 'device_id' => $model->device_id], ['class' => 'profile-link']);
                        //if ($model->product_id == null)
                        return Html::encode($model->name);
                    },
            ],
            'product.name',
            /*'status',*/
            [
                'label' => "Status",
                'value' => 'status',
                'filter' => false,
            ],
            /*'uuid',*/
            [
            'class' => 'yii\grid\ActionColumn',

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

    <?php
    /*
    $this->registerJs("
        $('#location-devices-table tbody td').click(function (e) {
            var id = $(this).closest('tr').attr('data-key');
            if(e.target == this)
                location.href = '" . Url::to(['device/view']) . "&device_id=' + id;
        });
    ");
    */
    ?>

</div>
</div>
</div>


<div class="location-view">
<div class="box">

<div class="box-header with-border">
    <h2 class="box-title">Location details</h2>
</div>

<div class="box-body">
    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'site_id' => $model->site_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'site_id' => $model->site_id], [
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
            'site_id',
            'name',
            'first_address',
            'last_address',
            'post_code',
            'city',
            /*'country_code',*/
            [
                'label' => "Country",
                'value' => $model->countryCode->name,
            ],
            'site_description',
        ],
    ]) ?>

</div>
</div>
</div>
