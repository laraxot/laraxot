---
title: "CommentsWidget — mount Livewire annidato (commentableType/Key)"
type: concept
module: Comment
tags: [livewire, commentable, filament, infolist, mount, 500]
created: 2026-06-10
updated: 2026-06-10
qmd: "CommentsWidget commentableType commentableKey Livewire nested Filament infolist model dehydrate InvalidArgumentException"
related:
  - ./filament-widgets-subject-subfolders.md
  - ./has-comments-implements-commentable.md
  - ../decisions/commentable-widget-naming.md
---

# CommentsWidget — mount in FO annidato

## Problema

`/it/tickets/{id}` monta `CommentsWidget` da `ticket-comments.blade.php` dentro
`ViewWidget` → infolist → `@livewire`. Passando `'model' => $record` l'Eloquent
non idratava: `$this->model` null → `InvalidArgumentException: CommentsWidget requires a Commentable model`.

## Causa

Livewire annidato in Filament non garantisce dehydrate/hydrate affidabile di
`public ?Model $model` tra parent e child.

## Soluzione (KISS)

Proprietà serializzabili + risoluzione lazy:

```php
public ?string $commentableType = null;  // class-string
public ?string $commentableKey = null;   // PK stringa
```

`mount()` e `resolveCommentable()` ricaricano il modello da DB quando serve.

## Blade (canon FO)

```blade
@livewire(\Modules\Comment\Filament\Widgets\Commentable\CommentsWidget::class, [
    'commentableType' => \Modules\Fixcity\Models\Ticket::class,
    'commentableKey' => (string) $record->getKey(),
    'readOnly' => false,
    'hideNotificationOptions' => true,
    'noReplies' => true,
    'noReactions' => true,
], key('ticket-comments-'.$record->getKey()))
```

## Checklist consumer

1. Host `implements Commentable` + `HasComments` ([has-comments-implements-commentable](./has-comments-implements-commentable.md))
2. Mount con `commentableType` + `commentableKey` (non solo `model`)
3. `key()` stabile per istanza Livewire

## Test

`Livewire::test(CommentsWidget::class, ['model' => $ticket])` resta valido per unit/feature.
