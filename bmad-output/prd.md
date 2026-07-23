# Product Requirements Document — Porting gestionale_commesse

> Track: BMad Method
> Fonte: `docs/gestionale-commesse-conversion-analysis.md`

## Executive Summary

Portare l'intero dominio field-service (23 moduli SRC) nella base Laraxot, convertendo ogni modulo alle convenzioni del framework. 5 moduli hanno corrispettivi esistenti da estendere, 2 vengono scissi, 16 sono nuovi.

## Functional Requirements

### FR-01 — Core field service
Customer registry with sites/contacts, WorkOrder lifecycle with status machine, Intervention with time punches and reporting.

### FR-02 — HR & User
Registration approval opt-in, AbsenceRequest E2E in Employee, EmployeeResource CRUD, de-mock leave/presence widgets.

### FR-03 — Commerciale a monte
Product catalog with classification axes, Quotation with items/templates/site surveys, linked to customers.

### FR-04 — Ciclo di chiusura
Invoicing from work orders, delivery notes, supplier invoices, fiscal engine (VAT calc, FatturaPA), signatures/sketches on interventions.

### FR-05 — Manufacturing & Verticali
Inventory (warehouses/movements/stock), BOM (distinta base), Production orders/phases, Compliance (heating systems/boilers), PublicProcurement (tender-to-contract), EnergyBroker, TimberBilling.

### FR-06 — Canali comunicazione & AI
Email/WhatsApp inbound inbox with CRM identity resolution, AI thread/message models with action proposals.

## Non-Functional Requirements

### NFR-01 — Convenzioni Laraxot
Ogni model estende XotBaseModel. Ogni resource estende XotBaseResource. Actions usano Spatie QueueableAction. Mai Filament\* diretto.

### NFR-02 — Struttura nwidart
Struttura piatta: `app/Models`, `app/Filament`, `app/Actions`. Non struttura DDD `Domain/Application/Infrastructure/`.

### NFR-03 — Multi-panel
Admin panel per gestione, Operator panel per operatori. Customer panel opzionale.

### NFR-04 — Naming
Singolare PascalCase modulo (`WorkOrder`, `Customer`). Tabelle snake_case plurale. Inglese ovunque, mai legacy IT.

## MoSCoW

| Priority | Requirements |
|----------|-------------|
| **Must** | FR-01 (Customer→WorkOrder→Intervention) |
| **Must** | FR-02 HR gap colmato |
| **Should** | FR-03 Catalog + Quotation |
| **Should** | FR-04 Billing + Fiscal + Signature |
| **Could** | FR-05 Manufacturing & Verticali |
| **Could** | FR-06 Canali + AI |
