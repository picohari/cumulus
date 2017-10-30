<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Device */

$this->title = Yii::t('app', 'Update {modelClass}: ', [
    'modelClass' => 'Device',
]) . $model->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Devices'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'device_id' => $model->device_id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="device-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    <?php

    $this->registerJs("

    	var location = ".$subscription->site_id.";
    	if (location != null)
			$('select#device-subscription').val(".$subscription->site_id.");
		else
			$('select#device-subscription').val('');
    ");
    ?>

</div>

<?php //echo '<pre>'; print_r($subscription); ?>
