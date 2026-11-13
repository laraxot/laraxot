---
title: "Decision log — Comment"
type: decision-log
module: Comment
status: active
created: 2026-10-16
updated: 2026-10-20
tags: [bmad, decision-log, comment]
qmd: "decision log comment"
related:
  - ../bmad-planning.md
  - ../bmad-brainstorming.md
  - ./project-context.md
  - ./filament-blueprint.md
---

# Decision log — Comment

**Scopo:** il modulo Comment esiste perché Ticket, Article e altri record dominio possano ospitare thread polimorfici con moderazione e notifiche senza che Fixcity/Blog reinventino persistenza, policy o chrome Filament.

## Hub (solo link)

- Standing PHPStan: [phpstan-modules-zero-standing-order](../../../Xot/docs/wiki/memories/phpstan-modules-zero-standing-order.md)
- `graphify-out/`: [graphify-out-gitignore](../../../Xot/docs/bmad/graphify-out-gitignore.md)
- UI admin: [filament-blueprint.md](./filament-blueprint.md)

## 2026-10-20 — Gate PHPStan Modules — rieseguito OK

- **Verifica:** `cd laravel && php -d memory_limit=2G ./vendor/bin/phpstan analyse Modules/Comment` (audit random con User, Notify, Activity) → exit 0.
- **Docs:** `md-filenames.py check` su `docs/` → 0 date/suffissi; 29 file con maiuscole (es. `00-INDEX.md`, `stories/phpstan-zero-Comment.story.md`) — segnalati, rinomina solo quando `md-filenames.py name` propone un target senza collisioni.
- **Track:** [phpstan-fixes.md](../phpstan-fixes.md), [00-INDEX.md](../00-index.md), [phpstan-comment-morphmany-cast.story.md](../stories/phpstan-comment-morphmany-cast.story.md), hub sopra.

## 2026-10-16 — Kit BMAD owner

- **Decisione:** adottare `docs/bmad-planning.md` + `docs/bmad-brainstorming.md` + `docs/bmad/filament-blueprint.md` come SSoT di planning per questo owner.
- **Perche:** rimuovere stub copiati "Ticket Timeline" non pertinenti; allineare second brain e Filament blueprint allo scopo reale.
- **Track:** `bmad-method`
