---
title: "Import status — Catalog"
type: concept
tags: [import, gestionale-commesse, catalog]
created: 2026-07-23
updated: 2026-07-23
qmd: "import status Catalog normalize"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./README.md"
---

# Import status — Catalog

- [x] Cartella singolare / replica
- [x] Layout `app/` (reshape)
- [x] Namespace `Modules\Catalog\`
- [x] `CatalogServiceProvider` → XotBaseServiceProvider
- [x] composer PSR-4 → `app/`
- [x] Resources → XotBaseResource
- [x] Pages → XotBase{Create,Edit,List,View}Record
- [x] RelationManager → XotBaseRelationManager
- [x] Migrazioni 1-per-modello, XotBaseMigration
- [x] Actions → QueueableAction
- [x] tests/TestCase.php + Pest.php scaffold
- [x] lang/{it,en} + config/metel.php
- [x] PHPStan (solo 2 categorie di errori minori/noti residue, vedi `gestionale-commesse-port.md`)
- [x] Registrato in `modules_statuses.json`
