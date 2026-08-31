---
title: IDE Helper e property_exists — nota storica
type: historical
updated: 2026-08-31
related:
  - ./ide-helper-models-governance.md
  - ./property-exists-replacement-guide.md
---

# IDE Helper e `property_exists()` — nota storica

Questo documento registrava una precedente wave di analisi. I comandi e le metriche originarie non sono una procedura operativa e non costituiscono evidenza dello stato corrente.

## Regola corrente

- Generare i model helper soltanto con `php artisan ide-helper:models --nowrite --no-interaction -v`.
- Verificare PHPStan soltanto con `./vendor/bin/phpstan analyse Modules`, usando l’immutabile `laravel/phpstan.neon`.
- Non usare `property_exists()` per attributi dinamici Eloquent: scegliere `hasAttribute()`, `getAttribute()`, `isset()` o `isFillable()` in base all’intento.
- Mantenere `property_exists()` per normali oggetti PHP con proprietà dichiarate.

Owner operativo: [ide-helper-models-governance.md](./ide-helper-models-governance.md). Guida al refactoring: [property-exists-replacement-guide.md](./property-exists-replacement-guide.md).
