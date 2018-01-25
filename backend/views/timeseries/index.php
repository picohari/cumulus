<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ContactSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

				//src="http://cumulus.intewa.net:3000/dashboard/snapshot/05pjCp5XeJFh30NwYXi3QRBIkoDg265V"

$this->title = Yii::t('app', 'OpenTSDB');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="opentsdb-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <div>
		<!-- iframe 
				src="http://cumulus.intewa.net:3000/dashboard-solo/db/cumulus?orgId=1&from=1516098890082&to=1516102490082&refresh=5s&panelId=1"
				width="100%"
				height="300"
				frameborder="0"></iframe -->    	
    </div>

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
