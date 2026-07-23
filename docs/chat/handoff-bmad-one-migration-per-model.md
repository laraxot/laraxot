---
title: "Handoff — /bmad/architecture 1 migrazione per modello"
updated: 2026-06-06
issue: "https://github.com/laraxot/base_techplanner_fila5/issues/23"
discussion: "https://github.com/laraxot/base_techplanner_fila5/discussions/19"
agents: [Cursor-Auto]
---

# Handoff — 1 modello = 1 migrazione (N = N)

## Regola utente

Nel modulo **Pippo** con **20 modelli owner** → **20** migrazioni `create_*` in `Modules/Pippo/database/migrations/`. Ogni modello deve **esistere** (1 file) e **non duplicarsi**.

## SSoT aggiornato

- `docs/wiki/bmad/architecture-one-migration-per-model.md` — regola cardine + esempio Pippo
- `docs/wiki/rules/one-migration-per-model.md` — regola compatta
- `docs/wiki/concepts/module-artifact-parity-snapshot.md` — snapshot audit 2026-06-06
- `.cursor/rules/one-migration-per-model.mdc` — alwaysApply

## Tool nuovo

```bash
bashscripts/tools/audit-duplicate-create-migrations.sh [Module]
```

Segnala più file `create_{table}_table` nello stesso modulo.

## Stato repo (backlog)

- **16/17 moduli in GAP** (solo Seo OK)
- TechPlanner: 14 modelli, 13 create_* — mancano migrazioni per Client, Device, …
- Duplicati: Activity (activity×4), Notify (mail_templates×13), User (profiles, team_user, users), …

## Prossimo agente

1. Scegliere modulo (es. TechPlanner) e chiudere GAP modello-per-modello
2. Consolidare duplicati → un canonico + `_bak/` per gli altri
3. Mai `add_*`/`alter_*` — bump timestamp sul file owner
4. Commentare issue [#23](https://github.com/laraxot/base_techplanner_fila5/issues/23) con modulo lavorato

— Auto (Cursor)
