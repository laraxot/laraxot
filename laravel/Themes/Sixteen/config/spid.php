<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | SPID Configuration
    |--------------------------------------------------------------------------
    |
    | Configurazione per l'integrazione SPID secondo le specifiche AGID
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Entity ID
    |--------------------------------------------------------------------------
    |
    | L'identificatore univoco del Service Provider (SP)
    | Generalmente corrisponde all'URL dell'applicazione
    |
    */
    'entity_id' => env('SPID_ENTITY_ID', config('app.url')),

    /*
    |--------------------------------------------------------------------------
    | Service Provider Information
    |--------------------------------------------------------------------------
    |
    | Informazioni del Service Provider per il metadata SAML
    |
    */
    'sp_info' => [
        'name' => env('SPID_SP_NAME', config('app.name')),
        'description' => env('SPID_SP_DESCRIPTION', 'Applicazione SPID-enabled'),
        'organization_name' => env('SPID_ORG_NAME', config('app.name')),
        'organization_display_name' => env('SPID_ORG_DISPLAY_NAME', config('app.name')),
        'organization_url' => env('SPID_ORG_URL', config('app.url')),
        'contact_email' => env('SPID_CONTACT_EMAIL', 'info@example.com'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Certificates
    |--------------------------------------------------------------------------
    |
    | Certificati per la firma e validazione SAML
    | In produzione, utilizzare certificati reali e sicuri
    |
    */
    'certificates' => [
        'signing_cert_path' => env('SPID_SIGNING_CERT_PATH', storage_path('certificates/spid/signing.crt')),
        'signing_key_path' => env('SPID_SIGNING_KEY_PATH', storage_path('certificates/spid/signing.key')),
        'encryption_cert_path' => env('SPID_ENCRYPTION_CERT_PATH', storage_path('certificates/spid/encryption.crt')),
        'encryption_key_path' => env('SPID_ENCRYPTION_KEY_PATH', storage_path('certificates/spid/encryption.key')),
    ],

    /*
    |--------------------------------------------------------------------------
    | SPID Identity Providers
    |--------------------------------------------------------------------------
    |
    | Configurazione dei provider SPID supportati
    | Aggiornare regolarmente in base alla registry SPID AGID
    |
    */
    'providers' => [
        'poste' => [
            'name' => 'Poste Italiane',
            'entityId' => 'https://posteid.poste.it',
            'sso_url' => env('SPID_POSTE_SSO', 'https://posteid.poste.it/jod-fs/ssoservicepost'),
            'slo_url' => env('SPID_POSTE_SLO', 'https://posteid.poste.it/jod-fs/sloservicepost'),
            'cert_file' => 'poste.crt',
            'logo' => 'spid-idp-posteid.svg',
            'logo_svg' => '<svg>...</svg>', // Logo SVG inline
            'active' => env('SPID_POSTE_ACTIVE', true),
        ],

        'sielte' => [
            'name' => 'Sielte',
            'entityId' => 'https://identity.sieltecloud.it',
            'sso_url' => env('SPID_SIELTE_SSO', 'https://identity.sieltecloud.it/simplesaml/saml2/idp/SSOService.php'),
            'slo_url' => env('SPID_SIELTE_SLO', 'https://identity.sieltecloud.it/simplesaml/saml2/idp/SingleLogoutService.php'),
            'cert_file' => 'sielte.crt',
            'logo' => 'spid-idp-sieltecloud.svg',
            'active' => env('SPID_SIELTE_ACTIVE', true),
        ],

        'tim' => [
            'name' => 'TIM Trust Technologies',
            'entityId' => 'https://login.id.tim.it/affwebservices/public/saml2sso',
            'sso_url' => env('SPID_TIM_SSO', 'https://login.id.tim.it/affwebservices/public/saml2sso'),
            'slo_url' => env('SPID_TIM_SLO', 'https://login.id.tim.it/affwebservices/public/saml2slo'),
            'cert_file' => 'tim.crt',
            'logo' => 'spid-idp-timid.svg',
            'active' => env('SPID_TIM_ACTIVE', true),
        ],

        'aruba' => [
            'name' => 'Aruba PEC',
            'entityId' => 'https://loginspid.aruba.it',
            'sso_url' => env('SPID_ARUBA_SSO', 'https://loginspid.aruba.it/ServiceLoginWelcome'),
            'slo_url' => env('SPID_ARUBA_SLO', 'https://loginspid.aruba.it/ServiceLogoutRequest'),
            'cert_file' => 'aruba.crt',
            'logo' => 'spid-idp-arubaid.svg',
            'active' => env('SPID_ARUBA_ACTIVE', true),
        ],

        'infocert' => [
            'name' => 'InfoCert',
            'entityId' => 'https://identity.infocert.it',
            'sso_url' => env('SPID_INFOCERT_SSO', 'https://identity.infocert.it/spid/samlsso'),
            'slo_url' => env('SPID_INFOCERT_SLO', 'https://identity.infocert.it/spid/samlslo'),
            'cert_file' => 'infocert.crt',
            'logo' => 'spid-idp-infocertid.svg',
            'active' => env('SPID_INFOCERT_ACTIVE', true),
        ],

        'lepida' => [
            'name' => 'Lepida',
            'entityId' => 'https://id.lepida.it/idp/shibboleth',
            'sso_url' => env('SPID_LEPIDA_SSO', 'https://id.lepida.it/idp/profile/SAML2/POST/SSO'),
            'slo_url' => env('SPID_LEPIDA_SLO', 'https://id.lepida.it/idp/profile/SAML2/POST/SLO'),
            'cert_file' => 'lepida.crt',
            'logo' => 'spid-idp-lepidaid.svg',
            'active' => env('SPID_LEPIDA_ACTIVE', true),
        ],

        'namirial' => [
            'name' => 'Namirial',
            'entityId' => 'https://idp.namirialtsp.com/idp',
            'sso_url' => env('SPID_NAMIRIAL_SSO', 'https://idp.namirialtsp.com/idp/profile/SAML2/POST/SSO'),
            'slo_url' => env('SPID_NAMIRIAL_SLO', 'https://idp.namirialtsp.com/idp/profile/SAML2/POST/SLO'),
            'cert_file' => 'namirial.crt',
            'logo' => 'spid-idp-namirialid.svg',
            'active' => env('SPID_NAMIRIAL_ACTIVE', true),
        ],

        'intesa' => [
            'name' => 'Intesa',
            'entityId' => 'https://spid.intesa.it',
            'sso_url' => env('SPID_INTESA_SSO', 'https://spid.intesa.it/Time4UserServices/services/idp/AuthnRequest/'),
            'slo_url' => env('SPID_INTESA_SLO', 'https://spid.intesa.it/Time4UserServices/services/idp/SingleLogout'),
            'cert_file' => 'intesa.crt',
            'logo' => 'spid-idp-intesaid.svg',
            'active' => env('SPID_INTESA_ACTIVE', true),
        ],

        'spiditalia' => [
            'name' => 'SPIDItalia Register.it',
            'entityId' => 'https://spid.register.it',
            'sso_url' => env('SPID_SPIDITALIA_SSO', 'https://spid.register.it/login/sso'),
            'slo_url' => env('SPID_SPIDITALIA_SLO', 'https://spid.register.it/login/slo'),
            'cert_file' => 'spiditalia.crt',
            'logo' => 'spid-idp-spiditalia.svg',
            'active' => env('SPID_SPIDITALIA_ACTIVE', true),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | SPID Levels
    |--------------------------------------------------------------------------
    |
    | Livelli SPID supportati dall'applicazione
    |
    */
    'levels' => [
        1 => 'https://www.spid.gov.it/SpidL1',
        2 => 'https://www.spid.gov.it/SpidL2',
        3 => 'https://www.spid.gov.it/SpidL3',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Settings
    |--------------------------------------------------------------------------
    */
    'default_level' => env('SPID_DEFAULT_LEVEL', 2),
    'default_binding' => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST',

    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    */
    'security' => [
        'name_id_encrypted' => false,
        'authn_requests_signed' => true,
        'logout_requests_signed' => true,
        'logout_responses_signed' => true,
        'sign_metadata' => true,
        'want_assertions_signed' => true,
        'want_name_id' => true,
        'want_assertions_encrypted' => false,
        'want_xml_validation' => true,
        'signature_algorithm' => 'http://www.w3.org/2001/04/xmldsig-more#rsa-sha256',
        'digest_algorithm' => 'http://www.w3.org/2001/04/xmlenc#sha256',
    ],

    /*
    |--------------------------------------------------------------------------
    | Requested Attributes
    |--------------------------------------------------------------------------
    |
    | Attributi SPID richiesti dall'applicazione
    |
    */
    'requested_attributes' => [
        'spidCode' => false,        // Codice identificativo SPID
        'name' => true,             // Nome (obbligatorio)
        'familyName' => true,       // Cognome (obbligatorio)
        'placeOfBirth' => false,    // Comune di nascita
        'countyOfBirth' => false,   // Provincia di nascita
        'dateOfBirth' => false,     // Data di nascita
        'gender' => false,          // Genere
        'companyName' => false,     // Ragione sociale
        'registeredOffice' => false, // Sede legale
        'fiscalNumber' => true,     // Codice fiscale (raccomandato)
        'ivaCode' => false,         // Partita IVA
        'idCard' => false,          // Documento di identità
        'mobilePhone' => false,     // Numero di cellulare
        'email' => false,           // Email
        'address' => false,         // Indirizzo
        'digitalAddress' => false,  // PEC
    ],

    /*
    |--------------------------------------------------------------------------
    | Session Settings
    |--------------------------------------------------------------------------
    */
    'session' => [
        'timeout' => env('SPID_SESSION_TIMEOUT', 3600), // 1 ora
        'remember_me' => env('SPID_REMEMBER_ME', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    */
    'routes' => [
        'prefix' => env('SPID_ROUTES_PREFIX', 'auth/spid'),
        'middleware' => ['web'],
        'login' => 'spid.login',
        'callback' => 'spid.callback',
        'logout' => 'spid.logout',
        'slo' => 'spid.slo',
        'metadata' => 'spid.metadata',
    ],

    /*
    |--------------------------------------------------------------------------
    | Test Environment
    |--------------------------------------------------------------------------
    |
    | Configurazione per l'ambiente di test SPID
    |
    */
    'test_environment' => [
        'enabled' => env('SPID_TEST_ENV', env('APP_ENV') !== 'production'),
        'demo_provider' => [
            'name' => 'SPID Test',
            'entityId' => 'https://demo.spid.gov.it',
            'sso_url' => 'https://demo.spid.gov.it/samlsso',
            'slo_url' => 'https://demo.spid.gov.it/samlslo',
            'cert_file' => 'demo.crt',
            'logo' => 'spid-idp-test.svg',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    */
    'logging' => [
        'enabled' => env('SPID_LOGGING_ENABLED', true),
        'level' => env('SPID_LOGGING_LEVEL', 'info'),
        'channel' => env('SPID_LOGGING_CHANNEL', 'spid'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'metadata_ttl' => env('SPID_METADATA_CACHE_TTL', 86400), // 24 ore
        'provider_metadata_ttl' => env('SPID_PROVIDER_METADATA_CACHE_TTL', 3600), // 1 ora
    ],
];
