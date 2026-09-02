---
title: Widget FO — stato UI in Spatie Laravel Data
type: concept
tags: [comment, filament, widget, spatie-data, livewire, ui]
qmd:
  index: true
issues:
  - https://github.com/laraxot/module_comment_fila5/issues/7
discussions:
  - https://github.com/laraxot/module_comment_fila5/discussions/15
created_at: 2026-06-10
updated_at: 2026-06-10
---

# Widget FO — stato UI in Spatie Laravel Data

## Perché (filosofia)

Un widget Livewire con **N proprietà pubbliche** (`showAvatar`, `writable`, `replyText`, …) è difficile da passare ai figli, fragile per PHPStan e lontano dal pattern Laraxot: **config/display in Data**, dominio in model/action.

**Regola:** opzioni display + bozze UI → **un bag** `*WidgetUiData extends Data implements Wireable` (`WireableData`).

Il widget espone solo record di dominio, **un** `public XxxWidgetUiData $ui`, e collezioni server-side non wireable se serve.

## Classi Comment

| Widget | Data |
|--------|------|
| `CommentWidget` | `CommentWidgetUiData` |
| `CommentsWidget` | `CommentsWidgetUiData` (include `text` bozza) |
| `MentionSearchWidget` | `MentionSearchWidgetUiData` |

## View e render

- **Niente `render()` custom** su `XotBaseSchemaWidget`: view da `GetViewByClassAction`.
- **`protected string $view` commentato** — pigrizia documentata; path sotto `resources/views/filament/widgets/…`.
- **Stato vuoto in Blade**, mai branch PHP in `render()`.

## Blade

- `$ui->showAvatar`, `wire:model="ui.replyText"`, `@error('ui.text')`
- Figli: `'ui' => ['showAvatar' => $ui->showAvatar, …]`

## Collegamenti

- [FO Filament widget no Livewire](./fo-filament-widgets-no-livewire.md)
- [Gerarchia XotBase](../../../../Xot/docs/wiki/concepts/xotbase-filament-widget-hierarchy.md)
