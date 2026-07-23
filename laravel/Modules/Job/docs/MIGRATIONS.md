# Job Module — Migrations Documentation

## Overview

This document describes the migration strategy for the Job module, which manages job scheduling, execution, monitoring, and result tracking.

**Module Path:** `laravel/Modules/Job/`

**Parity Status:** 15 concrete models, 13 migrations (2-model mismatch)

---

## Philosophy

Each migration represents the schema lifecycle for one or more models. Migrations follow the **XotBaseMigration pattern**:

1. **Single model per migration**: Each migration declares its model via `protected ?string $model_class`
2. **Idempotent operations**: Use `$this->tableCreate()` and `$this->tableUpdate()` to prevent "already exists" errors
3. **Audit columns built-in**: `$this->updateTimestamps()` adds `created_by`, `updated_by`, `deleted_by` automatically
4. **No hardcoded table names or connections**: Both derive from model

---

## Models & Migrations Table

| # | Model | Status | Migration | Table | Notes |
|---|-------|--------|-----------|-------|-------|
| 1 | `Job` | ✓ Migrated | `2022_03_01_000004_create_jobs_table.php` | `jobs` | Core job queue model |
| 2 | `JobBatch` | ✓ Migrated | `2022_02_17_124030_create_job_batches_table.php` | `job_batches` | Job batch grouping |
| 3 | `Schedule` | ✓ Migrated | `2023_02_26_172600_create_schedule_table.php` | `schedules` | Scheduled job definitions |
| 4 | `ScheduleHistory` | ✓ Migrated | `2023_02_26_175242_create_schedule_histories_table.php` | `schedule_histories` | Audit trail for schedule runs |
| 5 | `Frequency` | ✓ Migrated | `2023_03_13_000000_create_frequencies_table.php` | `frequencies` | Job frequency/recurrence patterns |
| 6 | `Parameter` | ✓ Migrated | `2023_03_13_000000_create_parameters_table.php` | `parameters` | Job parameters/config |
| 7 | `Result` | ✓ Migrated | `2023_03_13_000000_create_results_table.php` | `results` | Job execution results |
| 8 | `Task` | ✓ Migrated | `2023_03_13_000000_create_tasks_table.php` | `tasks` | Task definitions |
| 9 | `JobManager` | ✓ Migrated | `2024_01_01_000000_create_job_manager_table.php` | `job_managers` | Job manager metadata |
| 10 | `FailedJob` | ✓ Migrated | `2024_01_01_000001_create_failed_jobs_table.php` | `failed_jobs` | Failed job logging |
| 11 | `Import` | ✓ Migrated | `2024_01_01_000002_create_imports_table.php` | `imports` | Bulk import records |
| 12 | `Export` | ✓ Migrated | `2024_03_12_082158_create_exports_table.php` | `exports` | Export tracking (Filament) |
| 13 | `FailedImportRow` | ✓ Migrated | `2024_03_12_082158_create_failed_import_rows_table.php` | `failed_import_rows` | Failed import rows audit |
| — | **Base Classes** | — | — | — | — |
| — | `BaseModel` (abstract) | — | — | — | Base for all Job models |
| — | `BaseMorphPivot` (abstract) | — | — | — | Polymorphic pivot base |
| — | **Aliases (no table)** | — | — | — | — |
| 14 | `JobsWaiting` | ⊂ Job | (same as Job) | `jobs` | Alias for `Job` model; uses `jobs` table |
| — | **Missing Migrations** | ✗ NO | — | — | — |
| 15 | `TaskComment` | ✗ MISSING | (none) | `task_comments` | **ISSUE**: Table defined but no migration |

---

## Parity Analysis

### Summary
- **Concrete models:** 15 (13 with migrations + 2 without)
- **Migrations:** 13
- **Mismatch:** +2 models

### Breakdown

#### ✓ Models with Migrations (13)
1. Job
2. JobBatch
3. Schedule
4. ScheduleHistory
5. Frequency
6. Parameter
7. Result
8. Task
9. JobManager
10. FailedJob
11. Import
12. Export
13. FailedImportRow

#### ✗ Models without Migrations (2)

**1. JobsWaiting** (alias, expected)
- Parent: `Job`
- Table: Inherits `jobs` table from parent
- Status: ✓ EXPECTED (no separate table needed)
- Action: No migration required

**2. TaskComment** (standalone, unexpected)
- Parent: `BaseModel`
- Table: `task_comments` (declared in model)
- Status: ✗ **ISSUE** — Table exists in code but no migration
- Action: **CREATE migration** `create_task_comments_table.php`

---

## Key Models Explained

### Queue Management
- **Job** (`jobs`): Laravel queue job records (core)
- **JobBatch** (`job_batches`): Batch job grouping and coordination
- **FailedJob** (`failed_jobs`): Failed job archive
- **JobsWaiting** (alias): Query-focused view of waiting jobs (uses `jobs` table)

### Scheduling
- **Schedule** (`schedules`): Scheduled job definitions
- **ScheduleHistory** (`schedule_histories`): Audit trail of schedule runs
- **Frequency** (`frequencies`): Recurrence patterns (e.g., daily, weekly)

### Task & Result Tracking
- **Task** (`tasks`): Individual task definitions
- **TaskComment** (`task_comments`): Comments on tasks (⚠️ no migration)
- **Result** (`results`): Task execution results
- **Parameter** (`parameters`): Job/task parameters and config

