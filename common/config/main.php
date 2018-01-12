<?php

/*
	http://www.yiiframework.com/wiki/848/installation-guide-yii-2-advanced-template-with-rbac-system/
*/

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',

    'bootstrap' => [
        'queue', // The component registers own console commands
    ],
    
	'modules' => [
/*
    	'admin' => [
        	'class' => 'mdm\admin\Module',				// enable RBAC module
        ]
*/
        'user' => [
            'class' => 'dektrium\user\Module',
            // you will configure your module inside this file
            // or if need different configuration for frontend and backend you may
            // configure in needed configs
            'mailer' => [
                'sender'                => 'no-reply@myhost.com', // or ['no-reply@myhost.com' => 'Sender name']
                'welcomeSubject'        => 'Welcome subject',
                'confirmationSubject'   => 'Confirmation subject',
                'reconfirmationSubject' => 'Email change subject',
                'recoverySubject'       => 'Recovery subject',

            ],
            //'enableConfirmation'    => true,
            'admins' => ['admin'],
        ],

    ],

	'components' => [

        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        
        'queue' => [
            'class' => \yii\queue\file\Queue::class,
            'path' => '@runtime/queue',
        ],

        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
        ],

/*
    	'authManager' => [
        	//'class' => 'yii\rbac\PhpManager', 		   // or use 'yii\rbac\DbManager'  - Basic authentification method
        	'class' => 'yii\rbac\DbManager', 			   // or use 'yii\rbac\PhpManager' - switch to RBAC system
    	],
*/

/*
    	'user' => [
    		'class' => 'mdm\admin\models\User',		     // removed to enable RBAC system
        	'identityClass' => 'mdm\admin\models\User',
        	'loginUrl' => ['admin/user/login'],
    	]
*/

        'user' => [
            //'class' => 'dektrium\user\Module',
            'identityClass' => 'dektrium\user\models\User',
            'loginUrl'      => ['/user/security/login'],
        ],

        'rabbitmq'  => [
            'class' => 'mikemadisonweb\rabbitmq\Configuration',
            'connections' => [
                'default' => [
                    'host' => '127.0.0.1',
                    'port' => '5672',
                    'user' => 'xcore',
                    'password' => 'Ath7Ait3',
                    'vhost' => '/',
                    'heartbeat' => 0,
                ],
            ],
            'producers' => [
                'import_data' => [
                    'connection' => 'default',
                    'exchange_options' => [
                        'name' => 'import_data',
                        'type' => 'direct',
                    ],
                ],
            ],
            'consumers' => [
                'import_data' => [
                    'connection' => 'default',
                    'exchange_options' => [
                        'name' => 'import_data',    // Name of exchange to declare
                        'type' => 'direct',         // Type of exchange
                    ],
                    'queue_options' => [
                        'name' => 'import_data',            // Queue name which will be binded to the exchange adove
                        'routing_keys' => ['import_data'],  // Your custom options
                        'durable' => true,
                        'auto_delete' => false,
                    ],
                    // Or just '\path\to\ImportDataConsumer' in PHP 5.4
                    'callback' => \path\to\ImportDataConsumer::class,
                ],
            ],
        ],

        'urlManagerBackend' => [
            'class' => 'yii\web\urlManager',
            'enablePrettyUrl' => false,
            'showScriptName' => false,
            //FIXME: Workaround to prepend correct URL for BACKEND
            'baseUrl' => 'http://localhost/~dk6yf/sapiroid/yii2/backend/web',
        ],

        'urlManagerFrontend' => [
            'class' => 'yii\web\urlManager',
            'enablePrettyUrl' => false,
            'showScriptName' => true,
            //FIXME: Workaround to prepend correct URL for FRONTEND
            'scriptUrl' => 'http://localhost/~dk6yf/sapiroid/yii2/frontend/web',
        ],
	],

/*
    'as beforeRequest' => [  //if guest user access site so, redirect to login page.
        'class' => 'yii\filters\AccessControl',
        'rules' => [
            [
                'actions' => ['login', 'error'],
                'allow' => true,
            ],
            [
                'allow' => true,
                'roles' => ['@'],
            ],
        ],
    ],
*/

    'controllerMap' => [
        'rabbitmq-consumer' => \mikemadisonweb\rabbitmq\controllers\ConsumerController::class,
        'rabbitmq-producer' => \mikemadisonweb\rabbitmq\controllers\ProducerController::class,
    ],

   'aliases' => [
        '@YiiNodeSocket' => '@vendor/oncesk/yii-node-socket/lib/php',
        '@nodeWeb' => '@vendor/oncesk/yii-node-socket/lib/js'
    ],
];
