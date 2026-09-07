---
title: UI module root .md hygiene — completato
slug: ui-module-root-md-hygiene
status: done
scope: module:UI
---

# UI module root .md hygiene

Task: applicare la regola "root modulo max 6 file .md" a `laravel/Modules/UI/`.

Stato iniziale: 31 file `.md` in root, 0 `.txt` (già conforme). Stato finale: 6 file `.md` in root.

Story BMAD: `laravel/Modules/UI/docs/stories/ui-root-md-file-limit-audit.story.md`.

Commit: `d4e1f578` (repo `module_ui_fila5`, branch `dev`). Push: fast-forward `6f178537..d4e1f578` su remote `laraxot`.

## Root keepers (6)

`README.md`, `ARCHITECTURE.md`, `TESTING.md`, `PHILOSOPHY.md`, `GETTING_STARTED.md`, `CHANGELOG.md`.

## File rimossi dalla root (25)

Tutti e 25 avevano già una copia byte-identica (o quasi, un solo caso con differenza trascurabile) pre-esistente in `laravel/Modules/UI/docs/raw/root-import/<slug>-1.md` (dump raw, luglio) e in `laravel/Modules/UI/docs/raw/root-import/<slug>.md` (versione arricchita con frontmatter, settembre) — residuo di un ciclo di import precedente mai ripulito. Verificato con `diff -q` file per file (dettaglio in story). Rimossi con `git rm` (nessuna perdita di contenuto: la copia esiste già altrove nel repo):

api.md, blocks.md, carousel-slider.md, changelog.md (fuso in CHANGELOG.md), chunk.md, ci.md, custom-firm-fields.md, custom-theme.md, eav.md, effetcts.md, filament.md, flip-cards.md, global-search.md, links.md, media.md, megamenu.md, navbar.md, page-builder.md, qrcode.md, ratings.md, tailwind-themes.md, test.md, theme.md, ubuntu.md, widgets.md.

## Fusione

`changelog.md` (root, contenuto: nota su semantic-release) fuso come citazione in `CHANGELOG.md` (keeper), poi rimosso.

## Nota fuori scope

Trovati due file untracked pre-esistenti non correlati a questo task: `laravel/Modules/UI/docs/GETTING_STARTED.md` e `laravel/Modules/UI/docs/TESTING.md` (timestamp 2026-09-06 12:37, precedenti a questa sessione, contenuto identico ai keeper di root). Non toccati, non lockati, fuori scope (riguardano `docs/`, non la root del modulo).

## Verifica finale

```
find laravel/Modules/UI -maxdepth 1 -iname '*.md' | wc -l
# 6
```

Vincoli rispettati: `laravel/phpstan.neon` non toccato, nessun `@phpstan-ignore`, nessun `--force`/nuovo branch/`revert`/`reset --hard`/`checkout`, nessun contenuto perso.
