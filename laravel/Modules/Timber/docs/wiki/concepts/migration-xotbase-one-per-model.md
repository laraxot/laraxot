---
title: "Timber (ex TimberBilling) — XotBaseMigration 1:1 model"
type: concept
module: Timber (ex TimberBilling)
tags: [timber, migrations, xotbasemigration, religion]
created: 2026-07-27
updated: 2026-07-27
qmd: "timber XotBaseMigration one model one migration never Illuminate Schema create"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - ../../models-migrations-seeders-factories.md
  - ../../bugfix-users-cross-db-fk.md
  - ../../../../../docs/wiki/rules/009-one-migration-per-model.md
  - ../../../../../docs/wiki/memories/xotbasemigration-only-never-illuminate.md
---

# Migrazioni Timber — solo XotBase, 1:1

## Zen

Tabella = frase del Model. Migration = unico posto che la dichiara.  
`XotBaseMigration` legge il Model; non inventare nomi tabella in stringa.

## Template

```php
return new class() extends XotBaseMigration
{
    protected ?string $model_class = TimberLot::class;

    public function up(): void
    {
        $this->tableCreate(function (Blueprint $table): void {
            $table->id();
            $table->foreignIdFor(TimberPurchaseRecord::class)->constrained()->cascadeOnDelete();
            // colonne dominio — NO timestamps()
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps(table: $table, hasSoftDeletes: false);
        });
    }
};
```

## Vietato

- `extends Migration`
- `Schema::create('timber_…')` / `Schema::table('timber_…')`
- `add_*` / `drop_*` / file batch multi-`create`
- Pivot senza model + Migration plain

## Batch storico

`create_timber_tables.php` (N tabelle in 1 file) = **debito estinto**. Non reintrodurre.
