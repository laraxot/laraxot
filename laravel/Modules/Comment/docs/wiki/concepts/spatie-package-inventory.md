---
title: "Inventario packages/spatie — mapping migrazione"
type: concept
module: Comment
tags: [comment, spatie, inventory, migration, mapping]
created: 2026-06-06
updated: 2026-06-06
qmd: "spatie laravel-comments inventory mapping Modules Comment internalize packages"
issues:
  - "https://github.com/laraxot/module_comment_fila5/issues/4"
related:
  - native-comments-architecture.md
  - ../decisions/adr-internalize-spatie-comments.md
---

# Inventario `packages/spatie` → `Modules\Comment`

**111 file PHP** circa (core + livewire). Questo documento mappa **cosa** portare e **dove**.

Legenda fase: **P1** core, **P2** UI, **P3** HTTP/mail, **P4** drop.

---

## laravel-comments (core)

| Spatie path | Target Laraxot | Fase | Note |
|-------------|----------------|------|------|
| `Models/Comment.php` | Logica in `app/Models/Comment.php` | P1 | Già wrapper; portare metodi base |
| `Models/Reaction.php` | `app/Models/Reaction.php` | P1 | Già wrapper |
| `Models/CommentNotificationSubscription.php` | `app/Models/CommentNotificationSubscription.php` | P1 | Già wrapper |
| `Models/CommentNotificationOptOut.php` | `app/Models/CommentNotificationOptOut.php` | P1 | Già wrapper |
| `Models/Concerns/HasComments.php` | `app/Models/Concerns/HasComments.php` | P1 | Consumer trait |
| `Models/Concerns/InteractsWithComments.php` | merge in `InteractsWithComments.php` | P1 | Sostituisce `InteractsWithSpatieComments` |
| `Models/Concerns/Interfaces/CanComment.php` | `app/Models/Concerns/Interfaces/CanComment.php` | P1 | |
| `Models/Collections/ReactionCollection.php` | `app/Models/Collections/ReactionCollection.php` | P1 | |
| `Actions/*Action.php` (7) | `app/Actions/*Action.php` | P1 | + `QueueableAction` trait |
| `CommentTransformers/*` | `app/Transformers/` o `CommentTransformers/` | P1 | Markdown, mentions |
| `Support/Config.php` | `app/Support/CommentConfig.php` | P1 | Evitare clash nome |
| `Support/CommentSanitizer.php` | `app/Support/CommentSanitizer.php` | P1 | |
| `Support/CommentatorProperties.php` | `app/Support/CommentatorProperties.php` | P1 | |
| `Enums/NotificationSubscriptionType.php` | `app/Enums/NotificationSubscriptionType.php` | P1 | |
| `Events/CommentApprovedEvent.php` etc. | `app/Events/` | P1 | |
| `Jobs/SendNotifications*.php` | `app/Jobs/` | P1 | |
| `Notifications/*` | `app/Notifications/` | P1 | Integrare Notify module |
| `Exceptions/*` | `app/Exceptions/` | P1 | |
| `CommentsServiceProvider.php` | merge `CommentServiceProvider` | P1 | Route macro `comments()` |
| `Http/Controllers/*` (4) | Routes + Actions o Filament | P3 | No controller MVC app |
| `View/Components/Mentions/Mention.php` | `app/View/Components/` | P2 | |
| `config/comments.php` | `config/comments.php` (module) | P1 | |
| `resources/lang/*` | `lang/it`, `lang/en` only | P2 | DRY i18n |
| `resources/views/mail/*` | `resources/views/mail/` | P3 | |
| `resources/views/signed/*` | Filament o minimal blade | P3 | |
| `database/migrations/*` | **NON copiare** | — | Owner: `database/migrations/2024_01_01_000010_*` |
| `database/factories/*` | `database/factories/` | P1 | Allineare a module factories |

---

## laravel-comments-livewire

| Spatie path | Target Laraxot | Fase | Note |
|-------------|----------------|------|------|
| `Livewire/CommentsComponent.php` | `Http/Livewire/CommentsComponent.php` | P2 | Alias `comments` |
| `Livewire/CommentComponent.php` | `Http/Livewire/CommentComponent.php` | P2 | Singolo commento |
| `Livewire/MentionSearchComponent.php` | `Http/Livewire/MentionSearchComponent.php` | P2 | |
| `Policies/CommentPolicy.php` | `app/Policies/CommentPolicy.php` | P2 | |
| `Policies/ReactionPolicy.php` | `app/Policies/ReactionPolicy.php` | P2 | |
| `LivewireCommentsServiceProvider.php` | `CommentServiceProvider` boot | P2 | Register Livewire |
| `Support/Config.php` | unificare con core Config | P2 | |
| `Support/Gravatar.php` | `app/Support/Gravatar.php` o Media module | P2 | |
| `resources/views/livewire/*` | `resources/views/livewire/` | P2 | Namespace `comment::` |
| `resources/views/components/*` | `resources/views/components/` | P2 | Tailwind refactor |
| `resources/css/comments.css` | `resources/css/comments.css` + vite | P2 | Tema Sixteen |
| `resources/js/comments.js` | valutare Alpine/Livewire only | P2 | KISS |

---

## Consumer da migrare (grep)

| Module | File | Cambio |
|--------|------|--------|
| Fixcity | `Ticket.php`, `User.php`, `ticket-comments.blade.php` | import `Modules\Comment\*` |
| Blog | `Article.php`, TwentyOne articles blade | idem |
| User | `BaseUser.php` | `InteractsWithComments` |
| Config | `laravel/config/comments.php`, `local/fixcity/comments.php` | classi native |
| Comment | `app/Models/*.php` | rimuovere extends Spatie |

---

## Composer cleanup (P4)

Rimuovere da `Modules/Comment/composer.json`:

```json
"spatiex/laravel-comments": "*",
"spatiex/laravel-comments-livewire": "*",
"Spatie\\Comments\\": "packages/spatie/laravel-comments/src/"
```

Path repositories `./packages/spatie/*` → delete.

---

## Rischi

| Rischio | Mitigazione |
|---------|-------------|
| Break alias Livewire | Mantenere `comments` fino a cutover |
| Schema drift | No new migration; solo codice |
| PHPStan generics Comment model | Portare PHPDoc da Spatie |
| Signed route email in produzione | Test route names invariati o redirect |

---

## Backlink

- [Architettura nativa](native-comments-architecture.md)
- [ADR](../decisions/adr-internalize-spatie-comments.md)
