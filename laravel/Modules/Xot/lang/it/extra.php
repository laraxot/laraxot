<?php

declare(strict_types=1);

return [
    'navigation' => [
<<<<<<< HEAD
        'name' => 'extra',
=======
        'name' => 'Extra',
>>>>>>> laraxot/dev
        'plural' => 'estras',
        'group' => [
            'name' => 'Admin',
        ],
<<<<<<< HEAD
        'label' => 'extra.navigation',
        'icon' => 'extra.navigation',
=======
        'label' => 'Extra',
        'icon' => 'heroicon-o-plus',
>>>>>>> laraxot/dev
        'sort' => 38,
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
    'label' => 'Extra',
    'plural_label' => 'Extra (Plurale)',
    'fields' => [
        'id' => [
            'label' => 'Identificativo',
            'tooltip' => 'Identificativo univoco del record',
            'helper_text' => '',
            'description' => '',
<<<<<<< HEAD
            'placeholder' => 'id',
=======
>>>>>>> laraxot/dev
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
<<<<<<< HEAD
        'model_type' => [
            'label' => 'model_type',
        ],
        'model_id' => [
            'label' => 'model_id',
        ],
        'extra_attributes' => [
            'label' => 'extra_attributes',
        ],
        'post_type' => [
            'label' => 'post_type',
            'placeholder' => 'post_type',
            'helper_text' => 'post_type',
            'description' => 'post_type',
        ],
        'post_id' => [
            'label' => 'post_id',
            'placeholder' => 'post_id',
            'helper_text' => 'post_id',
            'description' => 'post_id',
        ],
        'value' => [
            'label' => 'value',
            'placeholder' => 'value',
            'helper_text' => '',
            'description' => 'value',
=======
        'value' => [
            'helper_text' => '',
>>>>>>> laraxot/dev
        ],
    ],
    'actions' => [
        'create' => [
            'label' => 'Crea Extra',
            'icon' => 'create',
<<<<<<< HEAD
            'tooltip' => 'create',
=======
>>>>>>> laraxot/dev
        ],
        'edit' => [
            'label' => 'Modifica Extra',
            'icon' => 'edit',
<<<<<<< HEAD
            'tooltip' => 'edit',
=======
>>>>>>> laraxot/dev
        ],
        'delete' => [
            'label' => 'Elimina Extra',
            'icon' => 'delete',
<<<<<<< HEAD
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
=======
        ],
        'createAnother' => [
            'icon' => 'createAnother',
        ],
        'save' => [
            'icon' => 'save',
>>>>>>> laraxot/dev
        ],
    ],
];
