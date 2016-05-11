<?php
namespace SSENSE\HiringTest\Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class WeatherController 
{
    /**
     * Display the homepage
     * 
     * @param Application $app
     * @param Request $request 
     */
    public function displayAction(Application $app, Request $request)
    {
        // Render the page
        return $app['twig']->render('homepage/display.html', []);
    }
}
