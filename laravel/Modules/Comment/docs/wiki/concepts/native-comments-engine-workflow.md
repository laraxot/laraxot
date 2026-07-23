---
title: "Workflow BMAD — Native Comments Engine (da Spatie a Laraxot)"
type: concept
tags: [comment, bmad, workflow, migration, spatie, laraxot, livewire]
created: 2026-06-06
updated: 2026-06-06
qmd: "comment native engine workflow bmad migrate spatie laravel-comments livewire packages elimination"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/296"
  - "https://github.com/laraxot/module_comment_fila5/issues/3"
discussions: []
related:
  - ../architecture/native-comments-engine.md
  - ./spatie-to-laraxot-namespace-map.md
  - ./spatie-comments-fo-ticket-integration.md
  - ../../../../../../docs/stories/STORY-158-native-comments-engine.md
---

# Workflow BMAD — Native Comments Engine

## Obiettivo

Eliminare `laravel/Modules/Comment/packages/spatie/` e possedere un motore commenti **nativo Laraxot**: `Modules\Comment\*`, convenzioni Xot, Actions, BaseModel, widget Filament FO dove serve.

## Perché (religione)

| Spatie (vendor fork) | Laraxot (target) |
|---------------------|------------------|
| Namespace `Spatie\Comments` | `Modules\Comment` |
| `PackageServiceProvider` | `XotBaseServiceProvider` + `CommentEngineServiceProvider` (manifest `module.json`, non register manuale) |
| `Http\Controllers` signed | Route modulo + Action (no controller monolitici) |
| Config duplicata (core + livewire) | `config/comments.php` unica |
| Livewire UI generica | Livewire FO + **Filament widget** per casi complessi |
| Trait `InteractsWithComments` + `RoutesNotifications` | `InteractsWithComments` modulo (già su `BaseUser`) |

## Inventario sorgente (109 file PHP)

### `laravel-comments` (~33 classi src)

| Cartella | File | Target Laraxot |
|----------|------|----------------|
| `Models` | Comment, Reaction, subscriptions, Concerns | `app/Models/` + `app/Models/Concerns/` |
| `Actions` | Approve, Reject, Process, Notify… | `app/Actions/` + `QueueableAction` |
| `Http/Controllers` | 4 controller signed | `app/Actions/` + `routes/web.php` sottili |
| `Jobs` | 2 job notifiche | `app/Actions/` queued o Job modulo |
| `Notifications` | 2 notification | `app/Notifications/` |
| `Events` | Approved, Rejected | `app/Events/` |
| `Enums` | NotificationSubscriptionType | `app/Enums/` |
| `Support` | Config, Sanitizer, CommentatorProperties | `app/Support/` |
| `CommentTransformers` | Markdown, Mentions | `app/Transformers/` |
| `Exceptions` | 3 | `app/Exceptions/` |

### `laravel-comments-livewire` (~11 classi src)

| Cartella | Target |
|----------|--------|
| `Livewire/*Component` | `app/Livewire/` |
| `Policies/*` | `app/Policies/` |
| `Support/Config` | merge in `app/Support/CommentConfig.php` |
| `resources/views` | `resources/views/` modulo |
| `resources/dist` | build Vite tema o `Modules/Comment` asset |

## Fasi (strangler fig)

### Fase 0 — Workflow + audit ✅

- [x] Workflow (questo file)
- [x] [architecture](../architecture/native-comments-engine.md)
- [x] [namespace map](./spatie-to-laraxot-namespace-map.md)
- [x] Script: `bashscripts/tools/comment/audit-spatie-usage.sh`
- [x] STORY-158 + issue GitHub

### Fase 1 — Fondamenta modulo (settimana 1)

1. `CommentEngineServiceProvider` in `module.json` + `composer.json` — Livewire, route signed, views, policies (sostituisce 2 provider Spatie; vedi [module-providers-manifest.md](./module-providers-manifest.md))
2. Spostare `Enums`, `Exceptions`, `Support` → `app/`
3. `CommentConfig` unificato (legge `config/comments.php`)
4. Alias composer temporaneo: `Spatie\Comments\` → `Modules\Comment\` (classmap o file autoload) **solo** per classi già migrate
5. Test Pest: `CommentEngineBootTest`, `CanCommentContractTest`

### Fase 2 — Dominio (settimana 2)

1. Modelli: `Comment`, `Reaction`, `CommentNotificationSubscription` **non** estendono più `Spatie\*` — estendono `BaseModel` modulo
2. Concerns: `HasComments`, `CanComment`, `InteractsWithComments` in `app/Models/Concerns/`
3. Rinominare `InteractsWithSpatieComments` → `InteractsWithComments` (deprecare alias)
4. Actions con `QueueableAction` per approve/reject/process/notify
5. Migrazione DB: già owner `Modules/Comment/database/migrations/` — allineare schema a modelli nativi

### Fase 3 — UI FO (settimana 3)

1. Livewire: `CommentsComponent` → `Modules\Comment\Livewire\CommentsComponent`
2. Blade views sotto `Modules/Comment/resources/views`
3. Asset: route `comment::scripts` / `comment::styles` (no `laravel-comments-livewire` nel nome)
4. Fixcity `ticket-comments.blade.php`: `<livewire:comment::comments>` o widget Filament `Comment\TicketCommentsWidget`
5. FO: no `route('login')` — `LaravelLocalization::localizeURL('/auth/login')`

### Fase 4 — Consumer + cleanup (settimana 4)

1. Aggiornare `config/comments.php` (root + local) — classi `Modules\Comment\*`
2. `Ticket`, `Article`, `BaseUser` — import nativi
3. Rimuovere `composer.json` require `spatiex/*` e path repositories `packages/spatie`
4. Rimuovere autoload `Spatie\Comments\` → `packages/...`
5. `rm -rf packages/spatie`
6. PHPStan L10 modulo Comment + test Fixcity `TicketCommentsTest`

## Definition of Done

- [ ] Zero file sotto `packages/spatie/`
- [ ] Zero `use Spatie\Comments` / `use Spatie\LivewireComments` nel monorepo (escluso changelog storico)
- [ ] `audit-spatie-usage.sh` → 0 riferimenti
- [ ] `/it/tickets/{id}` commenti read/write
- [ ] Wiki + ingest QMD aggiornati

## Comandi utili

```bash
# Audit riferimenti Spatie
bash bashscripts/tools/comment/audit-spatie-usage.sh

# Test modulo
cd laravel && ./vendor/bin/pest Modules/Comment/tests

# PHPStan
cd laravel/Modules/Comment && composer analyse
```

## Collegamenti

- [STORY-158](../../../../../../docs/stories/STORY-158-native-comments-engine.md)
- [BMAD architecture](../../../../../../docs/wiki/bmad/architecture.md)
- [no-controllers-rule](../../../../../../docs/wiki/rules/no-controllers-rule.md)
