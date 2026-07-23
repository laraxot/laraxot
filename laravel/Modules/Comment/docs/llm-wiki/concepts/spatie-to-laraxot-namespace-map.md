---
title: "Spatie to Native Namespace Migration Map"
created: 2026-06-08
updated: 2026-06-08
category: "architecture"
tags: ["comment", "migration", "namespace", "reference"]
related_issues: ["STORY-158"]
---

# Spatie to Native Namespace Migration Map

## Overview

Complete reference table for all class namespace migrations from Spatie packages to `Modules\Comment\*` namespace. Use this for:

- Updating imports in consuming modules
- Verifying migration completeness
- Cross-referencing original package documentation

---

## Core Models

| Spatie Package | Native Module | Status | Notes |
|---|---|---|---|
| `Spatie\Comments\Models\Comment` | `Modules\Comment\Models\Comment` | ✅ | Primary comment model, unchanged |
| `Spatie\Comments\Models\Reaction` | `Modules\Comment\Models\Reaction` | ✅ | Emoji reaction storage |
| `Spatie\Comments\Models\CommentNotificationSubscription` | `Modules\Comment\Models\CommentNotificationSubscription` | ✅ | Subscription preferences |
| `Spatie\Comments\Models\CommentNotificationOptOut` | `Modules\Comment\Models\CommentNotificationOptOut` | ✅ | Hard opt-outs |
| `Spatie\Comments\Models\BaseModel` | `Modules\Comment\Models\BaseModel` | ✅ | Abstract base |
| `Spatie\Comments\Models\BaseMorphPivot` | `Modules\Comment\Models\BaseMorphPivot` | ✅ | Morph pivot base |
| `Spatie\Comments\Models\BasePivot` | `Modules\Comment\Models\BasePivot` | ✅ | Pivot base |

---

## Model Concerns (Traits)

| Spatie Package | Native Module | Status | Notes |
|---|---|---|---|
| `Spatie\Comments\Models\Concerns\HasComments` | `Modules\Comment\Models\Concerns\HasComments` | ✅ | Add to commentable models (Ticket, Post, etc.) |
| `Spatie\Comments\Models\Concerns\InteractsWithComments` | `Modules\Comment\Models\Concerns\InteractsWithComments` | ✅ | Additional interaction methods |

---

## Model Contracts (Interfaces)

| Spatie Package | Native Module | Status | Notes |
|---|---|---|---|
| `Spatie\Comments\Models\Contracts\CanComment` | `Modules\Comment\Models\Contracts\CanComment` | ✅ | Implement on User model |
| `Spatie\Comments\Models\Contracts\Commentable` | `Modules\Comment\Models\Contracts\Commentable` | ✅ | Models with HasComments are commentable |

---

## Collections

| Spatie Package | Native Module | Status | Notes |
|---|---|---|---|
| `Spatie\Comments\Models\Collections\ReactionCollection` | `Modules\Comment\Models\Collections\ReactionCollection` | ✅ | Custom collection for reactions |

---

## Actions (QueueableAction)

| Spatie Package | Native Module | Status | Notes |
|---|---|---|---|
| `Spatie\Comments\Actions\ApproveCommentAction` | `Modules\Comment\Actions\ApproveCommentAction` | ✅ | Approve comment + send notifications |
| `Spatie\Comments\Actions\ProcessCommentAction` | `Modules\Comment\Actions\ProcessCommentAction` | ✅ | Transform + sanitize comment text |
| `Spatie\Comments\Actions\RejectCommentAction` | `Modules\Comment\Actions\RejectCommentAction` | ✅ | Reject pending comment |
| `Spatie\Comments\Actions\SendNotificationsForApprovedCommentAction` | `Modules\Comment\Actions\SendNotificationsForApprovedCommentAction` | ✅ | Send approval notifications |
| `Spatie\Comments\Actions\SendNotificationsForPendingCommentAction` | `Modules\Comment\Actions\SendNotificationsForPendingCommentAction` | ✅ | Send pending notifications to moderators |
| `Spatie\Comments\Actions\ResolveMentionsAutocompleteAction` | `Modules\Comment\Actions\ResolveMentionsAutocompleteAction` | ✅ | @mention autocomplete |

---

## Jobs (Queueable Wrappers)

| Spatie Package | Native Module | Status | Notes |
|---|---|---|---|
| `Spatie\Comments\Jobs\SendNotificationsForApprovedCommentJob` | `Modules\Comment\Jobs\SendNotificationsForApprovedCommentJob` | ✅ | Queue wrapper |
| `Spatie\Comments\Jobs\SendNotificationsForPendingCommentJob` | `Modules\Comment\Jobs\SendNotificationsForPendingCommentJob` | ✅ | Queue wrapper |

---

## Transformers

| Spatie Package | Native Module | Status | Notes |
|---|---|---|---|
| `Spatie\Comments\Transformers\CommentTransformer` | `Modules\Comment\Transformers\CommentTransformer` | ✅ | Base transformer interface |
| `Spatie\Comments\Transformers\MarkdownToHtmlTransformer` | `Modules\Comment\Transformers\MarkdownToHtmlTransformer` | ✅ | Markdown → HTML conversion |
| `Spatie\Comments\Transformers\MentionsTransformer` | `Modules\Comment\Transformers\MentionsTransformer` | ✅ | @mention → link conversion |

