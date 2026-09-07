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
    public function toArray($notifiable)
    {
        return [
            'type' => $this->getType(),
            'data' => $this->getData(),
        ];
    }
}
```

### Notifica Personalizzata
```php
// app/Notifications/AppointmentNotification.php
namespace App\Notifications;

class AppointmentNotification extends BaseNotification
{
    protected $appointment;

    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    public function getSubject()
    {
        return "Appuntamento {$this->appointment->date}";
    }

    public function getContent()
    {
        return "Hai un appuntamento il {$this->appointment->date} alle {$this->appointment->time}";
    }

    public function getActionText()
    {
        return 'Vedi Dettagli';
    }

    public function getActionUrl()
    {
        return route('appointments.show', $this->appointment);
    }
}
```

## Canali di Notifica

### Email
```php
// config/notifications.php
return [
    'channels' => [
        'mail' => [
            'driver' => 'smtp',
            'host' => env('MAIL_HOST'),
            'port' => env('MAIL_PORT'),
            'username' => env('MAIL_USERNAME'),
            'password' => env('MAIL_PASSWORD'),
            'encryption' => env('MAIL_ENCRYPTION'),
            'from' => [
                'address' => env('MAIL_FROM_ADDRESS'),
                'name' => env('MAIL_FROM_NAME'),
            ],
        ],
    ],
];
```

### Database
```php
// database/migrations/create_notifications_table.php
Schema::create('notifications', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('type');
    $table->morphs('notifiable');
    $table->text('data');
    $table->timestamp('read_at')->nullable();
    $table->timestamps();
});
```

### SMS
```php
// app/Notifications/Channels/SmsChannel.php
namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;

class SmsChannel
{
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toSms($notifiable);
        
        // Implementazione invio SMS
    }
}
```

## Best Practices

### 1. Gestione Code
- Utilizzare code separate per tipo
- Implementare retry policy
- Monitorare fallimenti
- Logging dettagliato

### 2. Performance
- Batch processing
- Rate limiting
- Caching
- Ottimizzazione query

### 3. Sicurezza
- Validazione input
- Sanitizzazione output
- Rate limiting
- Logging accessi

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
