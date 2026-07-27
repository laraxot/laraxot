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

## 004 — Workflow-init repair (idempotent)
**Date:** 2026-07-27
**Decision:** Completare workspace `bmad-output/` senza overwrite di decision-log/project-context; aggiungere `bmad-output/config.yaml` (track bmad-method) + `docs/bmm-workflow-status.yaml`. Lingue IT.
**Rationale:** `/workflow-init` su install già parziale — validate falliva per config.yaml mancante. Planning artifacts (PRD/arch/epics) già presenti; implementation in `docs/stories/` + `docs/sprint-status.yaml`.
**Source:** User `/workflow-init`.

## 005 — BMAD deep document-project obbligatorio
**Date:** 2026-07-27
**Decision:** Su brownfield Laraxot, ogni ciclo BMAD di documentazione/planning deve partire da scan codice multi-pass; output `bmad-output/project-documentation.md` + sync `project-context.md`. Religione in `docs/wiki/concepts/bmad-deep-code-documentation-religion.md`.
**Rationale:** `project-context` diceva “zero moduli dominio” mentre Customer/WorkOrder/… erano già presenti — rischio storie su falso gap. User: “con BMAD studia e analizza a fondo il codice e documenta”.
**Source:** User request deep BMAD analysis.