---

## Support & Utilities

| Spatie Package | Native Module | Status | Notes |
|---|---|---|---|
| `Spatie\Comments\Support\CommentConfig` | `Modules\Comment\Support\CommentConfig` | ✅ | SSOT for all config |
| `Spatie\Comments\Support\CommentSanitizer` | `Modules\Comment\Support\CommentSanitizer` | ✅ | HTML sanitization (XSS prevention) |
| `Spatie\Comments\Support\CommentatorProperties` | `Modules\Comment\Support\CommentatorProperties` | ✅ | Value object for display info |
| `Spatie\Comments\Support\Gravatar` | `Modules\Comment\Support\Gravatar` | ✅ | Gravatar URL generation |

---

## Events

| Spatie Package | Native Module | Status | Notes |
|---|---|---|---|
| `Spatie\Comments\Events\CommentApprovedEvent` | `Modules\Comment\Events\CommentApprovedEvent` | ✅ | Fired on approval |
| `Spatie\Comments\Events\CommentRejectedEvent` | `Modules\Comment\Events\CommentRejectedEvent` | ✅ | Fired on rejection |

---

## Notifications

| Spatie Package | Native Module | Status | Notes |
|---|---|---|---|
| `Spatie\Comments\Notifications\ApprovedCommentNotification` | `Modules\Comment\Notifications\ApprovedCommentNotification` | ✅ | Mail: comment approved |
| `Spatie\Comments\Notifications\PendingCommentNotification` | `Modules\Comment\Notifications\PendingCommentNotification` | ✅ | Mail: comment pending |

---

## Policies

| Spatie Package | Native Module | Status | Notes |
|---|---|---|---|
| `Spatie\Comments\Policies\CommentPolicy` | `Modules\Comment\Policies\CommentPolicy` | ✅ | Authorization for comments |
| `Spatie\Comments\Policies\ReactionPolicy` | `Modules\Comment\Policies\ReactionPolicy` | ✅ | Authorization for reactions |

---

## Exceptions

| Spatie Package | Native Module | Status | Notes |
|---|---|---|---|
| `Spatie\Comments\Exceptions\CannotCreateComment` | `Modules\Comment\Exceptions\CannotCreateComment` | ✅ | Validation error |
| `Spatie\Comments\Exceptions\CannotSendPendingCommentNotification` | `Modules\Comment\Exceptions\CannotSendPendingCommentNotification` | ✅ | Notification error |
| `Spatie\Comments\Exceptions\InvalidConfig` | `Modules\Comment\Exceptions\InvalidConfig` | ✅ | Config error |

---

## Enums

| Spatie Package | Native Module | Status | Notes |
|---|---|---|---|
| `Spatie\Comments\Enums\NotificationSubscriptionType` | `Modules\Comment\Enums\NotificationSubscriptionType` | ✅ | All, Participating, None |

---

## Livewire Components (laravel-comments-livewire)

| Spatie Package | Native Module | Status | Notes |
|---|---|---|---|
| `Spatie\CommentsLivewire\Components\CommentsComponent` | `Modules\Comment\Http\Livewire\CommentsComponent` | ✅ | Container component |
| `Spatie\CommentsLivewire\Components\CommentComponent` | `Modules\Comment\Http\Livewire\CommentComponent` | ✅ | Individual comment |
| `Spatie\CommentsLivewire\Components\MentionSearchComponent` | `Modules\Comment\Http\Livewire\MentionSearchComponent` | ✅ | Mention autocomplete |

---

## Service Providers

| Spatie Package | Native Module | Status | Notes |
|---|---|---|---|
| `Spatie\Comments\CommentServiceProvider` | `Modules\Comment\Providers\CommentServiceProvider` | ✅ | Main provider |
| `Spatie\Comments\EventServiceProvider` | `Modules\Comment\Providers\EventServiceProvider` | ✅ | Event listeners |
| N/A | `Modules\Comment\Providers\RouteServiceProvider` | ✅ | Folio moderation routes |

---

## Database Factories

| Spatie Package | Native Module | Status | Notes |
|---|---|---|---|
| `Spatie\Comments\Database\Factories\CommentFactory` | `Modules\Comment\Database\Factories\CommentFactory` | ✅ | Model factory |
| `Spatie\Comments\Database\Factories\CommentNotificationSubscriptionFactory` | `Modules\Comment\Database\Factories\CommentNotificationSubscriptionFactory` | ✅ | Model factory |
| `Spatie\Comments\Database\Factories\CommentNotificationOptOutFactory` | `Modules\Comment\Database\Factories\CommentNotificationOptOutFactory` | ✅ | Model factory |
| `Spatie\Comments\Database\Factories\ReactionFactory` | `Modules\Comment\Database\Factories\ReactionFactory` | ✅ | Model factory |

---

## Database Migrations

