<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ParametersAl */
/* @var $form ActiveForm */
?>
<div class="parameters-aqualoop">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'device_id') ?>
        <?= $form->field($model, 'user_id') ?>
        <?= $form->field($model, 'mem_cnt') ?>
        <?= $form->field($model, 'c1_cnt') ?>
        <?= $form->field($model, 'c2_cnt') ?>
        <?= $form->field($model, 't1_hour') ?>
        <?= $form->field($model, 't1_min') ?>
        <?= $form->field($model, 't2_hour') ?>
        <?= $form->field($model, 't2_min') ?>
        <?= $form->field($model, 'sludge_days') ?>
        <?= $form->field($model, 'sludge_sec') ?>
        <?= $form->field($model, 'air_on') ?>
        <?= $form->field($model, 'air_off') ?>
    
        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'Submit'), ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- parameters-aqualoop -->
