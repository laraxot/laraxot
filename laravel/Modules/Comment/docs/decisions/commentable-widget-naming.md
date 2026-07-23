---
title: "ADR — Commentable/ vs Comment/ (naming widget)"
type: decision
status: accepted
supersedes: alias CommentableWidget
confidence: high
created: 2026-06-10
updated: 2026-06-10
tags: [widget, naming, commentable, comment, ssot]
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/327"
related:
  - ../concepts/filament-widgets-subject-subfolders.md
  - ../concepts/comments-widget-livewire-mount.md
  - ../concepts/has-comments-implements-commentable.md
qmd: "Commentable CommentsWidget folder naming alias CommentableWidget retired SSOT"
---

# ADR — Perché `Commentable/CommentsWidget` e non `Comment/CommentableWidget`

## Contesto (brainstorming)

Domanda: la cartella `Commentable/` va rinominata in `Comment/`? La classe in `CommentableWidget`?

## Zen (soggetto ≠ modulo)

Nel modulo **Comment**, `Comment/` e `Commentable/` non sono ridondanza:

| Cartella | Soggetto | Widget | Proprietà mount |
|----------|----------|--------|-----------------|
| `Comment/` | riga `Comment` | `CommentWidget` | `?Comment $comment` |
| `Commentable/` | host polimorfico | `CommentsWidget` | `commentableType` + `commentableKey` |
| `Mention/` | UX menzioni | `MentionSearchWidget` | query autocomplete |

**Regola Laraxot (Xot):** cartella = **dominio/soggetto**; classe = **ruolo/contenuto** (`ViewWidget`, `CommentsWidget`).

## Decisione

1. **Mantenere** `Filament/Widgets/Commentable/CommentsWidget.php`
2. **Non** fondere in `Comment/` — conflazione host vs commento singolo
3. **Non** rinominare classe in `CommentableWidget` — il soggetto è già nel path
4. **Ritirare** alias `CommentableWidget` → `CommentableWidget.php.old` (violava SSOT: due nomi, un widget)

## Mount Livewire FO (2026-06-10)

In infolist Filament annidato, **non** passare `model` Eloquent:

```blade
@livewire(CommentsWidget::class, [
    'commentableType' => Ticket::class,
    'commentableKey' => (string) $record->getKey(),
    'readOnly' => false,
])
```

Canon: [comments-widget-livewire-mount.md](../concepts/comments-widget-livewire-mount.md)

## Conseguenze

- Import stabile: `Modules\Comment\Filament\Widgets\Commentable\CommentsWidget`
- Test unitari possono ancora usare `['model' => $ticket]` (mount accetta entrambi)
- View mirror: `resources/views/filament/widgets/commentable/comments.blade.php`

## Collegamenti

- [filament-widgets-subject-subfolders.md](../concepts/filament-widgets-subject-subfolders.md)
- [widgets-subject-subfolders.md](../../../../docs/wiki/rules/widgets-subject-subfolders.md)
