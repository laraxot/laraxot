<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurazione Tema Sixteen
    |--------------------------------------------------------------------------
    |
    | Configurazione per il tema Sixteen che implementa il design system
    | per i comuni italiani
    |
    */

    'name' => 'Sixteen',
    'version' => '1.0.0',
    'description' => 'Tema comunale con design system AGID',
    'author' => 'Fixcity Team',
    'website' => 'https://fixcity.it',

    'assets' => [
        'css' => [
            // NOTE: Sixteen theme uses Vite + Tailwind, NOT Bootstrap Italia CDN
            // All styles are compiled locally via resources/css/app.css
            // These entries are kept for reference only - DO NOT use CDN
            'civic-design' => 'css/civic-design-theme.css',
            'comune-custom' => 'css/comune-custom.css',
        ],
        'js' => [
            // NOTE: Sixteen theme uses Vite + Alpine.js + Lit, NOT Bootstrap Italia CDN
            // All scripts are compiled locally via resources/js/app.js
            'design-comuni' => 'js/design-comuni.js',
            'comune-functions' => 'js/comune-functions.js',
        ],
    ],

    'layouts' => [
        'app' => 'sixteen::layouts.app',
        'comune' => 'sixteen::layouts.app',
    ],

    'components' => [
        'header' => 'sixteen::components.header-comune',
        'footer' => 'sixteen::components.footer-comune',
        'navigation' => 'sixteen::components.navigation',
        'service-card' => 'sixteen::components.service-card',
    ],

    'pages' => [
        'homepage' => 'sixteen::pages.comune.homepage',
        'servizi' => 'sixteen::pages.comune.servizi',
        'novita' => 'sixteen::pages.comune.novita',
        'contatti' => 'sixteen::pages.comune.contatti',
        'documenti' => 'sixteen::pages.comune.documenti',
        'eventi' => 'sixteen::pages.comune.eventi',
        'anagrafe' => 'sixteen::pages.comune.anagrafe',
        'tributi' => 'sixteen::pages.comune.tributi',
        'urbanistica' => 'sixteen::pages.comune.urbanistica',
        'prenotazioni' => 'sixteen::pages.comune.prenotazioni',
    ],

    'routes' => [
        'prefix' => 'comune',
        'name' => 'comune.',
        'middleware' => ['web'],
    ],

    'features' => [
        'map' => [
            'enabled' => true,
            'provider' => 'leaflet',
            'default_zoom' => 13,
            'default_center' => [45.4642, 9.1900],
        ],
        'notifications' => [
            'enabled' => true,
            'types' => ['email', 'push', 'sms'],
        ],
        'search' => [
            'enabled' => true,
            'provider' => 'elasticsearch',
        ],
        'analytics' => [
            'enabled' => true,
            'provider' => 'google',
        ],
    ],

    'customization' => [
        'colors' => [
            'primary' => '#0066cc',
            'secondary' => '#00cc66',
            'accent' => '#ff6600',
            'dark' => '#333333',
            'light' => '#f8f9fa',
        ],
        'fonts' => [
            'primary' => 'Titillium Web',
            'secondary' => 'Roboto',
        ],
        'logo' => [
            'enabled' => true,
            'path' => 'images/logo-comune.png',
            'alt' => 'Logo Comune',
        ],
    ],

    'accessibility' => [
        'wcag_level' => 'AA',
        'contrast_ratio' => 4.5,
        'keyboard_navigation' => true,
        'screen_reader' => true,
        'focus_indicators' => true,
    ],

    'performance' => [
        'lazy_loading' => true,
        'minification' => true,
        'compression' => true,
        'caching' => true,
        'cdn' => false,
    ],

    'seo' => [
        'meta_tags' => true,
        'open_graph' => true,
        'twitter_cards' => true,
        'structured_data' => true,
        'sitemap' => true,
    ],

    'social' => [
        'facebook' => '',
        'twitter' => '',
        'instagram' => '',
        'youtube' => '',
        'linkedin' => '',
    ],

    'contact' => [
        'email' => 'info@comune.it',
        'phone' => '000-0000000',
        'address' => 'Via, 1',
        'city' => 'Nome Comune',
        'cap' => '00000',
        'province' => 'Provincia',
        'region' => 'Regione',
    ],

    'opening_hours' => [
        'monday_friday' => '8:30 - 12:30',
        'tuesday_thursday' => '14:30 - 16:30',
        'saturday' => '8:30 - 12:30',
        'sunday' => 'Chiuso',
    ],

    'services' => [
        'anagrafe' => [
            'name' => 'Anagrafe',
            'description' => 'Servizi anagrafici e stato civile',
            'icon' => 'user',
            'enabled' => true,
            'url' => '/comune/anagrafe',
        ],
        'tributi' => [
            'name' => 'Tributi',
            'description' => 'Pagamento tasse e tributi comunali',
            'icon' => 'credit-card',
            'enabled' => true,
            'url' => '/comune/tributi',
        ],
        'urbanistica' => [
            'name' => 'Urbanistica',
            'description' => 'Pratiche edilizie e urbanistiche',
            'icon' => 'building',
            'enabled' => true,
            'url' => '/comune/urbanistica',
        ],
        'sociale' => [
            'name' => 'Sociale',
            'description' => 'Servizi sociali e assistenziali',
            'icon' => 'heart',
            'enabled' => true,
            'url' => '/comune/sociale',
        ],
        'cultura' => [
            'name' => 'Cultura',
            'description' => 'Eventi culturali e biblioteca',
            'icon' => 'book',
            'enabled' => true,
            'url' => '/comune/cultura',
        ],
        'ambiente' => [
            'name' => 'Ambiente',
            'description' => 'Servizi ambientali e rifiuti',
            'icon' => 'leaf',
            'enabled' => true,
            'url' => '/comune/ambiente',
        ],
    ],

    'integrations' => [
        'fixcity' => [
            'enabled' => true,
            'api_url' => '/api/fixcity',
            'map_integration' => true,
            'notifications' => true,
        ],
        'google_maps' => [
            'enabled' => false,
            'api_key' => '',
        ],
        'google_analytics' => [
            'enabled' => false,
            'tracking_id' => '',
        ],
        'facebook_pixel' => [
            'enabled' => false,
            'pixel_id' => '',
        ],
    ],

    'debug' => [
        'enabled' => env('APP_DEBUG', false),
        'log_queries' => false,
        'log_views' => false,
        'log_assets' => false,
    ],
];
