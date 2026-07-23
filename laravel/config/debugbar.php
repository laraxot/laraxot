<?php

return [
    'enabled' => env('DEBUGBAR_ENABLED', false) && env('APP_DEBUG', false),
    'enabled' => env('DEBUGBAR_ENABLED', env('APP_DEBUG', false)),
    'enabled' => env('DEBUGBAR_ENABLED', false) && env('APP_DEBUG', false),
    'except' => [
        'telescope*',
        'horizon*',
    ],
    'storage' => [
        'enabled' => true,
        'driver' => 'file', // redis, file, custom
        'path' => storage_path('debugbar'), // For file driver
        'connection' => null, // Leave null for default connection (Redis/Log)
    ],
    'include_vendors' => true,
    'capture_ajax' => true,
    'add_ajax_timing' => false,
    'error_handler' => false,
    'clockwork' => false,
    'collectors' => [
        'phpinfo' => true,  // Php version
        'messages' => true,  // Messages
        'time' => true,  // Time Datalogger
        'memory' => true,  // Memory usage
        'exceptions' => true,  // Exception displayer
        'log' => true,  // Logs from Monolog (merged in messages if enabled)
        'db' => true,  // Show database (PDO) queries and bindings
        'views' => true,  // Views with their data
        'route' => true,  // Current route information
        'auth' => true, // Display Laravel authentication status
        'gate' => true, // Display Laravel Gate checks
        'session' => true, // Display session data
        'symfony_request' => true,  // Only one can be enabled..
        'mail' => true,  // Catch mail messages
        'laravel' => false, // Laravel version and environment
        'events' => false, // All events fired
        'default_request' => false, // Regular or special Symfony request logger
        'logs' => false, // Add the latest log messages
        'files' => false, // Show the included files
        'config' => false, // Display config
        'cache' => false, // Display cache events
    ],
    'options' => [
        'auth' => [
            'show_name' => true, // Also show the users name/email in the debugbar
        ],
        'db' => [
            'with_queries' => true, // Show SQL queries as statements
            'backtrace' => true, // Use a backtrace to find the origin of the query in your files.
            'timeline' => false, // Add the queries to the timeline
            'explain' => [ // Show EXPLAIN on select queries
                'enabled' => false,
                'types' => ['SELECT'],
            ],
            'hints' => true, // Show hints for common mistakes
        ],
        'mail' => [
            'full_log' => false,
        ],
        'views' => [
            'data' => false, // Note: Can slow down the application, because the data can be quite large..
            'data' => false, //Note: Can slow down the application, because the data can be quite large..
            'data' => false, // Note: Can slow down the application, because the data can be quite large..
        ],
        'route' => [
            'label' => true, // show complete route on bar
        ],
        'logs' => [
            'file' => null,
        ],
        'cache' => [
            'values' => true, // collect cache values
        ],
    ],
    'inject' => true, // Inject the debugbar into the response
    'route_prefix' => '_debugbar', // The route prefix that will be used to register the debugbar routes.
    'route_domain' => null, // The route domain that will be used to register the debugbar routes.
    'theme' => 'auto', // DEPRECATED: Theme is now always auto
    'editor' => 'phpstorm', // DEPRECATED: Editor is now always phpstorm
];
