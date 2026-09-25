---
title: "Handoff multi-org sync (STORY-003)"
type: handoff
tags: [git, multi-org, bmad, story-003]
created: 2026-07-21
updated: 2026-07-23
module: "UI"
issues:
  - "https://github.com/provtv/module_ui_fila5/issues/20"
discussions:
<<<<<<< HEAD
  - "https://github.com/provtv/<nome repository>/discussions/204"
<<<<<<< HEAD
<<<<<<< .merge_file_txVa9n
=======
<<<<<<< .merge_file_PEDcxB
=======
  - "https://github.com/provtv/base_ptv_fila5/discussions/204"
=======
<<<<<<< HEAD
  - "https://github.com/provtv/<nome repository>/discussions/204"
>>>>>>> .merge_file_K96djf
>>>>>>> .merge_file_OFzU4s
=======
>>>>>>> 804451c (Lint)
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
  - "https://github.com/provtv/base_ptv_fila5/discussions/204"
=======
  - "https://github.com/provtv/<nome repository>/discussions/204"
>>>>>>> 92912795 (.)
<<<<<<< HEAD
<<<<<<< .merge_file_txVa9n
=======
<<<<<<< .merge_file_PEDcxB
=======
>>>>>>> laraxot/dev
>>>>>>> .merge_file_K96djf
>>>>>>> .merge_file_OFzU4s
=======
>>>>>>> 804451c (Lint)
---

# Handoff — multi-org sync (STORY-003)

## Scopo

Allineare questo owner ai remote raggiungibili (**0 0**, working tree clean) e documentare decisioni di sessione 2026-07-21.

## Perché

Un tree dirty o un remote dietro/avanti **non** è sincronizzato, anche se l’altro org è a posto. Su PTVX i path vivono in `gitmodules.ini` con org `provtv` (+ `laraxot` se esiste).

## Link

| Tipo | URL |
|------|-----|
| Issue owner | https://github.com/provtv/module_ui_fila5/issues/20 |
<<<<<<< HEAD
| Discussion | https://github.com/provtv/base_ptv_fila5/discussions/204 |
| Hub base issue | https://github.com/provtv/base_ptv_fila5/issues/203 |
| Hub base discussion | https://github.com/provtv/base_ptv_fila5/discussions/204 |
<<<<<<< HEAD
<<<<<<< .merge_file_txVa9n
=======
<<<<<<< .merge_file_PEDcxB
=======
| Discussion | https://github.com/provtv/<nome repository>/discussions/204 |
| Hub base issue | https://github.com/provtv/<nome repository>/issues/203 |
| Hub base discussion | https://github.com/provtv/<nome repository>/discussions/204 |
=======
<<<<<<< HEAD
| Discussion | https://github.com/provtv/base_ptv_fila5/discussions/204 |
| Hub base issue | https://github.com/provtv/base_ptv_fila5/issues/203 |
| Hub base discussion | https://github.com/provtv/base_ptv_fila5/discussions/204 |
>>>>>>> .merge_file_K96djf
>>>>>>> .merge_file_OFzU4s
=======
>>>>>>> 804451c (Lint)
<<<<<<< HEAD
| Discussion | https://github.com/provtv/<nome repository>/discussions/204 |
| Hub base issue | https://github.com/provtv/<nome repository>/issues/203 |
| Hub base discussion | https://github.com/provtv/<nome repository>/discussions/204 |
=======
=======
| Discussion | https://github.com/provtv/<nome repository>/discussions/204 |
| Hub base issue | https://github.com/provtv/<nome repository>/issues/203 |
| Hub base discussion | https://github.com/provtv/<nome repository>/discussions/204 |
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
<<<<<<< HEAD
<<<<<<< .merge_file_txVa9n
=======
<<<<<<< .merge_file_PEDcxB
=======
>>>>>>> laraxot/dev
>>>>>>> .merge_file_K96djf
>>>>>>> .merge_file_OFzU4s
=======
>>>>>>> 804451c (Lint)
| Story monorepo | `docs/stories/STORY-003-multi-org-sync-geo-boundary-bashscripts.md` |

## Regole rapide

