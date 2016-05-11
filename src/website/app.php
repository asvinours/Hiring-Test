<?php
use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\HttpFoundation\Response;
use Igorw\Silex\ConfigServiceProvider;
use MJanssen\Provider\RoutingServiceProvider;

// Display all errors: should be on to prove that you are writing good code!
error_reporting(E_ALL);

// Class autoloader from composer
require_once __DIR__ . '/../../vendor/be/autoload.php';

// Application configurations
require_once __DIR__ . '/../../config/application.php';

// Create the application
$app = new Application();

// Register the routing into the application
$app->register(
    new ConfigServiceProvider(__DIR__ . '/../../config/routes.php')
);
$app->register(new RoutingServiceProvider('routing.routes'));

// Initiate MySQL databse connexion
$app->register(
    new ConfigServiceProvider(__DIR__ . '/../../config/databases.php')
);
$app->register(new Silex\Provider\DoctrineServiceProvider(), $app['mysql-db.options']);

// Initiate Twig within the application for tempalte rendering
$app->register(new TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));

// Initiate the cache
$app->register(new Moust\Silex\Provider\CacheServiceProvider(), array(
    'cache.options' => array(
        'filesystem' => array(
            'driver' => 'redis',
            'redis' => function() use ($app) {
                $redisOptions = $app['redis-cache.options'];
                $redis = new \Redis();
                $redis->connect($redisOptions['host'], $redisOptions['port']);
                return $redis;
            }
        )
    )
));

// Setup basic error handler for debugging
$app->error(function (\Exception $e, $code) {
    return new Response($e->getMessage());
});

// Return the application
return $app;
