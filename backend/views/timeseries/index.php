<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContactSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'OpenTSDB');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="opentsdb-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <h6>
    	<strong>Version: </strong><?php echo $tsdb_version['version']; ?>
		<strong> built on: </strong><?php echo date('r', $tsdb_version['timestamp']); ?>
		<strong> by </strong><?php echo $tsdb_version['user']; ?>
		<strong> on </strong><?php echo $tsdb_version['host']; ?>
	</h6>

    <h6>
		<strong>Branch: </strong><?php echo $tsdb_version['branch']; ?>
    	<strong> is </strong><?php echo $tsdb_version['repo_status']; ?>
		<strong> RevID: </strong><?php echo $tsdb_version['short_revision']; ?>
	</h6>

	<?php echo '<pre>'; print_r($data); echo '</pre>' ?>
	<?php echo '<pre>'; print_r($metrics); echo '</pre>' ?>
</div>
