---
title: "Import status — Vehicle"
type: concept
tags: [import, gestionale-commesse, vehicle]
created: 2026-07-23
updated: 2026-07-23
qmd: "import status Vehicle normalize"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./README.md"
---

# Import status — Vehicle

- [x] Cartella singolare / replica
- [x] Layout `app/` (reshape)
- [x] Namespace `Modules\Vehicle\`
- [x] `VehicleServiceProvider` → XotBaseServiceProvider
- [x] composer PSR-4 → `app/`
- [x] Resources → XotBaseResource
- [x] Migrazioni consolidate 1-per-modello (`create_vehicles_table`, `create_vehicle_documents_table`)
- [x] `DocumentsRelationManager` → XotBaseRelationManager
- [x] `VehiclePolicy` → `app/Models/Policies/VehicleBasePolicy`
- [x] `lang/{it,en}/vehicles.php`
- [x] `tests/{TestCase,Pest}.php` + `tests/Unit/VehicleModelTest.php` (8 assert, passano)
- [x] PHPStan: 2 errori residui (falso positivo costanti lette per reflection) — vedi
      `docs/gestionale-commesse-port.md`

Dettaglio redo reshape 2026-07-23: [../../../docs/gestionale-commesse-port.md](../../../docs/gestionale-commesse-port.md).
