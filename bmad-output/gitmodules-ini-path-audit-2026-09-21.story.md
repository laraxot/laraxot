---
name: gitmodules-ini-path-audit-2026-09-21
description: Audit path/url sync tra root gitmodules.ini e i 51 repo annidati reali (no git submodules, dual-tracked)
metadata:
  type: story
---

# BMAD Story — gitmodules.ini path sync audit

## Understand
- **Problema**: verificare che `gitmodules.ini` (root) rispecchi i path reali dei repo annidati e i loro remote. Non usiamo `git submodule` vero: ogni `Modules/<X>` e `Themes/<X>` e' un repo `.git` proprio, dual-tracked (root + nested), `gitmodules.ini` e' solo registro manuale.
- **Trigger**: richiesta utente "sincronizza i path dentro gitmodules.ini come sempre". Correlato a [[project_repo_wide_merge_marker_corruption_2026_09_21]] (stesso giorno, audit precedente aveva trovato Themes/Sixteen mancante e lo aveva aggiunto).

## Plan
1. `find laravel/Modules laravel/Themes bashscripts -maxdepth 2 -name .git` → elenco path reali con repo proprio.
2. Diff vs `path = ` in `gitmodules.ini` (mancanti da un lato o dall'altro).
3. Per ogni entry ini, `git -C <path> remote -v` → confronto url reale (remote `laraxot`) vs `url =` in ini.

## Verify
- Path filesystem vs ini: **51 = 51, zero differenze** (nessun path mancante, nessuna entry orfana).
- URL remote `laraxot` vs ini: **51/51 combacia**.
- Anomalia isolata (non-bloccante, non toccata): `laravel/Modules/Quotation` ha un secondo remote extra `origin` → `https://github.com/laraxot/Module_Quotation.git` (repo GitHub legacy, maiuscolo, https), non presente in `gitmodules.ini` e non usato per push/pull correnti (quelli vanno su `laraxot`). Da chiedere all'utente se va rimosso o e' un mirror voluto, non rimosso d'iniziativa.

## Status
- [x] Fatto — `gitmodules.ini` gia' sincronizzato al 100% con lo stato reale dei repo annidati. Nessuna modifica al file necessaria in questo passaggio.
