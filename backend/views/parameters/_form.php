<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\ParametersAl */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="parameters-al-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'device_id')->textInput() ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'mem_cnt')->textInput() ?>

    <?= $form->field($model, 'c1_cnt')->textInput() ?>

    <?= $form->field($model, 'c2_cnt')->textInput() ?>

    <?= $form->field($model, 't1_hour')->textInput() ?>

    <?= $form->field($model, 't1_min')->textInput() ?>

    <?= $form->field($model, 't2_hour')->textInput() ?>

    <?= $form->field($model, 't2_min')->textInput() ?>

    <?= $form->field($model, 'sludge_days')->textInput() ?>

    <?= $form->field($model, 'sludge_sec')->textInput() ?>

    <?= $form->field($model, 'air_on')->textInput() ?>

    <?= $form->field($model, 'air_off')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
