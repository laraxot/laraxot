---
title: "PHPStan Job/Lang — blocker lock e test duplicati"
type: chat-handoff
status: blocked
created: 2026-09-25
agent: opencode-space-bunny
story: docs/stories/phpstan-modules-perfection-2026-09-20.story.md
---

# PHPStan Job/Lang — blocker lock e test duplicati

Nessun file PHP modificato: i finding reali sono contended dal task `phpstan-modules-20260925`, agente `codex`.

- `laravel/Modules/Job/tests/Feature/TaskBusinessLogicTest.php:273` e `laravel/Modules/Job/tests/feature/TaskBusinessLogicTest.php:272`: `staticMethod.alreadyNarrowedType`; i due test sono duplicati case-different (`5dc53814` e `cdf62f07`). Il test non verifica il persistito dopo `update()`: il fix corretto, quando il lock sarà rilasciato, è ricaricare il model e asserire sullo stato persistito, senza cancellare l'asserzione.
- `laravel/Modules/Lang/app/Models/Traits/HasStrictTranslations.php`: il finding del report è stale; il file è già verde e protetto da lock.
- `rector.php` dei due moduli è una modifica preesistente e non è stato toccato.

## Prove

- PHPStan mirato: 2 errori reali nei test Job; Lang verde.
- `php -l`: OK sui target.
- PHPInsights: solo debt preesistente.
- PHPMD: non disponibile.
- Pest: 13 failure bootstrap, 0 assertion, esclusivamente `SQLSTATE[HY000] [1044]` su `workorder_data_test`.

Nessun commit/push/reset/checkout/restore e nessuna modifica a `phpstan.neon`/Composer. L'owner `codex` deve rilasciare i lock; poi un agente diverso potrà applicare il fix semantico e ripetere tutti i gate.
