---
title: "PHPStan Fixes - Gennaio 2025"
type: concept
tags: [phpstan, fixes, january, 2025]
created: 2026-07-14
updated: 2026-07-14
qmd: "phpstan-fixes-january-2025 phpstan fixes - gennaio 2025"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index.md"
  - "./04-datas.md"
  - "./advanced-form-components-1.md"
  - "./advanced-form-components.md"
  - "./agent-confidence-discipline.md"
  - "./agent-confidence-protocol.md"
  - "./agent-edit-discipline.md"
---

# PHPStan Fixes - Gennaio 2025

## Modulo UI - Correzioni Completate

### File Corretti

1. **Modules/UI/app/Filament/Blocks/Image.php**
   - Corretto tipo di ritorno per `Select::options()` usando closure con type hint esplicito

2. **Modules/UI/app/Filament/Forms/Components/InlineDatePicker.php**
   - Aggiunto type narrowing per `collect()` con `iterable<int, mixed>`

3. **Modules/UI/app/Filament/Forms/Components/SelectState.php**
   - Corretto type narrowing per `$modelInstance` con verifica `HasStatesContract`

4. **Modules/UI/app/Filament/Tables/Columns/IconStateColumn.php**
   - Corretto type narrowing per closure callbacks
   - Aggiunto type safety per `handleStateTransition()`

5. **Modules/UI/app/Filament/Tables/Columns/IconStateGroupColumn.php**
   - Corretto type narrowing per `$state` in `buildColumnsFromStates()`
   - Aggiunto type safety per `modalActionByRecord()`

6. **Modules/UI/app/Filament/Tables/Columns/IconStateSplitColumn.php**
   - Aggiunto controllo `is_iterable()` prima di foreach
   - Corretto type narrowing per `$stateKey` e `$state`

7. **Modules/UI/app/Filament/Tables/Columns/SelectStateColumn.php**
   - Corretto type narrowing per `$dayLabel` in `OpeningHoursRule`

8. **Modules/UI/app/Filament/Widgets/UserCalendarWidget.php**
   - Corretto type narrowing per `Str::of()`

9. **Modules/UI/app/Rules/OpeningHoursRule.php**
   - Corretto type narrowing per `$dayLabel` con cast esplicito

## Pattern Applicati

### Type Narrowing
- Uso di `is_string()`, `is_array()`, `is_object()` prima dell'uso
- Cast espliciti con PHPDoc `@var`

### Array Type Safety
- Verifica `is_iterable()` prima di foreach
- Uso di `array-key` per chiavi array

### Closure Type Hints
- Type hints espliciti per parametri closure
- Return types espliciti per closure

## Risultati

- **Errori PHPStan**: 0
- **File corretti**: 9
- **Pattern applicati**: Type narrowing, Array safety, Closure hints

