<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ParametersAlSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Parameters Als');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameters-al-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a(Yii::t('app', 'Create Parameters Al'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'parameters_id',
            'device_id',
            'user_id',
            'mem_cnt',
            'c1_cnt',
            // 'c2_cnt',
            // 't1_hour',
            // 't1_min',
            // 't2_hour',
            // 't2_min',
            // 'sludge_days',
            // 'sludge_sec',
            // 'air_on',
            // 'air_off',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
