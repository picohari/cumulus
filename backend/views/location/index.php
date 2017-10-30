<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\LocationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Locations');
$this->blocks['content-header'] = "Location list";

$this->params['breadcrumbs'][] = $this->title;
?>

<div class="location-index">

<div class="box">

<div class="box-header with-border">
    <h2 class="box-title"><?= Html::encode($this->title) ?></h2>
</div>

<div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Location'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'site_id',
            //'name',
            [
                'label' => "Name",
                'format' => 'raw',
                    'value' => function( $model ) {
                        return Html::a($model->name, ['location/view',   'site_id' => $model->site_id], ['class' => 'profile-link']);
                    },
            ],
            'city',
            [
                'label' => "Nr. of Device(s)",
                'value' => 'Subscriptionscount',
            ],

            ['class' => 'yii\grid\ActionColumn',
                
             'urlCreator' => function( $action, $model, $key, $index ) {
                    if ($action == "view") {
                        return Url::to(['location/view',   'site_id' => $model->site_id]);
                    }
                    if ($action == "update") {
                        return Url::to(['location/update', 'site_id' => $model->site_id]);
                    }
                    if ($action == "delete") {
                        return Url::to(['location/delete', 'site_id' => $model->site_id]);
                    }
                },

             'controller' => 'location'
            ],
        ],
    ]); ?>
</div>

<div class="box-body">
    <div class="callout tip-block col-md-6">
        <h4>Tip!</h4>
        <p>Locations are used for device aggregation. Each device can be assigned to exactly one location.</p>
    </div>
</div>

</div>


</div>
