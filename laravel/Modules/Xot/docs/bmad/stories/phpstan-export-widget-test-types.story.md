---
id: "xot-phpstan-export-test-types"
title: "PHPStan: CollectionExport WithMapping<mixed> + test types"
status: done
scope: module:Xot
related:
  - ./collection-export-intestazioni-esplicite.story.md
  - ../../../app/Exports/CollectionExport.php
  - ../../../tests/Unit/Exports/CollectionExportLabelledFieldsTest.php
  - ../../../tests/Unit/Exports/XotBaseExporterTest.php
qmd: "phpstan CollectionExport WithMapping mixed labelledRows XotBaseExporter filters"
---

# PHPStan 19 — metà Xot export tests

**Perché.** `map()` gia' accettava `mixed` (array rows + `data_get` sui path rating).
`@implements WithMapping<Model>` mentiva e faceva fallire i test che esportano array.
`Collection` e' invariante: `Collection<int, array<...>>` ≠ `Collection<int|string, mixed>`.

## Fix

- `CollectionExport`: `@implements WithMapping<mixed>`
- `labelledRows()`: return `Collection<int|string, mixed>`
- `resolveExporterColumns`: `@param array<string, mixed> $filters`

PHPStan sui file: 0. Pest skip (DB 53).

## Verifica `analyse Modules` (richiesta utente, cache ~191s)

`cd laravel && ./vendor/bin/phpstan analyse Modules` → `[OK] No errors` COLD:0.
Niente da sistemare: i 19 errori di questa story+IR+Ptv restano chiusi. Skill: `phpstan-solve-errors-no-ignores`.
