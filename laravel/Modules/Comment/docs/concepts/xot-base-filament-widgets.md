---
title: "Comment — widget Filament solo via XotBase*"
type: concept
module: Comment
tags: [comment, filament, xotbase, widget, architecture]
created: 2026-06-10
updated: 2026-06-10
qmd: "comment filament widget XotBaseSchemaWidget never extend Filament Widget"
issues:
  - "https://github.com/laraxot/module_comment_fila5/issues/20"
discussions:
  - "https://github.com/laraxot/module_comment_fila5/discussions/21"
related:
  - ./no-livewire-filament-widgets-only.md
  - ../../../../docs/wiki/rules/xot-base-filament-widgets.md
---

# Mai `Filament\*` — sempre `Modules\Xot\Filament\Widgets\XotBase*`

## Zen

Filament = vendor. XotBase = contratto Laraxot (TransTrait, form, viste, upgrade centralizzato).

**Vietato:** `extends Filament\Widgets\Widget`.

**Comment FO:** `XotBaseSchemaWidget` (blade custom + `mount(): void`).

## Gerarchia (`Modules/Xot/Filament/Widgets/`)

| Base | Uso |
|---|---|
| `XotBaseWidget` | Minimo assoluto |
| `XotBaseSchemaWidget` | Form/schema + vista custom |
| `XotBaseWizardWidget` | Wizard step |
| `XotBaseChartWidget` | Chart |
| `XotBaseStatsOverviewWidget` | Stats |
| `XotBaseTableWidget` | Table |
| `XotBaseInfolistWidget` | Infolist |

## Comment

`CommentsWidget`, `CommentWidget`, `MentionSearchWidget` → tutti `XotBaseSchemaWidget`.

Vedi anche [widget-ui-spatie-data.md](./widget-ui-spatie-data.md) per bag UI Spatie.
