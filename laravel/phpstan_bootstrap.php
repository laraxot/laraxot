<?php

declare(strict_types=1);
use Illuminate\Contracts\Http\Kernel;
use Modules\Xot\Datas\XotData;
use Nwidart\Modules\Facades\Module;

if (! defined('__DIR__')) {
    define('__DIR__', getcwd());
}

/*
|--------------------------------------------------------------------------
| Bootstrap The Application
|--------------------------------------------------------------------------
|
| This file is responsible for bootstrapping the Laravel application
| and setting up the necessary environment for PHPStan to analyze
| the codebase effectively. It includes the autoloader, boots the
| application, and prepares the container.
|
*/

// Define the path to the Composer autoloader
$autoloaderPath = __DIR__.'/vendor/autoload.php';

// Ensure the autoloader exists before including it
if (! file_exists($autoloaderPath)) {
    echo "Composer autoloader not found. Run 'composer install' to generate it.".PHP_EOL;
    exit(1);
}

// Include the Composer autoloader
require_once $autoloaderPath;

// Find and include module-specific autoloaders
$modulesPath = __DIR__.'/Modules';
if (is_dir($modulesPath)) {
    $modules = scandir($modulesPath);
    foreach ($modules as $module) {
        if ($module === '.' || $module === '..') {
            continue;
        }
        $moduleAutoloader = $modulesPath.'/'.$module.'/vendor/autoload.php';
        if (file_exists($moduleAutoloader)) {
            require_once $moduleAutoloader;
        }
    }
}

// Check if the application has already been bootstrapped
if (! isset($app)) {
    // Define the path to the application bootstrap file
    $appPath = __DIR__.'/bootstrap/app.php';

    // Ensure the application bootstrap file exists
    if (! file_exists($appPath)) {
        echo 'Laravel application bootstrap file not found.'.PHP_EOL;
        exit(1);
    }

    // Bootstrap the Laravel application
    $app = require $appPath;

    // Create an instance of the HTTP kernel
    $kernel = $app->make(Kernel::class);

    // Bootstrap the application for console commands
    $consoleKernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $consoleKernel->bootstrap();
}

// Manually register the service providers for modules
if (class_exists(Module::class)) {
    foreach (Module::getOrdered() as $module) {
        // Assuming the service provider follows the {ModuleName}ServiceProvider convention
        $serviceProvider = "Modules\\{$module->getName()}\\Providers\\{$module->getName()}ServiceProvider";
        if (class_exists($serviceProvider)) {
            $app->register($serviceProvider);
        }
    }
}

/*
|--------------------------------------------------------------------------
| PHPStan analysis context: force TechPlanner main_module
|--------------------------------------------------------------------------
|
| Larastan resolves Updater::deleter()/creator()/updater() BelongsTo targets via
| XotData::getProfileClass(). Without a stable main_module, analysis can point
| at a missing Fixcity Profile class from another tenant config snapshot.
|
*/
if (class_exists(XotData::class)) {
    config(['xra.main_module' => 'TechPlanner']);

    $xotDataReflection = new ReflectionClass(XotData::class);
    $instanceProperty = $xotDataReflection->getProperty('instance');
    $instanceProperty->setAccessible(true);
    $instanceProperty->setValue(null);

    XotData::make();
}
