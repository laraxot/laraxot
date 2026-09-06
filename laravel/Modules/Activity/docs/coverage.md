---
module: theme
topic: coverage
canonical: ../../../Themes/docs/shared-components/coverage.txt
---

See canonical documentation: ../../../Themes/docs/shared-components/coverage.txt
<<<<<<< .merge_file_iu2JdA

## PHPStan — 2026-09-02 (swarm long-tail, sessione 6748f176)

17 errori level max chiusi in 6 file: const PHP 8.3 typed
(`RedactModelAttributesAction::SENSITIVE_KEYS`), cast.string→narrowing
`is_scalar()`/`Stringable` in `ListLogActivities`, param type sui closure
di test mancanti, e migrazione di `FilamentTest.php` dai metodi Filament
deprecati `getTableColumns()/getTableFilters()/getTableActions()/
getTableBulkActions()` a `$page->table(Table::make($page))->getColumns()/
getFilters()/getRecordActions()/getToolbarActions()`. Verifica isolata
(`phpstan analyse -c <tmpDir dedicata> Modules/Activity`): 0 errori. Commit
`925e9b3e`, push rejected su laraxot e provtv (dev locale behind 1329) —
commit intatto, non forzato.

Test Pest non eseguibile in questa sessione: DB di test (10.100.200.53:3306)
## Status

**2026-09-06**: philosophy.md created. PHPStan analyzed (OK). Pest suite (TBD). Coverage target: +5% per module.

irraggiungibile (`nc -z` in timeout).
=======
>>>>>>> .merge_file_kSrt4V
