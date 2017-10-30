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

    <?php if ($this->params['customParam']): ?>

        <div class="intro-slogan">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="intro-message">
                        <!-- img class="image-responsive text-center" src="/frontend/web/images/intewa_logo.png" width="25%" -->

                        <h2>
                        <br><br><br><br>
                        <span>INTEWA Cumulus</span><br>
                        <span>The monitoring platform for rainwater applications</span>
                        </h2>
                        
                        <!--
                        <ul class="list-inline intro-register-buttons">
                            <li>
                                <a class="btn btn-default btn-lg" href="<?php echo Url::to(['site/login']); ?>"><i class="fa fa-login fa-fw"></i> <span class="network-name">Login</span></a>
                            </li>
                            <li>
                                <a class="btn btn-default btn-lg" href="<?php echo Url::to(['site/signup']); ?>"><i class="fa fa-signup fa-fw"></i> <span class="network-name">Register</span></a>
                            </li>
                        </ul>
                        -->


                    </div>
                </div>
            </div>
        </div>
        <!-- /.container -->

        </div>
        <div class="intro-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="">


                    </div>
                </div>
            </div>
        </div>
        <!-- /.container -->
        </div>

        
    <?php endif; ?>

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
                $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
                $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
            } else {
                $menuItems[] = [
                    'label' => 'Logout (' . Yii::$app->user->identity->username . ')',
                    'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']
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