1. `cd` owner → `git remote -v` → fetch tutti → merge senza force → push tutti
2. Dopo edit PHP: phpstan/phpmd/phpinsights scoped (prompt `02-gitmodules-sync.md`)
3. Mai `git restore` — forward-only
4. UI: non reintrodurre `InteractiveMap` (dominio Geo)
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< .merge_file_txVa9n
=======
<<<<<<< .merge_file_PEDcxB
=======
5. Push: se unpack fallisce → `--no-thin`; se GH008 LFS → `lfs fetch --all` dal remote sano, poi `lfs push --all` sul target ([playbook](./wiki/troubleshooting/git-push-lfs-missing-objects.md))
=======
<<<<<<< HEAD
>>>>>>> .merge_file_K96djf
>>>>>>> .merge_file_OFzU4s
=======
>>>>>>> 804451c (Lint)
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
5. Push: se unpack fallisce → `--no-thin`; se GH008 LFS → `lfs fetch --all` dal remote sano, poi `lfs push --all` sul target ([playbook](./wiki/troubleshooting/git-push-lfs-missing-objects.md))
<<<<<<< HEAD
5. Push: se unpack fallisce → `--no-thin`; se GH008 LFS → `lfs fetch --all` dal remote sano, poi `lfs push --all` sul target ([playbook](./wiki/troubleshooting/git-push-lfs-missing-objects.md))
=======
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
=======
5. Push: se unpack fallisce → `--no-thin`; se GH008 LFS → `lfs fetch --all` dal remote sano, poi `lfs push --all` sul target ([playbook](./wiki/troubleshooting/git-push-lfs-missing-objects.md))
>>>>>>> laraxot/dev
=======
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
<<<<<<< HEAD
<<<<<<< .merge_file_txVa9n
=======
<<<<<<< .merge_file_PEDcxB
=======
>>>>>>> laraxot/dev
>>>>>>> .merge_file_K96djf
>>>>>>> .merge_file_OFzU4s
=======
>>>>>>> 804451c (Lint)

## Note owner

InteractiveMap rimosso; vedi `geo-boundary.md` e `geo-dependency-violation-interactive-map.md`.

### Sessione push 2026-07-22

<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< .merge_file_txVa9n
=======
<<<<<<< .merge_file_PEDcxB
=======
`dev` allineato FF su `laraxot` e `provtv` a `b874935` con `--no-thin` + LFS da `laraxot` verso `provtv`. Dettaglio: [git-push-lfs-missing-objects.md](./wiki/troubleshooting/git-push-lfs-missing-objects.md) · [multi-org-sync-laraxot-provtv.md](./multi-org-sync-laraxot-provtv.md).
=======
<<<<<<< HEAD
>>>>>>> .merge_file_K96djf
>>>>>>> .merge_file_OFzU4s
=======
>>>>>>> 804451c (Lint)
=======
<<<<<<< HEAD
<<<<<<< HEAD
=======
`dev` allineato FF su `laraxot` e `provtv` a `b874935` con `--no-thin` + LFS da `laraxot` verso `provtv`. Dettaglio: [git-push-lfs-missing-objects.md](./wiki/troubleshooting/git-push-lfs-missing-objects.md) · [multi-org-sync-laraxot-provtv.md](./multi-org-sync-laraxot-provtv.md).
<<<<<<< HEAD
`dev` allineato FF su `laraxot` e `provtv` a `b874935` con `--no-thin` + LFS da `laraxot` verso `provtv`. Dettaglio: [git-push-lfs-missing-objects.md](./wiki/troubleshooting/git-push-lfs-missing-objects.md) · [multi-org-sync-laraxot-provtv.md](./multi-org-sync-laraxot-provtv.md).
=======
=======
<<<<<<< HEAD
>>>>>>> laraxot/dev
<<<<<<< HEAD
=======
`dev` allineato FF su `laraxot` e `provtv` a `b874935` con `--no-thin` + LFS da `laraxot` verso `provtv`. Dettaglio: [git-push-lfs-missing-objects.md](./wiki/troubleshooting/git-push-lfs-missing-objects.md) · [multi-org-sync-laraxot-provtv.md](./multi-org-sync-laraxot-provtv.md).
>>>>>>> laraxot/dev
=======
>>>>>>> f6fcbb6f (Fix merge conflict in .gitattributes by removing redundant lines and ensuring proper exclusion of image formats from text processing.)
<<<<<<< HEAD
=======
>>>>>>> laraxot/dev
>>>>>>> laraxot/dev
>>>>>>> 92912795 (.)
>>>>>>> laraxot/dev
<<<<<<< HEAD
<<<<<<< .merge_file_txVa9n
=======
<<<<<<< .merge_file_PEDcxB
=======
>>>>>>> laraxot/dev
>>>>>>> .merge_file_K96djf
>>>>>>> .merge_file_OFzU4s
=======
>>>>>>> 804451c (Lint)

### Caso User 2026-07-23 (unrelated)

`merge-base` vuoto vs un org → STOP. User: laraxot `3ea7273a` OK; provtv unrelated.
[../User/docs/wiki/troubleshooting/git-push-dual-remote-unrelated.md](../User/docs/wiki/troubleshooting/git-push-dual-remote-unrelated.md).
