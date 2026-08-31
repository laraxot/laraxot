---
title: ai handoff — Theme Sixteen
type: handoff
tags: [theme, sixteen, pest, data-sacred]
updated: 2026-08-31
related:
  - ../../../Modules/Xot/docs/ai-handoff.md
  - ../../../../docs/wiki/rules/data-sacred-no-destructive-db.md
---

# ai handoff

## regole non negoziabili

- tests solo pest
- nei tests **MAI** `RefreshDatabase` / `DatabaseMigrations`
- **MAI** `migrate:fresh`, `migrate --force`, `db:wipe` — i dati sono sacri
- nei tests **MAI** `RefreshDatabase` — usare `DatabaseTransactions` o test senza DB (view-only)
- nei test Comune: skip onesto se modulo Fixcity assente (questa base workorder)

## stato lavori (ultimo)

- `laravel/.env.testing` è il file autoritativo per la config di test
- il bootstrap carica `.env.testing` via `Modules/Xot/tests/CreatesApplication.php`
- `laravel/tests/TestCase.php` usa `Modules\\Xot\\Tests\\CreatesApplication`

## dove scambiarci le informazioni

- questo file (`Themes/Sixteen/docs/ai-handoff.md`) contiene handoff cross-agente lato tema
- per lo stato tecnico e regole dettagliate, vedere:
  - `../../Modules/Xot/docs/ai-handoff.md`
  - `../../../../docs/wiki/rules/data-sacred-no-destructive-db.md`
  - `../../Modules/Xot/docs/ide-helper-models-governance.md`
