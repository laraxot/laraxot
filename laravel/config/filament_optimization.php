<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Filament Memory Optimization
    |--------------------------------------------------------------------------
    |
    | Configurazioni per ottimizzare l'uso della memoria in Filament v4
    |
    */

    'memory_optimization' => [
        // Disabilita lazy loading per componenti pesanti
        'disable_lazy_loading' => false,

        // Limita il numero di record caricati per default
        'default_records_per_page' => 25,

        // Abilita caching per le query pesanti
        'enable_query_caching' => true,

        // Limita la profondità delle relazioni caricate
        'max_relation_depth' => 2,

        
        // Limita il numero di record caricati per default
        'default_records_per_page' => 25,
        
        // Abilita caching per le query pesanti
        'enable_query_caching' => true,
        
        // Limita la profondità delle relazioni caricate
        'max_relation_depth' => 2,
        
        // Disabilita debug mode in produzione
        'disable_debug_mode' => env('APP_ENV') === 'production',
    ],

    'performance' => [
        // Abilita compressione delle risposte
        'enable_response_compression' => true,

        // Limita il numero di widget per pagina
        'max_widgets_per_page' => 10,

        // Abilita caching per le traduzioni
        'cache_translations' => true,

        
        // Limita il numero di widget per pagina
        'max_widgets_per_page' => 10,
        
        // Abilita caching per le traduzioni
        'cache_translations' => true,
        
        // Limita il numero di azioni bulk
        'max_bulk_actions' => 5,
    ],
];
