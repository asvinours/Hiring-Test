<?php
use Igorw\Silex\ConfigServiceProvider;
use MJanssen\Provider\RoutingServiceProvider;
use Silex\Application;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\HttpFoundation\Response;

// Display all errors: should be on to prove that you are writing good code!
error_reporting(E_ALL);

// Class autoloader from composer
require_once __DIR__ . '/../../vendor/be/autoload.php';

// Create the application
$app = new Application();

// Application configurations
$app->register(
    new ConfigServiceProvider(__DIR__ . '/../../config/application.php')
);

// Register the routing into the application
$app->register(
    new ConfigServiceProvider(__DIR__ . '/../../config/routes.php')
);
$app->register(new RoutingServiceProvider('routing.routes'));
// Register the URL generator service
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

// Initiate MySQL databse connexion
$app->register(
    new ConfigServiceProvider(__DIR__ . '/../../config/databases.php')
);
$app->register(new Silex\Provider\DoctrineServiceProvider(), $app['db.options']);

// Initiate Twig within the application for tempalte rendering
$app->register(new TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/views',
));

// Initiate the cache
$app->register(new Moust\Silex\Provider\CacheServiceProvider(), array(
    'caches.options' => array(
        'redis' => array(
            'driver' => 'redis',
            'redis' => function () use ($app) {
                $redisOptions = $app['cache.options'];
                $redis = new \Redis();
                $redis->connect($redisOptions['host'], $redisOptions['port']);
                return $redis;
            }
        ),
        'filesystem' => array(
            'driver' => 'file',
            'cache_dir' => $app['cache.options']['folder']
        )
    )
));

// Create a global variable with the current route name
// Will allow us to add some style for the navigation menu
$app->before(function ($request) use ($app) {
    $app['twig']->addGlobal('current_route', $request->get("_route"));
});

// Setup basic error handler for debugging
$app->error(function (\Exception $e, $code) {
    return new Response($e->getMessage());
});

// Return the application
return $app;
