# EPIC: Allineamento moduli gestionale_commesse → base_workorder_fila5

**Fase:** 4 - Implementation (Story Definition)
**Creato:** 2026-07-23
**Fonte analisi:** agente Explore, report completo in questa conversazione BMAD `/create-story`

## Obiettivo

Mappare ogni modulo di `/var/www/_bases/gestionale_commesse` (Laravel modulare, stessa base XotBase/Laraxot) sul corrispondente modulo di questo repo, documentare i gap, e definire le story necessarie a colmarli o a creare i moduli mancanti.

## Story già dettagliate

| Story | Doc | Stato |
|---|---|---|
| Auth → User: colmare i gap | [`laravel/Modules/User/docs/gestionale-commesse-auth-vs-user.md`](../../laravel/Modules/User/docs/gestionale-commesse-auth-vs-user.md) | Definita — pronta per stima |
| HR → Employee: colmare i gap | [`laravel/Modules/Employee/docs/gestionale-commesse-hr-vs-employee.md`](../../laravel/Modules/Employee/docs/gestionale-commesse-hr-vs-employee.md) | Definita — pronta per stima (assenze da approfondire) |

## Backlog: moduli da creare ex-novo (corrispondenza assente)

Nessun modulo di dominio business esiste ancora in questo repo per queste aree — ognuna richiede una story di creazione modulo (`php artisan module:make`, migrazioni, model, Filament resource):

| Nuovo modulo proposto | Origine gestionale_commesse | Modelli principali da portare |
|---|---|---|
| `WorkOrder` | WorkOrders (core business, dà il nome al progetto target) | WorkOrder, WorkOrderAssignment, WorkOrderActivity, WorkOrderStatus, WorkOrderStatusEvent |
| `Customer` | Customers | Customer, CustomerSite, Contact, ContactRole, Reseller |
| `Billing` | Billing | Invoice, InvoiceWorkOrderLink, Supplier, SupplierInvoice, SupplierInvoiceLine, DeliveryNote, DeliveryNoteLine |
| `Catalog` | Catalog | Product, CatalogFrequency, CatalogPartType, CatalogModel, CatalogMeasureUnit, MetelCatalog, MetelCatalogItem |
| `Compliance` | Compliance | Equipment, HeatingSystem, CustomerSystem, Boiler, MaintenanceControl, ComplianceDocument, Subcontractor |
| `Intervention` | Interventions | Intervention, DailyNote, InterventionMaterialLine, InterventionReportShare, IssueReport, TimePunch |
| `Inventory` | Inventory | Warehouse, WarehouseLocation, InventoryMovement, ProductUnit |
| `Production` | Production | ProductionOrder, ProductionPhase |
| `Quotation` | Quotations | Quotation, QuotationItem, QuotationTemplate, SiteSurvey |
| `Vehicle` | Vehicles | Vehicle, VehicleDocument |
| `Bom` | Bom | Bom, BomItem |
| `Signature` | Signatures | Signature, Sketch |

## Backlog: da valutare con il Product Owner prima di aprire story

Verticali di dominio molto specifici di gestionale_commesse — rilevanza per base_workorder_fila5 non confermabile senza colloquio di scope:

- `Fiscal` (solo Domain layer, valutare se assorbito da `Billing`)
- `PublicProcurement` (appalti pubblici — 14 modelli, verticale molto specifico)
- `TimberBilling` (verticale settore legno — probabilmente da non portare, o generalizzare in `Billing`)
- `EnergyBroker` (verticale broker energia)

## Backlog: moduli con corrispondenza parziale (estendere esistente, non creare nuovo)

| Modulo gestionale_commesse | Modulo target da estendere | Gap |
|---|---|---|
| AiAssistant | `AI` | Mancano AiThread, AiMessage, AiToolLog, AiActionProposal (assistente conversazionale con log azioni); `AI` oggi è focalizzato su Sentiment/Predict |
| WhatsApp | `Notify` | Manca modello dedicato WhatsAppConversation/WhatsAppMessage/WhatsAppContactIdentity; Notify ha solo il canale di invio |
| Email | `Notify` | Manca casella email bidirezionale (EmailConversation/EmailMessage); Notify gestisce solo invio/template |
| Platform | `Xot` / `Activity` | `PlatformSetting` non ha equivalente; `AuditLog` è concettualmente coperto da `Activity` (event sourcing) |

## Corrispondenza già buona (nessuna azione)

- `Documents` (gestionale_commesse) ↔ `Media` (base_workorder_fila5): pattern equivalente, Media è già a livello pari o superiore.

## Note sui temi (Themes)

Nessun tema (`Barthelemy`, `Meetup`, `Sixteen`, `TwentyOne`, `Zero`) presenta contenuto direttamente confrontabile con i moduli Auth/HR di gestionale_commesse: sono temi di presentazione UI, i moduli confrontati sono backend/dominio. Non è stata prodotta documentazione nei temi per questo motivo — se in futuro emerge un tema equivalente lato gestionale_commesse da confrontare, va segnalato esplicitamente.

## Prossimi passi

1. Stimare le due story già dettagliate (Auth→User, HR→Employee) con `/create-story STORY-ID`.
2. Colloquio di scope con PO per i 4 moduli "da valutare".
3. Per ciascun nuovo modulo del backlog, aprire una story dedicata con `/create-story` seguendo il formato standard (user story, AC, note tecniche, DoD).

**Questa epic è stata creata usando BMAD Method v6 - Phase 4 (Implementation Planning).**
