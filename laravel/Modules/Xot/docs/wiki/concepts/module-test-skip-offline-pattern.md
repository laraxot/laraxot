---
title: "Pattern skip offline — test modulo senza schema dominio"
module: Xot
type: concept
status: approved
language: it-IT
created: 2026-08-19
updated: 2026-08-19
qmd: "skip offline activity-db no-activity-db TestCase filename pest feature unit coverage"
related:
  - ../stories/5.24.module-coverage-fifty-percent-floor.story.md
  - ../stories/5.25.module-suite-green-offline.story.md
  - ../coverage.md
  - ../../Activity/tests/TestCase.php
  - ../../../../docs/chat/pest-eseguibile-offline-sqlite.md
---

# Pattern skip offline — test modulo senza schema dominio

## Problema

`XotBaseTestCase` rimappa le connessioni su `database/ptv_data.sqlite`, che non contiene
tabelle di dominio (`activity_log`, `media`, `users`, …). I test Feature che persistono dati
falliscono con `no such table`; la suite resta rossa e **Pest non stampa coverage** (exit ≠ 0).

Obiettivo: suite **verde offline**, coverage misurabile sul perimetro Unit + test puri, skip
**condizionati** (AD-4) per ciò che richiede schema.

## Pilota: modulo Activity

Implementazione: [`Modules/Activity/tests/TestCase.php`](../../Activity/tests/TestCase.php).

### Regole

1. **`activityDbUnavailable()`** — statico: `true` se connessione `activity` manca o non ha
   `activity_log`.
2. **`shouldSkipForMissingActivityDb()`** in `setUp()`:
   - gruppo `activity-db` → skip se DB unavailable
   - gruppo `no-activity-db` → **non** skip
   - path test in `/tests/Unit/` (via Pest `$__filename`) → **non** skip
   - altrimenti (Feature) → skip
3. Test Unit che **richiedono** DB: `uses(...)->group('activity-db')` o `describe(...)->group('activity-db')`.
4. Test puri: gruppo `no-activity-db` opzionale; ereditano esecuzione Unit.

### Perché `$__filename` Pest

`ReflectionClass($this)` su test Pest punta al `TestCase`, non al file `.php` del test.
Le classi proxy Pest espongono `public static $__filename` — usarlo per distinguere Unit/Feature.

### Replicare su altri moduli

| Modulo | Connessione | Tabella guard | Gruppi |
|--------|-------------|---------------|--------|
| Activity | `activity` | `activity_log` | `activity-db`, `no-activity-db` |
| Media | `media` / default | `media` | `media-db`, `no-media-db` |
| User | `user` / default | `users` | `user-db`, `no-user-db` |

Copiare la struttura, **non** skip globale in `setUp()` (abbassa coverage al 4% come in Activity
prima del fix).

## Cosa non fare

- `RefreshDatabase` / `migrate:fresh` (AD-3)
- Skip incondizionato di tutti i test Unit
- `pest()->group()->in('Unit')` — non applica gruppi in Pest 5 su questo tree
- Test filler solo per alzare `%` — vietato in [5.24](../stories/5.24.module-coverage-fifty-percent-floor.story.md)

## Gate

Dopo il pattern, Activity raggiunge **54,2%** con `--min=50` e 220 pass / 205 skip (2026-08-19).

```bash
cd laravel
XDEBUG_MODE=coverage ./vendor/bin/pest -c Modules/Activity/phpunit.xml --coverage --min=50
```
