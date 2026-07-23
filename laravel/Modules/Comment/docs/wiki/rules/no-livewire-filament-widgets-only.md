---
title: Regola — FO commenti solo Filament widget
type: rule
tags: [comment, filament, livewire, fo]
qmd:
  index: true
issues:
  - https://github.com/laraxot/module_comment_fila5/issues/7
created_at: 2026-06-10
updated_at: 2026-06-10
---

# Regola — FO commenti solo Filament widget

| OK | NO |
|----|-----|
| `Filament/Widgets/{Contest}/\*Widget` | `Http/Livewire/*Component` |
| `@livewire(FQCN::class)` | `Livewire::component('alias', …)` |
| `XotBaseSchemaWidget` + view custom | `Livewire\Component` standalone |

Canon esteso: [fo-filament-widgets-no-livewire](../concepts/fo-filament-widgets-no-livewire.md)
