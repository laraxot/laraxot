<?php

declare(strict_types=1);

return [
    'resources' => 'Risorse',
    'pages' => 'Pagine',
    'widgets' => 'Widgets',
    'navigation' => [
<<<<<<< HEAD
        'name' => 'Registro',
=======
        'name' => 'log',
>>>>>>> 77b1e2748 (first)
        'plural' => 'logs',
        'group' => [
            'name' => 'Admin',
        ],
<<<<<<< HEAD
        'label' => 'Log',
        'icon' => 'heroicon-o-clipboard-document-list',
=======
        'label' => 'log.navigation',
        'icon' => 'log.navigation',
>>>>>>> 77b1e2748 (first)
        'sort' => 61,
    ],
    'fields' => [
        'name' => [
            'label' => 'Nome',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
<<<<<<< HEAD
=======
            'placeholder' => 'name',
>>>>>>> 77b1e2748 (first)
        ],
        'guard_name' => [
            'label' => 'Guard',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'permissions' => [
            'label' => 'Permessi',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'updated_at' => [
            'label' => 'Aggiornato il',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'first_name' => [
            'label' => 'Nome',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'last_name' => [
            'label' => 'Cognome',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'select_all' => [
            'name' => 'Seleziona Tutti',
            'message' => '',
            'label' => '',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
<<<<<<< HEAD
=======
        'id' => [
            'label' => 'id',
        ],
        'message' => [
            'label' => 'message',
        ],
        'level' => [
            'label' => 'level',
        ],
        'level_name' => [
            'label' => 'level_name',
        ],
        'context' => [
            'label' => 'context',
        ],
        'created_at' => [
            'label' => 'created_at',
        ],
        'path' => [
            'label' => 'path',
            'placeholder' => 'path',
            'helper_text' => 'path',
            'description' => 'path',
        ],
        'content' => [
            'label' => 'content',
            'placeholder' => 'content',
            'helper_text' => 'content',
            'description' => 'content',
        ],
        'file-content' => [
            'label' => 'file-content',
        ],
>>>>>>> 77b1e2748 (first)
    ],
    'actions' => [
        'import' => [
            'fields' => [
                'import_file' => 'Seleziona un file XLS o CSV da caricare',
            ],
        ],
        'export' => [
            'filename_prefix' => 'Aree al',
            'columns' => [
                'name' => 'Nome area',
                'parent_name' => 'Nome area livello superiore',
            ],
        ],
        'create' => [
<<<<<<< HEAD
            'icon' => 'create',
        ],
        'createAnother' => [
            'icon' => 'createAnother',
        ],
        'delete' => [
            'icon' => 'delete',
        ],
        'save' => [
            'icon' => 'save',
        ],
        'view' => [
            'icon' => 'view',
        ],
        'logout' => [
            'icon' => 'logout',
=======
            'label' => 'create',
            'icon' => 'create',
            'tooltip' => 'create',
        ],
        'createAnother' => [
            'label' => 'createAnother',
            'icon' => 'createAnother',
            'tooltip' => 'createAnother',
        ],
        'delete' => [
            'label' => 'delete',
            'icon' => 'delete',
            'tooltip' => 'delete',
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
        ],
        'logout' => [
            'tooltip' => 'logout',
            'icon' => 'logout',
            'label' => 'logout',
>>>>>>> 77b1e2748 (first)
        ],
    ],
    'label' => 'Log',
    'plural_label' => 'Log (Plurale)',
<<<<<<< HEAD
    'title' => 'Registro',
=======
    'title' => 'log',
>>>>>>> 77b1e2748 (first)
];
