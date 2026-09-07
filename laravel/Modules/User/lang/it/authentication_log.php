<?php

declare(strict_types=1);

return [
<<<<<<< HEAD
<<<<<<< HEAD
    'navigation' => ['label' => 'Authentication Logs', 'group' => 'Authentication', 'icon' => 'heroicon-o-shield-check', 'sort' => 5],
    'actions' => [
        'reorderRecords' => ['tooltip' => 'reorderRecords', 'icon' => 'reorderRecords', 'label' => 'reorderRecords'],
        'openColumnManager' => ['tooltip' => 'openColumnManager', 'label' => 'openColumnManager', 'icon' => 'openColumnManager'],
        'edit' => ['label' => 'edit', 'icon' => 'edit', 'tooltip' => 'edit'],
        'delete' => ['label' => 'delete', 'icon' => 'delete', 'tooltip' => 'delete'],
        'detach' => ['label' => 'detach', 'icon' => 'detach', 'tooltip' => 'detach'],
        'attach' => ['label' => 'attach', 'icon' => 'attach', 'tooltip' => 'attach'],
        'create' => ['label' => 'create', 'icon' => 'create', 'tooltip' => 'create'],
        'applyFilters' => ['label' => 'applyFilters', 'icon' => 'applyFilters', 'tooltip' => 'applyFilters'],
        'openFilters' => ['label' => 'openFilters', 'icon' => 'openFilters', 'tooltip' => 'openFilters'],
        'resetFilters' => ['label' => 'resetFilters', 'icon' => 'resetFilters', 'tooltip' => 'resetFilters'],
        'applyTableColumnManager' => ['label' => 'applyTableColumnManager', 'icon' => 'applyTableColumnManager', 'tooltip' => 'applyTableColumnManager'],
        'save' => ['label' => 'save', 'icon' => 'save', 'tooltip' => 'save'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
=======
=======
>>>>>>> 87273113 (.)
    'navigation' => [
        'label' => 'Authentication Logs',
        'group' => 'Authentication',
        'icon' => 'heroicon-o-shield-check',
        'sort' => 5,
    ],
    'actions' => [
        'reorderRecords' => [
            'tooltip' => 'reorderRecords',
            'icon' => 'reorderRecords',
            'label' => 'reorderRecords',
        ],
        'openColumnManager' => [
            'tooltip' => 'openColumnManager',
            'label' => 'openColumnManager',
            'icon' => 'openColumnManager',
        ],
        'edit' => [
            'label' => 'edit',
            'icon' => 'edit',
            'tooltip' => 'edit',
        ],
        'delete' => [
            'label' => 'delete',
            'icon' => 'delete',
            'tooltip' => 'delete',
        ],
        'detach' => [
            'label' => 'detach',
            'icon' => 'detach',
            'tooltip' => 'detach',
        ],
        'attach' => [
            'label' => 'attach',
            'icon' => 'attach',
            'tooltip' => 'attach',
        ],
        'create' => [
            'label' => 'create',
            'icon' => 'create',
            'tooltip' => 'create',
        ],
        'applyFilters' => [
            'label' => 'applyFilters',
            'icon' => 'applyFilters',
            'tooltip' => 'applyFilters',
        ],
        'openFilters' => [
            'label' => 'openFilters',
            'icon' => 'openFilters',
            'tooltip' => 'openFilters',
        ],
        'resetFilters' => [
            'label' => 'resetFilters',
            'icon' => 'resetFilters',
            'tooltip' => 'resetFilters',
        ],
        'applyTableColumnManager' => [
            'label' => 'applyTableColumnManager',
            'icon' => 'applyTableColumnManager',
            'tooltip' => 'applyTableColumnManager',
        ],
<<<<<<< HEAD
>>>>>>> 60a2c9a9 (.)
=======
=======
    'navigation' => ['label' => 'Authentication Logs', 'group' => 'Authentication', 'icon' => 'heroicon-o-shield-check', 'sort' => 5],
    'actions' => [
        'reorderRecords' => ['tooltip' => 'reorderRecords', 'icon' => 'reorderRecords', 'label' => 'reorderRecords'],
        'openColumnManager' => ['tooltip' => 'openColumnManager', 'label' => 'openColumnManager', 'icon' => 'openColumnManager'],
        'edit' => ['label' => 'edit', 'icon' => 'edit', 'tooltip' => 'edit'],
        'delete' => ['label' => 'delete', 'icon' => 'delete', 'tooltip' => 'delete'],
        'detach' => ['label' => 'detach', 'icon' => 'detach', 'tooltip' => 'detach'],
        'attach' => ['label' => 'attach', 'icon' => 'attach', 'tooltip' => 'attach'],
        'create' => ['label' => 'create', 'icon' => 'create', 'tooltip' => 'create'],
        'applyFilters' => ['label' => 'applyFilters', 'icon' => 'applyFilters', 'tooltip' => 'applyFilters'],
        'openFilters' => ['label' => 'openFilters', 'icon' => 'openFilters', 'tooltip' => 'openFilters'],
        'resetFilters' => ['label' => 'resetFilters', 'icon' => 'resetFilters', 'tooltip' => 'resetFilters'],
        'applyTableColumnManager' => ['label' => 'applyTableColumnManager', 'icon' => 'applyTableColumnManager', 'tooltip' => 'applyTableColumnManager'],
        'save' => ['label' => 'save', 'icon' => 'save', 'tooltip' => 'save'],
        'view' => ['label' => 'view', 'icon' => 'view', 'tooltip' => 'view'],
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    ],
    'label' => 'Authentication Log',
    'plural_label' => 'Authentication Log (Plurale)',
    'fields' => [
<<<<<<< HEAD
<<<<<<< HEAD
        'id' => ['label' => 'Identificativo', 'tooltip' => 'Identificativo univoco del record', 'helper_text' => '', 'description' => ''],
        'created_at' => ['label' => 'Data Creazione', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'updated_at' => ['label' => 'Ultima Modifica', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'ip_address' => ['label' => 'ip_address', 'placeholder' => 'ip_address', 'helper_text' => 'ip_address', 'description' => 'ip_address'],
        'user_agent' => ['label' => 'user_agent', 'placeholder' => 'user_agent', 'helper_text' => 'user_agent', 'description' => 'user_agent'],
        'login_successful' => ['label' => 'login_successful', 'placeholder' => 'login_successful', 'helper_text' => 'login_successful', 'description' => 'login_successful'],
        'login_at' => ['label' => 'login_at', 'placeholder' => 'login_at', 'helper_text' => 'login_at', 'description' => 'login_at'],
        'logout_at' => ['label' => 'logout_at', 'placeholder' => 'logout_at', 'helper_text' => 'logout_at', 'description' => 'logout_at'],
        'location' => ['label' => 'location'],
        'authenticatable_type' => ['label' => 'authenticatable_type', 'placeholder' => 'authenticatable_type', 'helper_text' => 'authenticatable_type', 'description' => 'authenticatable_type'],
        'authenticatable_id' => ['label' => 'authenticatable_id', 'placeholder' => 'authenticatable_id', 'helper_text' => 'authenticatable_id', 'description' => 'authenticatable_id'],
        'cleared_by_user' => ['label' => 'cleared_by_user', 'placeholder' => 'cleared_by_user', 'helper_text' => 'cleared_by_user', 'description' => 'cleared_by_user'],
        'authenticatable' => [
            'name' => ['label' => 'authenticatable.name'],
        ],
    ],
    'sections' => [
        'Authentication Information' => ['label' => 'Authentication Information', 'heading' => 'Authentication Information'],
        'Authentication Details' => ['label' => 'Authentication Details', 'heading' => 'Authentication Details'],
        'User Agent' => ['label' => 'User Agent', 'heading' => 'User Agent'],
        'Timestamps' => ['label' => 'Timestamps', 'heading' => 'Timestamps'],
        'Status' => ['label' => 'Status', 'heading' => 'Status'],
        'Location' => ['label' => 'Location', 'heading' => 'Location'],
    ],
=======
=======
>>>>>>> 87273113 (.)
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
        'ip_address' => [
            'label' => 'ip_address',
        ],
        'user_agent' => [
            'label' => 'user_agent',
        ],
        'login_successful' => [
            'label' => 'login_successful',
        ],
        'login_at' => [
            'label' => 'login_at',
        ],
        'logout_at' => [
            'label' => 'logout_at',
        ],
        'location' => [
            'label' => 'location',
        ],
=======
        'id' => ['label' => 'Identificativo', 'tooltip' => 'Identificativo univoco del record', 'helper_text' => '', 'description' => ''],
        'created_at' => ['label' => 'Data Creazione', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'updated_at' => ['label' => 'Ultima Modifica', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'ip_address' => ['label' => 'ip_address'],
        'user_agent' => ['label' => 'user_agent'],
        'login_successful' => ['label' => 'login_successful'],
        'login_at' => ['label' => 'login_at'],
        'logout_at' => ['label' => 'logout_at'],
        'location' => ['label' => 'location'],
>>>>>>> 2024e2e7 (.)
    ],
>>>>>>> 60a2c9a9 (.)
];
