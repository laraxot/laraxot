<?php

declare(strict_types=1);

return [
    'navigation' => ['label' => 'Provider SSO', 'group' => 'Authentication', 'icon' => 'heroicon-o-identification', 'sort' => 3],
    'label' => 'Provider SSO',
    'plural_label' => 'Provider SSO',
    'fields' => [
        'id' => ['label' => 'Identificativo', 'tooltip' => 'Identificativo univoco del record', 'helper_text' => '', 'description' => ''],
        'created_at' => ['label' => 'Data Creazione', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'updated_at' => ['label' => 'Ultima Modifica', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'name' => ['label' => 'name'],
        'display_name' => ['label' => 'display_name'],
        'type' => ['label' => 'type'],
        'is_active' => ['label' => 'is_active'],
    ],
    'actions' => [
        'create' => ['label' => 'Crea Sso Provider', 'icon' => 'create', 'tooltip' => 'create'],
        'edit' => ['label' => 'Modifica Sso Provider', 'icon' => 'edit', 'tooltip' => 'edit'],
        'delete' => ['label' => 'Elimina Sso Provider', 'icon' => 'delete', 'tooltip' => 'delete'],
        'createAnother' => ['label' => 'createAnother', 'icon' => 'createAnother', 'tooltip' => 'createAnother'],
        'save' => ['label' => 'save', 'icon' => 'save', 'tooltip' => 'save'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
    ],
];
