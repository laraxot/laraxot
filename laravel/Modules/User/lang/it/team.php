<?php

declare(strict_types=1);

return [
    'navigation' => [
        'name' => 'Team',
        'plural' => 'Teams',
        'group' => [
            'name' => 'Gestione Utenti',
            'description' => 'Gestione dei team e delle loro autorizzazioni',
        ],
        'label' => 'Team',
        'sort' => 18,
        'icon' => 'ui-user-team',
    ],
    'fields' => [
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
        'detach' => [
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'toggleColumns' => [
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'reorderRecords' => [
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'resetFilters' => [
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'create' => [
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'attach' => [
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'view' => [
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'edit' => [
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'openFilters' => [
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'applyFilters' => [
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'updated_at' => [
<<<<<<< .merge_file_sZjJfT
            'label' => 'Modificato il',
=======
>>>>>>> .merge_file_XVmohQ
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'created_at' => [
<<<<<<< .merge_file_sZjJfT
            'label' => 'Creato il',
=======
>>>>>>> .merge_file_XVmohQ
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'users_count' => [
<<<<<<< .merge_file_sZjJfT
            'label' => 'Utenti',
=======
>>>>>>> .merge_file_XVmohQ
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'name' => [
<<<<<<< .merge_file_sZjJfT
            'label' => 'Nome',
=======
>>>>>>> .merge_file_XVmohQ
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'recordId' => [
            'tooltip' => '',
        ],
        'personal_team' => [
<<<<<<< .merge_file_sZjJfT
            'label' => 'Team personale',
=======
>>>>>>> .merge_file_XVmohQ
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'role' => [
<<<<<<< .merge_file_sZjJfT
            'label' => 'Ruolo',
            'description' => 'role',
            'helper_text' => 'role',
            'placeholder' => 'role',
=======
>>>>>>> .merge_file_XVmohQ
            'tooltip' => '',
        ],
        'description' => [
            'label' => '',
            'tooltip' => '',
        ],
        'delete' => [
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
        'layout' => [
            'tooltip' => '',
            'helper_text' => '',
            'description' => '',
        ],
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
            'icon' => 'create',
        ],
        'logout' => [
            'icon' => 'logout',
        ],
        'reorderRecords' => [
            'icon' => 'reorderRecords',
        ],
        'openColumnManager' => [
            'icon' => 'openColumnManager',
        ],
        'applyTableColumnManager' => [
            'icon' => 'applyTableColumnManager',
        ],
        'resetFilters' => [
            'icon' => 'resetFilters',
        ],
        'applyFilters' => [
            'icon' => 'applyFilters',
        ],
        'openFilters' => [
            'icon' => 'openFilters',
        ],
        'detach' => [
            'icon' => 'detach',
        ],
        'cancel' => [
            'icon' => 'cancel',
        ],
        'attachAnother' => [
            'icon' => 'attachAnother',
        ],
        'attach' => [
            'icon' => 'attach',
        ],
        'submit' => [
            'icon' => 'submit',
        ],
        'profile' => [
            'icon' => 'profile',
        ],
        'delete' => [
            'icon' => 'delete',
        ],
        'view' => [
            'icon' => 'view',
        ],
        'createAnother' => [
            'icon' => 'createAnother',
        ],
        'save' => [
            'icon' => 'save',
        ],
        'resetColumnManager' => [
            'icon' => 'resetColumnManager',
        ],
    ],
    'plural' => [
        'model' => [
            'label' => 'team.plural.model',
        ],
    ],
    'model' => [
        'label' => 'team.model',
    ],
    'label' => 'Team',
    'plural_label' => 'Team (Plurale)',
];
