---
title: "Cartelle root maiuscole — tema Sixteen"
type: concept
module: Sixteen
status: active
tags: [theme-structure, archive]
updated: "2026-06-30"
related:
  - ../../../../../../docs/project/module-root-structure-analysis.md
  - ../../../../../Modules/Xot/docs/conventions.md
---

# Cartelle root maiuscole — Sixteen

## Rilevate alla root del tema

| Cartella | Nota |
|----------|------|
| `Http/` | Duplicato concettuale di `app/Http/` o `src/` — da allineare al layout del tema |
| `Main_files/` | Asset/static di riferimento design; non è codice PSR-4 |

## Regola Laraxot

Come per i moduli: alla root del tema preferire **lowercase** (`app/`, `resources/`, `config/`). PascalCase solo dentro `app/` o `src/` per namespace.

## Stato

Solo inventario (2026-06-30). **Nessuna rinomina `.bak` applicata ai temi** in questa fase — da discutere punto per punto con i moduli Predict/Xot.

## Duplicati interni noti

Filtri menu duplicati tra `src/Filters/` e `app/Filters/` (stesso ruolo, file diversi) — vedi ponytail-audit repo-wide.
