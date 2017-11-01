<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\ParametersAl */

$this->title = Yii::t('app', 'Create Parameters Al');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Parameters Als'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parameters-al-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
