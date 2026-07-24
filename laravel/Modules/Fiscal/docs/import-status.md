---
title: "Import status — Fiscal"
type: concept
tags: [import, gestionale-commesse, fiscal]
created: 2026-07-23
updated: 2026-07-23
qmd: "import status Fiscal normalize"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "./README.md"
---

# Import status — Fiscal

- [x] Cartella singolare / replica
- [x] Layout `app/` (reshape) — **redo 2026-07-23**: `Domain/{Actions,DTO,ValueObjects}` → `app/{Actions,Datas}`
- [x] Namespace `Modules\Fiscal\`
- [x] `FiscalServiceProvider` → XotBaseServiceProvider
- [x] composer PSR-4 → `app/`
- [x] Action con trait `Spatie\QueueableAction\QueueableAction`
- [x] 0 modelli persistibili confermato (motore di calcolo puro, nessuna migrazione)
- [x] `tests/` (TestCase + Pest.php + 4 Unit test)
- [x] `lang/{it,en}` scaffold
- [ ] PHPStan bloccato da `Modules/Customer/app/Providers/EventServiceProvider.php` (non di nostra proprietà) —
      vedi `docs/gestionale-commesse-port.md` root, verificato invece con `php -l` (pulito)
- [ ] Resources → XotBaseResource (n/a: nessuna persistenza, nessun Resource previsto)
