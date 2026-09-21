---
title: "Rating Module — Migrations"
type: guide
tags: [migrations, rating]
created: 2026-07-14
updated: 2026-07-14
qmd: "MIGRATIONS"
related:
  - "./ON-DEMAND-PATTERN.md"
---

# Rating Module — Migrations

## Overview

All migrations in this module follow the XotBaseMigration pattern with model-driven connection and table resolution.

## Pattern

```php
return new class extends XotBaseMigration {
    protected ?string $model_class = MyModel::class;
    
    public function up(): void
    {
        // Create or modify table
        $this->tableCreate(function (Blueprint $table) { ... });
        $this->tableUpdate(function (Blueprint $table) { ... });
    }
};
```

Key rules:
- Migrations extend `XotBaseMigration` (never `Migration`)
- Table name and connection derive from Model, never hard-coded
- Use `tableCreate()` for initial schema
- Use `tableUpdate()` for columns, modifications, and audit timestamps
- Use `$this->hasColumn()` for existence checks
- Call `$this->updateTimestamps($table, $softDeletes)` for audit columns

## Migrations

### 2023_01_01_000005 — `create_rating_morph_table.php`
**Model:** `Rating` (pivot model)
**Table:** `rating_morph`
**Connection:** default (or custom from model)

Initial schema for `rating_morph` pivot table. Creates:
- Morph relationship columns (`model_type`, `model_id`)
- Foreign key to `ratings` table
- User relationship
- Value and note fields
- Winner flag and reward tracking

Audit columns added via `updateTimestamps()` with soft deletes.

### 2026_07_15_120003 — `create_rating_morph_table.php`
**Model:** `RatingMorph`
**Table:** `rating_morph`
**Connection:** default (or custom from model)

**Consolidation note:** This migration consolidates two previous migrations:
- Old 2026_03_27_000001 `add_percentage_to_rating_morph_table.php` ❌
- Old 2026_03_27_000002 `add_percentage_to_rating_morph_table_on_rating_connection.php` ❌

Both were non-conforming (used `add_` prefix) and duplicated the same operation on the same table. Consolidated into single idempotent `tableCreate()` / `tableUpdate()` flow.

Adds:
- `percentage` column (decimal(10,3), nullable) — calculated percentage for rating

The migration is idempotent: if table exists, it uses `tableUpdate()` to add columns only if missing.

## Model-Migration Parity

| Model | Migration | Table | Connection |
|-------|-----------|-------|------------|
| `Rating` | 2023_01_01_000005 | `ratings` | default |
| `RatingMorph` | 2026_07_15_120003 | `rating_morph` | default |

## History & Corrections

**Date:** 2026-07-15
**Scope:** Naming standard enforcement + migration consolidation
**Changes:**
- Removed non-conforming `add_percentage_to_rating_morph_table.php` (prefix violation)
- Removed non-conforming `add_percentage_to_rating_morph_table_on_rating_connection.php` (prefix + hardcoded connection violations)
- Created single consolidated `2026_07_15_120003_create_rating_morph_table.php`
- Added full documentation in this file

**Rationale:**
- Per migration naming standard: use `create_<table>_table.php` prefix, never `add_`
- Per XotBaseMigration pattern: connection derives from Model, never hard-coded
- Two separate migrations modifying the same table are operationally redundant
- Consolidated migration is idempotent via `hasColumn()` guards

## Testing

Run migrations:
```bash
php artisan migrate
```

Verify table structure:
```bash
php artisan tinker
>>> DB::table('rating_morph')->getColumns()
```

Verify audit columns:
```bash
>>> DB::table('rating_morph')->getColumns()
// Should show: created_at, updated_at, created_by, updated_by, deleted_at, deleted_by
```

## References

- XotBaseMigration: `Modules/Xot/app/Database/Migrations/XotBaseMigration.php`
- Pattern: `docs/wiki/patterns/migration-xot-base-pattern.md`
- Standard: `bashscripts/ai/.agents/rules/migration-naming-standard.md`
