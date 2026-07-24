---
title: "Import status — Quotation"
type: concept
tags: [import, gestionale-commesse, quotation]
created: 2026-07-23
updated: 2026-07-23
qmd: "import status Quotation normalize"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./README.md"
---

# Import status — Quotation

- [x] Cartella singolare / replica
- [x] Layout `app/` (reshape)
- [x] Namespace `Modules\Quotation\`
- [x] `QuotationServiceProvider` → XotBaseServiceProvider
- [x] composer PSR-4 → `app/`
- [x] Resources → XotBaseResource
- [x] Pages/RelationManagers → XotBase*
- [x] `app/Domain/*` assorbito in `app/{DTO,Actions}`
- [x] Media custom (Document\Models\Media) → Spatie MediaLibrary (HasMedia/InteractsWithMedia)
- [x] Migrazioni 1-per-modello, XotBaseMigration
- [x] tests/TestCase.php + Pest.php scaffold
- [x] lang/{it,en} (quotations, site_surveys) + config/quotation_technical.php
- [x] PHPStan (solo errori minori/noti residui, vedi `gestionale-commesse-port.md`)
- [x] Registrato in `modules_statuses.json`
