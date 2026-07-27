---
title: "foreignIdFor — convenzione migrazioni TimberBilling"
type: concept
tags: [migrations, foreignIdFor, timberbilling, xotbasemigration]
created: 2026-07-27
updated: 2026-07-27
qmd: "foreignIdFor timber billing migration convention XotBaseMigration"
related:
  - ../../../../docs/wiki/concepts/migration-foreign-id-for.md
  - ./bugfix-pivot-no-xotbase-migration.md
  - ./bugfix-users-cross-db-fk.md
  - ./models-migrations-seeders-factories.md
---

# foreignIdFor — TimberBilling

## Regola

Tutte le migrazioni TimberBilling estendono `XotBaseMigration` e usano `foreignIdFor(Model::class, $column)` invece di `foreignId('column_name')`.

## Perché

| `foreignId('column')` | `foreignIdFor(Model::class, 'column')` |
|---|---|
| Assume `bigint` — sbagliato per UUID | Legge `$keyType` dal modello |
| Hardcoded table name nel `constrained()` | Deriva table name dal modello |
| Fragile al rename del model | Refactor-safe |
| Viola DRY (tipo duplicato nel model) | Single source of truth nel model |

## Pattern canonico

```php
use Modules\TimberBilling\Models\TimberInvoice;
use Modules\Xot\Datas\XotData;

$userClass = XotData::make()->getUserClass();

$table->foreignIdFor(TimberInvoice::class, 'invoice_id')->constrained('timber_invoices')->cascadeOnDelete();
$table->foreignIdFor($userClass, 'created_by_user_id')->nullable()->index();
```

## Cross-DB (User)

User su connection diversa → **mai** `constrained()`:

```php
$table->foreignIdFor($userClass, 'user_id')->nullable()->index();
```

## Eccezioni

- Pivot senza model Eloquent → `extends Migration` plain (vedi `bugfix-pivot-no-xotbase-migration.md`)
- Tabelle con nome >64 char → usare `indexName` esplicito (vedi `bugfix-mysql-identifier-64.md`)

## Checklist

1. [ ] `extends XotBaseMigration` (non `Migration`)
2. [ ] `$this->tableCreate()` / `$this->tableUpdate()` (non `Schema::create`)
3. [ ] `foreignIdFor(Model::class, $column)` (non `foreignId($column)`)
4. [ ] `$this->updateTimestamps($table, hasSoftDeletes: true)` (non `$table->timestamps()`)
5. [ ] Cross-DB → no `constrained()`
6. [ ] User → `XotData::make()->getUserClass()`
