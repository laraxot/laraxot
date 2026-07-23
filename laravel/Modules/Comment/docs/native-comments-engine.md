# Native Comments Engine — hub documentazione

Modulo **Comment** owner del dominio commenti Laraxot. Obiettivo: eliminare `packages/spatie/` e possedere stack morph + Livewire + notifiche.

## Perché

- Commenti su ticket (Fixcity) e articoli (Blog) = cittadinanza attiva, non widget vendor
- Convenzioni Laraxot: `QueueableAction`, `XotBaseServiceProvider`, PHPStan L10, no Services
- Design system Sixteen/Fixcity allineato

## Provider (regola architettura)

`CommentEngineServiceProvider` è in **`module.json`** e **`composer.json`** — non in `CommentServiceProvider::register()`.

→ [wiki/concepts/module-providers-manifest.md](wiki/concepts/module-providers-manifest.md)

## Documentazione canon

| Doc | Scopo |
|-----|--------|
| [wiki/concepts/module-providers-manifest.md](wiki/concepts/module-providers-manifest.md) | Manifest provider nwidart |
| [wiki/concepts/native-comments-architecture.md](wiki/concepts/native-comments-architecture.md) | Architettura target layer |
| [wiki/concepts/native-comments-engine-workflow.md](wiki/concepts/native-comments-engine-workflow.md) | Fasi strangler fig (0–4) |
| [wiki/concepts/spatie-package-inventory.md](wiki/concepts/spatie-package-inventory.md) | Inventario 111 file → mapping |
| [wiki/concepts/spatie-to-laraxot-namespace-map.md](wiki/concepts/spatie-to-laraxot-namespace-map.md) | Namespace Spatie → `Modules\Comment` |
| [wiki/decisions/adr-internalize-spatie-comments.md](wiki/decisions/adr-internalize-spatie-comments.md) | ADR decisione internalizzazione |
| [wiki/concepts/spatie-comments-fo-ticket-integration.md](wiki/concepts/spatie-comments-fo-ticket-integration.md) | Integrazione FO ticket |

## BMAD workflow

Comando: `/bmad/internalize-spatie-comments`  
File: `bashscripts/ai/.agents/commands/bmad/internalize-spatie-comments.md`  
Story: [STORY-158](../../../docs/stories/STORY-158-native-comments-internalization.md)

## Stato implementazione (2026-06-06)

### Fase 1 avviata — core nativo

| Componente | Path | Stato |
|------------|------|-------|
| `CommentConfig` | `app/Support/CommentConfig.php` | ✅ |
| `HasComments` | `app/Models/Concerns/HasComments.php` | ✅ |
| `InteractsWithComments` | `app/Models/Concerns/InteractsWithComments.php` | ✅ |
| `CanComment` | `app/Models/Contracts/CanComment.php` | ✅ |
| Enums/Exceptions | `app/Enums/`, `app/Exceptions/` | ✅ |
| Livewire UI | ancora `Spatie\LivewireComments\*` | ⏳ Fase 3 |
| Actions | ancora config → Spatie | ⏳ Fase 2 |
| `packages/spatie/` | fork path composer | ⏳ Fase 4 delete |

### Audit

```bash
bash bashscripts/tools/comment/audit-spatie-usage.sh
```

## Uso consumer (transizione)

```php
use Modules\Comment\Models\Concerns\HasComments;
use Modules\Comment\Models\Contracts\CanComment;
use Modules\Comment\Models\Concerns\InteractsWithComments;
```

Blade FO (invariato fino a cutover):

```blade
<livewire:comments :model="$ticket" />
<link rel="stylesheet" href="{{ route('comment::assets.styles') }}">
```

## Collegamenti

- [STORY-158](../../../docs/stories/STORY-158-native-comments-internalization.md)
- [ticket-comments.md](ticket-comments.md)
