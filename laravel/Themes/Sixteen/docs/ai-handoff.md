---
created: 2026-09-26
qmd: "ai handoff"
issues: []
discussions: []
title: ai handoff — Theme Sixteen
type: handoff
tags: [theme, sixteen, pest, data-sacred]
updated: 2026-08-31
related:
  - ../../../Modules/Xot/docs/ai-handoff.md
  - ../../../../docs/wiki/rules/data-sacred-no-destructive-db.md
status: draft
---

# ai handoff

## regole non negoziabili

- tests solo pest
- nei tests MAI RefreshDatabase
- i tests devono leggere `.env.testing`

## stato lavori (ultimo)

- `laravel/.env.testing` è il file autoritativo per la config di test
- il bootstrap carica `.env.testing` via `Modules/Xot/tests/CreatesApplication.php`
- `laravel/tests/TestCase.php` usa `Modules\\Xot\\Tests\\CreatesApplication`

## dove scambiarci le informazioni

- questo file (`Themes/Sixteen/docs/ai-handoff.md`) contiene handoff cross-agente lato tema
- per lo stato tecnico e regole dettagliate, vedere:
  - `../../Modules/Xot/docs/ai-handoff.md`
