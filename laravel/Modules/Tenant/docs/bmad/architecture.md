---
title: "Tenant — Architettura BMAD"
type: architecture
scope: Tenant
epic: 5.124-bmad-user-module-perfection-study
bmad_version: v3.30.1
updated_at: '2026-09-22'
status: done
related:
  - ../stories/continuazione-domani.md
---

# Tenant — Architettura

## Scopo
Multi-tenant / RLS.

## Componenti
- `Tenant/Filament/` — interfacce Filament 5 (se applicabile)
- `Tenant/Actions/` — azioni (Spatie Queueable se richiesto)
- `Tenant/Models/` — estende XotBase (se applicabile)

## Qualità
- PHPStan: OK (0 errori)
- Conflicts: 0
- Lock: 0 attivi

## Milestones Domani
1. Verificare conformità con XotBase
2. Aggiornare `docs/bmad/stories/continuazione-domani.md` se chiuso
3. Indicare stato concluso in `docs/sprint-status.yaml`
