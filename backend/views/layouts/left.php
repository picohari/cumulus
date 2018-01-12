<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= $directoryAsset ?>/img/user2-160x160.jpg" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= \Yii::$app->user->identity->profile->name ?></p>

                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="q" class="form-control" placeholder="Search..."/>
              <span class="input-group-btn">
                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu'],
                'items' => [

                    ['label' => 'Subscriptions', 'options' => ['class' => 'header']],
                    ['label' => 'Locations',        'icon' => 'home',    'url' => ['/location']],
                    ['label' => 'Devices',          'icon' => 'sitemap', 'url' => ['/device']],

                    ['label' => 'Applications', 'options' => ['class' => 'header']],
                    ['label' => 'Rainwater',        'icon' => 'tint',              'url' => ['#']],
                    ['label' => 'Graywater',        'icon' => 'shower',            'url' => ['#']],
                    ['label' => 'Infiltration',     'icon' => 'umbrella',          'url' => ['#']],
                    ['label' => 'Fire protection',  'icon' => 'fire-extinguisher', 'url' => ['#']],

                    ['label' => 'Alerting',  'options' => ['class' => 'header']],
                    /* ['label' => 'Notifications',    'icon' => 'bell-o',      'url' => ['/alerting']], */
                    ['label' => 'Contacts',         'icon' => 'user-circle', 'url' => ['/customer']],
                    ['label' => 'Service',          'icon' => 'wrench',      'url' => ['#']],

                    ['label' => 'Account',  'options' => ['class' => 'header']],
                    ['label' => 'Profile',          'icon' => 'user-circle', 'url' => ['/user/settings/profile']],
                    ['label' => 'Security',         'icon' => 'lock',        'url' => ['/user/settings/account']],
                    ['label' => 'Social networks',  'icon' => 'share-alt',   'url' => ['/user/settings/networks']],

                    ['label' => 'Menu Yii2', 'options' => ['class' => 'header']],
                    ['label' => 'User Admin',            'icon' => 'users',       'url' => ['/user/admin/index']],
                    ['label' => 'Gii',              'icon' => 'file-code-o', 'url' => ['/gii']],
                    ['label' => 'Debug',            'icon' => 'bug', 'url' => ['/debug']],

                    ['label' => 'Logout',    'url' => ['user/security/logout'], 'visible' => !Yii::$app->user->isGuest],

                ],
            ]
        ) ?>

    </section>

</aside>
