<?php

// MySQL configurations
return [
    'mysql-db.options' => [
        'driver' => 'pdo_mysql',
        'dbhost' => '127.0.0.1',
        'dbname' => 'ssense-test',
        'user' => 'root',
        'password' => '',
    ],
    'redis-cache.options' => [
        'host' => '127.0.0.1',
        'port' => 6379
    ]
];
