---
title: "Enum Select Component"
type: concept
tags: [enum, select, component]
created: 2026-07-14
updated: 2026-07-14
qmd: "enum-select-component enum select component"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./auth-register-focus-loss-overlay.md"
  - "./block-rendering-and-optional-services.md"
  - "./claude-audit-static.md"
  - "./code-redundancy-ui.md"
  - "./context-overflow-prevention.md"
  - "./enum-select-best-practices.md"
  - "./enum-select-contract-and-false-friends.md"
  - "./enum-select-usage.md"
---

## EnumSelect Component Specification

A reusable Iron Select for PHP-backed enums in Filament v5.

**Key Features:**
- : Enums via `->enum(MyEnum::class)`
- : Handles HasLabel/HasIcon interfaces
- : Fallback labels/icons via case name
- : HTML support in labels
- : Tom Select compatibility

**Usage Example:**
```php
use Modules\UI\Filament\Forms\Components\EnumSelect;

EnumSelect::make('type_id')
    ->enum(TicketTypeEnum::class)
    ->required()
    ->searchable()
```

... (continued in next file)
