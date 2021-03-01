<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'modules' => [

    ],
    'components' => [

        'cache' => [
            'class' => 'yii\redis\Cache',
            'keyPrefix' => 'smileup_'
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager', // or use 'yii\rbac\DbManager'
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => SQL_HOST,
            'username' => SQL_USER_NAME,
            'password' => SQL_PASSWORD,
            'charset' => 'utf8',
            'enableSchemaCache' => true,
            // Duration of schema cache.
            'schemaCacheDuration' => 3600,
            // Name of the cache component used to store schema information
            'schemaCache' => 'cache',
            'queryCache' => 'cache',
            'enableQueryCache' => true,
        ],
        'queue' => [
            'class' => \yii\queue\redis\Queue::class,
            'redis' => 'redis', // Redis connection component or its config
            'channel' => 'queue', // Queue channel key
        ],
        'redis' => [
            'class' => 'yii\redis\Connection',
            'hostname' => REDIS_HOST,
            'port' => REDIS_PORT,
            'database' => 0,
            'retries' => 1,
        ],

    ],
];
