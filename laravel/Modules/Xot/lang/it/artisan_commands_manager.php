<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Comandi Artisan',
        'plural' => 'Comandi Artisan',
        'group' => [
            'name' => 'Sistema',
            'description' => 'Gestione dei comandi Artisan',
        ],
        'sort' => 28,
        'label' => 'Comandi Artisan',
        'icon' => 'heroicon-o-command-line',
    ],
    'pages' => [
        'artisan-commands' => [
            'title' => 'Gestione Comandi Artisan',
            'description' => 'Esegui e gestisci i comandi Artisan',
            'commands' => [
                'migrate' => [
                    'label' => 'Migrazione Database',
                    'description' => 'Esegue le migrazioni del database',
                ],
                'optimize' => [
                    'label' => 'Ottimizzazione',
                    'description' => 'Ottimizza le prestazioni dell\'applicazione',
                ],
                'cache' => [
                    'label' => 'Gestione Cache',
                    'description' => 'Comandi per la gestione della cache',
                ],
            ],
            'notifications' => [
                'success' => 'Comando eseguito con successo',
                'error' => 'Errore nell\'esecuzione del comando',
            ],
        ],
    ],
    'actions' => [
        'queue_restart' => [
            'label' => 'queue_restart',
            'icon' => 'queue_restart',
            'tooltip' => 'queue_restart',
        ],
        'event_cache' => [
            'label' => 'event_cache',
            'icon' => 'event_cache',
            'tooltip' => 'event_cache',
        ],
        'route_cache' => [
            'label' => 'route_cache',
            'icon' => 'route_cache',
            'tooltip' => 'route_cache',
        ],
        'config_cache' => [
            'label' => 'config_cache',
            'icon' => 'config_cache',
            'tooltip' => 'config_cache',
        ],
        'view_cache' => [
            'label' => 'view_cache',
            'icon' => 'view_cache',
            'tooltip' => 'view_cache',
        ],
        'filament_optimize' => [
            'label' => 'filament_optimize',
            'icon' => 'filament_optimize',
            'tooltip' => 'filament_optimize',
        ],
        'filament_upgrade' => [
            'label' => 'filament_upgrade',
            'icon' => 'filament_upgrade',
            'tooltip' => 'filament_upgrade',
        ],
        'migrate' => [
            'label' => 'migrate',
            'icon' => 'migrate',
            'tooltip' => 'migrate',
        ],
        'save' => [
            'label' => 'save',
            'icon' => 'save',
            'tooltip' => 'save',
        ],
        'profile' => [
            'label' => 'profile',
            'icon' => 'profile',
            'tooltip' => 'profile',
        ],
        'logout' => [
            'label' => 'logout',
            'icon' => 'logout',
            'tooltip' => 'logout',
        ],
        'composer_dump_autoload' => [
            'label' => 'composer_dump_autoload',
            'icon' => 'composer_dump_autoload',
            'tooltip' => 'composer_dump_autoload',
        ],
        'notify_migrate_themes_to_mail_templates' => [
            'label' => 'notify_migrate_themes_to_mail_templates',
            'icon' => 'notify_migrate_themes_to_mail_templates',
            'tooltip' => 'notify_migrate_themes_to_mail_templates',
        ],
        'submit' => [
            'label' => 'submit',
            'icon' => 'submit',
            'tooltip' => 'submit',
        ],
        'cancel' => [
            'label' => 'cancel',
            'icon' => 'cancel',
            'tooltip' => 'cancel',
        ],
    ],
    'title' => 'artisan commands manager',
    'label' => 'Artisan Commands Manager',
    'plural_label' => 'Artisan Commands Manager (Plurale)',
    'fields' => [
        'id' => [
            'label' => 'Identificativo',
            'tooltip' => 'Identificativo univoco del record',
            'helper_text' => '',
            'description' => '',
        ],
        'created_at' => [
            'label' => 'Data Creazione',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'updated_at' => [
            'label' => 'Ultima Modifica',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
    ],
];
