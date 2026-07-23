---
title: UI Module — Migrations & Schema
created: 2026-07-15
updated: 2026-07-15
type: module-documentation
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

# UI Module — Migrations & Schema Parity

## Philosophy

**One model → One migration → One seeder → One factory**

All models in UI module must maintain 1:1:1:1 parity with migrations, seeders, and factories. This ensures:
- Single source of truth for schema
- Forward-only migrations (no rollbacks)
- Complete audit trail (created_by, updated_by, deleted_by)
- Consistent data generation (seeder/factory pair)

---

## Models & Migrations (1:1 Parity)

| Model | Migration | Status | Notes |
|-------|-----------|--------|-------|
| Category | 2026_07_15_100000_create_categories_table.php | ✅ | Hierarchical categories with parent_id, soft deletes |
| Collection | 2026_07_15_100001_create_collections_table.php | ✅ | UI collections with theme_id, soft deletes |
| FieldOption | 2026_07_15_100002_create_field_options_table.php | ✅ | Field options with field_id foreign key, soft deletes |

**Total: 3 models → 3 migrations**

---

## Key Models Explained

### Category
- **Table**: `categories`
- **Purpose**: Hierarchical categories for organizing UI content
- **Columns**: id, name, title, slug, parent_id, description, icon, is_active, sort_order, created_by, updated_by, deleted_at, deleted_by, timestamps
- **Special Features**:
  - Hierarchical structure via `parent_id` (nullable, allows root-level categories)
  - Slug field for URL-friendly identifiers
  - Soft deletes for audit trail
  - Sort order for display ordering

### Collection
- **Table**: `collections`
- **Purpose**: Groupings of UI elements or content
- **Columns**: id, name, description, type, theme_id, is_active, order, created_by, updated_by, deleted_at, deleted_by, timestamps
- **Relations**:
  - `theme_id` — Optional reference to a theme
  - Type field for categorizing collections

### FieldOption
- **Table**: `field_options`
- **Purpose**: Options for form fields or select lists
- **Columns**: id, field_id, label, value, order, created_by, updated_by, deleted_at, deleted_by, timestamps
- **Special Features**:
  - Field_id as string (flexible foreign key approach)
  - Label/value pairs for form options
  - Order field for option ordering

---

## Connection Strategy

**Default connection:** `default` (MySQL on primary database)

**Custom connections:**
- None. All tables use default connection.
- If future scaling needs multi-database, update Model `$connection = 'custom_db'` and migration will derive it.

**Why centralize in model:**
- Single source of truth for connection
- Avoid hardcoded connections in N migration files
- Model is the business entity, database is implementation detail

---

## Migration Naming Convention

All migrations follow format:
```
YYYY_MM_DD_HHMMSS_create_<table>_table.php
```

✅ **Correct:**
- `2026_07_15_100000_create_categories_table.php`
- `2026_07_15_100001_create_collections_table.php`
- `2026_07_15_100002_create_field_options_table.php`

❌ **Incorrect (never use):**
- `2025_05_16_221811_add_icon_to_categories_table.php` ← Wrong: "add_" prefix
- `update_categories_table.php` ← Wrong: Missing timestamp
- `CreateCategoriesTable.php` ← Wrong: CamelCase naming

---

## Timestamp & Audit Columns

All tables include soft deletes and audit tracking:
```php
$this->updateTimestamps($table, $softDeletes = true);
```

**Added columns:**
- `created_at` — When record was created
- `updated_at` — When record was last updated
- `created_by` — User ID who created it
- `updated_by` — User ID who updated it
- `deleted_at` — When record was soft-deleted
- `deleted_by` — User ID who deleted it

**Why soft deletes:**
- Preserves historical data for audit trails
- Allows "restoration" without losing referential integrity
- Maintains created_by/updated_by visibility after deletion

---

## XotBaseMigration Pattern (Required)

Every migration MUST:
1. Extend `XotBaseMigration` (not `Migration`)
2. Specify `protected ?string $model_class`
3. Use `$this->tableCreate()` for creating tables
4. Use `$this->tableUpdate()` for schema modifications
5. Use `$this->updateTimestamps()` for audit columns with soft deletes

**Template:**
```php
<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\UI\Models\Category;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration {
    protected ?string $model_class = Category::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->string('slug')->index();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->text('description')->nullable();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table, true);  // true = soft deletes
        });
    }
};
```

---

## Recent Changes

| Date | Change | Ticket |
|------|--------|--------|
| 2026-07-15 | Created migration parity audit documentation | MIGRATIONS-UI-001 |
| 2026-07-15 | All 3 migrations conform to XotBaseMigration standard | MIGRATIONS-UI-001 |

---

## Verification Checklist

Before committing migration changes:

- [ ] Migration file name: `YYYY_MM_DD_HHMMSS_create_<table>_table.php`
- [ ] Extends `XotBaseMigration` (not `Migration`)
- [ ] Has `protected ?string $model_class = ModelClass::class;`
- [ ] Uses `$this->tableCreate()` and `$this->tableUpdate()`
- [ ] Includes `$this->updateTimestamps($table, true)` for audit columns and soft deletes
- [ ] No hardcoded `protected $connection` (derives from model)
- [ ] No `down()` method (auto-implemented)
- [ ] PHPStan L10: 0 errors on migration files
- [ ] PHPMD: No violations on migration files
- [ ] Parity verified: 3 models = 3 migrations

---

## Related Documentation

- **Pattern Reference**: `docs/wiki/patterns/migration-naming-and-parity-convention.md`
- **XotBaseMigration Mechanics**: `docs/wiki/patterns/migration-xot-base-pattern.md`
- **Module Independence**: `docs/wiki/patterns/module-independence.md`

---

## Discovery Commands

```bash
# Count concrete models in UI module (excluding base classes and policies)
grep -r "^class [A-Z].*extends.*Model" /var/www/_bases/base_ptvx_fila5/laravel/Modules/UI/app/Models/*.php | \
  grep -v "BaseModel" | grep -v "Policy" | wc -l

# Count migrations
ls /var/www/_bases/base_ptvx_fila5/laravel/Modules/UI/database/migrations/*.php | grep -v ".gitkeep" | wc -l

# Should both return 3!

# Verify all migrations conform to XotBaseMigration
grep -l "extends XotBaseMigration" /var/www/_bases/base_ptvx_fila5/laravel/Modules/UI/database/migrations/*.php | wc -l

# Should return 3
```

---

**Last updated:** 2026-07-15  
**Maintainer:** Marco (marco76tv)  
**Status:** ✅ Active — All 3 migrations conform to XotBaseMigration standard, 100% parity
