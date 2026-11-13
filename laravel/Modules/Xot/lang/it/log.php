<?php

declare(strict_types=1);

return [
    'resources' => 'Risorse',
    'pages' => 'Pagine',
    'widgets' => 'Widgets',
    'navigation' => [
<<<<<<< HEAD
        'name' => 'log',
=======
        'name' => 'Registro',
>>>>>>> laraxot/dev
        'plural' => 'logs',
        'group' => [
            'name' => 'Admin',
        ],
<<<<<<< HEAD
        'label' => 'log.navigation',
        'icon' => 'log.navigation',
=======
        'label' => 'Log',
        'icon' => 'heroicon-o-clipboard-document-list',
>>>>>>> laraxot/dev
        'sort' => 61,
    ],
    'fields' => [
        'name' => [
            'label' => 'Nome',
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
<<<<<<< HEAD
            'placeholder' => 'name',
=======
>>>>>>> laraxot/dev
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
=======
>>>>>>> laraxot/dev
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
=======
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
>>>>>>> laraxot/dev
        ],
    ],
    'label' => 'Log',
    'plural_label' => 'Log (Plurale)',
<<<<<<< HEAD
    'title' => 'log',
=======
    'title' => 'Registro',
>>>>>>> laraxot/dev
];
