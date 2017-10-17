<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Customer */

$this->title = $model->last_name . ", " . $model->first_name;

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Customers'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>




<div class="customer-view">

<div class="box">

<div class="box-header with-border">
        <h2 class="box-title">Customer details: <?= Html::encode($this->title) ?></h2>
</div>

<div class="box-body">
    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'customer_id' => $model->customer_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'customer_id' => $model->customer_id], [
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
            'customer_id',
            'first_name',
            'last_name',
            'email:email',
            'prime_use',
        ],
    ]) ?>

</div>
</div>
</div>
