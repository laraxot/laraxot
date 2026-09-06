---
title: "PHPStan Quotation — cast.* + Filament v4→v5 + newFactory() overrides"
type: chat-handoff
tags: [phpstan, quotation, filament, cast, multi-agent, bmad, claim]
created: 2026-09-06
agent: claude (sonnet 5) — worker assigned to Modules/Quotation
scope: Modules/Quotation
status: in-progress
story: docs/stories/01.Quotation-phpstan-fix.story.md, docs/stories/phpstan-Quotation-fix.md
---

# Claim: Modules/Quotation — cast.* narrowing, Filament v4→v5 mechanical fixes, newFactory() overrides

Note: an earlier pass (see `docs/chat/phpstan-modules-zero.md`, row "Quotation | 0 |...")
already fixed a *different* issue in this module (Pest `uses(TestCase::class)` import
order in 24 test files) and confirmed 0 errors at that time. This is a **new, later**
error list (post `HasXotFactory::factory()` root-cause fix, see
`docs/chat/2026-09-06-hasxotfactory-factory-method-deleted-root-cause.md`) covering:

- 5x `newFactory()` overrides missing (Quotation, QuotationItem, QuotationTemplate,
  QuotationTemplateItem, SiteSurvey) — seeders report `method.nonObject` on
  `factory()->count()->create()` because Larastan can't statically follow the
  `GetFactoryAction` runtime indirection without an explicit override.
- ~15 files with `cast.double`/`cast.int`/`cast.string` PHPStan errors (mostly
  Filament form/table `get()`/`state()` callbacks returning `mixed`).
- Filament v4→v5 deprecations: `Placeholder::make()->content()` →
  `TextEntry::make()->state()`, `actions()`→`recordActions()`,
  `bulkActions()`→`toolbarActions()`, `Action::form()`→`->schema()`.

Claiming `Modules/Quotation` for this list. Will post outcome + final PHPStan count,
Pest coverage before/after, and git sync status here when done.
