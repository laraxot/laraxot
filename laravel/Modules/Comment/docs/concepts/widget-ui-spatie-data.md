---
title: "Comment FO widgets — UI in Spatie Data"
type: concept
module: Comment
tags: [comment, filament, widget, spatie-data, wireable, xot-base]
created: 2026-06-10
updated: 2026-06-10
qmd: "comment widget ui data spatie wireable XotBaseSchemaWidget blade empty"
issues:
  - "https://github.com/laraxot/module_comment_fila5/issues/24"
discussions:
  - "https://github.com/laraxot/module_comment_fila5/discussions/25"
related:
  - ./xot-base-filament-widgets.md
  - ../../../../docs/wiki/memories/filament-widget-ui-data-bag.md
---

# Widget FO — bag UI Spatie Laravel Data

## Perché

N `public bool|string` su Livewire = rumore. Un **Data Wireable** (`CommentWidgetUiData`, `CommentsWidgetUiData`, `MentionSearchWidgetUiData`) è il bag tipizzato — stesso zen di `CommentConfigData`.

## Regole

| Cosa | Dove |
|------|------|
| Modello (`$comment`, `$model`) | widget |
| Stato/opzioni UI + testo form lista (`ui.text`) | `*UiData` |
| Empty comment | Blade `@if(! $comment instanceof Comment)` — **mai** in `render()` |
| Vista | `// protected string $view = 'comment::filament.widgets…';` commentato; **nessun** `render()` custom — `GetViewByClassAction` risolve il path |

## Data classi

- `CommentWidgetUiData` — singolo commento
- `CommentsWidgetUiData` — lista (+ `text` per nuovo commento)
- `MentionSearchWidgetUiData` — autocomplete @

## Mount FO legacy

`readOnly`, `noReplies`, … → mappati in `$ui` da `CommentsWidget::mount()`.
