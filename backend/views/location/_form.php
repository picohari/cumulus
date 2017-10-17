<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use yii\helpers\ArrayHelper;
use backend\models\Country;

/* @var $this yii\web\View */
/* @var $model backend\models\Location */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="location-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'first_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'post_code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
    <?= Html::activeLabel($model, 'country_code'); ?>
    <?= Html::activeDropDownList($model, 'country_code', ArrayHelper::map(Country::find()->all(), 'country_code', 'name'), ['class' => "form-control"]) ?>
    </div>

    <?= $form->field($model, 'site_description')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
