<?php

declare(strict_types=1);

/*
 * Solo valori di default: niente env() in questo file. Larastan vieta env()
 * fuori dalla config/ di root (stessa scelta di Modules/Catalog/config/metel.php,
 * Modules/Wts/config/wts.php e altri Modules/*\/config/*.php). Questo file viene
 * caricato da ThemeServiceProvider::loadConfigFrom() via mergeConfigFrom(), quindi
 * non e' sotto config_path() e Larastan lo tratta come codice applicativo.
 * Per override via .env servirebbe un overlay nel ThemeServiceProvider (stesso
 * pattern di CatalogServiceProvider::applyMetelEnvOverrides(), che usa
 * Illuminate\Support\Env::get() invece della funzione env()) — fuori scope qui:
 * il task copre solo i 4 file di config, non i Providers.
 */
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
    'environment' => 'preprod',

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
    'client_id' => null,
    'client_secret' => null,

    /*
    |--------------------------------------------------------------------------
    | Redirect URIs
    |--------------------------------------------------------------------------
    |
    | URL di callback per l'autenticazione CIE
    |
    */
    'redirect_uris' => [
        'callback' => route('cie.callback'),
        'logout' => route('home'),
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
    'default_level' => 'level_2',
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
        'enabled' => true,
        'app_scheme' => 'cieid',
        'universal_link' => 'https://www.cartaidentita.interno.gov.it/cie-id',
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
        'timeout' => 3600, // 1 ora
        'extend_on_activity' => true,
        'remember_me' => false,
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
        'require_https' => config('app.env') === 'production',
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes Configuration
    |--------------------------------------------------------------------------
    */
    'routes' => [
        'prefix' => 'auth/cie',
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
        'jwks_uri' => null, // URL dei certificati pubblici CIE
        'cache_ttl' => 3600, // Cache TTL per i certificati (1 ora)
        'local_cert_path' => storage_path('certificates/cie/'), // Path locale certificati
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    */
    'logging' => [
        'enabled' => true,
        'level' => 'info',
        'channel' => 'cie',
        'log_tokens' => false, // ATTENZIONE: Non abilitare in produzione
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
        'redirect_on_error' => true,
        'error_route' => 'login',
        'show_technical_errors' => config('app.debug'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Integration Settings
    |--------------------------------------------------------------------------
    */
    'integration' => [
        'auto_create_user' => true,
        'update_user_on_login' => true,
        'sync_attributes' => true,
        'required_attributes' => ['fiscal_code'], // Attributi obbligatori per la registrazione
    ],

    /*
    |--------------------------------------------------------------------------
    | Development & Testing
    |--------------------------------------------------------------------------
    */
    'development' => [
        'mock_responses' => false,
        'test_user' => [
            'enabled' => config('app.env') !== 'production',
            'fiscal_code' => 'RSSMRA80A01H501U',
            'name' => 'Mario',
            'surname' => 'Rossi',
            'email' => 'mario.rossi@example.com',
        ],
        'bypass_signature_validation' => config('app.env') !== 'production',
    ],

    /*
    |--------------------------------------------------------------------------
    | Compliance Settings
    |--------------------------------------------------------------------------
    */
    'compliance' => [
        'agid_compliant' => true,
        'gdpr_compliant' => true,
        'privacy_policy_url' => '/privacy',
        'terms_of_service_url' => '/terms',
        'data_retention_days' => 365,
    ],
];
