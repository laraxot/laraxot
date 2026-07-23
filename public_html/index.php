<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));
define('LARAVEL_DIR', __DIR__.'/../laravel');


// Determine if the application is in maintenance mode...
if (file_exists($maintenance = LARAVEL_DIR.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require LARAVEL_DIR.'/vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once LARAVEL_DIR.'/bootstrap/app.php')
    ->handleRequest(Request::capture());
