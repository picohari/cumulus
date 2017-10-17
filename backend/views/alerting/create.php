<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Customer */

$this->title = Yii::t('app', 'Add contact');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Alerting'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="alerting-create">

    <h1><?= Html::encode($this->title) ?> to device: <?= Html::encode($device->name) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'device' => $device,
    ]) ?>

</div>
