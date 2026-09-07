---
title: "PHPStan EnergyBroker — canonical-structure follow-up (Schemas/EnergyBrokerDocumentForm return.type)"
type: coordination
tags: [phpstan, energybroker, filament, canonical-structure, bmad]
created: 2026-09-07
agent: claude sonnet 5 (worker, scoped to Modules/EnergyBroker only)
status: done
---

# Claim: Modules/EnergyBroker (PHPStan follow-up only)

Picked up as a small (~1 error expected) PHPStan-zero task for `Modules/EnergyBroker`.

## Findings before editing

- Lock check (`bashscripts/lock/check.sh laravel/Modules/EnergyBroker`): FREE.
- `docs/chat/INDEX.md`: no active claim specific to EnergyBroker today; the
  Epic4 `filament-canonical-structure` swarm's per-module list doesn't
  explicitly include EnergyBroker in the tracked table, but the working tree
  already contained a **complete, uncommitted** canonical-structure extraction
  for this module (`docs/stories/4.14.8.energybroker-canonical.story.md`,
  status `completed`, ~30-90min old at pickup): `EnergyBrokerDocumentResource.php`
  slimmed 350→88 lines, `Schemas/EnergyBrokerDocumentForm.php`,
  `Schemas/EnergyBrokerDocumentInfolist.php`,
  `Tables/EnergyBrokerDocumentsTable.php` created, `Pages/List*`/`Pages/View*`
  updated, `lang/it/energy_broker_document.php` extended.
- That extraction's own "Verify" section claimed "0 errori su 0 residui", but
  a fresh isolated `phpstan analyse Modules/EnergyBroker` (clean result cache)
  found **1 real residual error** it left behind.

## Fix

`app/Filament/Resources/EnergyBrokerDocumentResource/Schemas/EnergyBrokerDocumentForm.php:28`
— `getFormSchema()` declared `@return array<string, Component>` (matches
`XotBaseResourceForm::getFormSchema(): array`'s contract) but returned a
positional list (`array<int, Section>`, 5 un-keyed `Section::make()` entries).
Keyed the 5 sections with descriptive string keys
(`document_summary`/`customer_data`/`billing_data`/`supply_data`/`contract_data`),
same pattern already used in Billing/Catalog/Compliance canonical `*Form.php`
files. No behavioral change.

## Gate

- PHPStan `Modules/EnergyBroker`: 1 → 0 errors (clean result-cache verified).
- Pint: `EnergyBrokerDocumentForm.php` needed no changes; while committing the
  rest of the (already-complete) canonical-structure WIP together, found and
  fixed real Pint violations in `EnergyBrokerDocumentResource.php`
  (`unary_operator_spaces`, `no_unused_imports`, `not_operator_with_successor_space`)
  left behind by that extraction — `pint --test` now passes clean on all 6
  touched/new PHP files.
- PHPMD (`./tools/phpmd.sh Modules/EnergyBroker text phpmd.xml`): only
  pre-existing findings (Actions/Jobs/Providers), none in touched files.
- PHPInsights (`./tools/phpinsights.sh analyse Modules/EnergyBroker
  --no-interaction --composer=composer.lock`, confirmed installed and
  working — repo hook's "not installed" claim is stale/wrong, per second-brain
  memory): Code 92.8 / Complexity 74.4 / Architecture 88.2 / Style 86.7 —
  stable vs the prior round, no new findings on touched files.
- Pest (`./vendor/bin/pest Modules/EnergyBroker/tests --no-coverage`): 11
  passed / 11 failed, unchanged before/after. All 11 failures are the
  pre-existing shared test-DB gap (`workorder_data_test.roles` missing on the
  mariadb/Spatie-Permission connection) — did not touch migrations/DB per
  "data is sacred".
- Full detail: `Modules/EnergyBroker/docs/coverage.md` (Round 3).

## Story

Reused `Modules/EnergyBroker/docs/stories/4.14.8.energybroker-canonical.story.md`
(added a "Follow-up 2026-09-07" section under Verify) instead of creating a
new duplicate — several empty `phpstan-fix-*.story.md` stubs already exist in
that folder from earlier auto-created placeholders, left untouched (not mine
to fill/delete).

## Git

Committed the fix together with the already-complete, verified
canonical-structure WIP found in the shared working tree (same module, same
logical change-set, no conflicts, my gate run covers the full diff) — see
commit below for file list. Also included the deletion of a stray
`README.md.lock` (an accidentally-committed lock artifact from an unrelated
2026-09-03 task, `readme-computed-badges`/claude-fable-5.1, already deleted
in the working tree by someone else — pure housekeeping, not authored by me).
