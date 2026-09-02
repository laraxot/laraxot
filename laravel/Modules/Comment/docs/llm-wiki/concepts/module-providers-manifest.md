---
title: "Module Providers Manifest — No Controllers"
created: 2026-06-08
updated: 2026-06-08
category: "architecture"
tags: ["comment", "provider", "folio", "livewire", "no-controllers"]
related_issues: ["STORY-158"]
---

# Module Providers Manifest — No Controllers

## Overview

The Comment module follows a **no-controller** architecture pattern. All functionality is delivered via:

1. **Embedded Livewire components** in Folio templates
2. **Signed routes** for email verification links (approval/opt-out)
3. **Queueable actions** for business logic
4. **Service providers** for registration and bootstrapping

This document describes the provider architecture and the architectural decision behind the no-controller pattern.

---

## Provider Stack

### 1. CommentServiceProvider (Primary)

**Location:** `Modules\Comment\Providers\CommentServiceProvider`

**Extends:** `Modules\Xot\Providers\XotBaseServiceProvider`

**Responsibilities:**
- Register Livewire components globally
- Register authorization policies
- Register Folio moderation pages
- Bootstrap module on `app:boot`

**Key Methods:**

#### `boot()`
```php
public function boot(): void
{
    parent::boot();
    
    $this->registerFolioModerationPages();
    
    $this->app->booted(function (): void {
        Livewire::component('comments', CommentsComponent::class);
        Livewire::component('comments-comment', CommentComponent::class);
        $this->registerPolicies();
    });
}
```

**What it does:**
1. Call parent boot (publish assets, register migrations, load translations)
2. Register Folio moderation pages with signed route middleware
3. Register Livewire components: `<livewire:comments />` and `<livewire:comments-comment />`
4. Register authorization policies via `Gate`

#### `registerFolioModerationPages()`
```php
protected function registerFolioModerationPages(): void
{
    $path = dirname($this->module_dir, 2).'/resources/views/folio-moderation';
    
    if (! File::isDirectory($path)) {
        return;
    }
    
    Folio::path($path)->middleware([
        '*' => ['web', 'signed'],
    ]);
}
```

**What it does:**
- Locate Folio moderation pages at `resources/views/folio-moderation/`
- Register with Laravel Folio using `signed` middleware (URL signature verification)
- All routes in this path require a signed URL (prevents unauthorized access to approve/reject links)

#### `registerPolicies()`
```php
protected function registerPolicies(): void
{
    $commentPolicy = CommentConfig::commentPolicyClass();
    $reactionPolicy = CommentConfig::reactionPolicyClass();
    
    Gate::define('createComment', [$commentPolicy, 'create']);
    Gate::policy(Comment::class, $commentPolicy);
    Gate::policy(Reaction::class, $reactionPolicy);
}
```

**What it does:**
- Resolve policy classes from `CommentConfig` (allows overrides via config)
- Register comment policy with `Gate::policy()`
- Register reaction policy with `Gate::policy()`
- Define `createComment` gate (checked before form render in Livewire)

---

### 2. EventServiceProvider

**Location:** `Modules\Comment\Providers\EventServiceProvider`

**Extends:** `Illuminate\Foundation\Support\Providers\EventServiceProvider`

**Responsibilities:**
- Register event listeners for comment lifecycle
- Configure queued notification sending
- Integrate with Laravel's event discovery

**Typical Listeners:**
```php
protected $listen = [
    CommentApprovedEvent::class => [
        SendNotificationsForApprovedCommentAction::class,
    ],
    CommentRejectedEvent::class => [
        // Handle rejection logic
    ],
];
```

**What it does:**
- When `CommentApprovedEvent` fires, queue the notification action
- Provides loose coupling: models don't know about notifications

---

### 3. RouteServiceProvider

**Location:** `Modules\Comment\Providers\RouteServiceProvider`

**Extends:** `Illuminate\Foundation\Support\Providers\RouteServiceProvider`

**Responsibilities:**
- Register Folio moderation routes (email verification links)
- NO HTTP controllers or typical routes

