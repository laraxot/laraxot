---
title: "Composer root minimale — modulo Notify"
type: concept
tags: [composer, notify, nwidart, merge-plugin]
created: 2026-06-29
updated: 2026-06-29
qmd: "Notify composer dependencies root minimal nwidart merge-plugin"
issues:
discussions:
related:
  - "./claude-audit-static.md"
  - "./code-redundancy-notify.md"
  - "./context-overflow-prevention.md"
  - "./enum-standards.md"
  - "./llm-wiki-governance.md"
  - "./method-name-homonyms.md"
  - "./module-root-uppercase-folders-archive.md"
  - "./no-app-support-queueable-actions.md"
---

# Notify e composer root minimale

## Regola

Dipendenze del dominio **Notify** in `Modules/Notify/composer.json`. Il root `laravel/composer.json` resta skeleton come [base_fixcity_fila5](https://github.com/laraxot/base_fixcity_fila5/blob/dev/laravel/composer.json).




## Merge root — solo moduli

`laravel/composer.json` → merge **solo** `Modules/*/composer.json`. **Vietato** `Themes/*/composer.json` (nwidart owner = modulo; tema = vestito Blade/assets).

Perché: [composer-merge-plugin-modules-only](../../../Xot/docs/wiki/concepts/composer-merge-plugin-modules-only.md).

## Riferimento

[Composer root minimale nwidart](../../../../../../docs/wiki/concepts/composer-root-minimal-nwidart.md)
