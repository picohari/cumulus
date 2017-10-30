<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
$this->title = 'Applications';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-apps">
    <h1><?= Html::encode($this->title) ?></h1>




    <p>INTEWA Connect is a service for our customers only. You need one of our products to be able to use this service.</p>

    <p>In case you have an older INTEWA system, <?= Html::a('please contact us', ['/site/contact']) ?> for an upgrade of your hardware.</p>

    <br><br>
    <p>For more information see our homepage: <a href="http://www.intewa.com/">www.intewa.com</a></p>


</div>
