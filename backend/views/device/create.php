<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Device */

$this->title = Yii::t('app', 'Add Device');
$this->blocks['content-header'] = "Add new INTEWA Device";
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Devices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="device-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <?php
/*
    $this->registerJs("

        var site_id = ".$site.";
        if (location != 'none')
            $('select#device-subscription').val(".$site.");
        else
            $('select#device-subscription').val('');
    ");
*/
    ?>
</div>
