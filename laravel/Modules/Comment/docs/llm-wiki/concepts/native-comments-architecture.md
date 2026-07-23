---
title: "Native Comments Architecture"
created: 2026-06-08
updated: 2026-06-08
category: "architecture"
tags: ["comment", "module", "architecture", "internalization"]
related_issues: ["STORY-158"]
---

# Native Comments Architecture

## Overview

The Comment module is a self-contained Laravel module (`Modules\Comment\*`) providing a complete comment system with reactions, notifications, and HTML/mention transformations. This architecture replaced the Spatie `laravel-comments` package during STORY-158 internalization.

**Key principle:** Comments are embedded Livewire components in Folio templates. No HTTP controllers or routes. Actions are QueueableActions.

---

## Core Models

### Comment Model
**Location:** `Modules\Comment\Models\Comment`

The central model for storing and managing comments.

**Properties:**
- `id` - Primary key
- `original_text` - Raw user input (pre-transformation)
- `text` - Processed text (after transformations + sanitization)
- `commentator_type`, `commentator_id` - Polymorphic relation to user/commentator
- `commentable_type`, `commentable_id` - Polymorphic relation to parent model
- `parent_id` - Self-referencing for nested replies
- `approved_at` - Timestamp when comment was approved (null = pending)
- `extra` - JSON array for extensibility
- `created_at`, `updated_at`, `deleted_at` - Timestamps

**Key Methods:**
- `commentable()` - MorphTo relation to the model being commented on
- `commentator()` - MorphTo relation to the user who commented
- `parentComment()` - BelongsTo relation to parent comment (for nested replies)
- `nestedComments()` - HasMany relation to child replies
- `reactions()` - HasMany relation to reactions
- `approve()` / `reject()` - Change approval status via actions
- `react(string $reaction)` / `deleteReaction()` - Manage reactions
- `topLevel()` - Get the root comment in a nested thread
- `isApproved()` / `isPending()` - Status checks
- `wasMadeAnonymously()` / `wasMadeByDeletedCommentator()` - Comment origin checks
- `participatingCommentators()` - Get all users who have commented on this model
- `reactionCounts()` - Grouped reaction counts
- `getMentionees()` - Extract @mentioned users from text

**Database:** Uses `comment` connection (separate database).

**Scopes:**
- `approved()` - WHERE approved_at IS NOT NULL
- `pending()` - WHERE approved_at IS NULL
- `topLevel()` - WHERE parent_id IS NULL

---

### Reaction Model
**Location:** `Modules\Comment\Models\Reaction`

Stores emoji/emoji reactions on comments (e.g., +1, -1, ❤️, 🔥, 🎉).

**Properties:**
- `id` - Primary key
- `comment_id` - ForeignKey to comment
- `commentator_type`, `commentator_id` - Polymorphic relation to reactor
- `reaction` - Emoji string (validated by config)
- `created_at`, `updated_at` - Timestamps

**Key Methods:**
- `comment()` - BelongsTo relation
- `commentator()` - MorphTo relation to the user who reacted
- `madeBy(CanComment $commentator)` - Check if reaction is by a specific user

**Uses:** `ReactionCollection` custom collection for optimization.

---

### CommentNotificationSubscription Model
**Location:** `Modules\Comment\Models\CommentNotificationSubscription`

Tracks user subscription preferences for comment notifications on specific models.

**Properties:**
- `id` - Primary key
- `subscriber_type`, `subscriber_id` - Polymorphic relation to subscriber
- `commentable_type`, `commentable_id` - Polymorphic relation to model being subscribed to
- `type` - Subscription type enum (All, Participating, None)
- `created_at`, `updated_at` - Timestamps

**Subscription Types:** See `NotificationSubscriptionType` enum.

---

### CommentNotificationOptOut Model
**Location:** `Modules\Comment\Models\CommentNotificationOptOut`

Hard opt-out for users who wish to never receive notifications for a specific model.

---

## Concerns (Traits)

### HasComments Trait
**Location:** `Modules\Comment\Models\Concerns\HasComments`

Add this to any model that can be commented on (Commentable).

```php
class Ticket extends Model
{
    use HasComments; // Enable comments on Ticket
}
```

**Relations:**
- `comments()` - MorphMany to Comment model
- `notificationSubscriptions()` - MorphMany to CommentNotificationSubscription

**Methods:**
- `comment(string $text, ?CanComment $commentator = null): Comment` - Create a new comment
- `subscribers(?NotificationSubscriptionType $type = null): Collection` - Get subscribers of a specific type
- `participatingCommentators(): Collection` - Get all users who commented

**Abstract Methods (must implement in model):**
- `commentableName(): string` - Display name for notifications (e.g., "Ticket #123")
- `commentUrl(): string` - URL to the commentable model for notifications

---

