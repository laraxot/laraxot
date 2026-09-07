---
id: phpstan-Blog-fix
slug: phpstan-Blog
scope: [module:Blog, project:base_workorder_fila5]
status: Done
priority: High
created: 2026-09-06
updated: 2026-09-07
---

## Problema (risolto)
8 errori PHPStan: `Filament\Forms\Components\BaseFileUpload::enableOpen()`/
`enableDownload()` deprecati in Filament v5, sostituiti da `openable()`/
`downloadable()`.

## Fix
Rinomina meccanica in `CategoryForm.php`, `CategoryFormSchema.php`,
`TextWidgetResource.php`, `TextWidgetResource/Schemas/TextWidgetForm.php`.

## Verifica
- PHPStan: 8 -> 0 errori
- PHPMD: nessuna nuova segnalazione
- Dettaglio: `docs/coverage.md`
