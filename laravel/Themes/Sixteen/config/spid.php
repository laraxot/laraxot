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
    'entity_id' => config('app.url'),

    /*
    |--------------------------------------------------------------------------
    | Service Provider Information
    |--------------------------------------------------------------------------
    |
    | Informazioni del Service Provider per il metadata SAML
    |
    */
    'sp_info' => [
        'name' => config('app.name'),
        'description' => 'Applicazione SPID-enabled',
        'organization_name' => config('app.name'),
        'organization_display_name' => config('app.name'),
        'organization_url' => config('app.url'),
        'contact_email' => 'info@example.com',
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
        'signing_cert_path' => storage_path('certificates/spid/signing.crt'),
        'signing_key_path' => storage_path('certificates/spid/signing.key'),
        'encryption_cert_path' => storage_path('certificates/spid/encryption.crt'),
        'encryption_key_path' => storage_path('certificates/spid/encryption.key'),
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
            'sso_url' => 'https://posteid.poste.it/jod-fs/ssoservicepost',
            'slo_url' => 'https://posteid.poste.it/jod-fs/sloservicepost',
            'cert_file' => 'poste.crt',
            'logo' => 'spid-idp-posteid.svg',
            'logo_svg' => '<svg>...</svg>', // Logo SVG inline
            'active' => true,
        ],

        'sielte' => [
            'name' => 'Sielte',
            'entityId' => 'https://identity.sieltecloud.it',
            'sso_url' => 'https://identity.sieltecloud.it/simplesaml/saml2/idp/SSOService.php',
            'slo_url' => 'https://identity.sieltecloud.it/simplesaml/saml2/idp/SingleLogoutService.php',
            'cert_file' => 'sielte.crt',
            'logo' => 'spid-idp-sieltecloud.svg',
            'active' => true,
        ],

        'tim' => [
            'name' => 'TIM Trust Technologies',
            'entityId' => 'https://login.id.tim.it/affwebservices/public/saml2sso',
            'sso_url' => 'https://login.id.tim.it/affwebservices/public/saml2sso',
            'slo_url' => 'https://login.id.tim.it/affwebservices/public/saml2slo',
            'cert_file' => 'tim.crt',
            'logo' => 'spid-idp-timid.svg',
            'active' => true,
        ],

        'aruba' => [
            'name' => 'Aruba PEC',
            'entityId' => 'https://loginspid.aruba.it',
            'sso_url' => 'https://loginspid.aruba.it/ServiceLoginWelcome',
            'slo_url' => 'https://loginspid.aruba.it/ServiceLogoutRequest',
            'cert_file' => 'aruba.crt',
            'logo' => 'spid-idp-arubaid.svg',
            'active' => true,
        ],

        'infocert' => [
            'name' => 'InfoCert',
            'entityId' => 'https://identity.infocert.it',
            'sso_url' => 'https://identity.infocert.it/spid/samlsso',
            'slo_url' => 'https://identity.infocert.it/spid/samlslo',
            'cert_file' => 'infocert.crt',
            'logo' => 'spid-idp-infocertid.svg',
            'active' => true,
        ],

        'lepida' => [
            'name' => 'Lepida',
            'entityId' => 'https://id.lepida.it/idp/shibboleth',
            'sso_url' => 'https://id.lepida.it/idp/profile/SAML2/POST/SSO',
            'slo_url' => 'https://id.lepida.it/idp/profile/SAML2/POST/SLO',
            'cert_file' => 'lepida.crt',
            'logo' => 'spid-idp-lepidaid.svg',
            'active' => true,
        ],

        'namirial' => [
            'name' => 'Namirial',
            'entityId' => 'https://idp.namirialtsp.com/idp',
            'sso_url' => 'https://idp.namirialtsp.com/idp/profile/SAML2/POST/SSO',
            'slo_url' => 'https://idp.namirialtsp.com/idp/profile/SAML2/POST/SLO',
            'cert_file' => 'namirial.crt',
            'logo' => 'spid-idp-namirialid.svg',
            'active' => true,
        ],

        'intesa' => [
            'name' => 'Intesa',
            'entityId' => 'https://spid.intesa.it',
            'sso_url' => 'https://spid.intesa.it/Time4UserServices/services/idp/AuthnRequest/',
            'slo_url' => 'https://spid.intesa.it/Time4UserServices/services/idp/SingleLogout',
            'cert_file' => 'intesa.crt',
            'logo' => 'spid-idp-intesaid.svg',
            'active' => true,
        ],

        'spiditalia' => [
            'name' => 'SPIDItalia Register.it',
            'entityId' => 'https://spid.register.it',
            'sso_url' => 'https://spid.register.it/login/sso',
            'slo_url' => 'https://spid.register.it/login/slo',
            'cert_file' => 'spiditalia.crt',
            'logo' => 'spid-idp-spiditalia.svg',
            'active' => true,
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
    'default_level' => 2,
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
        'timeout' => 3600, // 1 ora
        'remember_me' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    */
    'routes' => [
        'prefix' => 'auth/spid',
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
        'enabled' => config('app.env') !== 'production',
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
        'enabled' => true,
        'level' => 'info',
        'channel' => 'spid',
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'metadata_ttl' => 86400, // 24 ore
        'provider_metadata_ttl' => 3600, // 1 ora
    ],
];