### InteractsWithComments Trait
**Location:** `Modules\Comment\Models\Concerns\InteractsWithComments`

Mixin trait for models that need comment interaction methods. Provides convenience methods for comment management.

---

## Contracts (Interfaces)

### CanComment Contract
**Location:** `Modules\Comment\Models\Contracts\CanComment`

Models that can create comments must implement this interface. Typically the User model.

**Required Interface Methods:**
- `getKey()` - Return model's primary key
- `getMorphClass()` - Return morph class name
- `commentatorComments(): MorphMany` - Comments created by this user
- `reactions(): MorphMany` - Reactions created by this user
- `notify($instance)` - Send notifications (typically via Notifiable trait)
- `commentatorProperties(): CommentatorProperties` - User display info
- `subscribeToCommentNotifications(Model $hasComments, NotificationSubscriptionType $type)` - Subscribe to notifications
- `unsubscribeFromCommentNotifications(Model $hasComments): self` - Unsubscribe from specific model
- `unsubscribeFromAllCommentNotifications(): self` - Unsubscribe globally
- `notificationSubscriptionType(Model $hasComment): ?NotificationSubscriptionType` - Get current subscription

**Implementation Pattern:**
```php
class User extends Model implements CanComment
{
    use Notifiable, HasComments;
    
    public function commentatorComments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentator');
    }
    
    public function reactions(): MorphMany
    {
        return $this->morphMany(Reaction::class, 'commentator');
    }
    
    public function commentatorProperties(): CommentatorProperties
    {
        return new CommentatorProperties(
            name: $this->name,
            avatar: $this->avatar_url,
            url: route('profile', $this),
        );
    }
    
    // ... implement subscription methods
}
```

---

### Commentable Contract
**Location:** `Modules\Comment\Models\Contracts\Commentable`

Models that can have comments (implements `HasComments` trait).

---

## Actions (QueueableActions)

All actions use `Spatie\QueueableAction\QueueableAction` trait for optional queue execution.

### ProcessCommentAction
**Location:** `Modules\Comment\Actions\ProcessCommentAction`

Transforms comment text through configured transformers and sanitizes.

```php
$action->execute($comment);
// or queue: $action->onQueue('default')->execute($comment);
```

**Pipeline:**
1. Apply comment transformers (MarkdownToHtmlTransformer, MentionsTransformer)
2. Sanitize HTML output (CommentSanitizer)
3. Store in `text` field (leaving `original_text` untouched)

---

### ApproveCommentAction
**Location:** `Modules\Comment\Actions\ApproveCommentAction`

Approves a pending comment and triggers notifications.

```php
$comment->approve(); // internally calls this action
```

**Pipeline:**
1. Set `approved_at` timestamp
2. Dispatch `CommentApprovedEvent`
3. Send notifications via `SendNotificationsForApprovedCommentAction`

---

### RejectCommentAction
**Location:** `Modules\Comment\Actions\RejectCommentAction`

Rejects a pending comment (marks as rejected, typically soft-delete logic).

```php
$comment->reject(); // internally calls this action
```

---

### SendNotificationsForApprovedCommentAction
**Location:** `Modules\Comment\Actions\SendNotificationsForApprovedCommentAction`

Sends `ApprovedCommentNotification` to all subscribers of the parent model.

Reads subscription preferences from `CommentNotificationSubscription`.

---

### SendNotificationsForPendingCommentAction
**Location:** `Modules\Comment\Actions\SendNotificationsForPendingCommentAction`

Sends `PendingCommentNotification` to moderators/admins.

Uses `PendingCommentNotification::$sendTo` closure to determine recipients.

---

### ResolveMentionsAutocompleteAction
**Location:** `Modules\Comment\Actions\ResolveMentionsAutocompleteAction`

Autocomplete endpoint for @mention suggestions. Uses the commentator model to search users.

---

## Livewire Components

### CommentsComponent
**Location:** `Modules\Comment\Http\Livewire\CommentsComponent`

Main container component for displaying and creating comments on a model.

**Public Properties:**
- `model: ?Model` - The model being commented on
- `text: string` - Comment text input
- `writable: bool` - Show comment form (default: true)
- `showAvatars: bool` - Display user avatars
- `showNotificationOptions: bool` - Show subscription preferences
- `newestFirst: bool` - Sort order
- `showReplies: bool` - Show nested replies
- `showReactions: bool` - Show emoji reactions
- `selectedNotificationSubscriptionType: string` - Current subscription selection
- `noCommentsText: ?string` - Custom empty state message

**Key Methods:**
- `comment()` - Create new top-level comment (validates text input)
- `updateSelectedNotificationSubscriptionType()` - Update user's notification preference
- `saveNotificationSubscription()` - Persist subscription changes

