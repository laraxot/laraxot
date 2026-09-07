---
name: asdsoci_coverage
description: PHPStan/PHPMD/Pest gate status for Modules/AsdSoci
metadata:
  type: reference
---

# Coverage e qualita' — AsdSoci

## PHPStan

- Prima: 2 errori (`cast.string`, `argument.type`) in `app/Filament/Pages/AsdSociDocumentationPage.php:31,33`.
- Causa: `request()->query('document', ...)` restituisce `array|string|null`; il cast diretto a `string` e `array_key_exists()` con chiave mixed violano level max.
- Fix: narrowing esplicito con `is_string()` prima dell'uso, nessun cast forzato.
- Dopo: 0 errori (`./vendor/bin/phpstan analyse Modules/AsdSoci --memory-limit=-1` -> `[OK] No errors`).

## PHPMD

- Ruleset proprio mancante: copiato da `Modules/Xot/phpmd.ruleset.xml` (SSoT, convenzione "copy and keep in sync").
- 1 finding pre-esistente, non azionabile: `LongVariable` su `tests/TestCase.php:18` (`$connectionsToTransact`) — nome imposto dal trait Laravel `DatabaseTransactions`, rinominarlo romperebbe l'override. Lasciato.

## Pest

- `tests/Feature/AsdSociPanelPagesTest.php`: 1 passed (`it sends a guest to the login`), 1 failed
  (`it opens every page of the panel`) per `SQLSTATE[42S02]: Base table or view not found: workorder_data_test.roles`
  sulla connessione `mariadb`. Non e' una regressione introdotta qui: gap ambientale gia'
  noto (vedi second-brain `project_test_db_missing_migrations_blocks_feature_tests.md`),
  il DB di test `workorder_data_test` non ha le migration applicate su quella connessione.
  Nessuna `migrate:fresh`/`--force` eseguita (dato sacro, decisione owner).

## PHPInsights

Ephemeral `/tmp` config (removing `ForbiddenSecurityIssues`, see
`project_phpinsights_composer_lock_scoped_path.md`): Code 99.0, Complexity 90.5,
Architecture 94.1, Style 90.4 — all above min-70.

## Stato

- PHPStan: 2 -> 0.
- Pest: invariato rispetto al gap pre-esistente (1/2 nella stessa suite, causa ambientale non di codice).
