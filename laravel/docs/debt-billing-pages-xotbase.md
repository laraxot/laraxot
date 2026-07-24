---
title: "Debito D11 — Billing Pages ancora Filament diretto"
type: how-to
tags: [technical-debt, billing, filament, xotbase, pages]
created: 2026-07-24
updated: 2026-07-24
qmd: "debt billing pages xotbase list create edit view record"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./gestionale-technical-debt.md"
  - "../Modules/Billing/docs/import-status.md"
---

# Debito D11 — Billing: Pages non-XotBase

## Perché

Le **Resource** Billing estendono già `XotBaseResource` (`getFormSchema`).  
Le **Pages** (List/Create/Edit/View) di Invoice, DeliveryNote, Supplier, SupplierInvoice estendono ancora classi Filament dirette (`ListRecords`, `CreateRecord`, …).

Religione Laraxot: mai estendere Filament “nudo” — usare `XotBaseListRecords` / `XotBaseCreateRecord` / `XotBaseEditRecord` / `XotBaseViewRecord`.

Impatto: inconsistenza UX (header actions, infolist, table columns pattern), debito PHPStan futuro, onboarding più difficile.

## Evidenza

Sotto `Modules/Billing/app/Filament/Resources/*/Pages/` (~16 file) compaiono `extends ListRecords|CreateRecord|EditRecord|ViewRecord`.

```bash
cd laravel
rg -n "extends (ListRecords|CreateRecord|EditRecord|ViewRecord)" \
  Modules/Billing/app/Filament/Resources --glob '*/Pages/*.php'
```

## Come risolvere (playbook meccanico)

### Prerequisito

- Resource già `XotBaseResource` + `getFormSchema()` OK.
- Per **View**: ogni Resource deve esporre `getInfolistSchema(): array` (o equivalente XotBase) — altrimenti View page fallisce a runtime.

### Passi per ogni Page

1. Lock file multi-agente sulla Page.
2. Sostituire `use Filament\Resources\Pages\ListRecords` → `use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords` (analogamente Create/Edit/View).
3. `class ListInvoices extends XotBaseListRecords`.
4. List: implementare `getTableColumns(): array` delegando a `*Table` se esiste (pattern Customer/WorkOrder).
5. Create/Edit: niente `validate()` custom se lo schema è nel Form; usare `getState()` dove applicabile.
6. View: `getInfolistSchema()` sulla Resource **prima** di convertire View page.
7. PHPStan scoped:
   ```bash
   ./vendor/bin/phpstan analyse Modules/Billing --memory-limit=-1
   ```
8. Smoke Filament: aprire list/create di Invoice in panel Billing.

### Ordine risorse consigliato

1. Invoice (cuore fatturazione)
2. DeliveryNote
3. Supplier / SupplierInvoice

### RelationManagers

Se esistono RelationManager Filament diretti: stessa onda → `XotBaseRelationManager` (story separata se >5 file).

## DoD

- [ ] Zero `extends ListRecords|CreateRecord|EditRecord|ViewRecord` sotto Billing Pages
- [ ] PHPStan Billing 0 errori reali
- [ ] Almeno un smoke HTTP o manuale documentato su Issue #14
- [ ] `Modules/Billing/docs/import-status.md` checkbox Pages XotBase = `[x]`

## Non fare

- Convertire View senza infolist (crash).
- Duplicare `TextInput` nel widget/page bypassando ResourceForm.
- Toccare DomPDF in questa story (è D7).

## Effort

Ordine di grandezza: **1–2 giorni** uomo/agente per 16 pages + infolist mancanti.
