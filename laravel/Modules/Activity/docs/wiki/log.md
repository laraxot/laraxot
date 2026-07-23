---
title: "Activity Log"
type: log
module: Activity
tags: [activity, phpstan, pest, qmd]
created: 2026-05-12
updated: 2026-06-13
qmd: "Activity log phpstan pest discipline"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/328"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/329"
---

---

## [2026-06-13] phpstan | Gate chef — 7 file Actions → Assert

- Convertiti tutti i test `tests/Unit/Actions/*` da `expect()` a `Assert::assert*()` per eliminare `method.internalClass` (82 errori piattaforma → 0).
- Aggiunti [overviews/completion-status.md](overviews/completion-status.md) e aggiornato [PROJECT-ROADMAP.md](PROJECT-ROADMAP.md) scope Activity.
- Hub: [Xot platform-completion-roadmap](../../Xot/docs/wiki/overviews/platform-completion-roadmap.md).
- GitHub: [Activity#18](https://github.com/laraxot/module_activity_fila5/issues/18) / base [#372](https://github.com/laraxot/base_fixcity_fila5/issues/372).

## [2026-06-10] testcase | Brainstorm Activity + nWidart BaseTestCase

- Confermato: Activity `TestCase` già estende `XotBaseTestCase`.
- `Nwidart\Modules\Tests\BaseTestCase` esiste su GitHub (Orchestra Testbench) ma non è autoloadato in app — non usare come parent.
- Gap: solo Geo ancora su Laravel `TestCase` diretto.
- Chat: [docs/chat/2026-06-10-testcase-brainstorm-activity-nwidart.md](../../../../docs/chat/2026-06-10-testcase-brainstorm-activity-nwidart.md)

## [2026-06-10] phpstan | Activity clean with Pest tests preserved

- `cd laravel && ./vendor/bin/phpstan analyse Modules/Activity` passa con `[OK] No errors` usando `laravel/phpstan.neon`.
- Correzioni stabili: namespace prima degli import, niente `Tests\TestCase` duplicato, helper fixture array-shape per Pest invece di stato `$this` non risolto da PHPStan.
- Pest e' rimasto runner/test style; la verifica Pest locale e' bloccata da credenziali MySQL (`forge_mysql_25_1`) prima delle assertion.
- Aggiornato [concepts/phpstan-pest-discipline](concepts/phpstan-pest-discipline.md).

## [2026-06-10] phpstan | Pest discipline Activity

- Aggiunto `concepts/phpstan-pest-discipline.md`.
- Regola: `phpstan.neon` solo utente; test Activity restano Pest; usare bridge/helper/assertion pubbliche dentro closure Pest per `method.internalClass`.

## [2026-06-10] schema | activity_log — una migrazione, XotBaseMigration

- Consolidati add/fix/update in `2026_06_10_140000_create_activity_table.php`
- Duplicate in `_bak/`; vietato `extends Migration`
- Doc: [concepts/activity-log-single-migration-contract.md](concepts/activity-log-single-migration-contract.md)

## [2026-06-05] docs | HackerNoon harness — tips 001-022 in wiki locale

- Stub/checklist: second-brain → canon Xot, ai-harness, [hackernoon map](../../../../../docs/wiki/concepts/hackernoon-ai-coding-tips-fixcity-map.md), [llm-wiki.txt](../../../../../bashscripts/tools/prompts/llm-wiki.txt)
- GitHub: [#272](https://github.com/laraxot/base_fixcity_fila5/issues/272) / [D#273](https://github.com/laraxot/base_fixcity_fila5/discussions/273)
