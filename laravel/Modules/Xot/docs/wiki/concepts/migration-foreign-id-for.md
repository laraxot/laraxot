---
title: "Migrazioni — foreignIdFor"
type: concept
module: Xot
tags: [migrations, foreign-key, foreignIdFor, xot-base-migration, cross-database]
created: 2026-07-27
updated: 2026-07-27
qmd: "migration foreignIdFor XotBaseMigration cross-database user_id constrained"
issues:
  - "https://github.com/laraxot/base_techplanner_fila5/issues/38"
discussions:
  - "https://github.com/laraxot/base_techplanner_fila5/discussions/12"
related:
  - ./migration-update-timestamps-only.md
  - ../../../../../docs/wiki/concepts/migration-foreign-id-for.md
  - ../../../../../docs/wiki/bmad/architecture-one-migration-per-model.md
  - ../../../../Tenant/docs/database-config-standard.md
  - ../../../../Blog/docs/wiki/concepts/migration-cross-database-user-refs.md
---

# foreignIdFor — religione delle FK in Laraxot

## Zen (una frase)

La colonna FK **deriva dal modello**, non dal nome tabella; il vincolo DB **solo se stessa connessione**; la relazione Eloquent è sempre la fonte di verità.

## Perché `foreignIdFor` e non `foreignId` / `string('user_id')`

| Anti-pattern | Problema |
|---|---|
| `$table->foreignId('user_id')` | Assume `bigint` — `BaseUser` usa `string` UUID (`$keyType = 'string'`, `$incrementing = false`) |
| `$table->string('user_id', 36)` | Duplica il tipo già definito nel modello — viola DRY |
| `->constrained('users')` | Assume tabella `users` sulla **stessa** connessione del migrante |
| `->constrained()` su moduli cross-DB | Errore MySQL 1824: *Failed to open the referenced table* |

`foreignIdFor(Model::class, $column)` legge dal modello:

- tipo colonna (`bigint` vs `uuid`/`string`)
- nome colonna default (`{snake(model)}_id`) se non passi il secondo argomento
- **nessun** vincolo DB finché non chiami esplicitamente `->constrained()`

## Politica `constrained()`

```php
// ✅ Stessa connessione, stesso database — FK DB ammessa
$table->foreignIdFor(Category::class)
    ->constrained()
    ->cascadeOnDelete();

// ✅ Cross-connessione (es. Blog → User) — NO constrained
$table->foreignIdFor(XotData::make()->getUserClass(), 'user_id')
    ->nullable()
    ->index();

// ❌ Cross-DB con constrained — migrate fallisce
$table->foreignIdFor($userClass, 'user_id')->constrained('users');
```

Connessioni modulari: `TenantServiceProvider::registerDB()` — ogni modulo ha una connection name (`blog`, `user`, …) che può puntare a **database diversi** (`workorder_data` vs `workorder_user`).

## User tenant-aware

Mai hardcodare `Modules\User\Models\User::class` nelle migrazioni:

```php
use Modules\Xot\Datas\XotData;

$userClass = XotData::make()->getUserClass();

$table->foreignIdFor($userClass, 'user_id')->nullable()->index();
```

Il tenant può sostituire la classe User senza toccare le migrazioni.

## Audit columns

`XotBaseMigration::updateTimestamps()` usa già `foreignIdFor($userClass, 'created_by'|'updated_by'|'deleted_by')`. Non duplicare `foreignId('created_by')` a mano.

## Pattern canonico in `tableCreate`

```php
use Modules\Blog\Models\Article;
use Modules\Xot\Datas\XotData;
use Modules\Xot\Database\Migrations\XotBaseMigration;

return new class extends XotBaseMigration {
    protected ?string $model_class = Comment::class;

    public function up(): void
    {
        $userClass = XotData::make()->getUserClass();

        $this->tableCreate(static function (Blueprint $table) use ($userClass): void {
            $table->id();
            $table->foreignIdFor(Article::class, 'post_id');
            $table->foreignIdFor($userClass, 'user_id')->nullable()->index();
        });

        $this->tableUpdate(function (Blueprint $table): void {
            $this->updateTimestamps($table, hasSoftDeletes: true);
        });
    }
};
```

## Caso reale: `profiles` su connessione `blog`

`Modules\Blog\Models\Profile` ha `$connection = 'blog'`; `BaseUser` ha `$connection = 'user'`. FK fisica impossibile → solo `foreignIdFor` senza `constrained`.

## Checklist agente

1. Riferimento a modello noto? → `foreignIdFor(Model::class, $column?)`
2. Riferimento a User? → `XotData::make()->getUserClass()`
3. Target su altra connection? → **no** `constrained()`
4. Audit (`created_by`, …)? → `updateTimestamps()` in `tableUpdate`
5. Stessa connection e serve integrità referenziale DB? → `->constrained()->cascadeOnDelete()` (o `nullOnDelete`)

## Riferimenti

- [migration-update-timestamps-only](./migration-update-timestamps-only.md)
- [database-config-standard (Tenant)](../../../../Tenant/docs/database-config-standard.md)
- [Root wiki — migration-foreign-id-for](../../../../../docs/wiki/concepts/migration-foreign-id-for.md)
