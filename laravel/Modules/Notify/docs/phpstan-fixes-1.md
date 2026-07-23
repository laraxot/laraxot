---
title: "PHPStan Error Fixes - Notify Module"
type: concept
tags: [phpstan, fixes]
created: 2026-07-14
updated: 2026-07-14
qmd: "phpstan-fixes-1 phpstan error fixes - notify module"
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

# PHPStan Error Fixes - Notify Module

## Error: Method Name Mismatch in Actions

### Problem
PHPStan riportava errori quando un Action usa `execute()` invece di `handle()`.

**Errore originale**:
```
Call to an undefined method SendNotificationAction::execute()
```

**Causa**:
- Spatie QueueableAction trait si aspetta il metodo `handle()` 
- Il metodo `execute()` non è definito nel trait

### Solution
Rinominare il metodo da `execute()` a `handle()` in tutte le Actions.

**File**: `Modules/Notify/app/Actions/SendNotificationAction.php`

```php
// PRIMA (ERRATO)
public function execute(
    Model $recipient,
    string $templateCode,
    // ...
): ?NotificationModel {
    // ...
}

// DOPO (CORRETTO)
public function handle(
    Model $recipient,
    string $templateCode,
    // ...
): ?NotificationModel {
    // ...
}
```

### Namespace Aliases for Notification

**Problema aggiuntivo**: Conflitto tra `Notification` facade e `Notification` model.

**Soluzione**: Usare alias negli import

```php
use Illuminate\Support\Facades\Notification as NotificationFacade;
use Modules\Notify\Models\Notification as NotificationModel;

// Uso:
NotificationFacade::send($recipient, $notification);  // Facade
$notification = new NotificationModel();  // Model
```

### PHPStan Result
✅ **Prima**: 22 errori nel file
✅ **Dopo**: 0 errore

### Files Fixed
- `Modules/Notify/app/Actions/SendNotificationAction.php`
- `Modules/Notify/app/Jobs/SendNotificationJob.php` (chiama `handle()` invece di `execute()`)

### Pattern da Applicare
Tutte le Actions che estendono `QueueableAction` devono:
1. Implementare il metodo `handle()`, non `execute()`
2. Documentare il tipo di ritorno con PHPDoc
3. Usare alias per evitare conflitti di nomi

### Verification
```bash
cd laravel
./vendor/bin/phpstan analyse Modules/Notify --memory-limit=-1
```

### Related
- Spatie QueueableAction documentation
- PHPStan Level MAX requirements
- Laravel Notification facade vs model distinction
