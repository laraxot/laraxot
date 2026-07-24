---
title: "Story: gestionale_commesse module parity documentation"
type: story
tags: [bmad, story, gestionale-commesse, ready-for-dev]
created: 2026-07-23
updated: 2026-07-24
status: in-progress
qmd: "story gestionale_commesse module parity Auth User HR Employee"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "../gestionale-commesse-comparison/module-mapping.md"
  - "../gestionale-commesse-comparison/inventory-src.md"
  - "../gestionale-commesse-comparison/EPIC.md"
  - "../gestionale-commesse-comparison/replica-singular-phase.md"
  - "./1.2.customer-module.story.md"
  - "./1.3.workorder-module.story.md"
  - "./1.4.intervention-module.story.md"
  - "./1.5.employee-hr-gap.story.md"
  - "./README.md"
  - "./2.5.migrate-gestionale-tests.story.md"
  - "./2.6.technical-debt-playbooks.story.md"
  - "../sprint-status.yaml"
  - "../../laravel/docs/debt-replica-auth-hr-enable.md"
---

# Story 1.1: gestionale_commesse module parity (docs + gap)

Status: **in-progress** (implementazione figlie in review; review naming #8 aperta)

> Indice vivo: [stories/README.md](./README.md) · Sprint: [sprint-status.yaml](../sprint-status.yaml)

## GitHub (tracciamento) — OBBLIGATORIO

| Tipo | Repo | # | URL |
|------|------|---|-----|
| **Discussion** (canon) | `laraxot/base_workorder_fila5` | 8 | https://github.com/laraxot/base_workorder_fila5/discussions/8 |
| Issue (owner) | `laraxot/base_workorder_fila5` | 7 | https://github.com/laraxot/base_workorder_fila5/issues/7 |

## Story

As a **platform architect**,
I want **una mappa completa gestionale_commesse → moduli Laraxot con gap Auth/HR e inventario modelli**,
so that **il porting crei i moduli giusti senza confondere Job/WorkOrder né Auth/User**.

## Acceptance Criteria

1. Esiste tabella master SRC→nostro con tipo (esistente/nuovo/estendere) e nome proposto.
2. Auth vs User documentato in `User/docs` (FO Folio, 2FA, approval, anti master-password).
3. HR vs Employee documentato in `Employee/docs` con checklist gap (AbsenceRequest, medical/training/docs, EmployeeResource).
4. Documents/WhatsApp/Email/AiAssistant/Platform mappati su Media/Notify/AI/Activity/Tenant.
5. Themes: documentato multi-panel SRC vs Themes FO.
6. Job esplicitamente ≠ WorkOrder.
7. Compliance esplicitamente ≠ Gdpr.
8. Inventario quantitativo SRC (modelli/hub/grafo) in comparison docs.
9. Nessun codice applicativo obbligatorio in questa story (docs-only); implementazione = story figlie 1.2–1.5+.

## Tasks / Subtasks

- [x] Inventario moduli/modelli/actions SRC (AC: #1, #8)
- [x] Docs mapping + glossary + new-modules + inventory (AC: #1, #8)
- [x] Docs User Auth gap (AC: #2)
- [x] Docs Employee HR gap (AC: #3)
- [x] Docs Media/Notify/AI/Activity/Tenant/Job/Gdpr/Themes (AC: #4–7)
- [x] Story figlie 1.2–1.5 stub ready-for-dev (AC: #9)
- [ ] Review umana su Discussion #8 (naming candidati) (AC: #1)

## Dev Notes

### Contesto business

SRC è gestionale field-service (commesse→interventi→firme→fatture) + verticali (appalti, energy, timber). La base attuale è CMS/identità/HR timeclock senza dominio WorkOrder.

### Vincoli Laraxot

- XotBase / LangBase — mai Filament diretto
- FO = Folio+Volt — mai Auth controllers SRC
- 1 model = 1 create migration; no RefreshDatabase
- QueueableAction per use-case

### Owned scope (questa story)

Solo file `docs/**` e `*/docs/**`:

- `docs/gestionale-commesse-comparison/`
- `laravel/Modules/{User,Employee,Media,Notify,AI,Activity,Tenant,Job,Gdpr}/docs/`
- `laravel/Themes/{docs,Sixteen/docs,Zero/docs}/`
- `docs/stories/1.*.story.md`

### References

- [module-mapping.md](../gestionale-commesse-comparison/module-mapping.md)
- [inventory-src.md](../gestionale-commesse-comparison/inventory-src.md)
- SRC: `/var/www/_bases/gestionale_commesse/Modules/*`

## File List

Vedi working tree path Owned scope + story figlie 1.2–1.5.
