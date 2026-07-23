---
title: "Mapping moduli gestionale_commesse → base_workorder_fila5"
type: concept
tags: [gestionale-commesse, module-mapping, parity, laraxot, bmad]
created: 2026-07-23
updated: 2026-07-23
qmd: "gestionale_commesse module mapping Auth User HR Employee WorkOrders parity"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./glossary-align.md"
  - "./new-modules-candidates.md"
  - "../../laravel/Modules/User/docs/gestionale-auth-vs-user.md"
  - "../../laravel/Modules/Employee/docs/gestionale-hr-gap.md"
  - "../../laravel/Themes/docs/gestionale-panels-vs-themes.md"
  - "../wiki/skills/bmad-create-story/stories/gestionale-commesse-module-parity.story.md"
---

# Mapping moduli gestionale_commesse → base_workorder_fila5

> **Perché:** `gestionale_commesse` è il dominio operativo (commesse, interventi, preventivi). Questo progetto è la base Laraxot su cui portarlo. I nomi moduli non coincidono: va esplicitato il corrispettivo o il candidato nuovo.

## Fonte analizzata

- Path: `/var/www/_bases/gestionale_commesse`
- Architettura SRC: `Domain/` + `Application/` + `Infrastructure/` (DDD leggero)
- Stack UI SRC: multi **Filament Panel** (Admin, Operator, Customer, Timber, …) — **nessun** package `Themes/`
- Glossario SRC: `docs/glossary.md` (Commessa→WorkOrder, Dipendente→Employee, …)

## Tabella master (SRC → nostro)

| Modulo SRC | Modelli (n) | Nostro corrispettivo | Tipo | Nome proposto Laraxot | Note |
|---|---:|---|---|---|---|
| **Auth** | 0 (User in `app/`) | **User** | rename/esistente | `User` | Vedi [gestionale-auth-vs-user.md](../../laravel/Modules/User/docs/gestionale-auth-vs-user.md) |
| **HR** | 5 | **Employee** | rename/esistente + gap | `Employee` | Vedi [gestionale-hr-gap.md](../../laravel/Modules/Employee/docs/gestionale-hr-gap.md) |
| **Customers** | 5 | — | **nuovo** | `Customer` | Cliente/sede/referente/rivenditore |
| **WorkOrders** | 5 | — (**non** Job) | **nuovo** | `WorkOrder` | Commessa; `Job` = code Laravel |
| **Interventions** | 7 | — (parziale overlap Employee TimePunch) | **nuovo** | `Intervention` | Timbrature su intervento ≠ WorkHour generico |
| **Quotations** | 5 | — | **nuovo** | `Quotation` | Preventivi + template + site survey |
| **Catalog** | 7 | — | **nuovo** | `Catalog` | Prodotti + Metel + lookup |
| **Documents** | 1 (`Media`) | **Media** | esistente (sottinsieme) | `Media` | SRC Documents è thin wrapper su media |
| **Signatures** | 2 | — | **nuovo** | `Signature` | Firma + sketch canvas |
| **Vehicles** | 2 | — | **nuovo** | `Vehicle` | Flotta + documenti veicolo |
| **Billing** | 7 | — | **nuovo** | `Billing` | Fatture, DDT, fornitori |
| **Platform** | 2 | **Tenant** + **Activity** + **Xot** | split | — | Settings→Tenant/Xot; AuditLog→Activity |
| **AiAssistant** | 4 | **AI** | esistente + gap | `AI` | Thread/proposal mancanti |
| **Inventory** | 5 | — | **nuovo** | `Inventory` | Magazzino/movimenti |
| **Bom** | 2 | — | **nuovo** | `Bom` | Distinta base |
| **Production** | 2 | — | **nuovo** | `Production` | Ordini/fasi produzione |
| **Compliance** | 9 | ≠ **Gdpr** | **nuovo** | `Compliance` | Impianti/POS/RCEE — non consensi GDPR |
| **PublicProcurement** | 15 | — | **nuovo** | `PublicProcurement` | Appalti/gare (verticale) |
| **EnergyBroker** | 6 | — | **nuovo** | `EnergyBroker` | Verticale energy |
| **WhatsApp** | 3 | **Notify** (canale) | estendere | restare in `Notify` | Preferire canale, non modulo gemello |
| **Email** | 3 | **Notify** (inbox) | estendere | restare in `Notify` | Poll inbox ≠ MailTemplate |
| **TimberBilling** | 4 | — | **nuovo** o sotto-Billing | `TimberBilling` | Verticale timber FE |
| **Fiscal** | 0 (solo actions) | — | **nuovo** (lib) | `Fiscal` | Calcoli IVA/XML — senza modelli |

## Moduli nostri senza corrispettivo SRC (non eliminare)

| Nostro | Ruolo | Impatto sul porting |
|---|---|---|
| Xot | Fondazione Laraxot / XotBase | Obbligatorio per ogni nuovo modulo |
| Lang / UI / Cms / Blog / Comment / Seo / Rating / Geo | Piattaforma FO/CMS | Fuori scope gestionale operativo |
| Job | Queue/schedule | **Non** mappare a WorkOrders |
| Gdpr | Consensi/trattamenti | Non sostituisce Compliance |
| Tenant | Multi-tenant | Assorbe parte Platform |

## Regola naming (politica)

- SRC usa **plurale** dominio (`Customers`, `WorkOrders`).
- Laraxot in questo repo usa **nome dominio** spesso singolare (`User`, `Employee`, `Media`).
- Per i nuovi moduli del porting: **singolare PascalCase** allineato al glossario inglese SRC (`WorkOrder`, `Customer`, `Intervention`), salvo brand verticali (`EnergyBroker`, `PublicProcurement`, `TimberBilling`).

## Temi / panels

SRC non ha Themes: ha **N panel Filament**. Noi: Themes Folio (Sixteen/Zero/…) + panel admin per modulo. Dettaglio: [gestionale-panels-vs-themes.md](../../laravel/Themes/docs/gestionale-panels-vs-themes.md).

## Priorità porting (business)

1. `Customer` + `WorkOrder` + `Intervention` (cuore commessa)
2. `Quotation` + `Catalog`
3. Gap `Employee` (HR) + allineamenti `User`
4. `Billing` / `Fiscal` / `Signature` / `Vehicle` / `Media`
5. Verticali (`PublicProcurement`, `EnergyBroker`, `TimberBilling`) e manufacturing (`Inventory`, `Bom`, `Production`)
6. Canali (`Notify` WhatsApp/Email inbox) + `AI` parity
