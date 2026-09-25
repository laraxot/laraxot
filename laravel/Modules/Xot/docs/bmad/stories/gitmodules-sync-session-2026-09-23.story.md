---
title: "Sync manuale sottomoduli da gitmodules.ini — sessione 2026-09-23"
type: story
module: Xot
epic: Infrastructure
story_id: "5.165-gitmodules-sync-2026-09-23"
slug: gitmodules-sync-session-2026-09-23
doc-phase: "done"
status: done
created: 2026-09-23
updated: 2026-09-23
---

# 5.165 — Sincronizzazione manuale sottomoduli (gitmodules.ini)

## Story

A seguito della divergenza accumulata su 23 sottomoduli attivi in `gitmodules.ini`, eseguire sync completo: per ogni path attivo (non commentato), `cd <path> && git fetch && git status`, quindi merge/rebase verso `laraxot/dev`.

## Perché

I 23 sottomoduli attivi mostravano divergenza su laraxot/dev (da behind 1 a behind 83 commit). Lo scopo era riportare ogni submodule allineato al remote senza perdere il lavoro locale. Nessun submodule usa `git submodule` — ognuno ha il proprio `.git/`, il sync avviene per directory indipendente.

## Esecuzione

1. Parse di `gitmodules.ini` — 23 path attivi identificati (16 commentati saltati)
2. `git fetch --all` su ogni submodule
3. Per ogni submodule diverso: `git merge laraxot/dev` con fallback `--allow-unrelated-histories`
4. **Conflitti risolti**: `IndennitaCondizioniLavoro` (14 file docs/) → `git checkout --theirs`, `git add`, commit. `IndennitaResponsabilita` — merge auto-risolto, 290 file in staging, commit diretto.
5. **Path bloccati**: nessuno, tutti sincronizzati
6. Verifica finale: tutti i 23 submodule su `laraxot/dev` senza divergenza (alcuni "ahead" per merge locali non pushati)

## Risultati

| Modulo | Stato | Note |
|--------|-------|------|
| public_html/noconsole | Synced | Merge unrelated-histories (ahead 23) |
| bashscripts | Synced | Stash + merge |
| laravel/Modules/Activity | Synced | Merge unrelated |
| laravel/Modules/Job | Synced | Up to date |
| laravel/Modules/Lang | Synced | Rebase, 2749 ahead |
| laravel/Modules/Media | Synced | Merge |
| laravel/Modules/Notify | Synced | Up to date |
| laravel/Modules/Rating | Synced | Merge (backup-orig-2026-09-22 branch esistente) |
| laravel/Modules/Tenant | Synced | Up to date |
| laravel/Modules/UI | Synced | Merge (ahead 2) |
| laravel/Modules/User | Synced | Merge (backup-user-2026-09-22 branch esistente) |
| laravel/Modules/Xot | Synced | Merge |
| laravel/Modules/Incentivi | Synced | Merge (era behind 83) |
| laravel/Modules/IndennitaCondizioniLavoro | Synced | Merge, 14 conflitti docs risolti con theirs |
| laravel/Modules/IndennitaResponsabilita | Synced | Merge auto-risolto |
| laravel/Modules/Pdnd | Synced | Up to date |
| laravel/Modules/Performance | Synced | Up to date |
| laravel/Modules/Progressioni | Synced | Up to date |
| laravel/Modules/Ptv | Synced | Merge (era behind 30) |
| laravel/Modules/Sigma | Synced | Up to date |
| laravel/Themes/Zero | Synced | In sync già |
| laravel/Themes/One | Synced | Up to date |
| laravel/Themes/Three | Synced | Merge (ahead 1, behind 7) |

## Riferimenti

- SSoT: `gitmodules.ini` (root repo)
- BMAD story precedente: `laravel/Modules/Xot/docs/bmad/stories/sync-modules-cli-bmad.story.md` (superseded)
- Second brain: `bashscripts/ai/wiki/memories/gitmodules-sync-2026-09-23.md`
