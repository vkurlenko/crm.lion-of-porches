<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.

            'transport' => [
                'class' => 'Swift_SmtpTransport',
                /*'host' => 'smtp.yandex.ru',
                'username' => 'lofporches@yandex.ru',
                'password' => 'lofporches1',
                'port' => '465',
                'encryption' => 'ssl',*/
                'host' => 'mail.nic.ru',
                'username' => 'loyalty@lion-of-porches.ru',
                'password' => '09dPmfP8BEVRk5JKytEG',//Chq3uUrb2cgbCBm5KR02',
                'port' => '465',
                'encryption' => 'ssl',
            ],
            'useFileTransport' => false,
            'viewPath' => '@app/mail',
            'messageConfig' => [
                'charset' => 'UTF-8',
                'from' => ['loyalty@lion-of-porches.ru' => 'Lion of Porches'],
            ],
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
    ],
    'params' => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
