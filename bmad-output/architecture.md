# Architecture — Porting gestionale_commesse

> Track: BMad Method
> Fonte: `docs/gestionale-commesse-conversion-analysis.md`

## Tech Stack

- **Framework:** Laravel 12 + Filament 3
- **Base Model:** XotBaseModel (Modules\Xot)
- **Actions:** Spatie\QueueableAction
- **Filament:** XotBaseResource / XotBasePage / XotBaseWidget
- **Auth:** Modules\User (Spatie Permission + Passport)
- **Media:** Spatie Media Library
- **DB:** MySQL, connessione `xot` per moduli business

## Module Boundary Architecture

Ogni modulo è un package nwidart (`laravel/Modules/{Name}/`) con struttura piatta:

```
Modules/{Name}/
├── app/
│   ├── Models/
│   ├── Filament/
│   │   ├── Resources/
│   │   └── Pages/
│   ├── Actions/
│   ├── Policies/
│   └── Providers/
├── database/
│   └── migrations/
└── module.json
```

## Dependency Graph

6 wave sequenziali:

```
Wave1:  Customer → WorkOrder → Intervention
Wave2:  User → Employee (gap)
Wave3:  Catalog → Quotation
Wave4:  Media → Signature → Billing ← Fiscal → Vehicle
Wave5:  Catalog → Bom → Production
        Customer → Compliance, PublicProcurement, EnergyBroker, TimberBilling
        Catalog → Inventory
Wave6:  Notify → Email/WhatsApp inbox
        AI → AiAssistant thread/proposal
```

## Component Specification

### Wave 1 — Core
- **Customer**: models (Customer, CustomerSite, Contact, ContactRole, Reseller), Resource CRUD
- **WorkOrder**: models (WorkOrder, WorkOrderStatus, WorkOrderAssignment, WorkOrderActivity, WorkOrderStatusEvent), status machine via tabella lookup
- **Intervention**: models (Intervention, TimePunch, DailyNote, IssueReport, InterventionMaterialLine), embedded relations su WorkOrder

### Wave 2 — HR
- **Employee**: AbsenceRequest model+migration+Resource, EmployeeResource CRUD, de-mock widgets
- **User**: approval fields opt-in

### Wave 3 — Commerciale
- **Catalog**: Product, CatalogModel, MeasureUnit, PartType, Frequency, MetelCatalog
- **Quotation**: Quotation, QuotationItem, QuotationTemplate, SiteSurvey

### Wave 4 — Chiusura
- **Signature**: Signature (morph), Sketch (morph), extends Media kind
- **Billing**: Invoice, DeliveryNote, Supplier, SupplierInvoice, InvoiceWorkOrderLink
- **Fiscal**: Value objects + actions (VAT calc, FatturaPA XML, payment plan). Zero models.
- **Vehicle**: Vehicle, VehicleDocument

### Wave 5 — Manufacturing & Verticali
- **Inventory**: Warehouse, WarehouseLocation, InventoryMovement, ProductUnit
- **Bom**: Bom, BomItem
- **Production**: ProductionOrder, ProductionPhase
- **Compliance**: HeatingSystem, Boiler, ComplianceDocument, Subcontractor, WorkOrderChangeOrder
- **PublicProcurement**: ~15 models (TenderSource, TenderOpportunity, Contract, EligibilityCheck, ...)
- **EnergyBroker**: EnergyBrokerDocument, ImportBatch, CustomerMatch, SupplyPoint, SupplyHistory
- **TimberBilling**: TimberInvoice, TimberInvoiceLine, TimberEInvoice

### Wave 6 — Canali & AI
- **Notify**: EmailMessage, EmailConversation, EmailContactIdentity; WhatsAppMessage, WhatsAppConversation, WhatsAppContactIdentity
- **AI**: AiThread, AiMessage, AiActionProposal, AiToolLog

## Cross-Cutting

- Tutti i nuovi moduli usano connessione DB `xot`
- Policies estendono UserBasePolicy
- Feature visibility gating via AccountFeatures
- Traduzioni in `Resources/lang/{it,en}/`
