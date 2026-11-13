<?php

declare(strict_types=1);

return [
<<<<<<< HEAD
    'navigation' => ['label' => 'Test', 'group' => 'Sviluppo', 'icon' => 'heroicon-o-beaker', 'sort' => 999],
    'label' => 'Test',
    'plural_label' => 'Test (Plurale)',
    'fields' => [
        'id' => ['label' => 'Identificativo', 'tooltip' => 'Identificativo univoco del record', 'helper_text' => '', 'description' => ''],
        'created_at' => ['label' => 'Data Creazione', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'updated_at' => ['label' => 'Ultima Modifica', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'alpha' => ['label' => 'alpha', 'placeholder' => 'alpha', 'helper_text' => 'alpha', 'description' => 'alpha'],
        'beta' => ['label' => 'beta', 'placeholder' => 'beta', 'helper_text' => 'beta', 'description' => 'beta'],
    ],
    'actions' => [
        'create' => ['label' => 'Crea Test'],
        'edit' => ['label' => 'Modifica Test'],
        'delete' => ['label' => 'Elimina Test'],
        'save' => ['label' => 'save', 'icon' => 'save', 'tooltip' => 'save'],
=======
    'navigation' => [
        'label' => 'Test',
        'group' => 'Sviluppo',
        'icon' => 'heroicon-o-beaker',
        'sort' => 999,
    ],
    'label' => 'Test',
    'plural_label' => 'Test (Plurale)',
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
    'actions' => [
        'create' => [
            'label' => 'Crea Test',
        ],
        'edit' => [
            'label' => 'Modifica Test',
        ],
        'delete' => [
            'label' => 'Elimina Test',
        ],
        'save' => [
            'icon' => 'save',
        ],
>>>>>>> laraxot/dev
    ],
];
