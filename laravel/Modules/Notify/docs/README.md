---
title: "Notify Module Documentation"
type: documentation
tags: [module, documentation]
created: 2026-06-05
updated: 2026-07-24
---

# Modulo Notify

## Overview

Il modulo **Notify** gestisce il sistema di notifiche dell'applicazione.

## Funzionalità

- Mail notifications
- Database notifications
- Template management
- Queue integration

## Modelli Principali (verificato 2026-07-24 contro `app/Models/`)

```php
Modules\Notify\Models\MailTemplate
Modules\Notify\Models\MailTemplateVersion
Modules\Notify\Models\MailTemplateLog
Modules\Notify\Models\Notification
Modules\Notify\Models\NotificationLog
Modules\Notify\Models\NotificationType
Modules\Notify\Models\NotificationChannel
Modules\Notify\Models\NotificationTemplate
Modules\Notify\Models\NotificationTemplateVersion
```

## Trait

> **Verificato 2026-07-24**: `Modules\Notify\Models\Traits\HasNotify` **non esiste** (unico trait presente in
> `app/Models/Traits/` è `HasContact.php`). Claim rimossa — se questo trait serve, va creato, non documentato come
> già presente.

## Collegamenti

- [Xot Base](../Xot/docs/)
- [User Module](../User/docs/)

## Backlinks

> **Verificato 2026-07-24**: le cartelle `filament/` e `phpstan/` sotto `docs/` non esistono entrambe — solo
> `phpstan/` esiste (vedi [PHPStan Config](./phpstan/)); il link "Filament Resources" a `./filament/` era rotto
> ed è stato rimosso.

- [PHPStan Config](./phpstan/)

## Documentation

- [On-Demand Pattern](./ON-DEMAND-PATTERN.md) — Pattern per caricamento efficiente
- [QMD Setup](./QMD-SETUP.md) — Configurazione ricerca locale
- [Performance](./PERFORMANCE-OPTIMIZATION.md) — Metriche e best practice
- [Project Structure](./PROJECT-STRUCTURE.md) — Directory layout