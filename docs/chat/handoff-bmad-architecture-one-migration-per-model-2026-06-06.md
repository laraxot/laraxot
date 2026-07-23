---
title: "Handoff — BMAD architecture N modelli = N migrazioni"
type: handoff
tags: [bmad, architecture, migrations, parity, multi-agent]
created: 2026-06-06
updated: 2026-06-06
qmd: "handoff bmad architecture one migration per model N models N migrations pippo audit parity"
issues:
  - "https://github.com/laraxot/base_techplanner_fila5/issues/23"
discussions:
  - "https://github.com/laraxot/base_techplanner_fila5/discussions/19"
related:
  - ../wiki/bmad/architecture-one-migration-per-model.md
  - ../wiki/bmad/architecture-module-model-artifact-parity.md
  - ../wiki/rules/one-migration-per-model.md
---

# Handoff — `/bmad/architecture` · N modelli = N migrazioni

**Issue:** [#23](https://github.com/laraxot/base_techplanner_fila5/issues/23)

## Regola richiesta (utente)

In ogni modulo Laraxot:

- **1 modello persistibile owner = 1 migrazione** `*_create_{table}_table.php`
- Modulo **Pippo** con **20** modelli → **20** migrazioni nel modulo Pippo
- Vietato: modello senza migrazione; duplicati `create_*` sulla stessa tabella

## Canon aggiornato (2026-06-06)

| Artefatto | Path |
|-----------|------|
| Architecture | [architecture-one-migration-per-model.md](../wiki/bmad/architecture-one-migration-per-model.md) |
| Parità N=N | [architecture-module-model-artifact-parity.md](../wiki/bmad/architecture-module-model-artifact-parity.md) |
| Rule | [one-migration-per-model.md](../wiki/rules/one-migration-per-model.md) |
| Memory | [one-migration-per-model-bump-timestamp.md](../wiki/memories/one-migration-per-model-bump-timestamp.md) |
| Cursor | `.cursor/rules/one-migration-per-model.mdc`, `module-model-artifact-parity.mdc` |

## Audit

```bash
bashscripts/tools/audit-module-artifact-parity.sh User Activity
bashscripts/tools/audit-all-modules-artifact-parity.sh
```

Script migliorato: segnala anche **duplicate `create_*`** sulla stessa tabella.

## Stato codebase (snapshot)

```
OK (1): Seo
GAP (16): Activity Blog Cms Employee Gdpr Geo Job Lang Media Notify Rating TechPlanner Tenant UI User Xot
```

Allineamento schema è **backlog** — non bloccante per documentazione regola, ma ogni nuovo modello deve rispettare N=N fin da subito.

## Prossimi passi (agenti)

1. Per modulo target: audit → elenco GAP per modello
2. Consolidare migrazioni duplicate (merge in un `create_*` + bump timestamp, archiviare duplicati in `_bak/`)
3. Creare migrazioni mancanti via `XotBaseMigration`
4. Documentare esclusioni nel wiki modulo se modello non persistibile

— Auto (Cursor Agent)
