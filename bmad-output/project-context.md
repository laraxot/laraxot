# Project Context

**Progetto:** Porting gestionale_commesse → base_workorder_fila5
**Stato:** Planning
**Repository:** `/var/www/_bases/base_workorder_fila5`
**SRC:** `/var/www/_bases/gestionale_commesse`

## Mission

Portare i 23 moduli del gestionale field-service (commesse, interventi, preventivi, fatturazione, magazzino, produzione, compliance, procurement) nella struttura Laraxot di base_workorder_fila5, convertendo ogni modulo con le convenzioni del framework (XotBase, Filament, QueueableAction, Folio/Volt).

## Principi

- Singolare PascalCase per nomi modulo (`WorkOrder`, `Customer`, `Intervention`)
- Ogni model estende `XotBaseModel`
- Mai `Filament\*` diretto — sempre `XotBase*`
- Mai Controllers FO — Filament Resources/Pages + Folio/Volt
- Azioni con Spatie QueueableAction
- Nessuna master password
- `Job` ≠ `WorkOrder` — Job è Laravel queue

## Track

BMad Method — PRD + Architecture + epics + story files (~25 stories, 6 wave).

## Stato attuale

18 moduli di piattaforma (Xot, User, Employee, Media, AI, Notify, Activity, Tenant, ...).
Zero moduli di dominio business (Customer, WorkOrder, Intervention, ...).
