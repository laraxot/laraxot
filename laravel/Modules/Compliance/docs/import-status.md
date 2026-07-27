---
title: "Import status — Compliance"
type: concept
tags: [import, gestionale-commesse, compliance, bmad]
created: 2026-07-23
updated: 2026-07-24
qmd: "import status Compliance disabled gate"
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

# Import status — Compliance

- [x] Cartella / reshape `app/` + XotBase SP
- [ ] Resources → XotBaseResource (wave)
- [x] PHPStan L10 zero reali (riverificato 2026-07-27, `./vendor/bin/phpstan analyse Modules/Compliance --memory-limit=-1`)
- [x] `modules_statuses=true` — riabilitato 2026-07-27, confermato dall'utente
- [x] Pest: passed. Fix applicato: `tests/TestCase.php` non chiamava
      `prepareSharedFixcitySqliteForTesting()` prima di `parent::setUp()` →
      connessione `sqlite` puntava al path sbagliato. Schema modulo migrato in
      `database/fixcity_data.sqlite`.

Matrice: [gc-modules-runtime-matrix.md](../../../../laravel/Themes/docs/gc-modules-runtime-matrix.md).
