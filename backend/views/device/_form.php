<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\ArrayHelper;
use backend\models\Product;
use backend\models\Location;
use backend\models\Contact;

/* @var $this yii\web\View */
/* @var $model backend\models\Device */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="device-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php /*
    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'product_id')->textInput() ?>

    <?= $form->field($model, 'product_id')->textInput(['maxlength' => true]) ?>
    */ ?>

    <div class="form-group">
    <?= Html::activeLabel($model, 'product.name'); ?>
    <?= Html::activeDropDownList($model, 'product_id', ArrayHelper::map(Product::find()->all(), 'product_id', 'name'), ['class' => "form-control"]) ?>
    </div>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'uuid')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
    <?= Html::activeLabel($model, 'subscription.site.name'); ?>
    <?= Html::activeDropDownList($model, 'subscription', [null => "none"] + ArrayHelper::map(Location::find()->all(), 'site_id', 'name'), ['class' => "form-control"])  /*, 'prompt'=>'NONE'*/ ?> 
    </div>

    <div class="form-group">
    <?= Html::activeLabel($model, 'contact.name'); ?>
    <?= Html::activeDropDownList($model, 'contact_id', [null => "none"] + ArrayHelper::map(Contact::find()->all(), 'contact_id', 'name'), ['class' => "form-control"])  /*, 'prompt'=>'NONE'*/ ?> 
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <?php /* = Html::a(Yii::t('app', 'Create Location'), ['location/create', 'id' => $model->subscription], ['class' => 'btn btn-success']) */ ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>