**Livewire Events:**
- Listens: `delete` (refresh on comment deletion)
- Listens: `reply-created` (update subscriptions)
- Dispatches: `comment-added`

**Usage in Folio:**
```blade
<livewire:comments :model="$ticket" :show-replies="true" :show-reactions="true" />
```

### CommentComponent
**Location:** `Modules\Comment\Http\Livewire\CommentComponent`

Individual comment display and interaction (edit, delete, approve, react).

### MentionSearchComponent
**Location:** `Modules\Comment\Http\Livewire\MentionSearchComponent`

Autocomplete dropdown for @mentions in comment editor.

---

## Events

### CommentApprovedEvent
**Location:** `Modules\Comment\Events\CommentApprovedEvent`

Fired when a comment is approved. Triggers notification sending.

```php
event(new CommentApprovedEvent($comment));
```

### CommentRejectedEvent
**Location:** `Modules\Comment\Events\CommentRejectedEvent`

Fired when a comment is rejected.

---

## Notifications

### PendingCommentNotification
**Location:** `Modules\Comment\Notifications\PendingCommentNotification`

Mail notification sent to moderators when new comment is pending approval.

**Configuration:**
```php
PendingCommentNotification::sendTo(function (Comment $comment) {
    return User::where('is_admin', true)->get();
});
```

---

### ApprovedCommentNotification
**Location:** `Modules\Comment\Notifications\ApprovedCommentNotification`

Mail notification sent to comment thread participants when a comment is approved.

---

## Policies

### CommentPolicy
**Location:** `Modules\Comment\Policies\CommentPolicy`

Authorization for comment actions:
- `create()` - Can user create comments?
- `update()` - Can user edit own comments?
- `delete()` - Can user delete own comments?
- `approve()` - Can user approve comments? (admin-only typically)
- `reject()` - Can user reject comments? (admin-only typically)

### ReactionPolicy
**Location:** `Modules\Comment\Policies\ReactionPolicy`

Authorization for reaction actions:
- `create()` - Can user add reactions?
- `delete()` - Can user remove own reactions?

---

## Support Classes

### CommentConfig
**Location:** `Modules\Comment\Support\CommentConfig`

**Single Source of Truth (SSOT)** for comment engine configuration. Resolves model classes, actions, and policies from `config/comments.php`.

**Key Methods:**
- `commentModelClass(): string` - Get Comment model class
- `commentatorModelClass(): string` - Get User model class
- `commentPolicyClass()` / `reactionPolicyClass()` - Get policy classes
- `processCommentAction()`, `approveCommentAction()`, etc. - Get action instances
- `allowedReactions(): array` - Configured emoji reactions
- `allowedAttributes(): array` - HTML tags allowed in sanitized output
- `allowAnonymousComments(): bool` - Allow comments without login
- `automaticallyApproveAllComments(): bool` - Skip approval workflow
- `mentionsEnabled(): bool` - Enable @mentions
- `notificationsEnabled(): bool` - Enable email notifications

---

### CommentSanitizer
**Location:** `Modules\Comment\Support\CommentSanitizer`

Sanitizes HTML output to prevent XSS. Uses `allowedAttributes` config.

```php
$clean = CommentSanitizer::sanitize($html);
```

---

### CommentatorProperties
**Location:** `Modules\Comment\Support/CommentatorProperties`

Value object encapsulating commentator display info (name, avatar, profile URL).

```php
$props = new CommentatorProperties(
    name: 'John Doe',
    avatar: 'https://example.com/avatar.jpg',
    url: 'https://example.com/users/john',
);
```

---

## Transformers

### CommentTransformer (Interface)
**Location:** `Modules\Comment\Transformers\CommentTransformer`

Base interface for comment text transformers.

```php
interface CommentTransformer
{
    public function handle(Comment $comment): void;
}
```

Transformers modify the `text` property. Applied in `ProcessCommentAction`.

### MarkdownToHtmlTransformer
**Location:** `Modules\Comment\Transformers\MarkdownToHtmlTransformer`

Converts Markdown to HTML using `Spatie\LaravelMarkdown\MarkdownRenderer`.

**Features:**
- Disallows raw HTML (via `DisallowedRawHtmlExtension`)
- Preserves safe HTML structure
- Escape user-provided HTML

### MentionsTransformer
**Location:** `Modules\Comment\Transformers\MentionsTransformer`

Converts `@username` mentions to HTML with `data-mention` attributes for client-side highlighting.

```html
Before: "Hey @john, check this out"
After:  "Hey <a data-mention="1">john</a>, check this out"
```

---

## Configuration

**Location:** `config/comments.php`

Central configuration file. Values resolved via `CommentConfig` class.

