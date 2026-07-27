---
title: "Migrazioni — foreignIdFor (temi e moduli)"
type: rule
tags: [themes, migrations, foreignIdFor, shared-components]
created: 2026-07-27
updated: 2026-07-27
qmd: "themes migration foreignIdFor shared components cross database"
related:
  - ./migration-patterns.md
  - ./migration-philosophy.md
  - ../../../Modules/Xot/docs/wiki/concepts/migration-foreign-id-for.md
  - ../../../../docs/wiki/concepts/migration-foreign-id-for.md
---

# foreignIdFor — guida per temi e moduli

I temi **non** contengono migrazioni dominio; questa regola si applica quando un tema documenta pattern condivisi o quando un agente tocca `Modules/*/database/migrations`.

## Regola

Usare sempre `$table->foreignIdFor(Model::class, $column?)` al posto di:

- `foreignId('…_id')` senza modello
- `string('user_id', 36)` duplicato
- `foreignUuid(…)->constrained('users')` cross-database

## User

```php
$table->foreignIdFor(XotData::make()->getUserClass(), 'user_id')->nullable()->index();
```

## `constrained()` — quando sì / no

| Scenario | `constrained()` |
|----------|-----------------|
| Parent e child stessa connection + stesso DB | Opzionale (integrità DB) |
| Child su `blog`, parent `User` su `user` | **Vietato** |
| SQLite testing (`.env.testing`) | Stesso pattern — no FK cross-DB |

## Documentazione canonica

- [Xot — migration-foreign-id-for](../../../Modules/Xot/docs/wiki/concepts/migration-foreign-id-for.md)
- [Root wiki](../../../../docs/wiki/concepts/migration-foreign-id-for.md)
- [Blog — cross-database user refs](../../../Modules/Blog/docs/wiki/concepts/migration-cross-database-user-refs.md)

Vedi anche [migration-patterns](./migration-patterns.md) e [migration-philosophy](./migration-philosophy.md).
