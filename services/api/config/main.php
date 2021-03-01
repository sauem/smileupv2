<?php
require_once(__DIR__ . '/const.php');

$rules = require(__DIR__ . '/rules.php');

$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/params.php'
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'api\controllers',
    'homeUrl' => '/api',
    'components' => [
        'request' => [
            'enableCookieValidation' => true,
            'cookieValidationKey' => 'dlfajafakxlcvjlk',
            "baseUrl" => '/api',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'user' => [
            'identityClass' => 'api\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
            'loginUrl' => false
        ],
        'session' => [
            'name' => 'api-backend',
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
        'jwt' => [
            'class' => \sizeg\jwt\Jwt::class,
            'key'   => 'secret',
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => true,
            'rules' => $rules,
        ],
    ],

    'params' => $params,
];
