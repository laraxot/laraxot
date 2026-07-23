---
title: "Filament V3 Rules"
type: rule
tags: [filament, rules]
created: 2026-07-14
updated: 2026-07-14
qmd: "filament-v3-rules filament v3 rules"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./boost-rules.md"
  - "./filament-rules.md"
  - "./fluxui-rules.md"
  - "./folio-rules.md"
  - "./foundation-rules.md"
  - "./laravel-core-rules.md"
  - "./laravel-v11-rules.md"
  - "./livewire-rules.md"
---

=== filament/v3 rules ===

## Filament 3

## Version 3 Changes To Focus On
- Resources are located in `app/Filament/Resources/` directory.
- Resource pages (List, Create, Edit) are auto-generated within the resource's directory - e.g., `app/Filament/Resources/PostResource/Pages/`.
- Forms use the `Forms\Components` namespace for form fields.
- Tables use the `Tables\Columns` namespace for table columns.
- A new `Filament\Forms\Components\RichEditor` component is available.
- Form and table schemas now use fluent method chaining.
- Added `php artisan filament:optimize` command for production optimization.
- Requires implementing `FilamentUser` contract for production access control.



---

## Cross-References

- ← [CLAUDE Index](INDEX.md) — All Laravel Boost guidelines
- ← [Main AI Docs Index](../INDEX.md) — Master index
- ← [../../../../docs/claude.md](../../../../docs/../../../../docs/claude.md) — Original source

