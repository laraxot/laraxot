---
title: "Comment Module Architecture - Spatie Migration"
type: concept
tags: [comments, spatie-comments, livewire, migration]
created: 2026-06-05
updated: 2026-06-05
sources: ["laravel/Modules/Comment/packages/spatie/laravel-comments-livewire/src/Livewire/CommentsComponent.php"]
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/296"
---

# Comment Module Architecture

## Stato attuale

- Spatie Comments package in `packages/spatie/` (da eliminare)
- User model manca `notificationSubscriptionType()` → errore runtime

## Target

1. Spostare logica in `Modules/Comment/app/`
2. Usa `XotBaseModel` + interfaccia `CanComment`
3. Rimuovere `packages/spatie/`

## Componenti chiave

### CommentableConcern

Implementare `HasComments` con:
- `comments()` → MorphMany
- `notificationSubscriptions()` → MorphMany
- `subscribers()` → Collection<CanComment>
- `participatingCommentators()` → Collection

### LivewireComments

Creare `Modules/Comment/app/Livewire/CommentsComponent.php`:
- Usa `WithPagination`
- Rimuovere dependency a `Spatie\Comments\Models\Concerns\HasComments`
- Inject `CommentFormSchema` per validazione

## File da creare

- `app/Livewire/CommentsComponent.php`
- `app/Livewire/CommentFormSchema.php`
- `app/Models/Concerns/HasComments.php`
- `resources/views/livewire/comments.blade.php`

## Eliminazioni

- `packages/spatie/laravel-comments/`
- `packages/spatie/laravel-comments-livewire/`