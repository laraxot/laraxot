# Comment Module Concepts

## Native comments (target — STORY-158)

Epic per eliminare `packages/spatie/` e internalizzare il dominio commenti.

| Doc | Scopo |
|-----|--------|
| [wiki/concepts/native-comments-architecture.md](../wiki/concepts/native-comments-architecture.md) | Architettura target |
| [wiki/concepts/spatie-package-inventory.md](../wiki/concepts/spatie-package-inventory.md) | Mapping file Spatie → module |
| [wiki/decisions/adr-internalize-spatie-comments.md](../wiki/decisions/adr-internalize-spatie-comments.md) | ADR |
| [wiki/concepts/spatie-comments-fo-ticket-integration.md](../wiki/concepts/spatie-comments-fo-ticket-integration.md) | Stato FO ticket (pre-migrazione) |

Workflow: `/internalize-spatie-comments` (`.claude/commands/bmad/`)

## Current Implementation (transitorio)
| [comments-widget-livewire-mount.md](./comments-widget-livewire-mount.md) | Mount FO `commentableType`/`commentableKey` |
| [comment-policy-blade-commentator.md](./comment-policy-blade-commentator.md) |
| [filament-resource-schemas-zen.md](./filament-resource-schemas-zen.md) | Resource Schemas/Tables zen (CommentForm/Infolist/Table) | Policy `see()` + `instanceof CanComment` in Blade |
| [filament-widgets-subject-subfolders.md](./filament-widgets-subject-subfolders.md) | Perché Commentable/ ≠ Comment/ |

### Models
- `Modules\Comment\Models\Comment` extends `Spatie\Comments\Models\Comment` → **da rimuovere**
- Connection DB: `comment`

### Livewire FO
- `<livewire:comments :model="$record" />` — alias Spatie Livewire

### Consumer
- Fixcity `Ticket` + `HasComments`
- Blog `Article`
- User `CanComment` via `InteractsWithSpatieComments`

## Integration Points

| Entity | Module | Route |
|--------|--------|-------|
| Ticket | Fixcity | `/it/tickets/{id}` |
| Article | Blog | `/it/articles/{slug}` |

## Database

Migration owner: `database/migrations/2024_01_01_000010_create_comments_table.php` — **data sacred**, no destructive changes.

## Quality gate (modulo owner)

| Doc | Scopo |
|-----|--------|
| [phpstan-module-owner-scope.md](./phpstan-module-owner-scope.md) | PHPStan da `laravel/` su `Modules/Comment` |
| [no-jobs-queueable-action-only.md](./no-jobs-queueable-action-only.md) | Async senza Jobs |
| [no-livewire-filament-widgets-only.md](./no-livewire-filament-widgets-only.md) | UI FO via Filament widgets |
| [xot-base-filament-widgets.md](./xot-base-filament-widgets.md) | Gerarchia XotBaseSchemaWidget |
| [phpstan-zero-errors-pest-assert.md](./phpstan-zero-errors-pest-assert.md) | 0 errori PHPStan inclusi test (Assert statico) |
| [widget-ui-spatie-data.md](./widget-ui-spatie-data.md) | UI widget in Spatie Data Wireable |
| [filament-widgets-subject-subfolders.md](./filament-widgets-subject-subfolders.md) | Sottocartelle Widgets per soggetto (Commentable ≠ Comment); alias vietati |
| [has-comments-implements-commentable.md](./has-comments-implements-commentable.md) | HasComments ⇒ `implements Commentable` (altrimenti 500 dal widget) |
