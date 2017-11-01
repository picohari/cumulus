<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\ParametersAl */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Parameters Al',
]) . $model->parameters_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Parameters Als'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->parameters_id, 'url' => ['view', 'id' => $model->parameters_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="parameters-al-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
