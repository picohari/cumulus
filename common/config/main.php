<?php

/*
	http://www.yiiframework.com/wiki/848/installation-guide-yii-2-advanced-template-with-rbac-system/
*/

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],

    'bootstrap' => [
        'queue', // The component registers own console commands
    ],
    
	'modules' => [
/*
    	'admin' => [
        	'class' => 'mdm\admin\Module',				// enable RBAC module
        ]
*/
    ],

	'components' => [
        'queue' => [
            'class' => \yii\queue\file\Queue::class,
            'path' => '@runtime/queue',
        ],

/*
    	'authManager' => [
        	//'class' => 'yii\rbac\PhpManager', 		// or use 'yii\rbac\DbManager'  - Basic authentification method
        	'class' => 'yii\rbac\DbManager', 			// or use 'yii\rbac\PhpManager' - switch to RBAC system
    	],
*/

/*
    	'user' => [
    		'class' => 'mdm\admin\models\User',		// removed to enable RBAC system
        	'identityClass' => 'mdm\admin\models\User',
        	'loginUrl' => ['admin/user/login'],
    	]
*/

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
                        'name' => 'import_data', // Name of exchange to declare
                        'type' => 'direct', // Type of exchange
                    ],
                    'queue_options' => [
                        'name' => 'import_data', // Queue name which will be binded to the exchange adove
                        'routing_keys' => ['import_data'], // Your custom options
                        'durable' => true,
                        'auto_delete' => false,
                    ],
                    // Or just '\path\to\ImportDataConsumer' in PHP 5.4
                    'callback' => \path\to\ImportDataConsumer::class,
                ],
            ],
        ],



	],

    'controllerMap' => [
        'rabbitmq-consumer' => \mikemadisonweb\rabbitmq\controllers\ConsumerController::class,
        'rabbitmq-producer' => \mikemadisonweb\rabbitmq\controllers\ProducerController::class,
    ],

   'aliases' => [
        '@YiiNodeSocket' => '@vendor/oncesk/yii-node-socket/lib/php',
        '@nodeWeb' => '@vendor/oncesk/yii-node-socket/lib/js'
    ],
];
