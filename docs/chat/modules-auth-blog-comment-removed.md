---
title: "Auth, Blog, Comment rimossi da questo progetto (fase corrente)"
type: how-to
tags: [modules, removal, auth, blog, comment, dangling-references]
created: 2026-07-27
updated: 2026-07-27
qmd: "Auth Blog Comment modules removed not needed this phase modules_statuses dangling references"
related:
  - "./audit-models-migrations-seeders-factories.md"
  - "../../laravel/modules_statuses.json"
---

# Auth, Blog, Comment — non servono in questo progetto (fase corrente)

Utente (2026-07-27): "i moduli Auth, Comment e Blog non ci servono in questo progetto in questa fase."

## Stato

- `modules_statuses.json`: entry `Auth` e `Blog` rimosse (non solo `false`, rimosse — stesso
  pattern già usato per `Comment`, che non era già presente).
- Le directory `Modules/Auth`, `Modules/Blog`, `Modules/Comment` sono state osservate in stato
  molto instabile durante questa sessione (a tratti assenti dal disco, non committate, poi di
  nuovo presenti) — probabile lavoro concorrente di un altro agente. Non toccate direttamente
  qui: nessun `git checkout`/restore, nessun `rm -rf` manuale di queste directory — regola
  git-forward-only.

## Dangling reference ripulite (moduli che NON vengono rimossi)

- `Modules/Cms/app/config/xra.php` — rimosse chiavi `'article'`/`'category'` → `Modules\Blog\Models\*`.
- `Modules/Cms/app/Config/xra.php` (duplicato stray, casing sbagliato, permessi eseguibili
  anomali, contenuto identico al file corretto in `app/config/`) — rimosso interamente via
  `git rm -f` + cartella vuota rimossa. Nessun riferimento nel codice puntava a `app/Config`
  (maiuscolo).
- `Modules/Geo/app/Filament/Blocks/MapBlock.php` — rimosso `use Modules\Blog\Models\Article`
  commentato + blocco di codice commentato che lo usava (dead code).

## Se trovi altri riferimenti a Modules\Auth / Modules\Blog / Modules\Comment

Stessa disciplina già applicata per `Blog\Article` → `Comment` (vedi
[audit-models-migrations-seeders-factories.md](./audit-models-migrations-seeders-factories.md)
§ "Incidente — Comment module rimosso"): rimuovere il riferimento dal modulo che lo consuma,
non ripristinare il modulo rimosso.
