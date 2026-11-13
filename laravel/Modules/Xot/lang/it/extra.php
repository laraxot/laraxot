<?php

declare(strict_types=1);

return [
    'navigation' => [
<<<<<<< HEAD
        'name' => 'Extra',
=======
        'name' => 'extra',
>>>>>>> 77b1e2748 (first)
        'plural' => 'estras',
        'group' => [
            'name' => 'Admin',
        ],
<<<<<<< HEAD
        'label' => 'Extra',
        'icon' => 'heroicon-o-plus',
=======
        'label' => 'extra.navigation',
        'icon' => 'extra.navigation',
>>>>>>> 77b1e2748 (first)
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
=======
            'placeholder' => 'id',
>>>>>>> 77b1e2748 (first)
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
        'value' => [
            'helper_text' => '',
=======
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
>>>>>>> 77b1e2748 (first)
        ],
    ],
    'actions' => [
        'create' => [
            'label' => 'Crea Extra',
            'icon' => 'create',
<<<<<<< HEAD
=======
            'tooltip' => 'create',
>>>>>>> 77b1e2748 (first)
        ],
        'edit' => [
            'label' => 'Modifica Extra',
            'icon' => 'edit',
<<<<<<< HEAD
=======
            'tooltip' => 'edit',
>>>>>>> 77b1e2748 (first)
        ],
        'delete' => [
            'label' => 'Elimina Extra',
            'icon' => 'delete',
<<<<<<< HEAD
        ],
        'createAnother' => [
            'icon' => 'createAnother',
        ],
        'save' => [
            'icon' => 'save',
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
>>>>>>> 77b1e2748 (first)
        ],
    ],
];
