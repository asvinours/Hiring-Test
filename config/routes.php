<?php

return [
    'routing.routes' => [
        
        // Homepage
        'homepage' => [
            'pattern' => '/',
            'controller' => 'SSENSE\HiringTest\Controllers\HomepageController::displayAction',
            'method' => ['get']
        ],
        'weather' => [
            'pattern' => '/weather_forecast',
            'controller' => 'SSENSE\HiringTest\Controllers\WeatherController::forecastAction',
            'method' => ['get']
        ],
        'products' => [
            'pattern' => '/products',
            'controller' => 'SSENSE\HiringTest\Controllers\ProductController::listAction',
            'method' => ['get']
        ],
        // other pages ...
    ]
];
