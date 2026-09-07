<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
## [2026-08-27] quality | PHPStan `Modules/Notify/app` — XOT-5.43

- `GenericNotification::via()` — PHPDoc `@param object` allineato al type hint nativo
- `HasNotificationTracking` / `HasTenantNotifications` — `@phpstan-ignore trait.unused` (trait composable, coverage in `tests/Unit/Traits/`)
- Doc: [concepts/phpstan-pest-test-doubles.md](concepts/phpstan-pest-test-doubles.md) §2b

<<<<<<< HEAD
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
## [2026-06-10] schema | notifications owner Notify — XotBaseMigration

- Canonico: `2026_06_10_133000_create_notifications_table.php`
- Vietato `create_notifications_table` in User/
- Doc: [concepts/notifications-database-contract.md](concepts/notifications-database-contract.md)

## [2026-06-05] docs | HackerNoon harness — tips 001-022 in wiki locale

<<<<<<< HEAD
<<<<<<< HEAD
- Stub/checklist: second-brain → canon Xot, ai-harness, [hackernoon map](../../../../../docs/wiki/concepts/hackernoon-ai-coding-tips-fixcity-map.md), [llm-wiki.txt](../../../../../bashscripts/tools/prompts/llm-wiki.txt)
- GitHub: [#272](https://github.com/laraxot/base_fixcity_fila5/issues/272) / [D#273](https://github.com/laraxot/base_fixcity_fila5/discussions/273)
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
- Stub/checklist: second-brain → canon Xot, ai-harness, [hackernoon map](../../../../../docs/wiki/concepts/hackernoon-ai-coding-tips-fixcity-map.md), [llm-wiki.txt](../../../../../bashscripts/tools/prompts/llm-wiki.txt)
- GitHub: [#272](https://github.com/laraxot/base_fixcity_fila5/issues/272) / [D#273](https://github.com/laraxot/base_fixcity_fila5/discussions/273)
>>>>>>> a988596b (first)
- Stub/checklist: second-brain → canon Xot, ai-harness, [hackernoon map](../../../../../docs/wiki/concepts/hackernoon-ai-coding-tips-laraxot-map.md), [llm-wiki.txt](../../../../../bashscripts/tools/prompts/llm-wiki.txt)
- GitHub: [#272](https://github.com/laraxot/platform/issues/272) / [D#273](https://github.com/laraxot/platform/discussions/273)

---
title: "Notify Wiki Activity Log"
module: "Notify"
---

# Notify - Wiki Activity Log

## [2026-05-11] Wiki Structure Created

- Created wiki structure: rules/, skills/, commands/, memories/, concepts/
<<<<<<< HEAD
<<<<<<< HEAD
- Created INDEX.md for each section
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
- Created INDEX.md for each section
>>>>>>> a988596b (first)
- Created index.md for each section
- Created module index.md
- Ready for on-demand loading via QMD


- 2026-06-10: boundary Notify schema / User runtime — vietato create_notifications in User (XotBaseMigration only)

## 2026-06-10 — notifications schema owner

- Unica `create_notifications_table` in Notify; `model_class` = `User\Models\Notification`
- Solo `XotBaseMigration` — mai `extends Migration`
- Vietato duplicato in User/ (es. pattern `2026_07_02_*` con bigint morphs)
