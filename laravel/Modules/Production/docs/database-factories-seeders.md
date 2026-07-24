# Production — Stato Factory / Seeder / Migration

Obiettivo: ogni modello concreto ha migration + factory + seeder.

## Copertura modelli

| Modello | Tabella | Migration | Factory | Seeder |
|---|---|---|---|---|
| `ProductionOrder` | `production_orders` | OK | `ProductionOrderFactory` (esistente) | `ProductionOrderSeeder` (nuovo) |
| `ProductionPhase` | `production_phases` | OK | `ProductionPhaseFactory` (esistente) | `ProductionPhaseSeeder` (nuovo) |

Orchestratore: `ProductionDatabaseSeeder` chiama `ProductionOrderSeeder` poi `ProductionPhaseSeeder`.

## Skip motivati

Nessuno skip. Entrambi i modelli sono concreti; nessun pivot/STI/tabella condivisa.

## Note

- I seeder sono idempotenti (early-return se la tabella ha già righe).
- `ProductionPhaseFactory` crea il `ProductionOrder` collegato al volo; `ProductionOrderFactory` crea il `Product` (modulo `Catalog`).
- `work_order_id` e `assigned_to_user_id` restano `null` di default (opzionali).
- `XotBaseModel` è la base astratta (modulo `Xot`), esclusa dal conteggio.
