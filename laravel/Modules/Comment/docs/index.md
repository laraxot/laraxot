# Comment Module

Sistema completo per gestione commenti, moderazione e interazioni utente con supporto threading e notifiche.

## Overview

Il modulo **Comment** fornisce funzionalità avanzate di commento per l'applicazione:

- 💬 **Sistema Commenti** - Commenti strutturati con threading (risposte)
- 👥 **Moderazione** - Strumenti di moderazione, approvazione e rifiuto
- 🔔 **Notifiche** - Notifiche per risposte e menzioni
- 🎨 **Interfaccia Filament** - Gestione admin moderna con Filament 4.x
- 🌐 **Multi-lingua** - Traduzioni IT/EN complete
- ✅ **PHPStan Level 9** - Compliance statica completa

## Key Features

### Comment Management
- Creazione e modifica commenti su qualsiasi modello (polymorphic)
- Sistema di threading con risposte nested
- Gestione dello stato (draft, pending, approved, rejected)
- Timestamp tracciamento creazione/modifica

### Moderation Tools
- Approvazione/rifiuto commenti
- Segnalazione spam e commenti problematici
- Gestione moderatori e permessi granulari
- Audit trail completo

### Notifications
- Notifiche per risposte ai commenti
- Menzioni utente (@username)
- Canali configurabili (mail, database)
- Template localizati

## Architecture

Il modulo segue i principi Laraxot di modular monolith:

```
Comment/
├── app/
│   ├── Models/
│   │   ├── Comment.php
│   │   └── CommentStatus.php
│   ├── Actions/
│   ├── Filament/
│   │   ├── Resources/
│   │   └── Pages/
│   └── Events/
├── database/
│   ├── migrations/
│   ├── factories/
│   └── seeders/
├── lang/
│   ├── it/
│   └── en/
└── docs/
```

**Base Classes**: Estende `XotBaseModel`, `XotBaseResource` per pattern coerenti.

## Core Components

### Models
- **Comment** - Modello principale per i commenti
- **CommentStatusEnum** - Stati possibili (draft, pending, approved, rejected, spam)
- Relazioni polimorfiche su qualsiasi modello (`commentable`)
- Relazione self-referencing per threading (`parent_id`)

### Filament Resources
- **CommentResource** - Gestione admin completa
- Filtri per status, autore, data
- Bulk actions per moderazione
- Column personalizzate per preview

### Events & Listeners
- `CommentCreated` - Trigg notifiche
- `CommentApproved` - Trigger notifiche approvazione
- `CommentRejected` - Log rifiuti

## Implementation Guide

### Quick Start
```bash
# Abilitare il modulo
php artisan module:enable Comment

# Eseguire le migrazioni
php artisan migrate

# Pubblicare configurazioni
php artisan vendor:publish --tag=comment-config
```

### Configurazione
File: `config/comment.php`
- Moderation settings (auto-approve, whitelist)
- Threading settings (max depth)
- Notification channels
- Privacy & GDPR compliance

### Creazione Commento
```php
$comment = Comment::create([
    'user_id' => $user->id,
    'commentable_type' => Post::class,
    'commentable_id' => $post->id,
    'content' => 'Ottimo articolo!',
    'status' => CommentStatusEnum::APPROVED,
]);

// Risposta a commento (threading)
$reply = $comment->replies()->create([
    'user_id' => $user->id,
    'content' => 'Grazie del feedback!',
]);
```

## Best Practices

### Security
- Validate `commentable_type` contro whitelist di modelli
- Check autorizzazione prima di visibility
- Sanitize content (HTML escaping, markdown validation)
- Rate limiting su creazione commenti

### Performance
- Eager load relazioni (author, parent)
- Paginate risultati per large comment threads
- Index su `commentable_type`, `commentable_id`, `status`
- Cache conteggi commenti

### Moderation
- Auto-approve solo per utenti verificati
- Manual review per nuovi utenti
- Spam detection via keyword filters
- Appeal process per commenti rifiutati

## Related Modules

- [User Module](../User/docs/) - Autenticazione e profili
- [Notify Module](../Notify/docs/) - Sistema notifiche avanzato
- [Activity Module](../Activity/docs/) - Activity logging
- [Xot Module](../Xot/docs/) - Base classes e patterns

## Troubleshooting

**I commenti non vengono moderati automaticamente**
- Verificare configurazione `auto_approve_verified_users`
- Controllare status utente nel database
- Run `php artisan module:publish Comment`

**Notifiche non inviate**
- Controllare queue configuration
- Verificare email address configurati
- Check notification settings in config

**Problemi performance con thread lunghi**
- Verificare `max_depth` in config
- Implementare pagination su replies
- Aggiungere indexes su migration

## Documentation

Vedi anche:
- [README](README.md) - Panoramica general
- [PRD](PRD.md) - Product requirements
- [Roadmap 2025](PRODUCT_ROADMAP.md) - Piano sviluppo
- [PHPStan Fixes](phpstan-fixes.md) - Conformità statica
- [File Naming Rules](file-naming-rules.md) - Convenzioni naming
- [Structure](structure.md) - Architettura dettagliata
- [Conflict Resolution](conflict-resolution.md) - Risoluzioni merge

---

**Status**: Active Development  
**PHPStan Level**: Target Level 9  
**Translation**: IT/EN ✅  
**Last Updated**: 2026-05-13
