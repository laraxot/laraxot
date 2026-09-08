---
name: production-coverage
description: PHPStan/PHPMD/Pest gate status for Modules/Production — 2026-09-06/07 PHPStan L10 cleanup
metadata:
  type: quality-gate
---

# Production — quality gate status (2026-09-07)

## PHPStan

- Before: 3 errors — Filament v4->v5 `Table::actions()`/`bulkActions()` deprecated,
  replaced by `recordActions()`/`toolbarActions()`.
- Fix: mechanical rename in `ProductionOrderResource.php`,
  `ProductionPhasesRelationManager.php`.
- After: **0 errors** (`phpstan analyse Modules/Production`, clean cache).

## PHPMD

No findings at all in this module.

## Pest

Not run for this module in this pass (mechanical, zero-behavior-change rename;
verified via PHPStan + `php -l` only). No functional change to test coverage.

## typeCoverage.constantTypeCoverage (2026-09-07, sessione batch finale)

Continuazione di `docs/stories/2.8.phpstan-constant-type-coverage.story.md` e
`docs/stories/5.1.phpstan-constant-type-coverage-remaining.story.md`
(`docs/chat/2026-09-07-claim-typecoverage-final-push-swarm.md`).

**Before** (full-tree scan `./vendor/bin/phpstan analyse --memory-limit=-1`,
~22:11, richiesto perché questa regola short-circuita su scan scoped — vedi
`vendor/tomasvotruba/type-coverage/src/Configuration/ScopeConfigurationResolver.php`):
3 costanti non tipate in `app/Filament/Resources/ProductionOrderResource.php`:

- riga 41 — `ACCOUNT_FEATURE_ADMIN = 'admin.production'`
- riga 44 — `ACCOUNT_FEATURE_OPERATOR = null`
- riga 47 — `ACCOUNT_FEATURE_OPERATOR_DEFAULT = false`

**Fix applicato** (tipo nativo PHP 8.3+, coerente con la firma di
`AccountFeatures::currentPanelAllowsFeature()` in `app/Support/AccountFeatures.php`
che dichiara `?string $operatorFeature`):

```php
private const string ACCOUNT_FEATURE_ADMIN = 'admin.production';
private const ?string ACCOUNT_FEATURE_OPERATOR = null;
private const bool ACCOUNT_FEATURE_OPERATOR_DEFAULT = false;
```

Working tree condiviso con un'estrazione Epic4 canonical-structure già in
corso e non committata su questo stesso file (`getFormSchema()`/`table()`
spostati in `Schemas/`/`Tables/`, story `4.14.5.production-canonical.story.md`)
— non toccata, il fix riguarda solo le 3 righe delle costanti. Il fix risulta
già presente in `git show HEAD` (verificato subito dopo l'edit: nessun diff
residuo su quelle 3 righe rispetto a HEAD), incluso da una sessione
concorrente che condivide lo stesso working tree — nessun commit aggiuntivo
necessario da questa sessione per queste 3 righe.

**Verifica full-tree post-fix bloccata** da corruzione repo-wide non
correlata: `Modules/User/module.json` contiene marker di conflitto git
committati (`<<<<<<< HEAD`/`=======`/`>>>>>>>`), causa
`Only arrays and Traversables can be unpacked, null given` in bootstrap
Laravel/Larastan per l'intero monorepo. Non nello scope di questo task, non
toccato, segnalato in `docs/chat/INDEX.md`.

Module-scoped (non valida per questa regola specifica, ma conferma nessuna
regressione sulle altre regole):

```bash
cd laravel && ./vendor/bin/phpstan analyse Modules/Production --no-progress --memory-limit=-1
# [OK] No errors
```
