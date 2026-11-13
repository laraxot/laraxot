<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Modulo',
        'plural' => 'Moduli',
        'group' => [
            'name' => 'Admin',
        ],
<<<<<<< HEAD
        'label' => 'Moduli',
        'icon' => 'heroicon-o-cube',
=======
        'label' => 'module.navigation',
        'icon' => 'module.navigation',
>>>>>>> 77b1e2748 (first)
        'sort' => 65,
    ],
    'fields' => [
        'name' => [
            'label' => 'Nome',
            'placeholder' => 'Nome',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'description' => [
            'label' => 'Descrizione',
            'placeholder' => 'Descrizione',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'is_visible' => [
            'label' => 'Visibile',
            'help' => 'Se selezionato, la pagina sarà visibile nella navigazione',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'is_active' => [
            'label' => 'Attivo',
            'help' => 'Se selezionato, la pagina sarà attiva',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'is_home' => [
            'label' => 'Home',
            'help' => 'Se selezionato, la pagina sarà la home',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'status' => [
            'label' => 'Stato',
            'placeholder' => 'Stato',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'priority' => [
            'label' => 'Priorità',
            'placeholder' => 'Priorità',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'colors' => [
            'label' => 'Colori',
            'placeholder' => 'Colori',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'key' => [
            'label' => 'color key',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'color' => [
<<<<<<< HEAD
            'label' => 'Colore',
=======
            'label' => 'color',
>>>>>>> 77b1e2748 (first)
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'value' => [
<<<<<<< HEAD
            'label' => 'Valore',
=======
            'label' => 'value',
>>>>>>> 77b1e2748 (first)
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'hex' => [
<<<<<<< HEAD
            'label' => 'Hex',
=======
            'label' => 'hex',
>>>>>>> 77b1e2748 (first)
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'icon' => [
            'label' => 'Icona',
            'placeholder' => 'Icona',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'timezone' => [
            'label' => 'Fuso orario',
            'placeholder' => 'Fuso orario',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'path' => [
<<<<<<< HEAD
            'label' => 'Percorso',
        ],
        'enabled' => [
            'label' => 'Attivo',
=======
            'label' => 'path',
        ],
        'enabled' => [
            'label' => 'enabled',
>>>>>>> 77b1e2748 (first)
        ],
    ],
    'pages' => [
        'health_check_results' => [
            'buttons' => [
                'refresh' => 'Refresh',
            ],
            'heading' => 'Application Health',
            'navigation' => [
                'group' => 'Settings',
                'label' => 'Application Health',
            ],
            'notifications' => [
                'check_results' => 'Check results from',
            ],
        ],
    ],
    'label' => 'Module',
    'plural_label' => 'Module (Plurale)',
    'actions' => [
        'create' => [
            'label' => 'Crea Module',
            'icon' => 'create',
<<<<<<< HEAD
            'tooltip' => '',
=======
            'tooltip' => 'create',
>>>>>>> 77b1e2748 (first)
        ],
        'edit' => [
            'label' => 'Modifica Module',
            'icon' => 'edit',
<<<<<<< HEAD
            'tooltip' => '',
=======
            'tooltip' => 'edit',
>>>>>>> 77b1e2748 (first)
        ],
        'delete' => [
            'label' => 'Elimina Module',
            'icon' => 'delete',
<<<<<<< HEAD
            'tooltip' => '',
        ],
        'createAnother' => [
            'label' => 'Salva e crea un altro',
            'icon' => 'createAnother',
            'tooltip' => '',
        ],
        'save' => [
            'label' => 'Salva',
            'icon' => 'save',
            'tooltip' => '',
        ],
        'view' => [
            'label' => 'Vista',
            'icon' => 'view',
            'tooltip' => '',
        ],
        'logout' => [
            'tooltip' => '',
            'icon' => 'logout',
=======
            'tooltip' => 'delete',
        ],
        'createAnother' => [
            'label' => 'createAnother',
            'icon' => 'createAnother',
            'tooltip' => 'createAnother',
        ],
        'save' => [
            'label' => 'save',
            'icon' => 'save',
            'tooltip' => 'save',
        ],
        'view' => [
            'label' => 'view',
            'icon' => 'view',
            'tooltip' => 'view',
>>>>>>> 77b1e2748 (first)
        ],
    ],
];
