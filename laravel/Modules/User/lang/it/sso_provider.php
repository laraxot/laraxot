<?php

declare(strict_types=1);

return [
    'navigation' => [
        'label' => 'Provider SSO',
        'group' => 'Authentication',
        'icon' => 'heroicon-o-identification',
        'sort' => 3,
    ],
    'label' => 'Provider SSO',
    'plural_label' => 'Provider SSO',
    'fields' => [
<<<<<<< .merge_file_nj3A8Q
        'id' => ['label' => 'Identificativo', 'tooltip' => 'Identificativo univoco del record', 'helper_text' => '', 'description' => ''],
        'created_at' => ['label' => 'Data Creazione', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'updated_at' => ['label' => 'Ultima Modifica', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'name' => ['label' => 'Nome'],
        'display_name' => ['label' => 'Nome visualizzato'],
        'type' => ['label' => 'Tipo'],
        'is_active' => ['label' => 'Attivo'],
=======
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
>>>>>>> .merge_file_1UAi10
    ],
    'actions' => [
        'create' => [
            'label' => 'Crea Sso Provider',
        ],
        'edit' => [
            'label' => 'Modifica Sso Provider',
        ],
        'delete' => [
            'label' => 'Elimina Sso Provider',
        ],
    ],
];
