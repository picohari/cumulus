<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CustomerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Profile');
$this->blocks['content-header'] = "Profile overview";

$this->params['breadcrumbs'][] = $this->title;
?>



<div class="customer-index">
<div class="box">

<div class="box-header with-border">
    <h2 class="box-title">Customers</h2>
</div>

<div class="box-body">
    <p>
        <?= Html::a(Yii::t('app', 'Create Customer'), ['customer/create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProviderCustomer,
        'filterModel' => $searchCustomerModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'first_name',
            'last_name',
            'email:email',
            // 'prime_use',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

<div class="box-body">
    <div class="callout tip-block col-md-6">
        <h4>Tip!</h4>
        <p>Customers are real people with administrative roles and functions. Customers can be assigned to locations for mailings and billings.</p>
    </div>
</div>

</div>
</div>



<div class="customer-index">
<div class="box">

<div class="box-header with-border">
    <h2 class="box-title">Contacts</h2>
</div>

<div class="box-body">
    <p>
        <?= Html::a(Yii::t('app', 'Create Contact'), ['contact/create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProviderContact,
        'filterModel' => $searchContactModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'email',
            'phone',
            // 'prime_use',

            ['class' => 'yii\grid\ActionColumn', 'controller' => 'contact'],
        ],
    ]); ?>
</div>

<div class="box-body">
    <div class="callout tip-block col-md-6">
        <h4>Tip!</h4>
        <p>Contacts are used for device notifications. Each device can be assigned to a contact for alarm or warning messaging.</p>
    </div>
</div>

</div>
</div>


