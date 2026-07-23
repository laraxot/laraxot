---
title: "Composer root minimale — memoria Xot (merge-plugin)"
type: memory
module: Xot
tags: [composer, xot, nwidart, merge-plugin, skeleton]
created: 2026-07-09
updated: 2026-07-09
qmd: "Xot composer root skeleton merge-plugin moduli owner vendor unico composer update -W"
issues:
  - "https://github.com/laraxot/module_xot_fila5/issues/30"
  - "https://github.com/laraxot/base_fixcity_fila5/issues/305"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/304"
related:
  - ../concepts/composer-root-skeleton-modular.md
  - ../concepts/composer-merge-plugin-modules-only.md
  - ../../../Activity/docs/wiki/concepts/package-ownership-event-sourcing.md
  - ../../../../../../bashscripts/ai/wiki/concepts/composer-root-minimal-nwidart.md
---

# Composer root minimale — prospettiva Xot

Xot documenta il **contratto infrastrutturale**: merge-plugin, skeleton root, confine temi.

Quando un agente installa un pacchetto:

- infrastruttura condivisa (Filament, Folio, PHPStan, Pest) → `Modules/Xot/composer.json`
- dominio verticale → modulo owner (es. event sourcing → Activity)

Workflow unico: `rm -rf Modules/<Owner>/vendor` poi `cd laravel && composer update -W`.

Canon: [composer-root-skeleton-modular](../concepts/composer-root-skeleton-modular.md)
