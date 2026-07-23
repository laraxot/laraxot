# PHPStan Level 10 Fixes - Report 2025-12-19

**Status**: 🚧 In Progress (4/19 errori fixati)
**Data**: 2025-12-19 15:30 CET
**Analizzato**: 3,877 file PHP
**Livello**: max (Level 10)

---

## 📊 Summary

| Metrica | Valore |
|---------|--------|
| **Errori iniziali** | 19 |
| **Errori fixati** | 4 |
| **Errori rimanenti** | 15 |
| **Success Rate** | 21% |
| **File modificati** | 3 |

---

## ✅ Fix Completati

### 1. Safe Functions - theCodingMachine/safe Library

**Problema**: Uso di funzioni PHP unsafe che possono restituire `false` invece di eccezioni.

#### Fix 1.1: `NormalizePhoneNumberAction.php`

**Errore**:
```
Function preg_replace is unsafe to use. It can return FALSE instead of throwing an exception.
```

**Fix**:
```php
// BEFORE
use Safe\preg_replace;

// AFTER
use function Safe\preg_replace;
```

**File**: `Modules/Notify/app/Actions/NormalizePhoneNumberAction.php`
**Riga**: 28
**Commit**: TBD

#### Fix 1.2: `RecordNotification.php`

**Errore**:
```
Function preg_match_all is unsafe to use.
```

**Fix**:
```php
// AFTER
use function Safe\preg_match_all;
```

**File**: `Modules/Notify/app/Notifications/RecordNotification.php`
**Riga**: 108
**Commit**: TBD

**Filosofia**: *"Safe functions enforce fail-fast behavior. Exceptions > silent failures."*

---

### 2. Anti-Pattern: `Model::make()` → `new Model()`

**Problema**: `Model::make()` esegue lavoro non necessario rispetto a `new Model()`.

#### Fix 2.1: `SendRecordNotificationAction.php` - SMS Route

**Errore**:
```
Called 'Model::make()' which performs unnecessary work, use 'new Model()'.
```

**Fix**:
```php
// BEFORE
$tempTemplate = \Modules\Notify\Models\MailTemplate::make();

// AFTER
$tempTemplate = new \Modules\Notify\Models\MailTemplate();
```

**File**: `Modules/Notify/app/Actions/SendRecordNotificationAction.php`
**Riga**: 122
**Commit**: TBD

#### Fix 2.2: `SendRecordNotificationAction.php` - WhatsApp Route

**Fix**:
```php
// BEFORE
$tempTemplate = \Modules\Notify\Models\MailTemplate::make();

// AFTER
$tempTemplate = new \Modules\Notify\Models\MailTemplate();
```

**File**: `Modules/Notify/app/Actions/SendRecordNotificationAction.php`
**Riga**: 160
**Commit**: TBD

**Filosofia**: *"Constructors are faster than factories when no side effects are needed."*

---

## 🚧 Errori Rimanenti (15)

### Categoria 1: Missing Methods (9 errori)

#### 1.1: `RecordNotification::mergeData()` non esiste

**Errore**:
```
Call to an undefined method Modules\Notify\Notifications\RecordNotification::mergeData().
```

**Occorrenze**:
- `SendSmsPage.php:117`
- `SendSpatieEmailPage.php:125`
- `XotBaseTransition.php:107`

**Analisi**:
- `RecordNotification` non ha metodo `mergeData()`
- Probabilmente confuso con `SpatieEmail::mergeData()`
- Richiede API design decision

**Soluzione Proposta**:
1. **Opzione A**: Aggiungere `mergeData()` a `RecordNotification`
2. **Opzione B**: Refactorare caller code per usare API corretta
3. **Opzione C**: Creare trait `HasMergeableData` condiviso

#### 1.2: `RecordNotification::addAttachments()` non esiste

**Errore**:
```
Call to an undefined method Modules\Notify\Notifications\RecordNotification::addAttachments().
```

**Occorrenze**:
- `XotBaseTransition.php:111`

**Soluzione Proposta**: Delegare a `MailMessage::attach()` standard Laravel

#### 1.3: `MailMessage::html()` non esiste

**Errore**:
```
Call to an undefined method Illuminate\Notifications\Messages\MailMessage::html().
```

**Occorrenze**:
- `RecordNotification.php:69`

**Analisi**:
- `MailMessage` standard non ha `->html()`
- Usa `->view()` o `->markdown()`

**Soluzione Proposta**:
```php
// BEFORE (non esiste)
return (new MailMessage())
    ->subject($subject)
    ->html($htmlContent);

// AFTER (opzione 1: view dinamica)
return (new MailMessage())
    ->subject($subject)
    ->view('notify::mail.dynamic', ['content' => $htmlContent]);

// AFTER (opzione 2: line + markdown)
return (new MailMessage())
    ->subject($subject)
    ->greeting('')
    ->line(new HtmlString($htmlContent));
```

#### 1.4: `Notification::toSms()` non esiste

