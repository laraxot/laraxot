<?php

declare(strict_types=1);

return [
    'fields' => [
        'email' => [
<<<<<<< HEAD
<<<<<<< HEAD
            'label' => 'Email address',
            'placeholder' => 'name@example.com',
            'helper_text' => 'Email used to register for online services',
            'tooltip' => 'Enter your account email',
            'description' => 'Email field for authentication',
=======
=======
>>>>>>> 87273113 (.)
            'label' => 'Email',
            'placeholder' => 'Enter your email',
            'help' => 'Enter the email address you used to register',
            'description' => 'Email address for login',
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
            'label' => 'Email address',
            'placeholder' => 'name@example.com',
            'helper_text' => 'Email used to register for online services',
            'tooltip' => 'Enter your account email',
            'description' => 'Email field for authentication',
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
        ],
        'password' => [
            'label' => 'Password',
            'placeholder' => 'Enter your password',
<<<<<<< HEAD
<<<<<<< HEAD
            'helper_text' => '',
            'tooltip' => 'Account password',
            'description' => 'Password field for authentication',
        ],
        'remember' => [
            'label' => 'Remember me',
            'placeholder' => '',
            'helper_text' => 'Keep me signed in on this device',
            'tooltip' => 'Extended session',
            'description' => 'Remember login option',
        ],
    ],
    'actions' => [
        'hidePassword' => [
            'label' => 'Hide password',
            'tooltip' => 'Hide password',
            'icon' => 'hidePassword',
        ],
        'showPassword' => [
            'label' => 'Show password',
            'tooltip' => 'Show password',
            'icon' => 'showPassword',
        ],
    ],
=======
=======
>>>>>>> 87273113 (.)
            'help' => 'Enter your account password',
            'description' => 'Password for login',
        ],
        'remember' => [
            'label' => 'Remember me',
            'placeholder' => 'Keep session active',
            'help' => 'Select to keep your session active for 30 days',
            'description' => 'Option to remember login',
        ],
    ],
    'actions' => [
        'login' => [
            'label' => 'Login',
            'tooltip' => 'Click to access your account',
        ],
    ],
    'messages' => [
        'login_success' => 'Login successful',
        'login_error' => 'Error during login',
        'validation_error' => 'Validation error',
        'credentials_incorrect' => 'Incorrect credentials',
    ],
    'ui' => [
        'login_button' => 'Login',
        'forgot_password' => 'Forgot password?',
        'errors_title' => 'Some errors occurred',
    ],
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
            'helper_text' => '',
            'tooltip' => 'Account password',
            'description' => 'Password field for authentication',
        ],
        'remember' => [
            'label' => 'Remember me',
            'placeholder' => '',
            'helper_text' => 'Keep me signed in on this device',
            'tooltip' => 'Extended session',
            'description' => 'Remember login option',
        ],
    ],
    'actions' => [
        'hidePassword' => [
            'label' => 'Hide password',
            'tooltip' => 'Hide password',
            'icon' => 'hidePassword',
        ],
        'showPassword' => [
            'label' => 'Show password',
            'tooltip' => 'Show password',
            'icon' => 'showPassword',
        ],
    ],
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
];
