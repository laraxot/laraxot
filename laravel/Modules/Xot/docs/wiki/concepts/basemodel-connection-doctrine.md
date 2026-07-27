# BaseModel Connection Doctrine

## Philosophy: Connection Management for Multi-Database Architecture

Every `Modules/<Module>/app/Models/BaseModel.php` MUST declare its connection explicitly via `protected $connection = '<module_connection>';`. This is NOT optional.

### Why Protected $connection is Mandatory

1. **Multi-Tenant Architecture**: The application uses multiple database connections simultaneously
2. **Model Isolation**: Each module's models must explicitly declare which database they operate on
3. **Connection Resolution**: XotBaseMigration uses the model's connection property to determine where to create tables
4. **Runtime Safety**: Prevents queries from hitting wrong database (MySQL error 1824 on FK constraints)

### Database Connections Map

| Module | Connection | Database |
|--------|------------|----------|
| User | user | workorder_user |
| Xot | xot | workorder_data (default) |
| TimberBilling | timber_billing | workorder_data |
| Notify | notify | workorder_data |
| Activity | activity | workorder_data |
| Media | media | workorder_data |
| Customer | customer | workorder_data |
| Rating | rating | workorder_data |
| Cms | cms | workorder_data |
| Quotation | quotation | workorder_data |
| Document | document | workorder_data |
| Signature | signature | workorder_data |
| Lang | lang | workorder_data |
| WorkOrder | work_order | workorder_data |
| Email | email | workorder_data |
| Geo | geo | workorder_data |
| Job | job | workorder_data |
| Intervention | intervention | workorder_data |
| Employee | employee | workorder_data |
| WhatsApp | whats_app | workorder_data |
| Gdpr | gdpr | workorder_data |
| Vehicle | vehicle | workorder_data |
| UI | u_i | workorder_data |
| Tenant | tenant | workorder_data |
| AiAssistant | ai_assistant | workorder_data |

### BaseModel Implementation Template

```php
<?php
declare(strict_types=1);

namespace Modules\YourModule\Models;

use Modules\Xot\Models\XotBaseModel;

abstract class BaseModel extends XotBaseModel
{
    /**
     * The connection name for this module's database.
     * 
     * @see config/database.php for connection definitions
     * @see each module's connection config in config/your-module/database.php
     */
    protected $connection = 'your_module';

    /** @return array<string, string> */
    protected function casts(): array
    {
        return [
            'id' => 'integer',  // MUST inherit from XotBaseModel's casting strategy
            'uuid' => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
            'created_by' => 'string',
            'updated_by' => 'string',
            'deleted_by' => 'string',
        ];
    }
}
```

### Critical ID/Type Consistency Rules

#### When XotBaseModel sets `'id' => 'string'`
- Table MUST have `id` as `char(36)` or `varchar(36)`
- Used for modules with UUID primary keys

#### When BaseModel overrides `'id' => 'integer'`
- Table MUST have `id` as `bigint unsigned auto_increment`
- Used for modules with traditional auto-increment IDs

**The Profile Model Issue (2026-07-27)**

The error `SQLSTATE[HY000]: General error: 1364 Field 'id' doesn't have a default value` occurred because:

1. **XotBaseModel declares**: `'id' => 'string'` (UUID expectation)
2. **BaseProfile declares**: `'id' => 'integer'` (auto-increment expectation)
3. **profiles table has**: `bigint unsigned auto_increment` (correct for auto-increment)
4. **Eloquent mismatch**: Model casts 'id' as string, but DB is auto-increment bigint

This is resolved in migration `2026_07_27_120000_create_profiles_table.php` which uses:
```php
convertIdFromUuidToBigintIfNeeded(...)
```

This method detects legacy UUID-id schemas and rebuilds them as auto-increment bigint while preserving existing data.

### Connection Naming Convention

- **Lowercase snake_case** of module name
- **Example**: `AiAssistant` → `ai_assistant`
- **Match** the connection name in `config/database.php`

### Exception Handling

Never omit `$connection` property. If you do:
- XotBaseMigration falls back to `getConn()->hasDatabaseName()` check
- Queries may hit wrong database on multi-connection setups
- FK constraints fail with MySQL error 1824

**Religion holds:** Every BaseModel, without exception, must declare its connection explicitly.