<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use yii\grid\GridView;

use yii\helpers\ArrayHelper;
use backend\models\Contact;
use backend\models\ContactSearch;
use backend\models\ContactQuery;

/* @var $this yii\web\View */
/* @var $model backend\models\Customer */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="alerting-form">

<?php /*
    <?= GridView::widget([
        'dataProvider' => ContactSearch::getUnusedAqualoopContacts(),
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'contact_id',
            'name',
        ],
    ]); */ ?>

    <?php $form = ActiveForm::begin(); ?>

	<div class="form-group field-device-name">
		<label class="control-label" for="device-name">Device Name</label>
		<input type="text" id="device-name" class="form-control" placeholder="<?= Html::encode($device->name) ?>" readonly>
		<div class="help-block"></div>
	</div>

    <?= Html::activeHiddenInput($model, 'device_id', ['value' => $device->device_id]) ?>

	<div class="form-group field-product-name">
		<label class="control-label" for="device-product">INTEWA Product</label>
		<input type="text" id="device-product" class="form-control" placeholder="<?= Html::encode($device->product->name) ?>" readonly>
		<div class="help-block"></div>
	</div>

    <div class="form-group">
    <?= Html::activeLabel($model, 'contact.name'); ?>
    <?php
    switch ($device->product_id) {

    	case 10:
    	echo Html::activeDropDownList($model, 'contact_id', [null => "none"] + ArrayHelper::map(ContactSearch::getUnusedAlContacts($device->device_id)->getModels(), 'contact_id', 'name'), ['class' => "form-control"])  /*, 'prompt'=>'NONE'*/; 
    	break;

    	case 20:
    	echo Html::activeDropDownList($model, 'contact_id', [null => "none"] + ArrayHelper::map(ContactSearch::getUnusedRmContacts($device->device_id)->getModels(), 'contact_id', 'name'), ['class' => "form-control"])  /*, 'prompt'=>'NONE'*/; 
    	break;
    } ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Save') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php //echo '<pre>'; print_r($postreturn); ?>



