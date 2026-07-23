---
title: "Spatie Comments — integrazione FO dettaglio ticket"
type: concept
tags: [comment, spatie, livewire, ticket, fixcity, frontoffice]
created: 2026-06-05
updated: 2026-06-05
qmd: "spatie comments frontoffice ticket fixcity livewire comments component HasComments morph STORY-160"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/301"
  - "https://github.com/laraxot/module_comment_fila5/issues/6"
  - "https://github.com/laraxot/module_fixcity_fila5/issues/39"
related:
  - ../../../../../docs/stories/STORY-160-ticket-detail-comments-not-working.md
  - ../../../packages/spatie/laravel-comments/README.md
  - ../../../../Fixcity/docs/wiki/concepts/ticket-view-fo-enrichment-map-media-comments.md
  - ../../../../../docs/stories/STORY-157-ux-design-ticket-detail-no-tabs-map-comments.md
---

# Spatie Comments — integrazione FO dettaglio ticket

## Perché Spatie (e non solo TicketComment)

Il modulo Comment vendorizza `spatie/laravel-comments` + `laravel-comments-livewire`. Offre:

- Commenti polimorfici su qualsiasi modello (`commentable_type` / `commentable_id`)
- Threading (`parent_id`), reazioni, menzioni, moderazione (`approved_at`)
- Componente Livewire registrato: `<livewire:comments :model="$ticket"/>`
- Notifiche e sottoscrizioni (`NotificationSubscriptionType`)

**DRY:** un solo stack commenti per Blog, Ticket, Predict — non reinventare form/lista in Blade Fixcity.

## Pacchetti nel repo

| Path | Ruolo |
|------|-------|
| `packages/spatie/laravel-comments` | Modello `Comment`, trait `HasComments`, policy, jobs |
| `packages/spatie/laravel-comments-livewire` | `CommentsComponent`, viste `comments::livewire.*` |
| `app/Models/Comment.php` | Wrapper Laraxot (se configurato in `comments.php`) |

Livewire alias: `comments` → `Spatie\LivewireComments\Livewire\CommentsComponent`.

## Precedente in codebase

`Themes/TwentyOne/.../articles/[slug].blade.php`:

```blade
@auth
    <livewire:comments :model="$article"/>
@endauth
@guest
    <livewire:comments read-only :model="$article"/>
@endguest
```

`Blog\Models\Article` usa `HasComments` senza override di `comments()`.

## Stato su Ticket (Fixcity) — STORY-160 ✅

| Pezzo | Stato |
|-------|-------|
| `Ticket` + `HasComments` | Morph `comments()` attivo; legacy `ticketComments()` separato |
| `ticket-comments.blade.php` | `@livewire(CommentsComponent::class)` nativo (non alias Spatie) |
| `Comment` model | Extends `Spatie\Comments\Models\Comment`; hook `saving` colonne legacy |
| `CommentsComponent` | `HasComments::comment()` via contratto `Commentable` |
| Guest FO | Lista read-only + login `/it/auth/login` |
| Test | `TicketCommentsTest` — `DatabaseTransactions` + `Fixcity\Tests\TestCase` |

### Root cause risolta (schema legacy)

Insert falliva su `comments.comment` NOT NULL. Fix: `Comment::booted()` popola `comment`, `post_id`, `user_id` in `saving`; `HasComments::comment()` approva con `approved_at` diretto.

### Residui STORY-158

- Alias globale `comments` → ancora Spatie su `CommentEngineServiceProvider::booted()`
- Config `actions.*` ancora classi Spatie
- Migrazione nullable esplicita colonne legacy (opzionale — hook copre)

## Config da verificare

`config/comments.php` (publish da pacchetto):

- `comment_model`, `user_model`
- `allow_anonymous_comments`
- `automatically_approve_all_comments` (FO demo vs produzione)

## UX (allineamento STORY-157)

- Sezione commenti **sotto mappa**, fuori da tab
- Guest: lista + CTA login Folio
- Auth: textarea Spatie + notifiche opzionali nascoste in FO se troppo dense

## Asset FO (implementato 2026-06-05)

Fork Composer `spatiex/laravel-comments-livewire` — le directive `@laravelCommentsLivewireStyles` falliscono su `InstalledVersions`. Usare:

```blade
<link rel="stylesheet" href="{{ route('laravel-comments-livewire.styles') }}">
<script src="{{ route('laravel-comments-livewire.scripts') }}"></script>
```

## Anti-pattern

- Duplicare lista in Blade **e** Livewire Spatie
- Nuovo `TicketCommentService` — usare API trait `$ticket->comment($text)`
- Controller FO per POST commenti

## Collegamenti

- [STORY-160](../../../../../docs/stories/STORY-160-ticket-detail-comments-not-working.md)
- [STORY-157](../../../../../docs/stories/STORY-157-ux-design-ticket-detail-no-tabs-map-comments.md)
- [ticket-view-fo-enrichment](../../../../Fixcity/docs/wiki/concepts/ticket-view-fo-enrichment-map-media-comments.md)
- [struttura-e-conflitti](../../struttura-e-conflitti.md)
