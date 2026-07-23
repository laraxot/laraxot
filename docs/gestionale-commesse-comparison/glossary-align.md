---
title: "Glossario allineato gestionale_commesse"
type: concept
tags: [gestionale-commesse, glossary, ubiquitous-language]
created: 2026-07-23
updated: 2026-07-23
qmd: "glossary commessa workorder dipendente employee gestionale"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./module-mapping.md"
  - "./new-modules-candidates.md"
---

# Glossario allineato gestionale_commesse

> **Perché:** stessa lingua ubiqua del SRC (`docs/glossary.md`) così il porting non inventa sinonimi.

| Legacy IT | Dominio EN (SRC) | Modulo target nostro |
|---|---|---|
| Commessa | WorkOrder | `WorkOrder` (nuovo) |
| Cliente | Customer | `Customer` (nuovo) |
| Sede | CustomerSite | `Customer` |
| Referente | Contact | `Customer` |
| Rivenditore | Reseller | `Customer` |
| Dipendente | Employee | `Employee` |
| Utente | User | `User` |
| Intervento | Intervention | `Intervention` (nuovo) |
| Timbratura | TimePunch | `Intervention` (non confondere con `WorkHour`) |
| Convalida ore | TimeApproval / Finalize report | `Intervention` |
| Preventivo | Quotation | `Quotation` (nuovo) |
| Prodotto | Product | `Catalog` (nuovo) |
| Documento / Foto | Media / Photo | `Media` |
| Firma | Signature | `Signature` (nuovo) |
| Disegno | Sketch | `Signature` |
| Veicolo | Vehicle | `Vehicle` (nuovo) |
| Fattura | Invoice | `Billing` (nuovo) |
| Segnalazione | IssueReport | `Intervention` |
| Note giornaliere | DailyNote | `Intervention` |
