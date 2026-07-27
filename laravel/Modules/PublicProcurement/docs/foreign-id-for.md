---
title: "foreignIdFor — convenzione migrazioni PublicProcurement"
type: concept
tags: [migrations, foreignIdFor, publicprocurement, xotbasemigration]
created: 2026-07-27
updated: 2026-07-27
qmd: "foreignIdFor public procurement migration convention XotBaseMigration"
related:
  - ../../../../docs/wiki/concepts/migration-foreign-id-for.md
  - ./bugfix-mysql-identifier-64.md
  - ./models-migrations-seeders-factories.md
---

# foreignIdFor — PublicProcurement

## Regola

Tutte le migrazioni PublicProcurement usano `foreignIdFor(Model::class, $column)` invece di `foreignId('column_name')`.

## Perché

| `foreignId('column')` | `foreignIdFor(Model::class, 'column')` |
|---|---|
| Assume `bigint` — sbagliato per UUID | Legge `$keyType` dal modello |
| Hardcoded table name nel `constrained()` | Deriva table name dal modello |
| Fragile al rename del model | Refactor-safe |
| Viola DRY (tipo duplicato nel model) | Single source of truth nel model |

## Pattern canonico

```php
use Modules\PublicProcurement\Models\PublicProcurementContract;
use Modules\Xot\Datas\XotData;

$userClass = XotData::make()->getUserClass();

$table->foreignIdFor(PublicProcurementContract::class, 'public_procurement_contract_id')->constrained()->cascadeOnDelete();
$table->foreignIdFor($userClass, 'created_by_user_id')->nullable()->index();
```

## Index name ≤64

Tabelle con nome lungo (`public_procurement_*`) generano indici auto che superano il limite MySQL di 64 caratteri. Usare sempre `indexName` esplicito:

```php
$table->index(['submission_deadline_at'], 'pp_tender_sub_deadline_idx');
```

## Checklist

1. [ ] `foreignIdFor(Model::class, $column)` (non `foreignId($column)`)
2. [ ] Index name esplicito ≤64 char per tabelle `public_procurement_*`
3. [ ] User → `XotData::make()->getUserClass()`
4. [ ] Cross-DB → no `constrained()`
