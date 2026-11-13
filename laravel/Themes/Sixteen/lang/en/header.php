<?php

declare(strict_types=1);

return [
    // Brand name for <x-cms::head> (pub_theme::header.brand.name): de/es inherit from en.
    'brand' => [
        'name' => 'FixCity',
    ],
    'guest' => [
        'login' => [
            'label' => 'Sign in',
            'placeholder' => '',
            'help' => '',
        ],
        'register' => [
            'label' => 'Register',
            'placeholder' => '',
            'help' => '',
        ],
        'cta' => [
            'label' => 'Personal area',
            'placeholder' => '',
            'help' => 'Link to citizen sign-in.',
        ],
    ],

    'user' => [
        'unread_notifications' => 'unread notifications',
        'personal_area' => 'Personal area',
        'welcome' => [
            'label' => 'Hello, :name',
            'placeholder' => '',
            'help' => '',
        ],
        'aria' => [
            'toggle_menu' => [
                'label' => 'Open user menu',
                'placeholder' => '',
                'help' => '',
            ],
        ],
        'dropdown' => [
            // DCP-106: voci che portano a pagine reali dell'area personale (area-personale/{pratiche,seguite,notifiche,impostazioni})
            'my_reports' => ['label' => 'My reports'],
            'followed_reports' => ['label' => 'Reports I follow'],
            'notification_center' => ['label' => 'Notifications'],
            'settings_privacy' => ['label' => 'Settings and privacy'],
            'my_services' => [
                'label' => 'My services',
                'placeholder' => '',
                'help' => '',
            ],
            'my_practices' => [
                'label' => 'My applications',
                'placeholder' => '',
                'help' => '',
            ],
            'notifications' => [
                'label' => 'Notifications',
                'placeholder' => '',
                'help' => '',
            ],
            'settings' => [
                'label' => 'Settings',
                'placeholder' => '',
                'help' => '',
            ],
            'logout' => [
                'label' => 'Sign out',
                'placeholder' => '',
                'help' => '',
            ],
        ],
    ],

    'slim' => [
        'region' => [
            'name' => [
                'label' => 'Region name',
                'placeholder' => '',
                'help' => 'Label shown in the top slim bar.',
            ],
            'portal_aria' => [
                'label' => 'Go to :region portal — external link — opens in a new tab',
                'placeholder' => '',
                'help' => '',
            ],
            'portal_title' => [
                'label' => 'Go to :region portal',
                'placeholder' => '',
                'help' => '',
            ],
        ],
    ],

    'center' => [
        'brand' => [
            'title' => [
                'label' => 'My municipality',
                'placeholder' => '',
                'help' => '',
            ],
            'tagline' => [
                'label' => 'A municipality to live in',
                'placeholder' => '',
                'help' => '',
            ],
            'home_link' => [
                'title' => [
                    'label' => 'Go to homepage',
                    'placeholder' => '',
                    'help' => '',
                ],
            ],
        ],
        'search' => [
            'label' => 'Search',
            'toggle_aria' => [
                'label' => 'Search the site',
                'placeholder' => '',
                'help' => '',
            ],
        ],
        'social' => [
            'follow' => [
                'label' => 'Follow us on',
                'placeholder' => '',
                'help' => '',
            ],
        ],
        'nav' => [
            'toggle_aria' => [
                'label' => 'Show/Hide navigation',
                'placeholder' => '',
                'help' => '',
            ],
            'close_aria' => [
                'label' => 'Hide navigation',
                'placeholder' => '',
                'help' => '',
            ],
            'primary_aria' => [
                'label' => 'Main',
                'placeholder' => '',
                'help' => '',
            ],
            'secondary_aria' => [
                'label' => 'Secondary',
                'placeholder' => '',
                'help' => '',
            ],
            'amministrazione' => [
                'label' => 'Administration',
                'placeholder' => '',
                'help' => '',
            ],
            'novita' => [
                'label' => 'News',
                'placeholder' => '',
                'help' => '',
            ],
            'servizi' => [
                'label' => 'Services',
                'placeholder' => '',
                'help' => '',
            ],
            'vivere' => [
                'label' => 'Living the municipality',
                'placeholder' => '',
                'help' => '',
            ],
            'iscrizioni' => [
                'label' => 'Registrations',
                'placeholder' => '',
                'help' => '',
            ],
            'estate' => [
                'label' => 'Summer in the city',
                'placeholder' => '',
                'help' => '',
            ],
            'polizia' => [
                'label' => 'Local police',
                'placeholder' => '',
                'help' => '',
            ],
            'argomenti' => [
                'label' => 'All topics',
                'placeholder' => '',
                'help' => '',
            ],
            'hamburger_brand' => [
                'label' => 'Municipality name',
                'placeholder' => '',
                'help' => '',
            ],
        ],
    ],

    'language' => [
        'active_prefix' => [
            'label' => 'Current language:',
            'placeholder' => '',
            'help' => '',
        ],
        'code_it' => [
            'label' => 'ITA',
            'placeholder' => '',
            'help' => '',
        ],
        'code_en' => [
            'label' => 'ENG',
            'placeholder' => '',
            'help' => '',
        ],
        'selected_suffix' => [
            'label' => 'selected',
            'placeholder' => '',
            'help' => '',
        ],
    ],

    'social' => [
        'twitter' => [
            'label' => 'Twitter',
            'placeholder' => '',
            'help' => '',
        ],
        'facebook' => [
            'label' => 'Facebook',
            'placeholder' => '',
            'help' => '',
        ],
        'youtube' => [
            'label' => 'YouTube',
            'placeholder' => '',
            'help' => '',
        ],
        'telegram' => [
            'label' => 'Telegram',
            'placeholder' => '',
            'help' => '',
        ],
        'whatsapp' => [
            'label' => 'WhatsApp',
            'placeholder' => '',
            'help' => '',
        ],
        'rss' => [
            'label' => 'RSS',
            'placeholder' => '',
            'help' => '',
        ],
    ],
];
