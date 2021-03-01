<?php

return [
    ROUTE_INDEX => 'site/index',
    ROUTE_ERROR => 'site/error',
    "POST,OPTIONS " . ROUTE_LOGIN => 'site/login',
    "POST " . ROUTE_REGISTER => 'site/register',
    "GET " . ROUTE_LOGOUT => 'site/logout',
    [
        'class' => \yii\rest\UrlRule::class,
        'controller' => 'user',
    ],
    '<controller>/<action>' => '<controller>/<action>',
];
