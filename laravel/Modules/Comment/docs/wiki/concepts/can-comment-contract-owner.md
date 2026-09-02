---
title: Modulo Comment - Governance
type: concept
tags: [comment, modulo, governance, architecture]
date: '2026-06-29'
---

# Comment Module Governance

## Dependency Rule

**User NON deve mai dipendere da Comment.**
**Comment PUO' dipendere da User.**

Questa regola è fondamentale per evitare cicli di dipendenza e mantiene la separazione dei domini.

## Architecture

### CanComment Contract

L'interfaccia `CanComment` definisce i contratti per gli utenti che possono commentare:
- Non ha dipendenze da User
- È implementata da User (via trait)
- Comment si affida a runtime validation

### InteractsWithComments Trait

Il trait fornisce implementazione di:
- `subscribeToCommentNotifications()`
- `unsubscribeFromCommentNotifications()`
- `notificationSubscriptionType()`

### Enums

`NotificationSubscriptionType` è definito in Comment:
- `Participating` - notifiche solo per commenti in cui l'utente partecipa
- `All` - tutte le notifiche
- `None` - nessuna notifica

## Integration Pattern

### User Module → Comment Module

```php
// In User → NON USARE MAI
use Modules\Comment\...; // ❌ VIETATO

// In Comment → USARE SOLO interfaccia
use Modules\Comment\Models\Contracts\CanComment; // ✅ OK
```

### Type Safety

I widget Livewire e Filament ricevono `auth()->user()` come mixed. Per PHPStan:
```php
$currentUser = auth()->user();
if ($currentUser instanceof CanComment && $this->model instanceof Commentable) {
    // Safe to call subscribeToCommentNotifications
}
```