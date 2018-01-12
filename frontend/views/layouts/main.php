<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use frontend\widgets\Alert;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <?php $this->beginBody() ?>

    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => '<img src="images/intewa_logo.png" height="70px" style="display:inline; position:relative; bottom:50px;">',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top',
                ],
            ]);
            $menuItems = [
                ['label' => 'Home', 'url' => ['/site/index']],
                ['label' => 'Product', 'url' => ['/site/product']],

                ['label' => 'Applications', 'url' => ['/site/applications'], 'items' => [
                    ['label' => 'Rainwater and Greywater', 'url' => ['site/rainwater']],
                    ['label' => 'Infiltration and Irrigation', 'url' => ['site/infiltration']],
                ]],

                ['label' => 'Pricing', 'url' => ['/site/pricing']],
                ['label' => 'About', 'url' => ['/site/about']],
                ['label' => 'Contact', 'url' => ['/site/contact']],
            ];
            if (Yii::$app->user->isGuest) {
                $menuItems[] = ['label' => 'Signup', 'url' => ['/user/registration/register']];
                //$menuItems[] = ['label' => 'Login',  'url' => ['/user/security/login']];
                $menuItems[] = ['label' => 'Login', 'url' => \Yii::$app->urlManagerBackend->baseUrl];
                //$menuItems[] = ['label' => 'Login', 'url' => \Yii::$app->urlManagerBackend->createUrl(['site/index'])];
            } else {
                $menuItems[] = [
                    'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/user/security/logout'],
                    'linkOptions' => ['data-method' => 'post','get']
                ];
            }
            echo Nav::widget([
                'options' => ['class' => 'navbar-nav navbar-right'],
                'items' => $menuItems,
            ]);
            NavBar::end();
        ?>

        <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
        <p class="pull-left">&copy; INTEWA GmbH <?= date('Y') ?></p>
        <p class="pull-right"><a href="http://www.intewa.com/">www.intewa.com</a></p>

        <!--p class="pull-right"><?= Yii::powered() ?></p -->
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>