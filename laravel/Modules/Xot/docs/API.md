---
title: "Xot Module API"
type: reference
tags: [xot, api, framework]
created: 2026-07-28
---

# Xot Module — API

## BaseModel

```php
class MyModel extends XotBaseModel {
    // Provides:
    // - UUID primary key
    // - Timestamps (created_at, updated_at)
    // - Soft delete support (deleted_at)
    // - JSON casts
}
```

## Filament Builders

- `TableBuilder` — Fluent table configuration
- `FormBuilder` — Fluent form configuration
- `ColumnBuilder` — Column definitions

## Utilities

- `SafeStringCastAction` — Type-safe casting
- `SafeIntCastAction` — Integer casting
- `SafeFloatCastAction` — Float casting
