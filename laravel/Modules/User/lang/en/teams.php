<?php

declare(strict_types=1);

<<<<<<< HEAD
<<<<<<< HEAD
=======

>>>>>>> f548be94 (.)
=======

=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
return [
    'name' => 'Teams',
    'fields' => [
        'name' => [
            'label' => 'Name',
            'placeholder' => 'Enter team name',
            'helper_text' => 'Team identifying name',
            'description' => 'The name that identifies this team',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => '',
=======
>>>>>>> f548be94 (.)
=======
=======
            'tooltip' => '',
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        ],
        'personal_team' => [
            'label' => 'Personal Team',
            'helper_text' => 'Indicates if this is a personal team',
            'description' => 'A personal team is associated with a single user',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => '',
=======
>>>>>>> f548be94 (.)
=======
=======
            'tooltip' => '',
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        ],
        'owner' => [
            'label' => 'Owner',
            'helper_text' => 'Team owner user',
            'description' => 'The user who created and manages this team',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => '',
=======
>>>>>>> f548be94 (.)
=======
=======
            'tooltip' => '',
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        ],
        'created_at' => [
            'label' => 'Created At',
            'helper_text' => 'Team creation date',
            'description' => 'Date and time when the team was created',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => '',
=======
>>>>>>> f548be94 (.)
=======
=======
            'tooltip' => '',
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        ],
        'updated_at' => [
            'label' => 'Last Modified',
            'helper_text' => 'Last modification date',
            'description' => 'Date and time of the last team modification',
<<<<<<< HEAD
<<<<<<< HEAD
            'tooltip' => '',
=======
>>>>>>> f548be94 (.)
=======
=======
            'tooltip' => '',
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        ],
    ],
    'actions' => [
        'create' => [
            'label' => 'New Team',
            'tooltip' => 'Create a new team',
        ],
        'edit' => [
            'label' => 'Edit',
            'tooltip' => 'Edit team data',
        ],
        'delete' => [
            'label' => 'Delete',
            'tooltip' => 'Delete team',
        ],
        'view' => [
            'label' => 'View',
            'tooltip' => 'View team details',
        ],
    ],
    'messages' => [
        'success' => [
            'created' => 'Team created successfully',
            'updated' => 'Team updated successfully',
            'deleted' => 'Team deleted successfully',
        ],
        'error' => [
            'create' => 'Error while creating team',
            'update' => 'Error while updating team',
            'delete' => 'Error while deleting team',
        ],
        'confirm' => [
            'delete' => 'Are you sure you want to delete this team?',
        ],
    ],
    'relationships' => [
        'members' => [
            'label' => 'Members',
            'description' => 'Users who are part of this team',
        ],
        'owner' => [
            'label' => 'Owner',
            'description' => 'User who created this team',
        ],
    ],
<<<<<<< HEAD
<<<<<<< HEAD
=======
=======
>>>>>>> 87273113 (.)
    'navigation' => [
        'label' => 'Missing Navigation Label',
        'plural_label' => 'Missing Navigation Plural Label',
        'group' => 'Missing Group',
        'icon' => 'heroicon-o-puzzle-piece',
        'sort' => 100,
    ],
    'label' => 'Missing Label',
    'plural_label' => 'Missing Plural label',
<<<<<<< HEAD
=======
>>>>>>> f548be94 (.)
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
];
