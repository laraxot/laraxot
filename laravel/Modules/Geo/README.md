---
id: module-geo-readme
title: "Geo — Gestione Geografica e Localizzazione"
type: module-readme
category: module-documentation
module: Geo
status: active
tags: [geo, geocoding, coordinates, maps]
created: 2026-09-14
updated: 2026-09-14
qmd: "geo geocoding coordinates maps providers leaflet module documentation"
issues:
  - "https://github.com/laraxot/module_geo_fila5/issues/86"
discussions:
  - "https://github.com/laraxot/module_geo_fila5/discussions/87"
related:
  - "./docs/"
sources: []
---

# 🌍 Geo

> **Gestione geografica e localizzazione.**

Coordinate, zone, limiti geografici e localizzazione per il servizio.

## Cosa offre

- **Coordinate** – gestione di posizioni geografiche
- **Zone** – aree geografiche di interesse
- **Limiti geografici** – confini e vincoli
- **Localizzazione** – supporto multilingua

## Confini architetturali

This module publishes contracts usable by other modules. Logic lives in `Actions`; admin UI follows Laraxot/XotBase.

## Integrazione rapida

```bash
cd laravel
php artisan module:list
./vendor/bin/phpstan analyse Modules/Geo
```

See local docs for integration patterns.

## Documentazione

The technical map is in [docs/README.md](./docs/README.md).

- [Story BMAD del modulo](./docs/stories/)
- [Regole del progetto](../../../docs/wiki/)
- [README del progetto](../../README.md)

## Qualità e manutenzione

Keep `declare(strict_types=1);` in PHP, respect project PHPStan config, and update docs when contracts evolve.

---

**Modulo** `geo` · **Laraxot ecosystem** · **Project-agnostic**
