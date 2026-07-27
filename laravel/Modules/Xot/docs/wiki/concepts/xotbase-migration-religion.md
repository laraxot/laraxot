---
title: "XotBaseMigration — religione delle migrazioni"
type: concept
module: Xot
tags: [migrations, xot-base-migration, one-migration-per-model, conventions]
created: 2026-07-27
updated: 2026-07-27
qmd: "XotBaseMigration migration convention create table model updateTimestamps no Migration class"
issues:
  - "https://github.com/laraxot/base_techplanner_fila5/issues/38"
discussions:
  - "https://github.com/laraxot/base_techplanner_fila5/discussions/12"
related:
  - ./migration-foreign-id-for.md
  - ./migration-update-timestamps-only.md
  - ../../../../../docs/wiki/concepts/basemodel-connection-religion.md
  - ../../../../../docs/wiki/bmad/architecture-one-migration-per-model.md
---

# XotBaseMigration — religione

## Zen (una frase)

Una tabella = un file `create_{table}_table.php` che estende `XotBaseMigration`; tabella e connection arrivano dal **Model** (`BaseModel::$connection`), non dalla migrazione.

## Perché (logica / politica / filosofia)

| Problema | Soluzione |
|----------|-----------|
| Typo su nome tabella | `getTable()` dal model |
| `$connection` hardcoded | connection del model (`BaseModel` / Tenant) |
| `extends Migration` + `Schema::create` | non idempotente; fail a metà → 1050 |
| `add_*_to_*` / `drop_*` separati | schema frammentato; riusare `tableUpdate` + bump timestamp |
| Timestamps/audit a mano | `updateTimestamps($table, true)` |
| FK `foreignId` / `constrained('users')` cross-DB | `foreignIdFor` — vedi [migration-foreign-id-for](./migration-foreign-id-for.md) |

## Prototipo obbligatorio

```php
<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration {
    public function up(): void
    {
        $this->tableCreate(static function (Blueprint $table): void {
            $table->id();
            // colonne dominio — NO timestamps/softDeletes qui se usi updateTimestamps
        });

        $this->tableUpdate(function (Blueprint $table): void {
            if (! $this->hasColumn('extra_field')) {
                $table->string('extra_field')->nullable();
            }

            $this->updateTimestamps($table, true); // softDeletes + audit
        });
    }
};
```

## Naming file

```
{YYYY_MM_DD_HHMMSS}_create_{table_snake}_table.php
```

Esempi:

- `2025_01_22_115959_create_teams_table.php` → model `Team`
- `2026_07_27_120004_create_timber_purchase_records_table.php` → `TimberPurchaseRecord`

**Vietato:** `add_owner_id_to_teams_table.php`, `fix_*`, `drop_*` sulla stessa tabella owner (evoluzione = edit create + **bump** timestamp).

## Cosa NON scrivere

- `protected string $connection` — la connection è del model
- `Schema::create('nome_tabella', …)` — usa `$this->tableCreate`
- `extends Migration` — solo `XotBaseMigration` (vendor/packages esclusi)
- `$table->timestamps()` + `$table->softDeletes()` + `created_by` a mano se chiami `updateTimestamps`
- **Editare `table_names`/`table_name` in un file di config di package** (es.
  `config('permission.table_names.*')` di spatie/laravel-permission) per farlo combaciare con lo
  schema fisico esistente. **La config è la convenzione scelta dall'utente/progetto — non si tocca
  per "far quadrare" un mismatch.** Se schema e config non combaciano, è lo **schema** ad essere
  sbagliato: si corregge con una migrazione vera (`Schema::rename` con il nome letto da
  `$this->getTable()`/dal model, non hardcoded — vedi punto sotto), mai editando la config per
  inseguire lo stato accidentale del database. Regola generale, non solo per spatie/permission:
  **le query vanno a prendere la tabella configurata, non il contrario.**
  Caso reale: [Modules/User/docs/bugfix-permission-table-names-singular.md](../../../../User/docs/bugfix-permission-table-names-singular.md).
- Passare un nome tabella **letterale** come secondo argomento a `tableCreate($closure, 'nome')` /
  `tableUpdate($closure, 'nome')` per bypassare `$model_class` — anche quando lo scopo è allineare lo
  schema alla config (il caso legittimo, sopra), il nome tabella target **deve** venire da
  `$this->getTable()` (cioè dal model, che a sua volta legge la config), mai da una stringa scritta a
  mano nella migrazione — altrimenti la migrazione stessa smette di seguire la config se questa
  cambia di nuovo in futuro. Il secondo argomento esiste **solo** per il caso limite di una tabella
  senza *alcun* model plausibile (vedi `timber_processing_step_processable` nell'audit
  `docs/chat/audit-models-migrations-seeders-factories.md`).
  Incidente reale (Passo 3): [Modules/User/docs/bugfix-permission-table-names-singular.md](../../../../User/docs/bugfix-permission-table-names-singular.md).

## `$model_class`

- Preferito: ricavato dal filename (`_create_{name}_table.php`)
- Opzionale esplicito: `protected ?string $model_class = Team::class` quando il mapping filename→model non è ovvio (pivot, plurali irregolari)

## X modelli → X migrazioni

Nel modulo con N modelli owner: N file `create_*` (+ factory + seeder). Mai un mega-file multi-tabella.

## Checklist agente

1. Grep `extends Migration` in `Modules/*/database/migrations` → convertire
2. Grep `add_.*_to_.*_table` → fondere in create + bump + `_bak/`
3. FK → `foreignIdFor`; User senza `constrained` cross-DB
4. `phpstan` / `phpmd` / `phpinsights` sul modulo toccato
5. Module git sync (`module-git-sync-after-fix`)

## Riferimenti

- Implementazione: `Modules/Xot/app/Database/Migrations/XotBaseMigration.php`
- Root: [xotbase-migration-religion](../../../../../docs/wiki/concepts/xotbase-migration-religion.md)
- BMAD: [one-migration-per-model](../../../../../docs/wiki/bmad/architecture-one-migration-per-model.md)
