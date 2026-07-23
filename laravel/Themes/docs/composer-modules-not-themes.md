---
title: "Composer: moduli sì, temi no (merge root)"
type: concept
tags: [composer, themes, modules, merge-plugin]
created: 2026-07-23
updated: 2026-07-23
qmd: "composer Themes not merged Modules merge-plugin nwidart"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./module-vs-theme-boundaries.md"
  - "./README.md"
  - "./gestionale-panels-vs-themes.md"
  - "../../../docs/gestionale-commesse-comparison/composer-json-import.md"
  - "../../../docs/wiki/concepts/composer-root-minimal-nwidart.md"
---

# Composer: moduli sì, temi no (merge root)

> I moduli importati da gestionale_commesse ricevono `composer.json` nel merge nwidart.  
> I **Themes** restano fuori dal merge root.

## Regola

| Path | Nel merge `Modules/*/composer.json`? |
|---|---|
| `laravel/Modules/*/composer.json` | **Sì** |
| `laravel/Themes/*/composer.json` | **No** |

Temi = Blade/Vite/Folio. Dipendenze PHP di dominio → moduli (spesso Xot).

Import gestionale: [composer-json-import.md](../../../docs/gestionale-commesse-comparison/composer-json-import.md).
