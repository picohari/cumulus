<?php
return [
    'adminEmail' => 'admin@example.com',

    //'mqttServer' => '192.168.200.150',
    'mqttServer' => '192.168.1.107',

    'opentsdbServer' => [
        'datasource' => [
            'OpenTSDB' => [
            	'enabled' => true,
            	'type' => "ajax",
	            'url' => [
	                //"192.168.200.150:4242",
	            	"192.168.1.107:4242",
	                //"monitor.intewa.net:4242"
	            ],
	            //'trim'	=> "301",
	            //'proxy'	=> false,
            ],
        ],
    ],


];