**Errore**:
```
Call to an undefined method Illuminate\Notifications\Notification::toSms().
```

**Occorrenze**:
- `SmsChannel.php:18`

**Soluzione Proposta**: Definire contract `HasSmsMessage` o usare naming standard

---

### Categoria 2: Type Safety Issues (4 errori)

#### 2.1: Mixed to String Cast in String Interpolation

**Errore**:
```
Part $message (mixed) of encapsed string cannot be cast to string.
```

**Occorrenze**:
- `SmsChannel.php:35`
- `WhatsAppChannel.php:35` (2 volte: $message e $to)

**Soluzione Proposta**:
```php
// BEFORE
logger()->info("Sending SMS to {$to}: {$message}");

// AFTER
logger()->info('Sending SMS', [
    'to' => $to,
    'message' => $message,
]);
```

#### 2.2: Return Type Mismatch

**Errore**:
```
Method should return string|null but returns mixed.
```

**Occorrenze**:
- `SmsChannel::getRecipientPhoneNumber():52`
- `RecordNotification::toMail():69`

**Soluzione Proposta**: Aggiungere type assertions e PHPDoc

---

### Categoria 3: Constructor Parameter Type Mismatch (3 errori)

**Errore**:
```
Parameter #2 $mailTemplate expects Modules\Notify\Models\MailTemplate, string given.
```

**Occorrenze**:
- `SendSpatieEmailPage.php:124`
- `XotBaseTransition.php:103`

**Analisi**: Passaggio di slug string invece di model instance

**Soluzione Proposta**:
```php
// BEFORE
$notification = new RecordNotification($record, 'template-slug');

// AFTER
$template = MailTemplate::where('slug', 'template-slug')->firstOrFail();
$notification = new RecordNotification($record, $template);
```

---

### Categoria 4: Unnecessary Null Coalesce (1 errore)

**Errore**:
```
Expression on left side of ?? is not nullable.
```

**Occorrenze**:
- `SendRecordsNotificationBulkAction.php:48`

**Soluzione Proposta**: Rimuovere `?? fallback` se espressione è typed non-nullable

---

### Categoria 5: Foreach on Mixed (1 errore)

**Errore**:
```
Argument of an invalid type mixed supplied for foreach, only iterables are supported.
```

**Occorrenze**:
- `SendRecordsNotificationBulkAction.php:87`

**Soluzione Proposta**: Aggiungere type assertion o PHPDoc

---

## 🎯 Action Plan

### Priorità ALTA (completare oggi)

- [x] ✅ Fix Safe Functions (2/2)
- [x] ✅ Fix Model::make() anti-patterns (2/2)
- [ ] 🚧 Fix `MailMessage::html()` → use `view()` o `HtmlString`
- [ ] 🚧 Fix Constructor parameter types (3 occorrenze)

### Priorità MEDIA (completare domani)

- [ ] Design API `RecordNotification::mergeData()`
- [ ] Implement `HasMergeableData` trait o equivalente
- [ ] Fix Type Safety: mixed to string casts (4 occorrenze)
- [ ] Fix Return type mismatches (2 occorrenze)

### Priorità BASSA (refactoring futuro)

- [ ] Refactor `toSms()` contract e naming
- [ ] Create comprehensive notification testing suite
- [ ] Document notification architecture

---

## 🧘 Zen Philosophical Analysis

### DRY Violations

**Problema**: `mergeData()` richiesto in 3 luoghi diversi
**Soluzione**: Centralizzare in trait condiviso

### KISS Violations

**Problema**: `Model::make()` aggiunge complessità inutile
**Soluzione**: ✅ Fixato con `new Model()`

### SOLID Violations

**Problema**: `RecordNotification` assume troppe responsabilità
**Soluzione**: Separare concerns (data merging, template rendering, delivery)

### Type Safety Philosophy

> "Mixed is the enemy of maintainability. Every mixed should be questioned."

**Problema**: Uso eccessivo di `mixed` in channels
**Soluzione**: Strict typing + assertions + PHPDoc

---

## 📈 Progress Timeline

| Data | Errori | Fix | Rimanenti | Note |
|------|--------|-----|-----------|------|
| 2025-12-19 14:00 | 19 | 0 | 19 | Initial analysis |
| 2025-12-19 15:30 | 19 | 4 | 15 | Safe functions + Model::make() |
| 2025-12-19 EOD | - | - | **Target: 10** | MailMessage + Constructor types |
| 2025-12-20 | - | - | **Target: 0** | Full PHPStan Level 10 compliance |

---

## 🔗 References

- [thecodingmachine/safe](https://github.com/thecodingmachine/safe)
- [Laravel Notification Docs](https://laravel.com/docs/11.x/notifications)
- [PHPStan Rules](https://phpstan.org/user-guide/rules)
- [Larastan Extension](https://github.com/larastan/larastan)

---

## 🐄 Generated with Super Mucca Analysis Powers

*"Fix, don't ignore. Type-safe, not type-unsafe. Exceptions, not false returns."*
