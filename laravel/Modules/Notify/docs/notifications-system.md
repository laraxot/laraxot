---
title: "Sistema di Notifiche"
type: concept
tags: [notifications, system]
created: 2026-07-14
updated: 2026-07-14
qmd: "notifications-system sistema di notifiche"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index-2.md"
  - "./00-index.md"
  - "./absolute-completion-100.md"
  - "./acronym-naming-conventions-1.md"
  - "./acronym-naming-conventions-2.md"
  - "./acronym-naming-conventions.md"
  - "./action-plan-immediate.md"
---

# Sistema di Notifiche

## Panoramica
Il sistema di notifiche utilizza `RecordNotification` come classe base per gestire tutte le notifiche dell'applicazione.

## RecordNotification

### Struttura Base
```php
namespace Modules\Notify\Notifications;

use Illuminate\Notifications\Notification;

class RecordNotification extends Notification
{
    protected $record;
    protected $type;
    protected $data;

    public function __construct($record, string $type, array $data = [])
    {
        $this->record = $record;
        $this->type = $type;
        $this->data = $data;
    }

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->getSubject())
            ->view($this->getView(), $this->getViewData());
    }

    public function toDatabase($notifiable): array
    {
        return [
            'type' => $this->type,
            'data' => $this->data,
            'record' => $this->record,
        ];
    }
}
```

## Implementazione

### Creazione Notifica
```php
// ❌ NON FARE
class DoctorRegistrationNotification extends Notification
{
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Registrazione Odontoiatra')
            ->view('emails.doctor.registration');
    }
}

// ✅ FARE
$notification = new RecordNotification(
    $doctor,
    'doctor.registration',
    [
        'subject' => 'Registrazione Odontoiatra',
        'view' => 'emails.doctor.registration',
    ]
);
```

### Template Email
```php
// resources/views/emails/doctor/registration.blade.php
@component('mail::message')

# {{ __('doctor.registration.title') }}

{{ __('doctor.registration.message') }}

@component('mail::button', ['url' => $url])
{{ __('doctor.registration.button') }}
@endcomponent

{{ __('doctor.registration.footer') }}
@endcomponent
```

### Traduzioni
```php
// lang/it/doctor.php
return [
    'registration' => [
        'title' => 'Registrazione Odontoiatra',
        'message' => 'La tua registrazione è stata ricevuta.',
        'button' => 'Vai al Profilo',
        'footer' => 'Grazie per esserti registrato.',
    ],
];
```

## Best Practices

### Tipi di Notifica
- Usare chiavi di traduzione per i tipi
- Mantenere i tipi consistenti
- Documentare i tipi disponibili

### Dati
- Includere solo dati necessari
- Validare i dati prima dell'invio
- Sanitizzare i dati sensibili

### Template
- Usare componenti Blade
- Mantenere il design consistente
- Supportare il tema scuro

## Metriche

### Performance
- Tempo di invio: <1s
- Tasso di consegna: >99%
- Tasso di apertura: >50%

### Monitoraggio
- Log delle notifiche
- Statistiche di invio
- Errori e retry

## Collegamenti
- [Documentazione API](./api.md)
- [Template Email](./templates.md)
- [Guida Contribuzione](./contributing.md)

## Note
- Testare le notifiche in ambiente di sviluppo
- Monitorare i tassi di consegna
- Aggiornare i template regolarmente
- Mantenere le traduzioni aggiornate 
