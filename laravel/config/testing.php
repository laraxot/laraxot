<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Testing Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration specific for testing environment.
    | This file defines the optimal setup for running tests in Laraxot.
    |
    */

    'database' => [
        'default' => 'sqlite',
        'connections' => [
            'sqlite' => [
                'driver' => 'sqlite',
                'database' => ':memory:', // In-memory SQLite for maximum speed
                'prefix' => '',
                'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
            ],
            'sqlite_file' => [
                'driver' => 'sqlite',
                'database' => database_path('test.sqlite'), // File-based for debugging
                'prefix' => '',
                'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
            ],
        ],
    ],

    'cache' => [
        'default' => 'array',
    ],

    'session' => [
        'driver' => 'array',
    ],

    'queue' => [
        'default' => 'sync',
    ],

    'mail' => [
        'default' => 'log',
    ],

    'broadcasting' => [
        'default' => 'log',
    ],

    /*
    |--------------------------------------------------------------------------
    | Test Performance Optimizations
    |--------------------------------------------------------------------------
    */

    

    'performance' => [
        'disable_telescope' => true,
        'disable_debugbar' => true,
        'disable_pulse' => true,
        'use_memory_cache' => true,
        'parallel_testing' => false, // Set to true for parallel test execution
    ],

    /*
    |--------------------------------------------------------------------------
    | Test Data Management
    |--------------------------------------------------------------------------
    */

    

    'data' => [
        'auto_migrate' => true,
        'auto_seed' => false,
        'use_factories' => true,
        'cleanup_after_test' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | REGOLA CRITICA: MAI RefreshDatabase
    |--------------------------------------------------------------------------
    |
    | MOTIVAZIONI:
    | 1. Performance: RefreshDatabase è 10-100x più lento
    | 2. Isolamento: .env.testing garantisce ambiente dedicato
    | 3. Velocità: SQLite in memoria è istantaneo
    | 4. CI/CD: Pipeline più veloci
    | 5. Controllo: Configurazione test centralizzata
    |
    | SEMPRE usare:
    | - .env.testing con DB_CONNECTION=sqlite DB_DATABASE=:memory:
    | - Configurazione testing dedicata
    | - Factory per dati di test
    | - Transazioni per isolamento se necessario
    |
    */
];
