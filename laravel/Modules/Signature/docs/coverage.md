---
name: signature-coverage
description: Copertura test del modulo Signature dopo il fix PHPStan level-max (2026-09-06)
metadata:
  module: Signature
  updated: 2026-09-06
---

# Coverage — Modules/Signature

## Nota sullo strumento

Xdebug/PCOV non sono disponibili in questo ambiente (`./vendor/bin/pest --coverage`
fallisce con "Unable to get coverage using Xdebug"), quindi non e' possibile
riportare una percentuale di riga/branch. I numeri sotto sono conteggio
test/assert (prima/dopo) piu' l'elenco qualitativo dei path coperti.

## Prima del fix (baseline)

- `Modules/Signature/tests`: 8 passed, 3 failed (29 - 14 = 15 assertions su 4 file Unit + 3 Feature).
- Nessun test esercitava `CaptureSignature`/`CaptureSketch` (pagine Filament) ne'
  la logica di narrowing `mixed → int|string` che viveva inline in quelle pagine.

## Dopo il fix

- `Modules/Signature/tests`: 11 passed, 3 failed (invariati, vedi sotto), 29 assertions.
- Nuovo file `tests/Unit/CanvasFormDataCoercionTest.php` (3 test, 14 assertion):
  copre `InteractsWithCanvasFormData::extractStringKeyedArray()`,
  `::coerceToInt()` e `::coerceToString()` — gli helper introdotti per
  eliminare gli errori PHPStan `cast.int`/`cast.string` su `mixed` in
  `CaptureSignature`/`CaptureSketch`.

## 3 fallimenti pre-esistenti (NON causati da questo fix, fuori scope)

Bloccati da uno stato del DB di test incompleto, non da codice:

1. `Feature/CanvasSignatureAndSketchTest.php` — `SQLSTATE[42S02]: Base table
   or view not found: customers`.
2. `Feature/SignatoryContactUpsertTest.php` — stesso problema, tabella `customers`
   mancante nel DB usato dai test.
3. `Feature/UserIdColumnsAcceptUuidTest.php` — asserisce che `signatures.user_id`
   esista come colonna e non esiste nello schema corrente.

Verificato che `customers` manca anche sulla connessione di default (non solo
sul DB di test), quindi e' un problema di migrazioni/schema pre-esistente
all'ambiente, indipendente dalle modifiche di questo task. Non toccato per
rispetto della regola "mai migrate:fresh/refresh/--force, mai RefreshDatabase,
i dati sono sacri".

## File toccati da questo fix

- `app/Filament/Pages/Concerns/InteractsWithCanvasFormData.php` (nuovo trait,
  condiviso da `CaptureSignature` e `CaptureSketch`).
- `app/Filament/Pages/CaptureSignature.php`, `app/Filament/Pages/CaptureSketch.php`
  (usano il trait, niente piu' cast ciechi su `mixed`).
- `tests/Feature/CanvasSignatureAndSketchTest.php` (2 `getKey()` → `id`,
  coerente con `@property int $id` su `Intervention`).
- `tests/Unit/CanvasFormDataCoercionTest.php` (nuovo).
- `phpmd.ruleset.xml` (copiato da `Modules/Xot/phpmd.ruleset.xml`, SSoT — il
  modulo non ne aveva uno proprio).
