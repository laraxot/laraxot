---
title: "Candidati nuovi moduli dal gestionale_commesse"
type: concept
tags: [gestionale-commesse, new-modules, naming, roadmap]
created: 2026-07-23
updated: 2026-07-23
qmd: "new modules WorkOrder Customer Intervention Quotation Catalog Billing"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./module-mapping.md"
  - "./glossary-align.md"
---

# Candidati nuovi moduli dal gestionale_commesse

> **Perché:** elenco decisionale — cosa creare come modulo nwidart vs estendere un esistente. Nessuna implementazione qui.

## Nuovi moduli consigliati (core)

| Nome proposto | Da SRC | Scope minimo (modelli) | Dipendenze |
|---|---|---|---|
| `Customer` | Customers | Customer, CustomerSite, Contact, ContactRole, Reseller | User (opz. customer_id portal) |
| `WorkOrder` | WorkOrders | WorkOrder, Assignment, Activity, Status, StatusEvent | Customer, User/Employee |
| `Intervention` | Interventions | Intervention, TimePunch, DailyNote, IssueReport, materials | WorkOrder, Media, Signature |
| `Quotation` | Quotations | Quotation, Item, Template, SiteSurvey | Customer, Catalog |
| `Catalog` | Catalog | Product, Metel*, lookup units/models | — |

## Nuovi moduli secondari

| Nome | Da SRC | Note |
|---|---|---|
| `Signature` | Signatures | Canvas firma/sketch morphable |
| `Vehicle` | Vehicles | Flotta + VehicleDocument |
| `Billing` | Billing | Invoice, DeliveryNote, Supplier* |
| `Fiscal` | Fiscal | Solo actions/calcoli (VAT, FE XML) — riusabile da Billing/Timber |
| `Inventory` | Inventory | Warehouse, movements |
| `Bom` | Bom | Distinta base |
| `Production` | Production | Ordini/fasi |
| `Compliance` | Compliance | Impianti/documenti tecnici — **non** Gdpr |
| `PublicProcurement` | PublicProcurement | Verticale appalti |
| `EnergyBroker` | EnergyBroker | Verticale energy |
| `TimberBilling` | TimberBilling | Verticale timber (o namespace in Billing) |

## Estendere esistenti (non creare gemelli)

| SRC | Estendere | Cosa manca |
|---|---|---|
| Documents | Media | Action attach-to-intervention; morph media domain |
| WhatsApp / Email | Notify | Conversation/Message inbox + identity resolve |
| AiAssistant | AI | AiThread, AiMessage, AiActionProposal, tool log |
| Platform.AuditLog | Activity | Audit strutturato business |
| Platform.Setting | Tenant / Xot | Settings multi-tenant |

## Anti-pattern

- Creare modulo `Job` per le commesse → **vietato** (`Job` = queue).
- Creare `Auth` parallelo a `User` → portare feature in `User`.
- Creare `HR` parallelo a `Employee` → colmare gap in `Employee`.
- Creare `Gdpr` per Compliance impianti → moduli diversi.

## Wave di implementazione (story figlie)

| Wave | Moduli / gap | Perché |
|---|---|---|
| 1 | `Customer` → `WorkOrder` → `Intervention` | Cuore field-service; sblocca Media morph e firme |
| 2 | Gap `Employee` (AbsenceRequest, EmployeeResource) + opt-in User approval | Operatori e cartellino coerenti |
| 3 | `Quotation` + `Catalog` | Preventivo → commessa |
| 4 | `Signature` + `Billing` + `Fiscal` | Chiusura ciclo commerciale |
| 5 | `Inventory`/`Bom`/`Production` + verticali | Manufacturing / appalti / energy / timber |
| 6 | Notify inbox + AI thread/proposal | Canali e assistant |

Inventario quantitativo SRC: [inventory-src.md](./inventory-src.md).
