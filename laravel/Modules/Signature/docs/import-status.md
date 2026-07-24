---
title: "Import status — Signature"
type: concept
tags: [import, gestionale-commesse, signature]
created: 2026-07-23
updated: 2026-07-23
qmd: "import status Signature normalize"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./README.md"
---

# Import status — Signature

- [x] Cartella singolare / replica
- [x] Layout `app/` (reshape)
- [x] Namespace `Modules\Signature\`
- [x] `SignatureServiceProvider` → XotBaseServiceProvider
- [x] composer PSR-4 → `app/`
- [x] `signable()`/`sketchable()` generici (nessun hardcode WorkOrder/Intervention)
- [x] disk/path/mime_type diretti sul modello (no Spatie MediaLibrary FK, decisione esplicita)
- [x] `lang/{it,en}/signatures.php`
- [x] `tests/{TestCase,Pest}.php` + `tests/Unit/SignatureModelTest.php` (8 assert totali col modulo, passano)
- [x] PHPStan: 0 errori residui su Actions/Models/seeders/tests
- [ ] `app/Filament/Pages/{CaptureSignature,CaptureSketch}.php` — restano accoppiate a Intervention/WorkOrder e a
      `App\Filament\Forms\Components\CanvasPad` (root app, class.notFound) + `Filament\Pages\Page` non-XotBase (55
      errori PHPStan pre-esistenti, fuori perimetro di questo redo — vedi
      `docs/gestionale-commesse-port.md`)

Dettaglio redo reshape 2026-07-23: [../../../docs/gestionale-commesse-port.md](../../../docs/gestionale-commesse-port.md).