### Import/Export
- **Import** (`imports`): Bulk import records
- **FailedImportRow** (`failed_import_rows`): Failed import row audit
- **Export** (`exports`): Export tracking (Filament export integration)

### Management
- **JobManager** (`job_managers`): Job manager status and metadata

---

## Connection Strategy

**Default:** All models use the default database connection (`mysql`).

**Custom connections:** None currently. If needed, add `protected $connection = 'custom_db';` to the model, and the migration will automatically derive it.

---

## XotBaseMigration Pattern

All Job module migrations MUST extend `XotBaseMigration`. Here is the template:

```php
<?php
declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Job\Models\YourModel;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration {
    protected ?string $model_class = YourModel::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->string('column_name');
            // ... other columns
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table);
        });
    }
};
```

### Key Methods

| Method | Purpose |
|--------|---------|
| `$this->tableCreate($closure)` | Create table (idempotent) |
| `$this->tableUpdate($closure)` | Modify table (idempotent) |
| `$this->hasColumn($col)` | Check if column exists |
| `$this->updateTimestamps($table, $softDeletes = false)` | Add audit columns + soft delete column (if true) |
| `$this->getTable()` | Get table name (from model) |
| `$this->getConn()` | Get connection (from model) |

### Soft Deletes Example

```php
$this->tableUpdate(function (Blueprint $table): void {
    // Pass true to include deleted_at, deleted_by columns
    $this->updateTimestamps($table, $softDeletes = true);
});
```

---

## Known Issues

### 1. TaskComment — Missing Migration
- **Status:** ✗ CRITICAL
- **Model Path:** `app/Models/TaskComment.php`
- **Issue:** Table `task_comments` declared in model but no migration file exists
- **Impact:** Schema cannot be version-controlled or reliably deployed
- **Resolution:** Create migration `create_task_comments_table.php` following XotBaseMigration pattern
- **Columns needed** (inferred from TaskComment model):
  - `id` (primary key)
  - `task_id` (FK to tasks)
  - `user_id` (FK to users)
  - `comment` (text)
  - Audit columns: `created_at`, `updated_at`, `deleted_at`, `created_by`, `updated_by`, `deleted_by`

### 2. Multiple Migrations with Same Timestamp
- Files `2023_03_13_000000_*` (4 files) and `2024_03_12_082158_*` (2 files) share timestamps
- **Status:** ⚠️ ADVISORY
- **Impact:** None if all migrations are idempotent (which they are via XotBaseMigration)
- **Best Practice:** Recommend staggering timestamps for clarity in `php artisan migrate:status`
- **Example:** Change to `2023_03_13_000001`, `2023_03_13_000002`, etc.

---

## Verification Checklist

Before shipping or modifying migrations:

- [ ] **Model count:** Verify all models have a corresponding migration (or are intentional aliases)
  ```bash
  php artisan tinker
  >>> count(collect(glob('app/Models/*.php'))->filter(fn($f) => !str_contains($f, 'Base')))
  => 15
  >>> count(glob('database/migrations/*.php'))
  => 13
  ```

- [ ] **XotBaseMigration compliance:** All migrations extend XotBaseMigration
  ```bash
  grep -r "extends Migration" laravel/Modules/Job/database/migrations/
  # Should return: (empty)
  grep -r "extends XotBaseMigration" laravel/Modules/Job/database/migrations/ | wc -l
  # Should return: 13
  ```

- [ ] **No hardcoded table names:** Migrations derive table from model
  ```bash
  grep -r "Schema::create\|Schema::table" laravel/Modules/Job/database/migrations/
  # Should return: (empty)
  ```

- [ ] **PHPStan L10 clean:** Migrations pass static analysis
  ```bash
  phpstan analyse laravel/Modules/Job/database/migrations/ --level=10
  # Should return: 0 errors
  ```

- [ ] **PHPMD clean:** Code quality standards
  ```bash
  tools/phpmd.sh laravel/Modules/Job/database/migrations/
  # Should return: 0 violations
  ```

- [ ] **Database tests pass:** Schema matches model expectations
  ```bash
  php artisan test Modules/Job/tests/ --filter="DatabaseConnection\|Migration"
  ```

---

## Migration Deployment

### Development
```bash
cd laravel
php artisan migrate --path=Modules/Job/database/migrations/
```

### Production
```bash
# Verify all migrations are XotBaseMigration compliant
phpstan analyse Modules/Job/database/migrations/ --level=10

# Run migrations with no-interaction for CI/CD
php artisan migrate --force --path=Modules/Job/database/migrations/
```

### Rollback
```bash
php artisan migrate:rollback --path=Modules/Job/database/migrations/
```

---

## Related Documentation

- **Pattern Reference:** See `Modules/Xot/docs/migrations.md` for XotBaseMigration detailed docs
- **Audit Trail:** See `docs/chat/migration-xot-base-standardization.md` for module migration audit status
- **Module Models:** See `app/Models/` for all 15 concrete models
- **Factories:** See `database/factories/` for model factories (used in tests and seeding)

---

## Session Notes

**Created:** 2026-07-15  
**Analysis:** Full parity audit (15 models vs 13 migrations)  
**Key Finding:** TaskComment model missing migration — requires creation  
**XotBaseMigration Status:** 13/13 migrations conform  

---
