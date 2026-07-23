---
title: "PHPStan Level 10 Roadmap - Job Module"
module: "Job"
type: concept
tags: [phpstan]
created: 2026-07-14
updated: 2026-07-14
qmd: "phpstan"
related:
  - "./phpstan-fixes-archive-2.md"
---
# PHPStan Level 10 Roadmap - Job Module

**Status**: ✅ Completato
**Errori Totali**: 1

## Errori Identificati

### Filament Pages

- [x] `app/Filament/Resources/ScheduleResource/Pages/EditSchedule.php:28` - `return.type` - Method `getformSchema()` should return `array<int, Component>` but returns `array`.

  - **Nota**: Il metodo dovrebbe probabilmente chiamarsi `getFormSchema()` (camelCase) e restituire `array<string, mixed>` secondo le regole Laraxot.

## Pattern di Correzione

- **return.type**: Rinominare il metodo in `getFormSchema` se necessario, aggiornare il return type hint in PHPDoc e assicurarsi che restituisca un array associativo con chiavi stringa.

## Prossimi Passi

- [x] Correggere `EditSchedule.php`
- [x] Verificare con PHPStan
- [x] Verifica PHPStan Level 10

## Verifica

- [x] `./vendor/bin/phpstan analyse Modules --level=10`