**Routes Registered:**
- `comments.comment.approve` — Signed URL: approve comment via email link
- `comments.comment.reject` — Signed URL: reject comment via email link
- (Optional) `comments.mentions-autocomplete` — API endpoint for mention suggestions

**Architecture Decision:**
These are **not typical HTTP routes**. They are:
- Folio routes (directory-based, auto-routed by Laravel Folio)
- Signed routes (URL signature verification prevents unauthorized access)
- Single-action routes (each route has one clear purpose)

---

### 4. Filament AdminPanelProvider

**Location:** `Modules\Comment\Providers\Filament\AdminPanelProvider`

**Responsibilities:**
- Optional Filament integration for admin dashboard
- Register comment moderation panels
- Configure comment approval workflow in Filament

**Example:**
```php
public function panel(Panel $panel): Panel
{
    return $panel
        ->default()
        ->id('admin')
        ->resources([
            CommentResource::class,
        ])
        ->pages([
            Dashboard::class,
        ]);
}
```

---

## No Controllers Pattern — Architectural Decision

### Why No Controllers?

**Problem Statement:**
Comments are typically embedded UI elements, not standalone pages. Traditional REST controllers create:
- Unnecessary routing complexity
- Tight coupling between comment logic and HTTP layer
- Duplicate request validation logic (Livewire + Controller)

**Solution:**
Use **Embedded Livewire Components** instead:

### Component-Based Architecture

**Flow:**
```
Folio Template (e.g., /tickets/{id})
    ↓
Embedded Livewire Component: <livewire:comments :model="$ticket" />
    ↓
CommentsComponent (container)
    ├─ CommentComponent (individual comment, repeating)
    └─ MentionSearchComponent (autocomplete dropdown)
    ↓
User Actions (create, edit, delete, react)
    ↓
Queueable Actions (ProcessCommentAction, ApproveCommentAction, etc.)
    ↓
Database Model Mutations
```

### Benefits

| Aspect | Controller Approach | Livewire Component Approach |
|--------|---|---|
| **Routing** | Verbose routes.php | Auto-routed via Folio template location |
| **Validation** | Form request + Livewire rules | Single Livewire validation rule |
| **Reactivity** | Page reload + JavaScript | Real-time updates via Livewire wire() |
| **State** | Request scope | Component property scope |
| **Reusability** | Low (tightly coupled to routes) | High (embed anywhere in app) |
| **Testing** | HTTP testing | Component testing + Unit testing |

---

## Service Provider Boot Sequence

### Load Order (Laravel Bootstrap)

```
1. CommentServiceProvider::register()
   ├─ Register service bindings (if any)
   └─ Don't touch facades yet

2. (All providers register phase completes)

3. CommentServiceProvider::boot()
   ├─ parent::boot() — load translations, migrations, assets
   ├─ registerFolioModerationPages() — set up signed routes
   └─ $this->app->booted() callback:
       ├─ Register Livewire components globally
       └─ Register authorization policies via Gate

4. EventServiceProvider::boot()
   └─ Discover and register event listeners

5. (App fully booted)
   ├─ Livewire ready
   ├─ Gates/Policies registered
   └─ Folio routes available
```

### Booted Callback Reason

The `registerPolicies()` call is wrapped in `$this->app->booted()` because:
- Policies need to run after all service providers have registered
- `Gate` facade needs to be fully initialized
- Livewire components need to be callable before policy checks

```php
$this->app->booted(function (): void {
    // At this point: all providers have booted
    // Safe to register policies that depend on other providers
    Livewire::component('comments', CommentsComponent::class);
    $this->registerPolicies();
});
```

---

## Configuration Resolution via Service Provider

### CommentConfig Static Facade

**Location:** `Modules\Comment\Support\CommentConfig`

The `CommentConfig` class acts as a service locator, resolving configuration at runtime:

```php
// Service Provider doesn't resolve these — CommentConfig does (lazy)
CommentConfig::commentPolicyClass()  // Returns from config('comments.policies.comment')
CommentConfig::commentModelClass()   // Returns from config('comments.models.comment')
CommentConfig::allowedReactions()    // Returns from config('comments.allowed_reactions')
```

