---
title: Theme Sixteen tests — dati sacri e skip onesti
type: concept
tags: [sixteen, theme, tests, data-sacred, fixcity]
created: 2026-08-31
updated: 2026-08-31
qmd: sixteen theme tests RefreshDatabase DatabaseTransactions fixcity skip
related:
  - ./ai-handoff.md
  - ../../../../docs/wiki/rules/data-sacred-no-destructive-db.md
  - ../../../Modules/Xot/docs/testing/laraxot-test-db-architecture.md
  - ../overviews/perfection-checklist.md
  - ../../page-directory-structure.md
  - ../../../../Themes/docs/progetto-perfezione-roadmap.md
---

# Test Theme Sixteen — policy

## Dati sacri

- **Mai** `RefreshDatabase` / `migrate` in `beforeEach`
- **Mai** `migrate:fresh` / `--force` nei test
- Preferire `DatabaseTransactions` solo se il modulo owner esiste

## Skip onesti (base workorder)

| File | Motivo |
|------|--------|
| `ComunePagesTest` / `ComuneControllerTest` | Modulo `Fixcity` assente |
| `BootstrapItaliaComponentsTest` | View `pub_theme::bootstrap-italia.*` legacy non portate |
| `NoSemanticFolioPageDirectoriesTest` | Directory semantiche Fixcity in `pages/` — migrazione `[container0]` WIP |

Quando Fixcity o i componenti BI saranno in scope, riattivare i test senza reintrodurre wipe.

## Test attivi

Unit contract (Folio, header, block naming) — senza DB, allineati alla struttura reale del tema.
