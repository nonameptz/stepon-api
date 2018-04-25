<?php

use Application\Util\DoctrineExtension\IfElse;

return [
    'doctrine' => [
        'connection'               => [
            'orm_default' => [
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params'      => [
                    'host'          => 'localhost',
                    'port'          => '3306',
                    'user'          => '',
                    'password'      => '',
                    'dbname'        => '',
                    'charset'       => 'utf8',
                    'driverOptions' => [
                        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'",
                    ],
                ],
            ],
        ],
        'configuration'            => [
            'orm_default' => [
                'metadata_cache'   => 'memcached',
                'query_cache'      => 'memcached',
                'generate_proxies' => false,
                'proxy_dir'        => __DIR__ . '/../../data/cache/DoctrineModule',
                'string_functions' => [
                    'ifelse' => IfElse::class,
                ],
            ],
        ],
        'migrations_configuration' => [
            'orm_default' => [
                'name'      => 'Stepon Migrations',
                'table'     => 'doctrine_migrations',
                'directory' => 'data/migrations',
            ],
        ],
        'cache'                    => [
            'memcached' => [
                'instance' => 'memcached',
            ],
        ],
    ],
];
