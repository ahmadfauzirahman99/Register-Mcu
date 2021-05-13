<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$dbsimrs = require __DIR__ . '/dbsimrs.php';
$dbmirai = require __DIR__ . '/dbmirai.php';
$dbpost = require __DIR__ . '/dbpost.php';

$config = [
    'id' => 'reg-mcu',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'timeZone'=>'Asia/Jakarta',
    'homeUrl'=>'https://registrasi.rsudarifinachmad.riau.go.id/',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
	'defaultRoute'=>'auth/index',
    //'catchAll'=>['site/offline'],
    'components' => [
        'request' => [
            'cookieValidationKey' => sha1('&^TR%$DUYBIUH)OJOKJNOU*&T^%E$EDYJGTF&^R'),
        ],
        'cache' => [
            'class' => 'yii\caching\DbCache',
            'db' => 'db',
            'cacheTable' => 'cache',
        ],
        'session' => [
            'class' => 'yii\web\DbSession',
            'name'=>md5('r$8)^!^%hyTYF#&%^GY#%$E&^TG*&YG*&T%#$D'),
            'db' => 'db',
            'sessionTable' => 'session',
			'timeout'=>3600*24
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
			'loginUrl' => ['site/index'],
			//'loginUrl' => ['auth/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
		'dbsimrs' => $dbsimrs,
        //'dbmirai' => $dbmirai,
        'dbpost'=>$dbpost,
	'assetManager' => [
			'class' => 'yii\web\AssetManager',
			'bundles' => [
		                'yii\web\JqueryAsset' => [
		                    'js' => [
		                        'jquery.min.js'
		                    ]
		                ],
		                'yii\bootstrap\BootstrapAsset' => [
		                    'css' => [
		                        'css/bootstrap.min.css',
		                    ]
		                ],
		                'yii\bootstrap\BootstrapPluginAsset' => [
		                    'js' => [
		                        'js/bootstrap.min.js',
		                    ]
		                ]
			],
		],	
        
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => true,
            'rules' => [
            ],
        ],
        
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    // $config['bootstrap'][] = 'debug';
    // $config['modules']['debug'] = [
    //     'class' => 'yii\debug\Module',
    //     // uncomment the following to add your IP if you are not connecting from localhost.
    //     //'allowedIPs' => ['127.0.0.1', '::1'],
    // ];

    //$config['bootstrap'][] = 'gii';
    //$config['modules']['gii'] = [
        //'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    //];
}

return $config;