```php
return [
    'models' => [
        'comment' => \Modules\Comment\Models\Comment::class,
        'reaction' => \Modules\Comment\Models\Reaction::class,
        'comment_notification_subscription' => 
            \Modules\Comment\Models\CommentNotificationSubscription::class,
        'commentator' => null, // Set to User model in app
        'name' => 'name', // Field name on commentator model
        'avatar' => 'avatar', // Field name on commentator model
    ],

    'policies' => [
        'comment' => \Modules\Comment\Policies\CommentPolicy::class,
        'reaction' => \Modules\Comment\Policies\ReactionPolicy::class,
    ],

    'actions' => [
        'process_comment' => \Modules\Comment\Actions\ProcessCommentAction::class,
        'approve_comment' => \Modules\Comment\Actions\ApproveCommentAction::class,
        'reject_comment' => \Modules\Comment\Actions\RejectCommentAction::class,
        // ... more actions
    ],

    'notifications' => [
        'enabled' => true,
        'mail' => [
            'from' => ['address' => null, 'name' => null],
        ],
        'notifications' => [
            'pending_comment' => \Modules\Comment\Notifications\PendingCommentNotification::class,
            'approved_comment' => \Modules\Comment\Notifications\ApprovedCommentNotification::class,
        ],
    ],

    'comment_transformers' => [
        \Modules\Comment\Transformers\MarkdownToHtmlTransformer::class,
        \Modules\Comment\Transformers\MentionsTransformer::class,
    ],

    'comment_sanitizer' => \Modules\Comment\Support\CommentSanitizer::class,

    'allowed_reactions' => ['+1', '-1', '❤️', '🔥', '🎉'],

    'allowed_attributes' => [
        'a' => ['href', 'target', 'rel'],
        'ul' => [], 'ol' => [], 'li' => [],
        'p' => [], 'pre' => [], 'code' => [],
        'blockquote' => [], 'strong' => [], 'em' => [],
    ],

    'allow_anonymous_comments' => false,
    'automatically_approve_all_comments' => true,

    'gravatar' => ['default_image' => 'mp'],
    'mentions' => ['enabled' => false],

    'ui' => [
        'show_avatars' => true,
        'autoload_fontawesome' => true,
        'show_avatars_in_mentions_autocomplete' => true,
        'editor' => 'comment::editors.textarea',
    ],

    'pagination' => [
        'results' => 10_000,
        'page_name' => 'page',
        'theme' => 'tailwind',
    ],
];
```

---

## Service Provider

**Location:** `Modules\Comment\Providers\CommentServiceProvider`

**Responsibilities:**
1. Register Livewire components: `comments`, `comments-comment`
2. Register authorization policies via `Gate`
3. Register Folio moderation pages (email verification links)

**No HTTP controllers or routes.** Comments are embedded Livewire components in Folio pages.

---

## Consumer Integration Pattern

To add comments to a model:

### 1. Model Setup
```php
class Ticket extends Model
{
    use HasComments; // Add HasComments trait
    
    public function commentableName(): string
    {
        return "Ticket #{$this->id}";
    }
    
    public function commentUrl(): string
    {
        return route('tickets.show', $this);
    }
}
```

### 2. Folio Template
```blade
<!-- resources/views/folio/tickets/[id].blade.php -->
<div class="ticket-details">
    <!-- Ticket content -->
</div>

<!-- Embedded Livewire component -->
<livewire:comments :model="$ticket" :show-replies="true" />
```

### 3. User Model Configuration
```php
// config/comments.php
'commentator' => \App\Models\User::class, // Set your user model
```

### 4. User Model Implementation
```php
class User extends Model implements CanComment
{
    // ... implement CanComment interface
}
```

---

## Database Schema

**Connection:** `comment` (separate database for performance isolation)

**Tables:**
- `comments` - Main comments table
- `reactions` - Emoji reactions on comments
- `comment_notification_subscriptions` - User subscription preferences
- `comment_notification_opt_outs` - Hard opt-outs

---

## Workflow: Creating and Approving a Comment

```
User submits comment via CommentsComponent
    ↓
HasComments::comment() creates Comment model
    ↓
Comment model boot event: ProcessCommentAction runs
    - Apply transformers (Markdown → HTML, mentions)
    - Sanitize HTML
    ↓
Comment saved to database (approved_at = null if not auto-approved)
    ↓
If auto-approve enabled: Set approved_at = now()
    ↓
ApproveCommentAction runs:
    - Dispatch CommentApprovedEvent
    - SendNotificationsForApprovedCommentAction queued
    ↓
Notifications sent to subscribers based on subscription type
```

---

## Related Documentation

- [Spatie Package Inventory](../concepts/spatie-package-inventory.md)
- [Namespace Migration Map](../concepts/spatie-to-laraxot-namespace-map.md)
- [Native Comments Engine Workflow](../concepts/native-comments-engine-workflow.md)
- [Module Providers Manifest](../concepts/module-providers-manifest.md)
