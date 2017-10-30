<?php

use yii\helpers\Html;
use yii\helpers\Url;
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

            ['class' => 'yii\grid\ActionColumn',
                
             'urlCreator' => function( $action, $model, $key, $index ) {
                    if ($action == "view") {
                        return Url::to(['customer/view',   'customer_id' => $model->customer_id]);
                    }
                    if ($action == "update") {
                        return Url::to(['customer/update', 'customer_id' => $model->customer_id]);
                    }
                    if ($action == "delete") {
                        return Url::to(['customer/delete', 'customer_id' => $model->customer_id]);
                    }
                },

             'controller' => 'customer'
            ],
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



<div class="contact-index">
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

            ['class' => 'yii\grid\ActionColumn',

             'urlCreator' => function( $action, $model, $key, $index ) {
                    if ($action == "view") {
                        return Url::to(['contact/view',   'contact_id' => $model->contact_id]);
                    }
                    if ($action == "update") {
                        return Url::to(['contact/update', 'contact_id' => $model->contact_id]);
                    }
                    if ($action == "delete") {
                        return Url::to(['contact/delete', 'contact_id' => $model->contact_id]);
                    }
                },
                'controller' => 'contact'],
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


