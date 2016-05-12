<?php
namespace SSENSE\HiringTest\Controllers;

use Silex\Application;
use SSENSE\HiringTest\Models\Weather;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class WeatherController
 * @package SSENSE\HiringTest\Controllers
 */
class WeatherController
{
    /**
     * Display the weather forecast
     *
     * @param Application $app
     * @param Request $request
     */
    public function forecastAction(Application $app, Request $request)
    {
        // We need to call the API
        // Store the json result in Redis
        // Return the content
        // Render it

        $weatherRepository = new Weather($app);
        try {
            $forecast = $weatherRepository->retrieveForecast(45.5088400, -73.5878100);
        } catch(\Exception $ex) {
            $forecast = false;
            error_log("something wrong happened with the forecast: " . $ex->getMessage());
        }

        // Render the page
        return $app['twig']->render(
            'weather/forecast.html',
            [
                'forecast' => $forecast,
                'styles' => ['/assets/css/weather.css'],
            ]
        );
    }
}
