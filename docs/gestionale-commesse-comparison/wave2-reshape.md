---
title: "Wave 2 reshape — 13 moduli restanti"
type: concept
tags: [gestionale-commesse, wave2, reshape, xotbase]
created: 2026-07-23
updated: 2026-07-23
qmd: "wave2 reshape XotBase Billing Bom Catalog Compliance EnergyBroker Fiscal Inventory Production PublicProcurement Quotations Signatures TimberBilling Vehicles"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./copy-reshape-playbook.md"
  - "./wave1-reshape.md"
  - "../wiki/skills/bmad-create-story/stories/1.9.wave2-reshape-remaining-modules.story.md"
  - "../wiki/concepts/laraxot-module-canonical-structure.md"
---

# Wave 2 reshape — 13 moduli restanti

> Stesso pattern di [wave1-reshape.md](./wave1-reshape.md) (Customer/WorkOrder/Intervention), applicato a:
> Billing, Bom, Catalog, Compliance, EnergyBroker, Fiscal, Inventory, Production, PublicProcurement, Quotations, Signatures, TimberBilling, Vehicles.

## Matrice gap (2026-07-23)

| Modulo | Albero `app/` | Composer→`app/` | XotBaseServiceProvider | Actions QueueableAction | Resources XotBaseResource | BaseModel/XotBaseModel | Factories/Migrations parity | PHPStan L10 |
|---|---|---|---|---|---|---|---|---|
| Billing | OK | OK | OK | OK (8/8) | OK (4/4) | TODO | TODO | non eseguibile (ambiente) |
| Bom | OK | OK | OK | OK | OK | OK (già extends XotBaseModel) | parziale | non eseguibile |
| Catalog | OK | OK | OK | OK | OK | TODO | TODO | non eseguibile |
| Compliance | OK | OK | OK | OK (10/10) | OK | TODO | TODO | non eseguibile |
| EnergyBroker | OK | OK (era root `""`) | OK (era `ServiceProvider`) | OK (11/11) | OK (1/1) | TODO | TODO | non eseguibile |
| Fiscal | OK | OK | OK | OK | n/a (nessuna Resource CRUD) | TODO | TODO | non eseguibile |
| Inventory | OK | OK | OK | OK | OK | TODO | TODO | non eseguibile |
| Production | OK | OK | OK | OK | OK | TODO | TODO | non eseguibile |
| PublicProcurement | OK | OK (era root `""`) | OK (era `ServiceProvider`) | OK (6/6) | OK (3/15, resto via RelationManager) | TODO | TODO | non eseguibile |
| Quotations | OK | OK (era root `""`) | OK (era `ServiceProvider`) | OK (17/17) | OK (3/3) | TODO | TODO | non eseguibile |
| Signatures | OK | OK (era root `""`) | OK (era `ServiceProvider`) | OK (2/2) | n/a (solo Filament Page custom capture) | TODO | TODO | non eseguibile |
| TimberBilling | OK | OK (era root `""`) | OK (già presente) | OK (13/13 completate in questa wave) | OK | TODO | OK (factories 4/4 già presenti) | non eseguibile |
| Vehicles | OK | OK | OK | OK (1/1) | OK (1/1) | TODO | TODO | non eseguibile |

Nota: diversi moduli (Bom, Catalog, Compliance, Fiscal, Inventory, Production, parte di PublicProcurement/TimberBilling) risultavano **già reshaped** all'apertura di questo task da agenti concorrenti che lavoravano in parallelo sullo stesso albero — il lavoro di questa sessione si è concentrato su: **EnergyBroker, Quotations, Signatures** (reshape completo da zero, root Domain/Application/Infrastructure/Filament/Providers/Database ancora presenti), più fix puntuali su **Billing** (4 Resources + 8 Actions), **Vehicles** (1 Resource + 1 Action), **PublicProcurement** (composer.json + ServiceProvider + 6 Actions), **Production** (creata `ProductionServiceProvider` mancante, in coordinamento con agente concorrente).

## PHPStan — motivo blocco (comune a tutti e 13)

```
Error: Class "Modules\Customer\Providers\RouteServiceProvider" not found
```

Bootstrap applicativo rotto per un problema nel modulo `Customer` (fuori scope, non toccabile) — pre-esistente, riprodotto identico su tutti i moduli testati. Verifica sostitutiva: `php -l` su ogni file toccato (0 errori su tutti e 13 i moduli).

## TODO espliciti rimandati (come da playbook, non bloccanti per questa wave)

- `BaseModel extends XotBaseModel` — rimandato ovunque tranne Bom (connessione DB per-modulo da configurare, stessa nota di Wave 1)
- Factories/Seeders parity 1:1 con i model owner
- Consolidamento migrations 1 `create_*` per model
- Pest smoke + PHPStan L10 (bloccato da bug ambiente `Modules\Customer`)
- `modules_statuses.json` → resta `false` per tutti e 13

## Gap noto — Quotations → WorkOrder

`Quotations/app/Filament/Resources/QuotationResource/Pages/ViewQuotation.php` referenzia `Modules\WorkOrder\Actions\CreateWorkOrderAction`, namespace aggiornato coerentemente col reshape Wave 1 di `WorkOrder`, ma quella classe azione non esiste (più) nel modulo `WorkOrder` reshaped. Fuori scope risolverlo qui (richiederebbe toccare `WorkOrder`).
