<?php

use App\Command;
use Cycle\Schema\Generator;

return [
    'debugger.enabled' => true,

    'mailer' => [
        'host' => getenv('APP_MAIL_HOST'),
        'port' => getenv('APP_MAIL_PORT'),
        'encryption' => getenv('APP_MAIL_ENCRYPTION'),
        'username' => getenv('APP_MAIL_USERNAME'),
        'password' => getenv('APP_MAIL_PASSWORD'),
    ],

    'supportEmail' => getenv('APP_MAIL_FROM'),

    'aliases' => [
        '@root' => dirname(__DIR__),
        '@views' => '@root/resources/views',
        '@resources' => '@root/resources',
        '@src' => '@root/src',
    ],

    'session' => [
        'options' => ['cookie_secure' => 0],
    ],

    'console' => [
        'commands' => [
            'user/create' => Command\User\CreateCommand::class,
        ],
    ],

    // cycle DBAL config
    'cycle.dbal' => [
        'default' => 'default',
        'aliases' => [],
        'databases' => [
            'default' => [
                'connection' => getenv('APP_DB_ENGINE')
            ],
        ],
        'connections' => [
            'sqlite' => [
                'driver' => \Spiral\Database\Driver\SQLite\SQLiteDriver::class,
                'connection' => 'sqlite:@runtime/database.db',
                'username' => '',
                'password' => '',
            ],
            'mysql' => [
                'driver' => \Spiral\Database\Driver\MySQL\MySQLDriver::class,
                'options' => [
                    'connection' => 'mysql:host=' . getenv('APP_DB_HOST') . ';dbname=' . getenv('APP_DB_DATABASE') . ';port=' . getenv('APP_DB_PORT') . '',
                    'username' => getenv('APP_DB_USERNAME'),
                    'password' => getenv('APP_DB_PASSWORD'),
                ]
            ],
        ],
    ],
    // cycle common config
    'cycle.common' => [
        'entityPaths' => [
            '@src/Entity',
        ],
        'cacheEnabled' => true,
        'cacheKey' => 'Cycle-ORM-Schema',
        'generators' => [
            // sync table changes to database
            Generator\SyncTables::class,
        ],
        //'promiseFactory' => \Cycle\ORM\Promise\ProxyFactory::class,
        //'queryLogger' => \Yiisoft\Yii\Cycle\Logger\StdoutQueryLogger::class,
    ],
    // cycle migration config
    'cycle.migrations' => [
        'directory' => '@root/database/migrations',
        'namespace' => 'App\\Database\\Migration',
        'table' => 'migration',
        'safe' => false,
    ],
];
