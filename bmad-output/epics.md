# Epics — Porting gestionale_commesse → base_workorder_fila5

> Track: BMad Method
> Sources: prd.md, architecture.md

---

## Epic 1: Core Field Service

**Goal:** Customer registry, WorkOrder lifecycle, Intervention field tracking.

**In scope (cited):**
- FR-01 — Customer registry with sites/contacts/resellers [Source: prd.md]
- FR-01 — WorkOrder lifecycle with status machine [Source: prd.md]
- FR-01 — Intervention with time punches, issue reporting, daily notes [Source: prd.md]
- NFR-01, NFR-02, NFR-04 — Convenzioni Laraxot [Source: architecture.md]

**Architecture touchpoints:** Nuovi moduli Customer, WorkOrder, Intervention. Dipende da: User (Auth), Media (Documents). [Source: architecture.md#wave-1]

**Out of scope:** Employee gap, Catalog, Billing, verticali.

**Stories (ordered):**

| ID | Slug | Intent | Status |
|------|------|--------|--------|
| 1.1 | customer-module | Create Customer module with Customer, CustomerSite, Contact, Reseller | backlog |
| 1.2 | workorder-module | Create WorkOrder module with status machine, assignments, activities | backlog |
| 1.3 | intervention-module | Create Intervention module with time punches, issues, notes | backlog |

**Cross-epic dependencies:**
- Blocked by: (none — piattaforma User esiste)
- Blocks: Epic 2 (HR), Epic 4 (Billing needs WorkOrder), Epic 5 (Compliance needs WorkOrder)

---

## Epic 2: HR & User Alignment

**Goal:** Complete Employee module (AbsenceRequest, EmployeeResource), extend User with approval flow.

**In scope (cited):**
- FR-02 — AbsenceRequest E2E [Source: prd.md]
- FR-02 — EmployeeResource CRUD [Source: prd.md]
- FR-02 — User approval opt-in [Source: prd.md]

**Architecture touchpoints:** Employee module gap filling, User module opt-in fields. [Source: architecture.md#wave-2]

**Out of scope:** Employee WorkHour clock (già esiste).

**Stories (ordered):**

| ID | Slug | Intent | Status |
|------|------|--------|--------|
| 2.1 | absence-request | AbsenceRequest model, migration, Resource, Policy | backlog |
| 2.2 | employee-resource-crud | EmployeeResource CRUD with MedicalVisit/Training/Document RMs | backlog |
| 2.3 | user-approval-optin | Add approved_at/approved_by fields to User, approval action | backlog |

**Cross-epic dependencies:**
- Blocked by: Epic 1 (Intervention condivide Employee scope)
- Blocks: (none)

---

## Epic 3: Commerciale (Catalog + Quotation)

**Goal:** Product catalog and quotation management.

**In scope (cited):**
- FR-03 — Product catalog with classification axes [Source: prd.md]
- FR-03 — Quotation with items, templates, site surveys [Source: prd.md]

**Architecture touchpoints:** Nuovo modulo Catalog, Quotation. [Source: architecture.md#wave-3]

**Out of scope:** Billing (si appoggia a preventivi ma è wave separata).

**Stories (ordered):**

| ID | Slug | Intent | Status |
|------|------|--------|--------|
| 3.1 | catalog-module | Create Catalog module with Product, CatalogModel, MeasureUnit, PartType, Frequency, MetelCatalog | backlog |
| 3.2 | quotation-module | Create Quotation module with Quotation, QuotationItem, QuotationTemplate, SiteSurvey | backlog |

**Cross-epic dependencies:**
- Blocked by: Epic 1 (Customer needed by Quotation)
- Blocks: Epic 4 (Billing needs Catalog)

---

## Epic 4: Ciclo di Chiusura

**Goal:** Invoicing, fiscal engine, signatures on interventions, fleet registry.

**In scope (cited):**
- FR-04 — Invoice/DeliveryNote from work orders [Source: prd.md]
- FR-04 — Fiscal engine (VAT, FatturaPA, payment plans) [Source: prd.md]
- FR-04 — Signatures/Sketches on interventions [Source: prd.md]

**Architecture touchpoints:** Nuovo Billing, Fiscal (solo actions), Signature, Vehicle. Estendere Media (kind). [Source: architecture.md#wave-4]

**Out of scope:** TimberBilling (verticale, wave 5).

**Stories (ordered):**

| ID | Slug | Intent | Status |
|------|------|--------|--------|
| 4.1 | signature-module | Create Signature module with Signature/Sketch morph models | backlog |
| 4.2 | fiscal-module | Create Fiscal engine: VAT calc, FatturaPA XML, payment plan actions | backlog |
| 4.3 | billing-module | Create Billing module with Invoice, DeliveryNote, Supplier | backlog |
| 4.4 | vehicle-module | Create Vehicle module | backlog |

**Cross-epic dependencies:**
- Blocked by: Epic 1 (WorkOrder, Intervention), Epic 3 (Catalog for products)
- Blocks: Epic 5 (TimberBilling needs Fiscal)

---

## Epic 5: Manufacturing & Verticali

**Goal:** Inventory/Warehouse, BOM, Production, Compliance (heating), PublicProcurement, EnergyBroker, TimberBilling.

**In scope (cited):**
- FR-05 — Inventory movements and stock [Source: prd.md]
- FR-05 — BOM and Production orders [Source: prd.md]
- FR-05 — Compliance (heating systems, boilers, subcontractors) [Source: prd.md]
- FR-05 — PublicProcurement tender-to-contract [Source: prd.md]
- FR-05 — EnergyBroker and TimberBilling verticals [Source: prd.md]

**Architecture touchpoints:** Nuovi Inventory, Bom, Production, Compliance, PublicProcurement, EnergyBroker, TimberBilling. [Source: architecture.md#wave-5]

**Out of scope:** Canali comunicazione (wave 6).

**Stories (ordered):**

| ID | Slug | Intent | Status |
|------|------|--------|--------|
| 5.1 | inventory-module | Warehouse, Location, Movement, ProductUnit | backlog |
| 5.2 | bom-module | Bom, BomItem | backlog |
| 5.3 | production-module | ProductionOrder, ProductionPhase | backlog |
| 5.4 | compliance-module | HeatingSystem, Boiler, ComplianceDocument, Subcontractor, ChangeOrder | backlog |
| 5.5 | publicprocurement-module | ~15 models tender-to-contract | backlog |
| 5.6 | energybroker-module | Document, ImportBatch, SupplyPoint, SupplyHistory | backlog |
| 5.7 | timberbilling-module | TimberInvoice, TimberInvoiceLine, TimberEInvoice | backlog |

**Cross-epic dependencies:**
- Blocked by: Epic 1, Epic 3, Epic 4 (Fiscal per TimberBilling)
- Blocks: (none)

---

## Epic 6: Canali Comunicazione & AI

**Goal:** Email/WhatsApp inbound inbox, AI thread/action proposals.

**In scope (cited):**
- FR-06 — Email inbound with CRM identity resolution [Source: prd.md]
- FR-06 — WhatsApp Business API inbound [Source: prd.md]
- FR-06 — AI thread/message/proposal models [Source: prd.md]

**Architecture touchpoints:** Estendere Notify (inbox), estendere AI (thread/proposal). [Source: architecture.md#wave-6]

**Out of scope:** AI streaming/STT/TTS (P1).

**Stories (ordered):**

| ID | Slug | Intent | Status |
|------|------|--------|--------|
| 6.1 | email-inbox-notify | EmailMessage, EmailConversation, identity resolution in Notify | backlog |
| 6.2 | whatsapp-inbox-notify | WhatsAppMessage, WhatsAppConversation in Notify | backlog |
| 6.3 | ai-thread-message | AiThread, AiMessage models in AI module | backlog |
| 6.4 | ai-action-proposal | AiActionProposal, AiToolLog engine | backlog |

**Cross-epic dependencies:**
- Blocked by: Epic 1 (WorkOrder, Intervention for tool calling)
- Blocks: (none)

---

## Delivery Tracking (count-based)

- Total stories: 25
- Done: 0
- Remaining: 25
- Completion rate: 0%

## Notes

Wave 1 è la priorità assoluta: sblocca tutte le altre wave. Wave 2 va in parallelo parziale con Wave 1 (Employee non dipende da core, solo da User). Wave 5 è la più grande: 7 moduli, shardabili in parallelo se Catalog e WorkOrder sono pronti.