| Spatie Package | Native Module | Status | Notes |
|---|---|---|---|
| `2024_01_01_000010_create_comments_table` | `Modules\Comment\database\migrations\2024_01_01_000010_create_comments_table` | ✅ | Comments table + indices |
| `2024_01_01_000010_create_reactions_table` | `Modules\Comment\database\migrations\2024_01_01_000010_create_reactions_table` | ✅ | Reactions table |
| `2024_01_01_000010_create_comment_notification_subscriptions_table` | `Modules\Comment\database\migrations\2024_01_01_000010_create_comment_notification_subscriptions_table` | ✅ | Subscriptions table |

---

## Configuration Files

| Spatie Package | Native Module | Status | Notes |
|---|---|---|---|
| `config/comments.php` | `Modules\Comment\config\comments.php` | ✅ | Main config (consolidated) |

---

## Migration Checklist

Use this checklist when updating imports in consumer modules:

- [ ] **Models:** Replace `Spatie\Comments\Models\*` → `Modules\Comment\Models\*`
- [ ] **Concerns:** Replace `Spatie\Comments\Models\Concerns\*` → `Modules\Comment\Models\Concerns\*`
- [ ] **Contracts:** Replace `Spatie\Comments\Models\Contracts\*` → `Modules\Comment\Models\Contracts\*`
- [ ] **Actions:** Replace `Spatie\Comments\Actions\*` → `Modules\Comment\Actions\*`
- [ ] **Transformers:** Replace `Spatie\Comments\Transformers\*` → `Modules\Comment\Transformers\*`
- [ ] **Support:** Replace `Spatie\Comments\Support\*` → `Modules\Comment\Support\*`
- [ ] **Events:** Replace `Spatie\Comments\Events\*` → `Modules\Comment\Events\*`
- [ ] **Notifications:** Replace `Spatie\Comments\Notifications\*` → `Modules\Comment\Notifications\*`
- [ ] **Policies:** Replace `Spatie\Comments\Policies\*` → `Modules\Comment\Policies\*`
- [ ] **Exceptions:** Replace `Spatie\Comments\Exceptions\*` → `Modules\Comment\Exceptions\*`
- [ ] **Enums:** Replace `Spatie\Comments\Enums\*` → `Modules\Comment\Enums\*`
- [ ] **Livewire:** Replace `Spatie\CommentsLivewire\Components\*` → `Modules\Comment\Http\Livewire\*`
- [ ] **Factories:** Replace `Spatie\Comments\Database\Factories\*` → `Modules\Comment\Database\Factories\*`
- [ ] **Config:** Update `config('comments.*')` references (still valid, same config file)

---

## Common Update Patterns

### Before: Spatie Imports
```php
use Spatie\Comments\Models\Comment;
use Spatie\Comments\Models\Contracts\CanComment;
use Spatie\Comments\Models\Concerns\HasComments;
use Spatie\Comments\Actions\ApproveCommentAction;
use Spatie\Comments\Notifications\PendingCommentNotification;
```

### After: Native Module Imports
```php
use Modules\Comment\Models\Comment;
use Modules\Comment\Models\Contracts\CanComment;
use Modules\Comment\Models\Concerns\HasComments;
use Modules\Comment\Actions\ApproveCommentAction;
use Modules\Comment\Notifications\PendingCommentNotification;
```

---

## Search & Replace for Bulk Migration

Use the following regex patterns for IDE-based find & replace:

### Pattern 1: Models
```
Find:    Spatie\Comments\Models\
Replace: Modules\Comment\Models\
```

### Pattern 2: Actions
```
Find:    Spatie\Comments\Actions\
Replace: Modules\Comment\Actions\
```

### Pattern 3: Livewire
```
Find:    Spatie\CommentsLivewire\Components\
Replace: Modules\Comment\Http\Livewire\
```

### Pattern 4: Support Classes
```
Find:    Spatie\Comments\Support\
Replace: Modules\Comment\Support\
```

### Pattern 5: All Spatie\Comments
```
Find:    Spatie\Comments\
Replace: Modules\Comment\
```

---

## FAQ: Namespace Migration

**Q: What about `Spatie\CommentsLivewire\`?**
A: All Livewire components moved to `Modules\Comment\Http\Livewire\`. Use namespace replacement.

**Q: Do I need to update config key references?**
A: No. Config keys remain `comments.*` — configuration file path is still `config/comments.php`.

**Q: What if I see `Spatie\Comments\*` in my codebase?**
A: Run grep to find all occurrences: `grep -r "Spatie\\Comments" --include="*.php"`. Update using patterns above.

**Q: Are there any breaking changes in method signatures?**
A: No. All methods and signatures are identical to Spatie originals.

**Q: What about the database migrations?**
A: Migrations use same timestamps (`2024_01_01_000010_*`) in both versions. No additional migrations needed.

---

## Related Documentation

- [Native Comments Architecture](./native-comments-architecture.md)
- [Spatie Package Inventory](./spatie-package-inventory.md)
- [Native Comments Engine Workflow](./native-comments-engine-workflow.md)
