---
title: Mentions FO opt-in
type: concept
tags:
  - comment
  - mentions
  - filament-widget
  - front-office
qmd:
  index: true
issues:
  - https://github.com/laraxot/module_comment_fila5/issues/8
discussions:
  - https://github.com/laraxot/module_comment_fila5/discussions/11
created_at: 2026-06-10
updated_at: 2026-06-10
---

# Mentions FO opt-in

## Scopo

Abilitare le @mention nel front office solo quando richiesto, senza impatto su installazioni che non usano la funzione.

## Attivazione

```env
COMMENTS_MENTIONS_ENABLED=true
COMMENTS_COMMENTATOR_MODEL=Modules\\User\\Models\\User
```

Se `COMMENTS_COMMENTATOR_MODEL` è assente, `CommentEngineServiceProvider` risolve `XotData::getUserClass()` quando implementa `CanComment`.

## Flusso UI (Filament widget)

1. `widgets/commentable/partials/new-comment.blade.php` — `wire:model.live` + `@livewire(MentionSearchWidget::class)` se enabled.
2. `CommentsWidget::updatedText()` — dispatch `mention-search`.
3. `MentionSearchWidget` — `ResolveMentionsAutocompleteAction`.
4. `mention-selected` → `CommentsWidget::insertMention()` — token `@{id|Nome}`.
5. `MentionsTransformer` → `<span data-mention="id">`.

## Test

- `tests/Unit/MentionsFoOptInTest.php`

## Collegamenti

- [fo-filament-widgets-no-livewire](./fo-filament-widgets-no-livewire.md)
- [spatie-v2-parity-gap-analysis](./spatie-v2-parity-gap-analysis.md)
