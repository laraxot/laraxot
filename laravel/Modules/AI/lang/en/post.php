<?php

declare(strict_types=1);

// AI translations — LangServiceProvider SSoT (never ->label() in Filament PHP).
// Canon: Modules/AI/docs/wiki — domain i18n only.
// File: lang/en/post.php
return [
    'navigation' => [
        'name' => 'Posts',
        'plural' => 'Posts',
        'group' => [
            'name' => 'Content',
        ],
    ],
    'fields' => [
        'name' => 'Name',
        'guard_name' => 'Guard',
        'permissions' => 'Permissions',
        'roles' => 'Roles',
        'updated_at' => 'Updated on',
        'first_name' => 'First Name',
        'last_name' => 'Last name',
    ],
    'actions' => [
        'import' => [
            'fields' => [
                'import_file' => 'Select an XLS or CSV file to upload',
            ],
        ],
        'export' => [
            'filename_prefix' => 'Areas al',
            'columns' => [
                'name' => 'Area name',
                'parent_name' => 'Top level area name',
            ],
        ],
    ],
];
