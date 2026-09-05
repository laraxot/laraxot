---
title: "Xot Module Patterns"
type: guide
tags: [xot, patterns, framework]
created: 2026-07-28
---

# Xot Module — Patterns

## Model Inheritance Pattern

✅ Always extend XotBaseModel for framework features:
```php
class User extends XotBaseModel {
    // Inherits: UUID PK, timestamps, soft delete, JSON casts
}
```

## Safe Type Casting Pattern

✅ Use SafeStringCastAction for string casting:
```php
use Modules\Xot\Actions\SafeStringCastAction;

$value = (new SafeStringCastAction)->execute($mixed);
```

## Migration Pattern

✅ Always extend XotBaseMigration:
```php
class CreateMyTable extends XotBaseMigration {
    public function getTableName(): string {
        return 'my_table';
    }
}
```
