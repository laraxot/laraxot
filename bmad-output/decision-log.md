# Decision Log

## 001 — Track selection
**Date:** 2026-07-23
**Decision:** BMad Method track
**Rationale:** ~25 stories across 6 wave — too large for Quick Flow, no Security/DevOps streams required.
**Source:** User confirmation in session.

## 002 — Naming convention
**Date:** 2026-07-23
**Decision:** Singolare PascalCase (`WorkOrder`, `Customer`, `Intervention`). Brand verticali come `EnergyBroker`, `PublicProcurement`, `TimberBilling` preservano la capitalizzazione SRC.
**Rationale:** Allineato a `docs/gestionale-commesse-conversion-analysis.md#3`.

## 003 — Wave ordering
**Date:** 2026-07-23
**Decision:** Wave 1 (Core) → Wave 2 (HR) → Wave 3 (Commerciale) → Wave 4 (Chiusura) → Wave 5 (Manufacturing) → Wave 6 (Canali).
**Rationale:** Dependency-driven: Customer→WorkOrder→Intervention sblocca tutti gli altri moduli.
