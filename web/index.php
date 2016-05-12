<?php
/*
* Main application file for the Website application
*/

// include the application with all the configs and services
$app = require_once __DIR__ . '/../src/website/app.php';

// Run the app !
$app->run();
