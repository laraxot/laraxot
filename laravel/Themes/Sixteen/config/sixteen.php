<?php

declare(strict_types=1);
use Themes\Sixteen\Filters\ActiveMenuFilter;
use Themes\Sixteen\Filters\GateMenuFilter;
use Themes\Sixteen\Filters\HrefMenuFilter;

return [
    /*
    |--------------------------------------------------------------------------
    | Sixteen Theme Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration file per il tema Sixteen Bootstrap Italia
    | Basato sul tema ufficiale italia/design-laravel-theme
    |
    */

    /*
    |--------------------------------------------------------------------------
    | App Information
    |--------------------------------------------------------------------------
    */
    'app' => [
        'name' => env('SIXTEEN_APP_NAME', config('app.name', 'Applicazione PA')),
        'tagline' => env('SIXTEEN_TAGLINE', 'Servizi digitali per i cittadini'),
        'description' => env('SIXTEEN_DESCRIPTION', 'Ente di appartenenza'),
        'version' => env('SIXTEEN_VERSION', '1.0.0'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Brand Configuration
    |--------------------------------------------------------------------------
    */
    'brand' => [
        'logo' => [
            'type' => env('SIXTEEN_LOGO_TYPE', 'icon'), // 'icon', 'image', 'text'
            'source' => env('SIXTEEN_LOGO_SOURCE', 'heroicon-o-building-office'),
            'alt' => env('SIXTEEN_LOGO_ALT', 'Logo istituzionale'),
            'width' => env('SIXTEEN_LOGO_WIDTH', 40),
            'height' => env('SIXTEEN_LOGO_HEIGHT', 40),
        ],
        'colors' => [
            'primary' => env('SIXTEEN_PRIMARY_COLOR', '#0066CC'),
            'secondary' => env('SIXTEEN_SECONDARY_COLOR', '#5A6772'),
            'success' => env('SIXTEEN_SUCCESS_COLOR', '#00B373'),
            'warning' => env('SIXTEEN_WARNING_COLOR', '#FFB400'),
            'danger' => env('SIXTEEN_DANGER_COLOR', '#D9364F'),
        ],
        'favicon' => env('SIXTEEN_FAVICON', '/favicon.ico'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Layout Configuration
    |--------------------------------------------------------------------------
    */
    'layout' => [
        'slim_header' => [
            'enabled' => env('SIXTEEN_SLIM_HEADER', true),
            'light_theme' => env('SIXTEEN_SLIM_HEADER_LIGHT', false),
            'show_owner' => env('SIXTEEN_SHOW_OWNER', true),
        ],
        'header' => [
            'small' => env('SIXTEEN_SMALL_HEADER', true),
            'sticky' => env('SIXTEEN_STICKY_HEADER', true),
            'show_search' => env('SIXTEEN_SHOW_SEARCH', true),
            'show_social' => env('SIXTEEN_SHOW_SOCIAL', true),
        ],
        'footer' => [
            'show_social' => env('SIXTEEN_FOOTER_SHOW_SOCIAL', true),
            'show_newsletter' => env('SIXTEEN_SHOW_NEWSLETTER', false),
            'show_contacts' => env('SIXTEEN_SHOW_CONTACTS', true),
            'show_address' => env('SIXTEEN_SHOW_ADDRESS', true),
        ],
        'breadcrumbs' => [
            'enabled' => env('SIXTEEN_BREADCRUMBS', true),
            'show_home' => env('SIXTEEN_BREADCRUMBS_HOME', true),
            'separator' => env('SIXTEEN_BREADCRUMBS_SEPARATOR', '/'),
        ],
        'back_to_top' => env('SIXTEEN_BACK_TO_TOP', true),
        'cookiebar' => env('SIXTEEN_COOKIEBAR', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Configuration
    |--------------------------------------------------------------------------
    */
    'auth' => [
        'enabled' => env('SIXTEEN_AUTH_ENABLED', true),
        'login' => [
            'route' => env('SIXTEEN_LOGIN_ROUTE', 'login'),
            'text' => 'Accedi',
            'icon' => 'heroicon-o-arrow-right-on-rectangle',
        ],
        'logout' => [
            'route' => env('SIXTEEN_LOGOUT_ROUTE', 'logout'),
            'method' => env('SIXTEEN_LOGOUT_METHOD', 'post'),
            'text' => 'Esci',
            'icon' => 'heroicon-o-arrow-left-on-rectangle',
        ],
        'register' => [
            'enabled' => env('SIXTEEN_REGISTER_ENABLED', true),
            'route' => env('SIXTEEN_REGISTER_ROUTE', 'register'),
            'text' => 'Registrati',
        ],
        'spid' => [
            'enabled' => env('SIXTEEN_SPID_ENABLED', false),
            'button_size' => env('SIXTEEN_SPID_BUTTON_SIZE', 'medium'), // small, medium, large
            'route' => env('SIXTEEN_SPID_ROUTE', 'spid.login'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes Configuration
    |--------------------------------------------------------------------------
    */
    'routes' => [
        'home' => [
            'route' => env('SIXTEEN_HOME_ROUTE', 'home'),
            'url' => env('SIXTEEN_HOME_URL', '/'),
        ],
        'search' => [
            'enabled' => env('SIXTEEN_SEARCH_ENABLED', true),
            'route' => env('SIXTEEN_SEARCH_ROUTE', 'search'),
            'placeholder' => 'Cerca nel sito...',
        ],
        'newsletter' => [
            'enabled' => env('SIXTEEN_NEWSLETTER_ENABLED', false),
            'route' => env('SIXTEEN_NEWSLETTER_ROUTE', 'newsletter'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Configuration
    |--------------------------------------------------------------------------
    */
    'menu' => [
        'slim_header' => [
            [
                'text' => 'Amministrazione Trasparente',
                'url' => '/amministrazione-trasparente',
                'icon' => 'heroicon-o-eye',
            ],
            [
                'text' => 'Contatti',
                'url' => '/contatti',
                'icon' => 'heroicon-o-phone',
            ],
        ],

        'header' => [
            [
                'text' => 'Home',
                'route' => 'home',
                'icon' => 'heroicon-o-home',
                'active_urls' => ['/', 'home'],
            ],
            [
                'text' => 'Servizi',
                'url' => '/servizi',
                'icon' => 'heroicon-o-cog-6-tooth',
                'dropdown' => [
                    [
                        'text' => 'Certificati anagrafici',
                        'url' => '/servizi/certificati',
                        'icon' => 'heroicon-o-document-text',
                    ],
                    [
                        'text' => 'Tributi e pagamenti',
                        'url' => '/servizi/tributi',
                        'icon' => 'heroicon-o-credit-card',
                    ],
                    [
                        'text' => 'Pratiche edilizie',
                        'url' => '/servizi/pratiche-edilizie',
                        'icon' => 'heroicon-o-building-office-2',
                    ],
                    '-',
                    [
                        'text' => 'Tutti i servizi',
                        'url' => '/servizi',
                        'icon' => 'heroicon-o-squares-2x2',
                    ],
                ],
            ],
            [
                'text' => 'Amministrazione',
                'url' => '/amministrazione',
                'icon' => 'heroicon-o-user-group',
                'megamenu' => [
                    [
                        'Organi di Governo',
                        [
                            'text' => 'Sindaco',
                            'url' => '/amministrazione/sindaco',
                            'icon' => 'heroicon-o-user',
                        ],
                        [
                            'text' => 'Giunta Comunale',
                            'url' => '/amministrazione/giunta',
                            'icon' => 'heroicon-o-users',
                        ],
                        [
                            'text' => 'Consiglio Comunale',
                            'url' => '/amministrazione/consiglio',
                            'icon' => 'heroicon-o-building-library',
                        ],
                    ],
                    [
                        'Uffici e Servizi',
                        [
                            'text' => 'Segreteria',
                            'url' => '/uffici/segreteria',
                            'icon' => 'heroicon-o-clipboard-document-list',
                        ],
                        [
                            'text' => 'Ufficio Tributi',
                            'url' => '/uffici/tributi',
                            'icon' => 'heroicon-o-calculator',
                        ],
                        [
                            'text' => 'Anagrafe',
                            'url' => '/uffici/anagrafe',
                            'icon' => 'heroicon-o-identification',
                        ],
                        [
                            'text' => 'URP',
                            'url' => '/uffici/urp',
                            'icon' => 'heroicon-o-chat-bubble-left-right',
                        ],
                    ],
                ],
            ],
            [
                'text' => 'Notizie',
                'url' => '/notizie',
                'icon' => 'heroicon-o-newspaper',
            ],
            [
                'text' => 'Eventi',
                'url' => '/eventi',
                'icon' => 'heroicon-o-calendar-days',
            ],
        ],

        'footer' => [
            [
                [
                    'text' => 'Amministrazione',
                    'url' => '/amministrazione',
                ],
                [
                    'text' => 'Sindaco',
                    'url' => '/amministrazione/sindaco',
                ],
                [
                    'text' => 'Giunta e Consiglio',
                    'url' => '/amministrazione/organi',
                ],
                [
                    'text' => 'Aree tematiche',
                    'url' => '/aree-tematiche',
                ],
                [
                    'text' => 'Documenti e dati',
                    'url' => '/documenti',
                ],
            ],
            [
                [
                    'text' => 'Servizi',
                    'url' => '/servizi',
                ],
                [
                    'text' => 'Certificati',
                    'url' => '/servizi/certificati',
                ],
                [
                    'text' => 'Pratiche online',
                    'url' => '/servizi/pratiche',
                ],
                [
                    'text' => 'Pagamenti',
                    'url' => '/servizi/pagamenti',
                ],
                [
                    'text' => 'Segnalazioni',
                    'url' => '/servizi/segnalazioni',
                ],
            ],
            [
                [
                    'text' => 'Vivere il comune',
                    'url' => '/vivere-comune',
                ],
                [
                    'text' => 'Luoghi',
                    'url' => '/luoghi',
                ],
                [
                    'text' => 'Eventi',
                    'url' => '/eventi',
                ],
                [
                    'text' => 'Associazioni',
                    'url' => '/associazioni',
                ],
                [
                    'text' => 'Trasporti',
                    'url' => '/trasporti',
                ],
            ],
        ],

        'footer_bar' => [
            [
                'text' => 'Privacy policy',
                'url' => '/privacy',
            ],
            [
                'text' => 'Note legali',
                'url' => '/note-legali',
            ],
            [
                'text' => 'Dichiarazione di accessibilità',
                'url' => '/accessibilita',
            ],
            [
                'text' => 'Mappa del sito',
                'url' => '/mappa-sito',
            ],
            [
                'text' => 'RSS',
                'url' => '/feed',
                'external' => true,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Contact Information
    |--------------------------------------------------------------------------
    */
    'contact' => [
        'address' => env('SIXTEEN_ADDRESS', 'Via Roma 1<br>00100 Roma (RM)'),
        'phone' => env('SIXTEEN_PHONE', '+39 06 12345678'),
        'fax' => env('SIXTEEN_FAX'),
        'email' => env('SIXTEEN_EMAIL', 'info@comune.esempio.it'),
        'pec' => env('SIXTEEN_PEC', 'protocollo@pec.comune.esempio.it'),
        'cf_piva' => env('SIXTEEN_CF_PIVA', '12345678901'),
        'ipa_code' => env('SIXTEEN_IPA_CODE', 'c_a123'),
        'urp' => [
            'enabled' => env('SIXTEEN_URP_ENABLED', true),
            'phone' => env('SIXTEEN_URP_PHONE'),
            'email' => env('SIXTEEN_URP_EMAIL'),
            'hours' => env('SIXTEEN_URP_HOURS', 'Lun-Ven: 9:00-12:00, 15:00-17:00'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Media Links
    |--------------------------------------------------------------------------
    */
    'social' => [
        'facebook' => env('SIXTEEN_FACEBOOK'),
        'twitter' => env('SIXTEEN_TWITTER'),
        'youtube' => env('SIXTEEN_YOUTUBE'),
        'instagram' => env('SIXTEEN_INSTAGRAM'),
        'linkedin' => env('SIXTEEN_LINKEDIN'),
        'telegram' => env('SIXTEEN_TELEGRAM'),
        'whatsapp' => env('SIXTEEN_WHATSAPP'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Filtri applicati agli elementi del menu prima del rendering
    |
    */
    'menu_filters' => [
        HrefMenuFilter::class,
        ActiveMenuFilter::class,
        GateMenuFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Integrations
    |--------------------------------------------------------------------------
    */
    'integrations' => [
        'analytics' => [
            'google_tag_id' => env('SIXTEEN_GOOGLE_TAG_ID'),
            'matomo_url' => env('SIXTEEN_MATOMO_URL'),
            'matomo_site_id' => env('SIXTEEN_MATOMO_SITE_ID'),
        ],
        'maps' => [
            'provider' => env('SIXTEEN_MAPS_PROVIDER', 'osm'), // 'google', 'osm', 'mapbox'
            'api_key' => env('SIXTEEN_MAPS_API_KEY'),
            'default_zoom' => env('SIXTEEN_MAPS_ZOOM', 15),
        ],
        'recaptcha' => [
            'enabled' => env('SIXTEEN_RECAPTCHA_ENABLED', false),
            'site_key' => env('SIXTEEN_RECAPTCHA_SITE_KEY'),
            'secret_key' => env('SIXTEEN_RECAPTCHA_SECRET_KEY'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Settings
    |--------------------------------------------------------------------------
    */
    'performance' => [
        'cdn_enabled' => env('SIXTEEN_CDN_ENABLED', false),
        'lazy_loading' => env('SIXTEEN_LAZY_LOADING', true),
        'preload_critical_css' => env('SIXTEEN_PRELOAD_CSS', true),
        'minify_html' => env('SIXTEEN_MINIFY_HTML', false),
        'cache_menu' => env('SIXTEEN_CACHE_MENU', true),
        'cache_ttl' => env('SIXTEEN_CACHE_TTL', 3600), // secondi
    ],

    /*
    |--------------------------------------------------------------------------
    | SEO Configuration
    |--------------------------------------------------------------------------
    */
    'seo' => [
        'meta' => [
            'description' => env('SIXTEEN_META_DESCRIPTION', 'Sito ufficiale del Comune - Servizi digitali per i cittadini'),
            'keywords' => env('SIXTEEN_META_KEYWORDS', 'comune, servizi, cittadini, pubblica amministrazione'),
            'author' => env('SIXTEEN_META_AUTHOR', 'Comune'),
            'robots' => env('SIXTEEN_META_ROBOTS', 'index, follow'),
        ],
        'og' => [
            'enabled' => env('SIXTEEN_OG_ENABLED', true),
            'image' => env('SIXTEEN_OG_IMAGE', '/images/og-image.jpg'),
            'locale' => env('SIXTEEN_OG_LOCALE', 'it_IT'),
        ],
        'schema' => [
            'enabled' => env('SIXTEEN_SCHEMA_ENABLED', true),
            'organization_type' => env('SIXTEEN_SCHEMA_ORG_TYPE', 'GovernmentOrganization'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Accessibility
    |--------------------------------------------------------------------------
    */
    'accessibility' => [
        'skip_links' => env('SIXTEEN_SKIP_LINKS', true),
        'high_contrast' => env('SIXTEEN_HIGH_CONTRAST', false),
        'font_size_controls' => env('SIXTEEN_FONT_SIZE_CONTROLS', false),
        'keyboard_navigation' => env('SIXTEEN_KEYBOARD_NAV', true),
        'screen_reader_content' => env('SIXTEEN_SCREEN_READER', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Development & Debug
    |--------------------------------------------------------------------------
    */
    'debug' => [
        'show_menu_debug' => env('SIXTEEN_DEBUG_MENU', false),
        'show_component_info' => env('SIXTEEN_DEBUG_COMPONENTS', false),
        'log_menu_build' => env('SIXTEEN_LOG_MENU', false),
    ],
];
