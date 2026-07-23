---
title: "Comment FO — Filament widgets, no Http/Livewire"
type: concept
module: Comment
tags: [comment, filament, widget, no-livewire, fo]
created: 2026-06-10
updated: 2026-06-10
qmd: "comment filament widgets no livewire Http Commentable CommentsWidget"
issues:
  - "https://github.com/laraxot/module_comment_fila5/issues/18"
discussions:
  - "https://github.com/laraxot/module_comment_fila5/discussions/19"
related:
  - ../../../../docs/wiki/rules/no-module-livewire-use-filament-widgets.md
---

# No `Http/Livewire` — Filament widgets

## Perché

Livewire grezzo nel modulo duplica lo stack Filament/Xot. FO = `XotBaseSchemaWidget` in cartelle dominio.

## Layout

- `Filament/Widgets/Commentable/CommentsWidget`
- `Filament/Widgets/Comment/CommentWidget`
- `Filament/Widgets/Mention/MentionSearchWidget`

## Uso

```blade
@livewire(\Modules\Comment\Filament\Widgets\Commentable\CommentsWidget::class, ['model' => $ticket])
```

Vietato: `Http/Livewire/*`, `Livewire::component()`, `<livewire:comments>`.

Vedi [xot-base-filament-widgets](./xot-base-filament-widgets.md).
