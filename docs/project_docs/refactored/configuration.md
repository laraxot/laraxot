# Configurazione

## Panoramica

Il tema One può essere configurato attraverso vari file di configurazione. Questa documentazione descrive le opzioni disponibili e come configurarle.

## File di Configurazione

### 1. `config/theme.php`

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Nome del Tema
    |--------------------------------------------------------------------------
    |
    | Il nome del tema utilizzato nell'applicazione.
    |
    */
    'name' => 'One',

    /*
    |--------------------------------------------------------------------------
    | Versione del Tema
    |--------------------------------------------------------------------------
    |
    | La versione del tema utilizzata nell'applicazione.
    |
    */
    'version' => '1.0.0',

    /*
    |--------------------------------------------------------------------------
    | Provider del Tema
    |--------------------------------------------------------------------------
    |
    | Il provider del tema utilizzato nell'applicazione.
    |
    */
    'provider' => \Laraxot\ThemeOne\ThemeOneServiceProvider::class,

    /*
    |--------------------------------------------------------------------------
    | Asset del Tema
    |--------------------------------------------------------------------------
    |
    | Gli asset del tema utilizzati nell'applicazione.
    |
    */
    'assets' => [
        'css' => [
            'resources/css/app.css',
        ],
        'js' => [
            'resources/js/app.js',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Viste del Tema
    |--------------------------------------------------------------------------
    |
    | Le viste del tema utilizzate nell'applicazione.
    |
    */
    'views' => [
        'path' => 'resources/views',
        'namespace' => 'theme',
    ],

    /*
    |--------------------------------------------------------------------------
    | Componenti del Tema
    |--------------------------------------------------------------------------
    |
    | I componenti del tema utilizzati nell'applicazione.
    |
    */
    'components' => [
        'path' => 'resources/views/components',
        'namespace' => 'theme',
    ],

    /*
    |--------------------------------------------------------------------------
    | Layout del Tema
    |--------------------------------------------------------------------------
    |
    | Il layout del tema utilizzato nell'applicazione.
    |
    */
    'layout' => [
        'path' => 'resources/views/layouts',
        'namespace' => 'theme',
    ],

    /*
    |--------------------------------------------------------------------------
    | Blocchi del Tema
    |--------------------------------------------------------------------------
    |
    | I blocchi del tema utilizzati nell'applicazione.
    |
    */
    'blocks' => [
        'path' => 'resources/views/blocks',
        'namespace' => 'theme',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagine del Tema
    |--------------------------------------------------------------------------
    |
    | Le pagine del tema utilizzate nell'applicazione.
    |
    */
    'pages' => [
        'path' => 'resources/views/pages',
        'namespace' => 'theme',
    ],

    /*
    |--------------------------------------------------------------------------
    | Documentazione del Tema
    |--------------------------------------------------------------------------
    |
    | La documentazione del tema utilizzata nell'applicazione.
    |
    */
    'docs' => [
        'path' => 'docs',
        'namespace' => 'theme',
    ],
];
```

### 2. `config/filament.php`

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurazione Filament
    |--------------------------------------------------------------------------
    |
    | Configurazione di Filament per l'admin panel.
    |
    */
    'theme' => [
        'name' => 'One',
        'version' => '1.0.0',
        'provider' => \Laraxot\ThemeOne\ThemeOneServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Asset Filament
    |--------------------------------------------------------------------------
    |
    | Asset di Filament per l'admin panel.
    |
    */
    'assets' => [
        'css' => [
            'resources/css/filament.css',
        ],
        'js' => [
            'resources/js/filament.js',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Viste Filament
    |--------------------------------------------------------------------------
    |
    | Viste di Filament per l'admin panel.
    |
    */
    'views' => [
        'path' => 'resources/views/filament',
        'namespace' => 'filament',
    ],

    /*
    |--------------------------------------------------------------------------
    | Componenti Filament
    |--------------------------------------------------------------------------
    |
    | Componenti di Filament per l'admin panel.
    |
    */
    'components' => [
        'path' => 'resources/views/components/filament',
        'namespace' => 'filament',
    ],

    /*
    |--------------------------------------------------------------------------
    | Layout Filament
    |--------------------------------------------------------------------------
    |
    | Layout di Filament per l'admin panel.
    |
    */
    'layout' => [
        'path' => 'resources/views/layouts/filament',
        'namespace' => 'filament',
    ],

    /*
    |--------------------------------------------------------------------------
    | Blocchi Filament
    |--------------------------------------------------------------------------
    |
    | Blocchi di Filament per l'admin panel.
    |
    */
    'blocks' => [
        'path' => 'resources/views/blocks/filament',
        'namespace' => 'filament',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagine Filament
    |--------------------------------------------------------------------------
    |
    | Pagine di Filament per l'admin panel.
    |
    */
    'pages' => [
        'path' => 'resources/views/pages/filament',
        'namespace' => 'filament',
    ],

    /*
    |--------------------------------------------------------------------------
    | Documentazione Filament
    |--------------------------------------------------------------------------
    |
    | Documentazione di Filament per l'admin panel.
    |
    */
    'docs' => [
        'path' => 'docs/filament',
        'namespace' => 'filament',
    ],
];
```

### 3. `config/vite.php`

```php
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configurazione Vite
    |--------------------------------------------------------------------------
    |
    | Configurazione di Vite per il frontend.
    |
    */
    'theme' => [
        'name' => 'One',
        'version' => '1.0.0',
        'provider' => \Laraxot\ThemeOne\ThemeOneServiceProvider::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Asset Vite
    |--------------------------------------------------------------------------
    |
    | Asset di Vite per il frontend.
    |
    */
    'assets' => [
        'css' => [
            'resources/css/app.css',
        ],
        'js' => [
            'resources/js/app.js',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Viste Vite
    |--------------------------------------------------------------------------
    |
    | Viste di Vite per il frontend.
    |
    */
    'views' => [
        'path' => 'resources/views',
        'namespace' => 'theme',
    ],

    /*
    |--------------------------------------------------------------------------
    | Componenti Vite
    |--------------------------------------------------------------------------
    |
    | Componenti di Vite per il frontend.
    |
    */
    'components' => [
        'path' => 'resources/views/components',
        'namespace' => 'theme',
    ],

    /*
    |--------------------------------------------------------------------------
    | Layout Vite
    |--------------------------------------------------------------------------
    |
    | Layout di Vite per il frontend.
    |
    */
    'layout' => [
        'path' => 'resources/views/layouts',
        'namespace' => 'theme',
    ],

    /*
    |--------------------------------------------------------------------------
    | Blocchi Vite
    |--------------------------------------------------------------------------
    |
    | Blocchi di Vite per il frontend.
    |
    */
    'blocks' => [
        'path' => 'resources/views/blocks',
        'namespace' => 'theme',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagine Vite
    |--------------------------------------------------------------------------
    |
    | Pagine di Vite per il frontend.
    |
    */
    'pages' => [
        'path' => 'resources/views/pages',
        'namespace' => 'theme',
    ],

    /*
    |--------------------------------------------------------------------------
    | Documentazione Vite
    |--------------------------------------------------------------------------
    |
    | Documentazione di Vite per il frontend.
    |
    */
    'docs' => [
        'path' => 'docs/vite',
        'namespace' => 'vite',
    ],
];
```

## Opzioni di Configurazione

### 1. Tema

- `name`: Nome del tema
- `version`: Versione del tema
- `provider`: Provider del tema
- `assets`: Asset del tema
- `views`: Viste del tema
- `components`: Componenti del tema
- `layout`: Layout del tema
- `blocks`: Blocchi del tema
- `pages`: Pagine del tema
- `docs`: Documentazione del tema

### 2. Filament

- `theme`: Configurazione del tema
- `assets`: Asset di Filament
- `views`: Viste di Filament
- `components`: Componenti di Filament
- `layout`: Layout di Filament
- `blocks`: Blocchi di Filament
- `pages`: Pagine di Filament
- `docs`: Documentazione di Filament

### 3. Vite

- `theme`: Configurazione del tema
- `assets`: Asset di Vite
- `views`: Viste di Vite
- `components`: Componenti di Vite
- `layout`: Layout di Vite
- `blocks`: Blocchi di Vite
- `pages`: Pagine di Vite
- `docs`: Documentazione di Vite

## Personalizzazione

### 1. Tema

Per personalizzare il tema, modifica i file di configurazione in `config/theme.php`.

### 2. Filament

Per personalizzare Filament, modifica i file di configurazione in `config/filament.php`.

### 3. Vite

Per personalizzare Vite, modifica i file di configurazione in `config/vite.php`.

## Riferimenti

- [Laravel Documentation](https://laravel.com/docs)
- [Filament Documentation](https://filamentphp.com/docs)
- [Vite Documentation](https://vitejs.dev/guide)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs) 