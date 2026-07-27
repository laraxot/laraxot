# XotBaseMigration Convention — The Universal Religion

## Overview

This document defines the **universal migration convention** used across ALL modules and themes in this Laravel application. It is the single source of truth for how database migrations must be written.

## The Doctrine

### 1. One Migration Per Model
Every Eloquent model has exactly ONE `create_*_table.php` migration. No more, no less.

### 2. Extend XotBaseMigration Only
```php
// CORRECT
use Modules\Xot\Database\Migrations\XotBaseMigration;
return new class() extends XotBaseMigration { }

// WRONG
use Illuminate\Database\Migrations\Migration;
return new class() extends Migration { }
```

### 3. Table Name From Model
```php
protected ?string $model_class = MyModel::class;

// Then use:
$this->tableCreate(fn($table) => $table->id());
// NOT: Schema::create('my_models', ...)
```

### 4. No Hardcoded Connections
```php
// NEVER do this:
protected string $connection = 'my_db';

// XotBaseMigration resolves connection from $model->getConnectionName()
```

### 5. Foreign Keys via foreignIdFor
```php
// For cross-DB references (users in separate DB):
$uc = XotData::make()->getUserClass();
$table->foreignIdFor($uc, 'created_by_user_id')->nullable()->index();
// NO ->constrained() on cross-DB tables

// For same-DB:
$table->foreignIdFor(MyModel::class)->constrained()->cascadeOnDelete();
```

### 6. Timestamps via updateTimestamps
```php
$this->updateTimestamps($table, true); // true = soft deletes
// Handles: created_at, updated_at, deleted_at, created_by, updated_by, deleted_by
```

### 7. Naming Pattern
```
YYYY_MM_DD_HHMMSS_create_<model_name>_table.php
```
- `model_name` = snake_case singular of model class name
- Example: `2026_07_07_100000_create_timber_customers_table.php` for `TimberCustomer`

---

## Migration Lifecycle

### CREATE (tableCreate)
```php
$this->tableCreate(function (Blueprint $table): void {
    $table->id();
    // ... columns ...
    $this->updateTimestamps($table, true);
});
```

### UPDATE (tableUpdate) - for ALTER TABLE
```php
$this->tableUpdate(function (Blueprint $table): void {
    if (! $this->hasColumn('new_col')) {
        $table->string('new_col')->nullable()->index();
    }
    $this->updateTimestamps($table, true);
});
```

### DROP
```php
public function down(): void
{
    $this->dropTableIfExists($this->getTable());
}
```

---

## Prohibited Patterns

| Pattern | Why |
|---------|-----|
| `extends Migration` | Bypasses XotBaseMigration helpers |
| `Schema::create('table', ...)` | Hardcodes table name |
| `protected $connection` | Connection resolved from model |
| `->constrained('users')` cross-DB | MySQL 1824 error |
| Multiple `create_*` for same model | Violates single responsibility |
| `Schema::table` without tableUpdate | No idempotency |

---

## Validation Checklist

- [ ] Extends `XotBaseMigration`
- [ ] Has `protected ?string $model_class = Model::class;`
- [ ] Uses `$this->tableCreate()` for CREATE
- [ ] Uses `$this->tableUpdate()` for ALTER
- [ ] Uses `$this->foreignIdFor()` for FK
- [ ] Uses `$this->updateTimestamps()`
- [ ] No hardcoded table names
- [ ] No `protected $connection`
- [ ] File name matches `create_<model>_table.php`
- [ ] One file per model

---

## Applying Across Modules

This convention applies to:
- ALL `Modules/*/database/migrations/`
- ALL `Themes/*/database/migrations/`
- ALL new migrations created via `php artisan make:migration` (then convert to XotBaseMigration)

---

## Enforcement

- PHPStan rules: `laraxot-model-rules`, `laraxot-module-dev`
- Code review gates
- CI pipeline validation
- Memory: `feedback_xotbase_migration_model_guessing.md`