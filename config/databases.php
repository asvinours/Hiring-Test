<?php

// MySQL configurations
return [
    'db.options' => [
        'driver' => 'pdo_mysql',
        'dbhost' => '127.0.0.1',
        'dbname' => 'ssense-test',
        'user' => 'root',
        'password' => '',
        'charset' => 'utf8'
    ],
    'cache.options' => [
        'host' => '127.0.0.1',
        'port' => 6379,
        'folder' => __DIR__ . '/../tmp',
    ]
];
