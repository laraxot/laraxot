<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | CIE Configuration
    |--------------------------------------------------------------------------
    |
    | Configurazione per l'integrazione CIE (Carta di Identità Elettronica)
    | secondo le specifiche AGID per l'identità digitale
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Environment
    |--------------------------------------------------------------------------
    |
    | Ambiente CIE da utilizzare: preprod (pre-produzione) o prod (produzione)
    |
    */
    'environment' => env('CIE_ENVIRONMENT', 'preprod'),

    /*
    |--------------------------------------------------------------------------
    | Base URLs per ambienti
    |--------------------------------------------------------------------------
    */
    'urls' => [
        'preprod' => [
            'base_url' => 'https://preprod.idserver.servizicie.interno.gov.it/idp',
            'federation_url' => 'https://preprod.idserver.servizicie.interno.gov.it/idp/federation',
        ],
        'prod' => [
            'base_url' => 'https://idserver.servizicie.interno.gov.it/idp',
            'federation_url' => 'https://idserver.servizicie.interno.gov.it/idp/federation',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | OAuth2 Client Configuration
    |--------------------------------------------------------------------------
    |
    | Credenziali OAuth2 per l'integrazione CIE
    | Ottenibili dal portale sviluppatori CIE
    |
    */
    'client_id' => env('CIE_CLIENT_ID'),
    'client_secret' => env('CIE_CLIENT_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Redirect URIs
    |--------------------------------------------------------------------------
    |
    | URL di callback per l'autenticazione CIE
    |
    */
    'redirect_uris' => [
        'callback' => env('CIE_REDIRECT_URI', route('cie.callback')),
        'logout' => env('CIE_LOGOUT_REDIRECT_URI', route('home')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Scopes OIDC
    |--------------------------------------------------------------------------
    |
    | Scope OpenID Connect richiesti
    |
    */
    'scopes' => [
        'openid',           // Obbligatorio per OIDC
        'profile',          // Informazioni profilo utente
        'email',            // Indirizzo email
        'offline_access',   // Refresh token per sessioni prolungate
    ],

    /*
    |--------------------------------------------------------------------------
    | ACR Values
    |--------------------------------------------------------------------------
    |
    | Authentication Context Class Reference per livelli CIE
    |
    */
    'acr_values' => [
        'level_2' => 'https://www.spid.gov.it/SpidL2',
        'level_3' => 'https://www.spid.gov.it/SpidL3',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Settings
    |--------------------------------------------------------------------------
    */
    'default_level' => env('CIE_DEFAULT_LEVEL', 'level_2'),
    'prompt' => 'login', // Forza sempre l'autenticazione
    'response_type' => 'code',
    'response_mode' => 'form_post',

    /*
    |--------------------------------------------------------------------------
    | Mobile App Integration
    |--------------------------------------------------------------------------
    |
    | Configurazione per l'integrazione con l'app CieID mobile
    |
    */
    'mobile' => [
        'enabled' => env('CIE_MOBILE_ENABLED', true),
        'app_scheme' => 'cieid',
        'universal_link' => env('CIE_UNIVERSAL_LINK', 'https://www.cartaidentita.interno.gov.it/cie-id'),
        'deep_link_timeout' => 10, // secondi
        'fallback_to_web' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Token Configuration
    |--------------------------------------------------------------------------
    */
    'token' => [
        'validation' => [
            'verify_signature' => true,
            'verify_audience' => true,
            'verify_issuer' => true,
            'verify_expiry' => true,
            'verify_nonce' => true,
        ],
        'algorithms' => ['RS256', 'RS384', 'RS512'], // Algoritmi JWT supportati
        'leeway' => 60, // Tolleranza per timestamp (secondi)
    ],

    /*
    |--------------------------------------------------------------------------
    | User Attributes Mapping
    |--------------------------------------------------------------------------
    |
    | Mappatura degli attributi CIE ai campi applicazione
    |
    */
    'user_mapping' => [
        'id' => 'sub',
        'name' => 'given_name',
        'surname' => 'family_name',
        'full_name' => 'name',
        'fiscal_code' => 'fiscal_number',
        'email' => 'email',
        'email_verified' => 'email_verified',
        'phone' => 'phone_number',
        'phone_verified' => 'phone_number_verified',
        'birth_date' => 'birthdate',
        'birth_place' => 'place_of_birth',
        'gender' => 'gender',
        'address' => 'address',
        'locale' => 'locale',
        'timezone' => 'zoneinfo',
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Configuration
    |--------------------------------------------------------------------------
    */
    'session' => [
        'timeout' => env('CIE_SESSION_TIMEOUT', 3600), // 1 ora
        'extend_on_activity' => env('CIE_EXTEND_SESSION', true),
        'remember_me' => env('CIE_REMEMBER_ME', false),
        'max_remember_duration' => 86400 * 30, // 30 giorni
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    */
    'security' => [
        'state_length' => 64,       // Lunghezza state parameter
        'nonce_length' => 64,       // Lunghezza nonce
        'pkce_enabled' => true,     // Proof Key for Code Exchange
        'pkce_method' => 'S256',    // Challenge method
        'require_https' => env('CIE_REQUIRE_HTTPS', env('APP_ENV') === 'production'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes Configuration
    |--------------------------------------------------------------------------
    */
    'routes' => [
        'prefix' => env('CIE_ROUTES_PREFIX', 'auth/cie'),
        'middleware' => ['web'],
        'names' => [
            'login' => 'cie.login',
            'mobile' => 'cie.mobile',
            'callback' => 'cie.callback',
            'logout' => 'cie.logout',
            'refresh' => 'cie.refresh',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Certificates and Keys
    |--------------------------------------------------------------------------
    |
    | Certificati per la validazione delle signature JWT
    |
    */
    'certificates' => [
        'jwks_uri' => env('CIE_JWKS_URI'), // URL dei certificati pubblici CIE
        'cache_ttl' => 3600, // Cache TTL per i certificati (1 ora)
        'local_cert_path' => storage_path('certificates/cie/'), // Path locale certificati
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    */
    'logging' => [
        'enabled' => env('CIE_LOGGING_ENABLED', true),
        'level' => env('CIE_LOGGING_LEVEL', 'info'),
        'channel' => env('CIE_LOGGING_CHANNEL', 'cie'),
        'log_tokens' => env('CIE_LOG_TOKENS', false), // ATTENZIONE: Non abilitare in produzione
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'prefix' => 'cie:',
        'discovery_ttl' => 3600,    // Cache discovery document (1 ora)
        'jwks_ttl' => 3600,         // Cache JWKS (1 ora)
        'userinfo_ttl' => 300,      // Cache user info (5 minuti)
    ],

    /*
    |--------------------------------------------------------------------------
    | Error Handling
    |--------------------------------------------------------------------------
    */
    'errors' => [
        'redirect_on_error' => env('CIE_REDIRECT_ON_ERROR', true),
        'error_route' => env('CIE_ERROR_ROUTE', 'login'),
        'show_technical_errors' => env('CIE_SHOW_TECHNICAL_ERRORS', env('APP_DEBUG', false)),
    ],

    /*
    |--------------------------------------------------------------------------
    | Integration Settings
    |--------------------------------------------------------------------------
    */
    'integration' => [
        'auto_create_user' => env('CIE_AUTO_CREATE_USER', true),
        'update_user_on_login' => env('CIE_UPDATE_USER_ON_LOGIN', true),
        'sync_attributes' => env('CIE_SYNC_ATTRIBUTES', true),
        'required_attributes' => ['fiscal_code'], // Attributi obbligatori per la registrazione
    ],

    /*
    |--------------------------------------------------------------------------
    | Development & Testing
    |--------------------------------------------------------------------------
    */
    'development' => [
        'mock_responses' => env('CIE_MOCK_RESPONSES', false),
        'test_user' => [
            'enabled' => env('CIE_TEST_USER_ENABLED', env('APP_ENV') !== 'production'),
            'fiscal_code' => env('CIE_TEST_FISCAL_CODE', 'RSSMRA80A01H501U'),
            'name' => env('CIE_TEST_NAME', 'Mario'),
            'surname' => env('CIE_TEST_SURNAME', 'Rossi'),
            'email' => env('CIE_TEST_EMAIL', 'mario.rossi@example.com'),
        ],
        'bypass_signature_validation' => env('CIE_BYPASS_SIGNATURE', env('APP_ENV') !== 'production'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Compliance Settings
    |--------------------------------------------------------------------------
    */
    'compliance' => [
        'agid_compliant' => true,
        'gdpr_compliant' => true,
        'privacy_policy_url' => env('CIE_PRIVACY_POLICY_URL', '/privacy'),
        'terms_of_service_url' => env('CIE_TERMS_URL', '/terms'),
        'data_retention_days' => env('CIE_DATA_RETENTION', 365),
    ],
];
