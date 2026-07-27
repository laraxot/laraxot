---
title: "Blog — riferimenti user cross-database"
type: concept
module: Blog
tags: [blog, migrations, foreignIdFor, user, cross-database]
created: 2026-07-27
updated: 2026-07-27
qmd: "blog migration user_id foreignIdFor cross database profiles comments"
issues:
  - "https://github.com/laraxot/base_techplanner_fila5/issues/38"
discussions:
  - "https://github.com/laraxot/base_techplanner_fila5/discussions/12"
related:
  - ../../../../Xot/docs/wiki/concepts/migration-foreign-id-for.md
  - ../../../../../docs/wiki/concepts/migration-foreign-id-for.md
  - ./user-comment-module-boundary.md
---

# Blog — `user_id` e connessione `blog`

## Architettura

- Modelli Blog (`Profile`, `Article`, `Comment`, …): `$connection = 'blog'` → tipicamente `workorder_data`.
- `BaseUser`: `$connection = 'user'` → tipicamente `workorder_user`.
- FK MySQL **non attraversano** database diversi.

## Regola modulo Blog

Ogni colonna `user_id` (o audit verso User) nelle migrazioni Blog:

```php
$userClass = XotData::make()->getUserClass();

$table->foreignIdFor($userClass, 'user_id')->nullable()->index();
// Vietato: ->constrained('users')
// Vietato: $table->foreignId('user_id')
// Vietato: $table->string('user_id', 36) — usare foreignIdFor
```

## FK interne Blog (stessa connection)

Tra tabelle sullo stesso DB `blog`, `constrained()` è opzionale ma consentita:

```php
$table->foreignIdFor(Article::class, 'post_id');
$table->foreignIdFor(Category::class)->constrained()->cascadeOnDelete(); // se serve integrità DB
```

## Migrazioni owner `profiles`

- Canon create: `2021_01_01_000008_create_profiles_table.php` (evoluzione in-place, no `add_*`).
- Evoluzione schema: edit + bump timestamp — vedi regola one-migration-per-model.

## Riferimenti

- [migration-foreign-id-for (Xot)](../../../../Xot/docs/wiki/concepts/migration-foreign-id-for.md)
- [Root wiki](../../../../../docs/wiki/concepts/migration-foreign-id-for.md)
