---
title: "Handoff multi-org sync (STORY-003)"
type: handoff
tags: [git, multi-org, bmad, story-003]
created: 2026-07-21
updated: 2026-07-21
module: "Rating"
issues:
  - "https://github.com/provtv/module_rating_fila5/issues/14"
discussions:
  - "https://github.com/provtv/<nome repository>/discussions/204"
---

# Handoff — multi-org sync (STORY-003)

## Scopo

Allineare questo owner ai remote raggiungibili (**0 0**, working tree clean) e documentare decisioni di sessione 2026-07-21.

## Perché

Un tree dirty o un remote dietro/avanti **non** è sincronizzato, anche se l’altro org è a posto. Su PTVX i path vivono in `gitmodules.ini` con org `provtv` (+ `laraxot` se esiste).

## Link

| Tipo | URL |
|------|-----|
| Issue owner | https://github.com/provtv/module_rating_fila5/issues/14 |
| Discussion | https://github.com/provtv/<nome repository>/discussions/204 |
| Hub base issue | https://github.com/provtv/<nome repository>/issues/203 |
| Hub base discussion | https://github.com/provtv/<nome repository>/discussions/204 |
| Story monorepo | `docs/stories/STORY-003-multi-org-sync-geo-boundary-bashscripts.md` |

## Regole rapide

1. `cd` owner → `git remote -v` → fetch tutti → merge senza force → push tutti
2. Dopo edit PHP: phpstan/phpmd/phpinsights scoped (prompt `02-gitmodules-sync.md`)
3. Mai `git restore` — forward-only
4. UI: non reintrodurre `InteractiveMap` (dominio Geo)

## Note owner

Seguire sync multi-org e mantenere docs allineate alla story.

### Sync 2026-07-23 (batch 5-item)

- `laraxot` e `provtv` entrambi raggiungibili, stesso tip `5fc001e`, entrambi **0 0** contro HEAD.
- Working tree pulito, nessun commit da fare.
- Nessuna modifica necessaria in questo giro.
