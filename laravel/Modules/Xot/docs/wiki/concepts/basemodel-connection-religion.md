---
title: "BaseModel — protected $connection obbligatorio"
type: concept
module: Xot
tags: [basemodel, connection, eloquent, tenant, multi-db]
created: 2026-07-27
updated: 2026-07-27
qmd: "BaseModel protected connection mandatory module snake name TenantServiceProvider never null"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
related:
  - ./xotbase-migration-religion.md
  - ../../../../../../docs/wiki/concepts/basemodel-connection-religion.md
  - ../../../Tenant/docs/wiki/concepts/module-database-connections.md
  - ../../../Activity/docs/basemodel-connection-why-activity-not-null.md
---

# BaseModel — `protected $connection` (religione)

## Zen

La connessione vive nel **Model**, non nella migrazione. Ogni modulo dichiara il proprio DB una sola volta nel `BaseModel`.

## Legge

Ogni `Modules/{Module}/app/Models/BaseModel.php` **deve** contenere:

```php
/** @var string */
protected $connection = '{module_snake}';
```

Dove `{module_snake}` = `Module::find('{Module}')->getSnakeName()` (es. `WorkOrder` → `work_order`, `UI` → `u_i`).

## Perché (non `null`)

| `$connection = null` | `$connection = 'employee'` |
|---------------------|------------------------------|
| Usa solo `database.default` | Usa connessione modulo da `TenantServiceProvider` |
| Rompe `$connectionsToTransact` nei test | Rollback coerente per modulo |
| Impedisce `DB_DATABASE_{MODULE}` per tenant | Multi-DB per modulo possibile |

**Mai** `null` sul `BaseModel` del modulo.

## Catena

```
XotBaseModel ($connection = 'xot')
  └── Modules\{M}\Models\BaseModel ($connection = '{snake}')
        └── Modelli concreti (ereditano, non ridichiarare)
```

`XotBaseMigration` legge `$model_class::$connection` — **non** dichiarare `$connection` nella migrazione.

## Audit

```bash
for f in laravel/Modules/*/app/Models/BaseModel.php; do
  rg -q 'protected \$connection' "$f" || echo "MISSING: $f"
done
```

## Pivot / altre basi

`BasePivot`, `BaseMorphPivot` del modulo: stessa stringa `$connection` del `BaseModel` owner (vedi User, Geo, Cms).

## Vietato

- Omettere `$connection` e affidarsi solo a `XotBaseModel` (`xot`) nel modulo dominio
- `$connection` nella classe migrazione
- Stringa inventata diversa da `getSnakeName()`
