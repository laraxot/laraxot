# Notify

[![Module](https://img.shields.io/badge/Module-Notify-8B0000.svg)]()
[![Laravel](https://img.shields.io/badge/Laravel-13-red?style=for-the-badge)](https://laravel.com/)](https://laravel.com/)
[![Filament](https://img.shields.io/badge/Filament-5-ffab00?style=for-the-badge)](https://filamentphp.com/)](https://filamentphp.com/)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge)](https://php.net/)](https://php.net/)
[![PHP](https://img.shields.io/badge/PHP-8.4+-777BB4?style=for-the-badge)](https://php.net/)](https://phpstan.org/)
[![PSR-12](https://img.shields.io/badge/Code-PSR--12-blue?style=for-the-badge)](https://www.php-fig.org/psr/psr-12/)](https://www.php-fig.org/psr/psr-12/)
[![Architecture](https://img.shields.io/badge/Architecture-Modular-purple?style=for-the-badge)](https://martinfowler.com/articles/paradigm-shifts.html)]()
]()

> **Core module for the FixCity Platform.**

## Perché esiste

Core module for the FixCity Platform.

## Superpoteri

- Modular component with XotBase patterns
- Professional-grade implementation
- Integrated with FixCity Platform

## Documentazione

<<<<<<< HEAD
Grazie,<br>
{{ config('app.name') }}
@endcomponent
```

## Editor Visuale

### Integrazione GrapesJS
```php
// app/Filament/Resources/EmailTemplateResource.php
use Filament\Forms\Components\Builder;

public static function form(\Filament\Schemas\Schema $form): \Filament\Schemas\Schema
{
    return $form->schema([
        Builder::make('content')
            ->blocks([
                Builder\Block::make('text')
                    ->schema([
                        Forms\Components\RichEditor::make('content')
                            ->required()
                    ]),
                Builder\Block::make('image')
                    ->schema([
                        Forms\Components\FileUpload::make('image')
                            ->required()
                    ]),
            ])
    ]);
}
```

## Personalizzazione

### Variabili Template
```php
// app/Notifications/WelcomeNotification.php
public function toMail($notifiable)
{
    return (new MailMessage)
        ->subject('Benvenuto {name}')
        ->greeting('Ciao {name}')
        ->line('Benvenuto in {app_name}')
        ->action('Accedi', $this->loginUrl)
        ->line('Grazie per esserti registrato!')
        ->with([
            'name' => $notifiable->name,
            'app_name' => config('app.name'),
        ]);
}
```

### Stili Personalizzati
```css
/* resources/css/email.css */
.email-container {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
}

.email-header {
    text-align: center;
    padding: 20px 0;
}

.email-footer {
    text-align: center;
    padding: 20px 0;
    font-size: 12px;
    color: #666;
}
```

## Best Practices

### 1. Struttura Template
- Utilizzare layout responsive
- Mantenere stili inline
- Testare su diversi client
- Supportare modalità testo

### 2. Performance
- Ottimizzare immagini
- Minimizzare CSS
- Utilizzare CDN
- Implementare cache

### 3. Accessibilità
- Contrasto adeguato
- Test screen reader
- Tag semantici
- Alt text immagini

## Note
- Tutti i collegamenti sono relativi
- La documentazione è mantenuta in italiano
- I collegamenti sono bidirezionali quando appropriato
- Ogni sezione ha il suo README.md specifico

## Contribuire
Per contribuire alla documentazione, seguire le [Linee Guida](../../../../../docs/linee-guida-documentazione.md) e le [Regole dei Collegamenti](../../../../../docs/regole_collegamenti_documentazione.md).

## Collegamenti Completi
Per una lista completa di tutti i collegamenti tra i README.md, consultare il file [README_links.md](../../../../../docs/readme_links.md). 
<<<<<<< HEAD
<<<<<<< HEAD
=======

>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
---

<!-- Merged from readme.md, which collided with this file on case-insensitive filesystems. -->
=======
| Lingua | Link |
|--------|------|
| 🇮🇹 Presentazione | Questo file (`README.md`) |
| 🇬🇧 Business card | [docs/readme-en.md](./docs/readme-en.md) |
| 📚 Wiki tecnica | [./docs/wiki/](./docs/) |
>>>>>>> d822d97f (.)

---

**Modulo** `Notify` · **Laraxot** · **FixCity Platform** · PHPStan 10 · Filament 5
