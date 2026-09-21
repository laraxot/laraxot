---
title: "PHPStan Modules alla perfezione — 2026-09-20"
type: story
status: in-progress
created: 2026-09-20
owner: qwen-code-agent
issues:
  - https://github.com/laraxot/base_workorder_fila5/issues/14
discussions:
  - https://github.com/laraxot/base_workorder_fila5/discussions/23
---
# Story

Come maintainer voglio che tutti i moduli superino PHPStan con la configurazione canonica, così che i contratti statici riflettano il comportamento reale senza nascondere difetti.

# Acceptance criteria

1. `cd laravel && ./vendor/bin/phpstan analyse Modules --no-progress` termina con exit code 0 e `[OK] No errors`.
2. Nessun file `phpstan*.neon*` viene creato, modificato o usato come configurazione alternativa dalla campagna.
3. Ogni correzione documenta una causa funzionale verificata tramite codice, chiamanti, test o storia Git.
4. Nessun `@phpstan-ignore`, baseline, abbassamento del livello o cast cieco viene introdotto.
5. Tutti i file PHP modificati superano `php -l` e Pint.
6. I test mirati dei moduli modificati passano oppure ogni blocco esterno è riportato con output reale.
7. PHPMD e PHPInsights vengono eseguiti secondo i comandi correnti individuati nel repository, senza dichiarare verdi controlli non eseguiti.
8. Il riepilogo finale distingue modifiche preesistenti e modifiche prodotte dalla campagna in ogni repository interessato.
9. Gli agenti lavorano su scope disgiunti e il coordinamento è registrato in `docs/chat/INDEX.md` o in un handoff dedicato.
10. Non vengono eseguiti commit, push, restore, checkout, reset, revert o rollback.

# Dipendenze

- Analysis: `docs/analysis/phpstan-modules-perfection-2026-09-20.md`
- Planning: `docs/planning/phpstan-modules-perfection-2026-09-20.md`
- Architecture: `docs/architecture/phpstan-modules-perfection-2026-09-20.md`
- Coordination: issue 14 e discussion 23.
