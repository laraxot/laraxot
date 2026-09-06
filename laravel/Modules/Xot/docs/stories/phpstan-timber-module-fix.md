---
id: phpstan-timber-module-fix
slug: phpstan-timber-module
scope:
  - module:Timber
  - project:base_workorder_fila5
status: superseded-partially
epic: PHPStan Quality Gates
priority: High
created: 2026-09-06
updated: 2026-09-06
superseded_by: ../../Xot/docs/stories/18.2.1.hasxotfactory-factory-method-regression-fix.story.md
---

## Problema (aggiornamento 2026-09-06 21:10)

**Non erano 50+ errori nei seeder da riscrivere.** Root cause reale: il trait
condiviso `Modules/Xot/app/Models/Traits/HasXotFactory.php` aveva perso il metodo
pubblico `factory()` (regressione introdotta e rimossa nello stesso giorno da un
altro agente). Fix applicato li', non qui: vedi
`Modules/Xot/docs/stories/18.2.1.hasxotfactory-factory-method-regression-fix.story.md`.

Dopo il fix: `phpstan analyse Modules/Timber` e' passato da 1210 a 374 file_errors
totali (846 → 10 errori reali non-Pest). **Nessun seeder/factory di Timber e' stato
toccato o rigenerato** — non serviva.

## Errori Principali (storico, causa reale sopra)

1. **staticMethod.notFound**: factory() non definiti — causa reale: trait condiviso, non Timber
2. **method.nonObject**: count(), create() su mixed — cascata dal punto 1

## Scope residuo (10 errori reali rimasti in Timber dopo il fix del trait)

5 `cast.string`, 3 `argument.type`, 1 `binaryOp.invalid`, 1 `cast.double` — da
triagare con una story dedicata (non ancora creata).

## Acceptance Criteria

- [x] Causa radice identificata e fissata (a livello di trait Xot, non Timber)
- [ ] 0 PHPStan errori residui in Timber module (10 rimasti, story dedicata da aprire)
- [ ] PHPMD passes
- [ ] PHPInsights > 90%
- [ ] Pest coverage incremented
