# Project Context

**Progetto:** Porting gestionale_commesse → base_workorder_fila5  
**Stato:** Implementation (planning artifacts presenti; sprint attivo)  
**Repository:** `/var/www/_bases/base_workorder_fila5`  
**SRC:** `/var/www/_bases/gestionale_commesse`  
**Ground truth:** [project-documentation.md](./project-documentation.md) (scan 2026-07-27)

## Mission

Portare i moduli del gestionale field-service (commesse, interventi, preventivi, fatturazione, magazzino, produzione, compliance, procurement, …) nella struttura Laraxot di `base_workorder_fila5`, convertendo con le convenzioni del framework (XotBase, Filament, QueueableAction, Folio/Volt).

## Principi

- Singolare PascalCase per nomi modulo (`WorkOrder`, `Customer`, `Intervention`)
- Model: `BaseModel` modulo → `XotBaseModel` (non sempre extend diretto)
- Mai `Filament\*` diretto — sempre `XotBase*`
- Mai Controllers FO — Filament Resources/Pages + Folio/Volt
- Azioni con Spatie QueueableAction
- Nessuna master password
- `Job` ≠ `WorkOrder` — Job è Laravel queue / modulo Job
- BMAD: **studiare a fondo il codice prima di documentare** — [religione](../docs/wiki/concepts/bmad-deep-code-documentation-religion.md)

## Track

BMad Method — PRD + Architecture + epics + story files; implementation in `docs/stories/` + `docs/sprint-status.yaml`.

## Stato attuale (post deep scan 2026-07-27)

- **38** moduli con `module.json`; **~40** enabled in `modules_statuses.json`.
- **Dominio presente** (non “zero”): Customer, WorkOrder, Intervention, Quotation, Billing, Catalog, Signature, Document, Vehicle, Bom, Production, Inventory, Compliance, PublicProcurement, EnergyBroker, TimberBilling, …
- **Piattaforma:** Xot, User, Tenant, Cms, Geo, Lang, Media, UI, Activity, Notify, Gdpr, Job, HR, Employee, Rating, Seo, Platform, Fiscal, AI, AiAssistant, Email, WhatsApp, …
- Totali orientativi: ~498 models · ~725 actions · ~136 Filament Resource · ~300 migrations · ~1246 test PHP.
- Temi FO: Sixteen / TwentyOne / Zero (+ altri) — Folio pages.
- Gap noti: collision Document/Media `media`; Pest D17 env; Controllers FO residui (~29 namespace).

## Artifact chiave

| File | Ruolo |
|------|--------|
| `bmad-output/project-documentation.md` | Brownfield SSoT scan |
| `bmad-output/prd.md` | PRD |
| `bmad-output/architecture.md` | Architecture |
| `bmad-output/epics.md` | Epics |
| `docs/sprint-status.yaml` | Coda implementazione |
| `docs/bmm-workflow-status.yaml` | Stato fasi BMAD |
