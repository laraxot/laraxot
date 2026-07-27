---
title: "Handoff multi-org sync (STORY-003)"
type: handoff
tags: [git, multi-org, bmad, story-003]
created: 2026-07-21
updated: 2026-07-23
module: "Zero"
issues:
  - "https://github.com/provtv/theme_zero_fila5/issues/12"
discussions:
  - "https://github.com/provtv/base_ptv_fila5/discussions/204"
---

# Handoff — multi-org sync (STORY-003)

## Scopo

Allineare questo owner ai remote raggiungibili (**0 0**, working tree clean) e documentare decisioni di sessione 2026-07-21.

## Perché

Un tree dirty o un remote dietro/avanti **non** è sincronizzato, anche se l’altro org è a posto. Su PTVX i path vivono in `gitmodules.ini` con org `provtv` (+ `laraxot` se esiste).

## Link

| Tipo | URL |
|------|-----|
| Issue owner | https://github.com/provtv/theme_zero_fila5/issues/12 |
| Discussion | https://github.com/provtv/base_ptv_fila5/discussions/204 |
| Hub base issue | https://github.com/provtv/base_ptv_fila5/issues/203 |
| Hub base discussion | https://github.com/provtv/base_ptv_fila5/discussions/204 |
| Story monorepo | `docs/stories/STORY-003-multi-org-sync-geo-boundary-bashscripts.md` |

## Regole rapide

1. `cd` owner → `git remote -v` → fetch tutti → merge senza force → push tutti
2. Dopo edit PHP: phpstan/phpmd/phpinsights scoped (prompt `02-gitmodules-sync.md`)
3. Mai `git restore` — forward-only
4. UI: non reintrodurre `InteractiveMap` (dominio Geo)

## Note owner

Tema: sync multi-org + handoff docs.

### Playbook push dual-remote (2026-07-22)

Se unpack fallisce → `git push --no-thin`. Se GH008 / LFS missing e un remote sibling ha già il tip → `git lfs fetch <sibling> --all` + `git lfs push <target> --all` + push.
Canon (caso UI `b874935`): [../../../Modules/UI/docs/wiki/troubleshooting/git-push-lfs-missing-objects.md](../../../Modules/UI/docs/wiki/troubleshooting/git-push-lfs-missing-objects.md).
Forward-only: niente reset/squash/force per aggirare LFS.

### Caso User 2026-07-23 (unrelated)

`merge-base` vuoto vs un org → STOP. User: laraxot `3ea7273a` OK; provtv unrelated.
[../../../Modules/User/docs/wiki/troubleshooting/git-push-dual-remote-unrelated.md](../../../Modules/User/docs/wiki/troubleshooting/git-push-dual-remote-unrelated.md).

### Sync 2026-07-23 (batch 5-item)

- `laraxot` e `provtv` entrambi raggiungibili, stesso tip `766ad44` prima del sync.
- Working tree dirty (2 doc file già modificati da altra sessione) → committato (`d30876e`).
- Entrambi i remote erano 0 behind / 1 ahead dopo il commit → push riuscito su entrambi, ora allineati a `d30876e`.
- Nessun conflitto, nessuna rottura di codice trovata.
