# Laravel Migration Conventions (XotBaseMigration)

## Core Principles

### 1. Extend XotBaseMigration, Never Migration Directly
Every migration in this application **must** extend `Modules\Xot\Database\Migrations\XotBaseMigration` instead of `Illuminate\Database\Migrations\Migration`.

**Correct:**
```php
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class() extends XotBaseMigration {
    // migration content
};
```

**Incorrect:**
```php
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration {
    // migration content
};
```

### 2. Never Hardcode Table Names
XotBaseMigration automatically derives the table name from the model class. **Never** write table names directly in your migrations.

**Correct Usage (with XotBaseMigration):**
```php
public function up(): void
{
    $this->tableCreate(function (Blueprint $table): void {
        // $this->getTable() will return the correct table name
        $table->id();
        $table->foreignIdFor(\Modules\MyModule\Models\MyModel::class);
    });
}
```

**Incorrect Usage:**
```php
public function up(): void
{
    Schema::create('my_models', function (Blueprint $table): void {
        $table->id(); // Hardcoded table name
    });
}
```

### 3. One Migration Per Model
Each Eloquent model should have exactly one corresponding migration file. Exceptions include:
- Pivot tables for polymorphic relationships (must be documented)
- Migration-only utility functions
- Database seed data (should use seeders, not migrations)

### 4. File Naming Convention
Migration files **must** follow this exact format:
```
YYYY_MM_DD_HHMMSS_create_<model_name>_table.php
```

Examples:
- `2026_07_07_100000_create_timber_customers_table.php`
- `2026_07_07_100100_create_timber_products_table.php`
- `2026_07_07_100200_create_timber_stock_movements_table.php`

### 5. No protected $connection Property
Do not declare `$connection` in your migrations. XotBaseMigration automatically handles database connections based on the model.

**Correct:**
```php
// No connection property needed
public function up(): void { /* ... */ }
```

**Incorrect:**
```php
protected string $connection = 'my_connection'; // Remove this!
```

### 6. Use XotBaseMigration Helper Methods
Always prefer XotBaseMigration methods over raw Schema calls:

- `$this->tableCreate(Closure $callback)` instead of `Schema::create()`
- `$this->tableUpdate(Closure $callback)` instead of `Schema::table()`
- `$this->foreignIdFor(string $class, ?string $column = null)` instead of `$table->foreignId()->constrained()`
- `$this->updateTimestamps(Blueprint $table, bool $hasSoftDeletes = false)` for timestamps
- `$this->dropTableIfExists(string $table)` for dropping tables

### 7. Timestamps Handling
Use XotBaseMigration's timestamp methods instead of manual timestamp columns:

**Correct:**
```php
$this->updateTimestamps($table, true); // Includes soft deletes
```

**Incorrect:**
```php
$table->timestamps();
$table->softDeletes(); // Manual approach
```

## Why These Conventions Exist

### Philosophy
The conventions exist to create a **single source of truth** for database schema. By deriving table names and structures from model classes, we ensure:
- **Consistency**: Database schema matches the application's data model exactly
- **Maintainability**: Schema changes flow naturally from model changes
- **Reduced Errors**: Eliminates typos in table/column names
- **DRY Principle**: Don't Repeat Yourself - schema definition lives in one place

### Technical Benefits
1. **Model-First Development**: The model defines the database, not vice-versa
2. **Automatic Connection Handling**: XotBaseMigration resolves the correct DB connection from the model
3. **Foreign Key Safety**: Proper handling of cross-database foreign keys (users table in separate DB)
4. **Soft Delete Management**: Consistent handling of deleted_at/created_by/updated_by/deleted_by columns
5. **UUID/BIGINT Migration Support**: Built-in support for migrating from UUID to BIGINT primary keys

### Architecture Alignment
These conventions support the application's **modular monolith** architecture by:
- Keeping each module self-contained with its own migrations
- Ensuring migrations can run in any order (within reason) without breaking
- Providing a clear migration path when extracting modules to separate packages
- Supporting the XotBase pattern for cross-cutting concerns (auditing, soft deletes, etc.)

## Special Cases & Exceptions

### Pivot Tables
Pivot tables for polymorphic relationships sometimes require direct `Migration` extension, but **must be documented** with:
```php
/*
 * Pivot polymorphic table for ModelA::morphMany(ModelB::class, ...)
 * Exception to XotBaseMigration rule documented in:
 * docs/wiki/rules/009-one-migration-per-model.md § Exception 2
 */
return new class() extends Migration {
    // migration content
};
```

### Cross-Database References
When referencing tables in other database connections (like `users` in the `user` connection):
- Never use `->constrained('other_connection.table')` 
- Always use `->foreignIdFor($modelClass)->nullable()->index()` 
- Handle the foreign key relationship at the application level, not DB level

## Verification

To verify your migration follows conventions:
1. Run `php artisan migrate:fresh` and ensure it completes successfully
2. Check that your model's `$table` property matches what would be generated
3. Verify no hardcoded table names appear in your migration
4. Confirm you extend `XotBaseMigration` (unless documented exception)
5. Ensure you use XotBaseMigration helper methods

## Examples from the Codebase

### Standard Model Migration
See: `Modules/TimberBilling/database/migrations/2026_07_07_100000_create_timber_customers_table.php`

### Migration with Soft Deletes
See: `Modules/TimberBilling/database/migrations/2026_07_24_000501_create_timber_invoices_table.php`

### Migration with Cross-DB Reference (users table)
See: `Modules/TimberBilling/database/migrations/2026_07_07_100200_create_timber_stock_movements_table.php`

## Enforcement

These conventions are enforced by:
- Code review process
- Automated checks in CI pipeline
- PHPStan rules (`laraxot-model-rules`, `laraxot-module-dev`)
- Team knowledge sharing and documentation

## Frequently Asked Questions

**Q: What if I need to add columns to an existing table?**
A: Use `$this->tableUpdate()` with a closure, just like `tableCreate()`.

**Q: How do I handle database seed data?**
A: Use Laravel seeders (`database/seeders/`), not migrations.

**Q: What about changing column types or dropping columns?**
A: Still use `$this->tableUpdate()` - XotBaseMigration handles the Schema facade internally.

**Q: My model doesn't exist yet - should I create the migration first?**
A: Create the model first, then the migration. The migration needs the model class to resolve the table name.

## Further Reading
- `docs/wiki/rules/009-one-migration-per-model.md`
- `Modules/Xot/app/Database/Migrations/XotBaseMigration.php` (source)
- `docs/wiki/concepts/xotbase-architecture.md`