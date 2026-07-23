---
title: "Eloquent: isset() vs property_exists()"
type: concept
tags: [eloquent, isset, property, exists]
created: 2026-07-14
updated: 2026-07-14
qmd: "eloquent-isset-vs-property-exists eloquent: isset() vs property_exists()"
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

# Eloquent: isset() vs property_exists()

## Regola Fondamentale

**MAI usare `property_exists()` con Eloquent Model**

**SEMPRE usare `isset()` per magic properties**

## Perché

Eloquent Model usa `__get()` e `__isset()` magic methods per accedere agli attributi del database.

Gli attributi NON sono proprietà PHP reali dichiarate nella classe.

## Pattern Corretti nel Modulo UI

### IconStateColumn.php

```php
// ✅ CORRETTO - Usa isset()
if (isset($record->state)
    && is_object($record->state)
    && method_exists($record->state, 'transitionTo')) {
    $record->state->transitionTo($state, $message);
}

// ❌ SBAGLIATO - Non funziona
if (property_exists($record, 'state')) {
    // Mai eseguito!
}
```

### SelectStateColumn.php

```php
// ✅ CORRETTO - isset() per magic properties
if (isset($state->{$name})) {
    $stateValue = $state->{$name};
}

// ✅ CORRETTO - isset() per record state
if (isset($record->state)
    && is_object($record->state)
    && method_exists($record->state, 'transitionTo')) {
    $record->state->transitionTo($state, $message);
}
```

### IconStateSplitColumn.php

```php
// ✅ CORRETTO - isset() per state access
if (isset($record->state) && is_object($record->state)) {
    if (method_exists($record->state, 'canTransitionTo')) {
        return $record->state->canTransitionTo($stateClass);
    }
}
```

### UserCalendarWidget.php

```php
// ✅ CORRETTO - Type checking con is_object e method_exists
if (is_object($modelInstance) && method_exists($modelInstance, 'getModel')) {
    $modelResult = $modelInstance->getModel();
}

// Per Eloquent models:
if (isset($model->attribute)) {
    $value = $model->attribute;
}
```

## Quick Reference

| File | Linea | Pattern |
|------|-------|---------|
| IconStateColumn.php | 141 | `isset($record->state)` ✅ |
| IconStateColumn.php | 214 | `isset($record->state)` ✅ |
| SelectStateColumn.php | 42 | `isset($state->{$name})` ✅ |
| SelectStateColumn.php | 67 | `isset($record->state)` ✅ |
| IconStateSplitColumn.php | 99 | `isset($record->state)` ✅ |
| IconStateSplitColumn.php | 214 | `isset($record->state)` ✅ |

## PHPStan Level 10

Tutti i file del modulo UI sono PHPStan Level 10 compliant usando `isset()`.

## See Also

- `Modules/Xot/docs/eloquent-magic-properties-rule.md` - Guida completa
- `Modules/Xot/docs/eloquent-properties-best-practices.md` - Best practices

---

**Ultimo aggiornamento**: [DATE]
**Status**: ✅ PHPStan Level 10 - 0 Errors
