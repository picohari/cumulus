<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ParametersAlSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="parameters-al-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'parameters_id') ?>

    <?= $form->field($model, 'device_id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'mem_cnt') ?>

    <?= $form->field($model, 'c1_cnt') ?>

    <?php // echo $form->field($model, 'c2_cnt') ?>

    <?php // echo $form->field($model, 't1_hour') ?>

    <?php // echo $form->field($model, 't1_min') ?>

    <?php // echo $form->field($model, 't2_hour') ?>

    <?php // echo $form->field($model, 't2_min') ?>

    <?php // echo $form->field($model, 'sludge_days') ?>

    <?php // echo $form->field($model, 'sludge_sec') ?>

    <?php // echo $form->field($model, 'air_on') ?>

    <?php // echo $form->field($model, 'air_off') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
