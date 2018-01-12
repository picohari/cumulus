<?php
return [
    'adminEmail' => 'admin@example.com',

    'mqttServer' => 'cumulus.intewa.net',
    //'mqttServer' => '192.168.1.101',

    'opentsdbServer' => [
        'datasource' => [
            'OpenTSDB' => [
            	'enabled' => true,
            	'type' => "ajax",
	            'url' => [
	                "192.168.200.162:4242",
	            	//"192.168.1.101:4242",
	                //"monitor.intewa.net:4242"
	            ],
	            //'trim'	=> "301",
	            //'proxy'	=> false,
            ],
        ],
    ],


];
