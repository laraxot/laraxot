<?php

declare(strict_types=1);

return [
    'title' => 'Password Scaduta, Reimposta Password',
    'heading' => 'Crea una Nuova Password',
    'sub_heading' => 'La tua password è scaduta, per favore crea una nuova password',
    'fields' => [
<<<<<<< HEAD
<<<<<<< HEAD
        'current_password' => ['label' => 'Current Password', 'validation_attribute' => 'current_password', 'tooltip' => '', 'helper_text' => '', 'description' => '', 'placeholder' => 'current_password'],
        'password' => ['label' => 'Password', 'validation_attribute' => 'password', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'password_confirmation' => ['label' => 'Confirm Password', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
    ],
    'form' => [
        'current_password' => ['label' => 'Current Password', 'validation_attribute' => 'current_password'],
        'password' => ['label' => 'Password', 'validation_attribute' => 'password'],
        'password_confirmation' => ['label' => 'Confirm Password'],
=======
=======
>>>>>>> 87273113 (.)
        'current_password' => [
            'label' => 'Current Password',
            'validation_attribute' => 'current_password',
        ],
        'password' => [
            'label' => 'Password',
            'validation_attribute' => 'password',
        ],
        'password_confirmation' => [
            'label' => 'Confirm Password',
        ],
    ],
    'form' => [
        'current_password' => [
            'label' => 'Current Password',
            'validation_attribute' => 'current_password',
        ],
        'password' => [
            'label' => 'Password',
            'validation_attribute' => 'password',
        ],
        'password_confirmation' => [
            'label' => 'Confirm Password',
        ],
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        'current_password' => ['label' => 'Current Password', 'validation_attribute' => 'current_password', 'tooltip' => '', 'helper_text' => '', 'description' => '', 'placeholder' => 'current_password'],
        'password' => ['label' => 'Password', 'validation_attribute' => 'password', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
        'password_confirmation' => ['label' => 'Confirm Password', 'tooltip' => '', 'helper_text' => '', 'description' => ''],
    ],
    'form' => [
        'current_password' => ['label' => 'Current Password', 'validation_attribute' => 'current_password'],
        'password' => ['label' => 'Password', 'validation_attribute' => 'password'],
        'password_confirmation' => ['label' => 'Confirm Password'],
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    ],
    'actions' => [
        'reset_password' => ['label' => 'Reset Password'],
        'cancel' => ['label' => 'Cancel'],
<<<<<<< HEAD
<<<<<<< HEAD
        'showPassword' => ['label' => 'showPassword', 'icon' => 'showPassword', 'tooltip' => 'showPassword'],
        'hidePassword' => ['label' => 'hidePassword', 'icon' => 'hidePassword', 'tooltip' => 'hidePassword'],
        'resetPassword' => ['label' => 'resetPassword', 'icon' => 'resetPassword', 'tooltip' => 'resetPassword'],
=======
>>>>>>> f548be94 (.)
=======
=======
        'showPassword' => ['label' => 'showPassword', 'icon' => 'showPassword', 'tooltip' => 'showPassword'],
        'hidePassword' => ['label' => 'hidePassword', 'icon' => 'hidePassword', 'tooltip' => 'hidePassword'],
        'resetPassword' => ['label' => 'resetPassword', 'icon' => 'resetPassword', 'tooltip' => 'resetPassword'],
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    ],
    'reset_password' => 'Reset Password',
    'password_reset' => 'Password Reset',
    'notifications' => [
<<<<<<< HEAD
<<<<<<< HEAD
        'wrong_password' => ['title' => 'Wrong Password', 'body' => 'The current password you entered is incorrect.'],
        'column_not_found' => ['title' => 'Column Not Found', 'body' => 'Either the column ":column_name" or the password column ":password_column_name" was not found in the :table_name table.'],
        'password_reset' => ['success' => 'Password Reset Successful'],
        'same_password' => ['title' => 'Same Password', 'body' => 'The new password must be different from the current password.'],
    ],
    'exceptions' => ['column_not_found' => 'Either the column ":column_name" or the password column ":password_column_name" was not found in the ":table_name" table. Please publish migrations and run them, if the error still persists, publish the config file and update the table_name, column_name, and password_column_name values.'],
    'navigation' => [
        'name' => 'Password Expired',
        'plural' => 'Password Expired',
        'group' => ['name' => 'General', 'description' => 'General Settings'],
        'label' => 'Password Expired',
        'sort' => 1,
        'icon' => 'heroicon-o-collection',
    ],
    'label' => 'Password Expired',
    'plural_label' => 'Password Expired (Plurale)',
=======
=======
>>>>>>> 87273113 (.)
        'wrong_password' => [
            'title' => 'Wrong Password',
            'body' => 'The current password you entered is incorrect.',
        ],
        'column_not_found' => [
            'title' => 'Column Not Found',
            'body' => 'Either the column ":column_name" or the password column ":password_column_name" was not found in the :table_name table.',
        ],
        'password_reset' => [
            'success' => 'Password Reset Successful',
        ],
        'same_password' => [
            'title' => 'Same Password',
            'body' => 'The new password must be different from the current password.',
        ],
    ],
    'exceptions' => [
        'column_not_found' => 'Either the column ":column_name" or the password column ":password_column_name" was not found in the ":table_name" table. Please publish migrations and run them, if the error still persists, publish the config file and update the table_name, column_name, and password_column_name values.',
    ],
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        'wrong_password' => ['title' => 'Wrong Password', 'body' => 'The current password you entered is incorrect.'],
        'column_not_found' => ['title' => 'Column Not Found', 'body' => 'Either the column ":column_name" or the password column ":password_column_name" was not found in the :table_name table.'],
        'password_reset' => ['success' => 'Password Reset Successful'],
        'same_password' => ['title' => 'Same Password', 'body' => 'The new password must be different from the current password.'],
    ],
    'exceptions' => ['column_not_found' => 'Either the column ":column_name" or the password column ":password_column_name" was not found in the ":table_name" table. Please publish migrations and run them, if the error still persists, publish the config file and update the table_name, column_name, and password_column_name values.'],
    'navigation' => [
        'name' => 'Password Expired',
        'plural' => 'Password Expired',
        'group' => ['name' => 'General', 'description' => 'General Settings'],
        'label' => 'Password Expired',
        'sort' => 1,
        'icon' => 'heroicon-o-collection',
    ],
    'label' => 'Password Expired',
    'plural_label' => 'Password Expired (Plurale)',
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
];