**Why?**
- Allows configuration overrides per tenant/environment
- Avoids tightly coupling provider to config values
- Supports swappable implementations (custom policies, models, etc.)

---

## Folio Moderation Pages

### Signed Routes Pattern

Moderation pages (approval/opt-out links in emails) use **signed routes**:

```php
// In approval email:
$comment->approveUrl() 
// Returns: /comments/approve?signature=...&expires=...
```

**Why Signed Routes?**
- Email links are public (no authentication context)
- Signature prevents unauthorized modifications
- Expiration prevents infinite validity
- No need for database tokens

### Implementation

**RouteServiceProvider:**
```php
Folio::path($path)->middleware([
    '*' => ['web', 'signed'],
]);
```

**Folio Page Example:**
```php
// resources/views/folio-moderation/comment/approve.blade.php
{{ $comment->approve()->id }}
```

---

## Testing Providers

### Unit Testing Policies

```php
test('user can create comment', function () {
    $user = User::factory()->create();
    $ticket = Ticket::factory()->create();
    
    $result = Gate::forUser($user)->allows('createComment', $ticket);
    expect($result)->toBeTrue();
});
```

### Testing Livewire Components

```php
test('comments component mounts with model', function () {
    $ticket = Ticket::factory()->create();
    
    Livewire::test(CommentsComponent::class, ['model' => $ticket])
        ->assertViewHas('model', $ticket);
});
```

---

## Extending the Providers

### Custom Policy

Create a policy and override via config:

```php
// app/Policies/CustomCommentPolicy.php
class CustomCommentPolicy extends CommentPolicy
{
    public function create(CanComment $user): bool
    {
        return $user->can('moderate_comments');
    }
}

// config/comments.php
'policies' => [
    'comment' => CustomCommentPolicy::class,
],
```

### Custom Action

Create an action and override via config:

```php
// app/Actions/CustomProcessCommentAction.php
class CustomProcessCommentAction extends ProcessCommentAction
{
    public function handle(Comment $comment): void
    {
        // Custom logic
        parent::handle($comment);
    }
}

// config/comments.php
'actions' => [
    'process_comment' => CustomProcessCommentAction::class,
],
```

### Custom Livewire Component

```php
// app/Livewire/CustomCommentsComponent.php
class CustomCommentsComponent extends CommentsComponent
{
    // Override behavior
}

// In a service provider:
Livewire::component('comments', CustomCommentsComponent::class);
```

---

## Service Provider Registration Checklist

Use this when setting up Comment module in a new application:

**Provider Registration:**
- [ ] `CommentServiceProvider` is registered in `config/app.php` providers
- [ ] `EventServiceProvider` is registered in `config/app.php` providers
- [ ] `RouteServiceProvider` is registered in `config/app.php` providers

**Configuration:**
- [ ] `config/comments.php` exists and is published
- [ ] `comments.models.commentator` is set to `User::class`
- [ ] `comments.models.name` matches user name column
- [ ] `comments.models.avatar` matches user avatar column

**Database:**
- [ ] Migrations have been published: `php artisan vendor:publish --tag=comment-migrations`
- [ ] Migrations have been run: `php artisan migrate`
- [ ] `comment` database connection is configured (or uses default)

**User Model:**
- [ ] Implements `CanComment` interface
- [ ] Has `Notifiable` trait (for notifications)
- [ ] Implements subscription methods (from interface)

**Frontend:**
- [ ] Livewire is installed in the app
- [ ] Folio is installed in the app
- [ ] Components are embeddable in Folio templates

**Testing:**
- [ ] Tests pass: `php artisan test`
- [ ] Comment creation works
- [ ] Livewire components render
- [ ] Notifications send

---

## Related Documentation

- [Native Comments Architecture](./native-comments-architecture.md)
- [Native Comments Engine Workflow](./native-comments-engine-workflow.md)
- [Namespace Migration Map](./spatie-to-laraxot-namespace-map.md)
- [Spatie Package Inventory](./spatie-package-inventory.md)
