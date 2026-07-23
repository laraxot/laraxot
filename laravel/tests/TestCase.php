<?php

declare(strict_types=1);

namespace Tests;

use Dotenv\Dotenv;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Mockery;

// Manually require Composer's autoloader to ensure it's loaded
require_once __DIR__.'/../vendor/autoload.php';

abstract class TestCase extends BaseTestCase
{
    /**
     * Creates the application.
     */
    public function createApplication(): Application
    {
        // Dynamically override XotBaseMigration::getModelClass() for tests
        // This is a workaround for the XotBaseMigration constructor trying to resolve
        // a model from the migration filename, which fails in the test environment.
        $xotBaseMigrationClass = \Modules\Xot\Database\Migrations\XotBaseMigration::class;
        $mockModelClass = \Illuminate\Database\Eloquent\Model::class;

        // Explicitly set the base path before requiring bootstrap/app.php
        $basePath = realpath(__DIR__.'/../'); // Adjust path for tests/ directory
        $_ENV['APP_BASE_PATH'] = $basePath; // Set in $_ENV for early access

        // Manually load the environment file for testing to ensure it's available early
        $dotenv = Dotenv::createImmutable($basePath, '.env.testing');
        $dotenv->load();

        $app = require $basePath.'/bootstrap/app.php';

        // Bind essential paths if they are not correctly resolved
        $app->instance('path.base', $basePath);
        $app->bind('path.public', function () use ($basePath) {
            return $basePath.'/public_html'; // Assuming public_html is the public directory
        });
        $app->bind('path.storage', function () use ($basePath) {
            return $basePath.'/storage';
        });

        // Re-bootstrap kernel to ensure all service providers and aliases are registered
        // This is necessary because some aliases/providers might not be registered early enough
        $app->make(Kernel::class)->bootstrap();
        $app->boot(); // Ensure all service providers are booted

        return $app;
    }
}