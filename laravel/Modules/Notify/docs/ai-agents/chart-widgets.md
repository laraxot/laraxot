---
title: "Chart widgets (Chart.js + Filament v5)"
type: concept
tags: [chart, widgets]
created: 2026-07-14
updated: 2026-07-14
qmd: "chart-widgets chart widgets (chart.js + filament v5)"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index.md"
  - "./01-gsd-workflow.md"
  - "./02-bmad-workflow.md"
  - "./03-architecture-zen.md"
  - "./04-filament-philosophy.md"
  - "./05-front-office-audit.md"
  - "./06-cinematic-effects.md"
  - "./07-mcp-tailwind-ui.md"
---

# Chart widgets (Chart.js + Filament v5)

## Centralized plugin registration

Chart.js plugins / JS assets are registered **only** in `Modules/Chart`. Other modules only configure options.

## getOptions() return type

- `getOptions()` must return an **array**.
- Use `RawJs::make(<<<'JS' ... JS)` only for callbacks/formatters.

## Nowdoc for JS callbacks

Do not use multiline single-quoted PHP strings for JS callbacks: use nowdoc/heredoc.
