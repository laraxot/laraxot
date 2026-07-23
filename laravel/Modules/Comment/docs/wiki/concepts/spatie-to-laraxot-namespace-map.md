---
title: "Mappa namespace Spatie → Modules Comment"
type: concept
tags: [comment, migration, namespace, spatie, mapping]
created: 2026-06-06
updated: 2026-06-06
qmd: "spatie comments namespace map migration Modules Comment laravel-comments livewire"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/296"
discussions: []
related:
  - ./native-comments-engine-workflow.md
  - ../architecture/native-comments-engine.md
---

# Mappa namespace Spatie → `Modules\Comment`

## Core (`laravel-comments`)

| Da `Spatie\Comments\…` | A `Modules\Comment\…` |
|------------------------|------------------------|
| `Enums\NotificationSubscriptionType` | `Enums\NotificationSubscriptionType` |
| `Exceptions\*` | `Exceptions\*` |
| `Support\Config` | `Support\CommentConfig` |
| `Support\CommentSanitizer` | `Support\CommentSanitizer` |
| `Support\CommentatorProperties` | `Support\CommentatorProperties` |
| `Models\Comment` | `Models\Comment` (già esiste — assorbire logica base) |
| `Models\Reaction` | `Models\Reaction` |
| `Models\CommentNotificationSubscription` | `Models\CommentNotificationSubscription` |
| `Models\CommentNotificationOptOut` | `Models\CommentNotificationOptOut` |
| `Models\Concerns\HasComments` | `Models\Concerns\HasComments` |
| `Models\Concerns\Interfaces\CanComment` | `Models\Contracts\CanComment` |
| `Models\Concerns\InteractsWithComments` | *(deprecato — usare `InteractsWithComments` modulo)* |
| `Models\Collections\ReactionCollection` | `Models\Collections\ReactionCollection` |
| `Actions\*` | `Actions\*` (+ `QueueableAction`) |
| `Jobs\*` | `Actions\*` o `Jobs\*` |
| `Notifications\*` | `Notifications\*` |
| `Events\*` | `Events\*` |
| `CommentTransformers\*` | `Transformers\*` |
| `Http\Controllers\*` | `Actions\*` + route closure |
| `CommentsServiceProvider` | `Providers\CommentEngineServiceProvider` |

## Livewire (`laravel-comments-livewire`)

| Da `Spatie\LivewireComments\…` | A `Modules\Comment\…` |
|--------------------------------|------------------------|
| `Livewire\CommentsComponent` | `Livewire\CommentsComponent` |
| `Livewire\CommentComponent` | `Livewire\CommentComponent` |
| `Livewire\MentionSearchComponent` | `Livewire\MentionSearchComponent` |
| `Policies\CommentPolicy` | `Policies\CommentPolicy` |
| `Policies\ReactionPolicy` | `Policies\ReactionPolicy` |
| `Support\Config` | *(merge in `CommentConfig`)* |
| `LivewireCommentsServiceProvider` | *(merge in `CommentEngineServiceProvider`)* |

## View / asset

| Spatie | Laraxot |
|--------|---------|
| `comments::livewire.comments` | `comment::livewire.comments` |
| `route('laravel-comments-livewire.scripts')` | `route('comment::assets.scripts')` |
| `route('laravel-comments-livewire.styles')` | `route('comment::assets.styles')` |

## Config (`config/comments.php`)

```php
// Prima
use Spatie\Comments\Models\Comment;
'process_comment' => Spatie\Comments\Actions\ProcessCommentAction::class,

// Dopo
use Modules\Comment\Models\Comment;
'process_comment' => Modules\Comment\Actions\ProcessCommentAction::class,
```

## Alias temporaneo (Fase 1–3)

Durante migrazione, in `composer.json` modulo:

```json
"autoload": {
  "psr-4": {
    "Modules\\Comment\\": "app/",
    "Spatie\\Comments\\": "app/_compat/Spatie/Comments/"
  }
}
```

Compat = re-export `class X extends \Modules\Comment\…` fino a Fase 4.
