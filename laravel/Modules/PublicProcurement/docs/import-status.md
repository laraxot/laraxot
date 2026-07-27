---
title: "Import status — PublicProcurement"
type: concept
tags: [import, gestionale-commesse, publicprocurement, bmad]
created: 2026-07-23
updated: 2026-07-24
qmd: "import status PublicProcurement disabled gate"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./README.md"
  - "../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md"
---

# Import status — PublicProcurement

- [x] Cartella / reshape `app/` + XotBase SP
- [ ] Resources → XotBaseResource (wave)
- [x] PHPStan L10 zero reali (riverificato 2026-07-27, `./vendor/bin/phpstan analyse Modules/PublicProcurement --memory-limit=-1`)
- [x] `modules_statuses=true` — riabilitato 2026-07-27, confermato dall'utente
- [x] Pest: passed. Fix applicati 2026-07-27:
      - `tests/TestCase.php` mancava `prepareSharedFixcitySqliteForTesting()`.
      - 2 bug di ordinamento migration (FK verso tabelle create più avanti nella
        sequenza timestamp): `create_public_procurement_tender_opportunities_table`
        spostata prima di `create_public_procurement_contracts_table` (che la
        referenzia); `create_public_procurement_document_requirements_table`
        spostata dopo `Compliance/.../create_compliance_documents_table` (FK
        cross-modulo — problema già noto, vedi
        `docs/chat/migration-foreignidfor-swarm.md`).

Matrice: [gc-modules-runtime-matrix.md](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md).
