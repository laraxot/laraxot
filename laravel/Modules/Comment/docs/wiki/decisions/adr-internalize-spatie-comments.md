---
title: "ADR — Internalizzare Spatie comments nel modulo Comment"
type: decision
module: Comment
tags: [adr, comment, spatie, internalization]
created: 2026-06-06
updated: 2026-06-06
status: accepted
issues:
  - "https://github.com/laraxot/module_comment_fila5/issues/4"
  - "https://github.com/laraxot/base_fixcity_fila5/issues/297"
related:
  - adr-models-contracts-vs-app-contracts.md
  - ../concepts/native-comments-architecture.md
  - ../concepts/spatie-package-inventory.md
---

# ADR: Internalizzare Spatie Laravel Comments

## Contesto

Il modulo Comment vendorizza fork in `packages/spatie/` con namespace `Spatie\Comments` e dipendenze composer path. Fixcity FO ticket, Blog articles e User CanComment dipendono da questo stack.

## Decisione

**Portare** il codice necessario in `Modules\Comment` con namespace nativo, **eliminare** `packages/spatie/`, mantenere schema DB e alias Livewire `<livewire:comments>` per compatibilità.

## Alternative considerate

| Opzione | Pro | Contro |
|---------|-----|--------|
| Restare su fork Spatie | Zero lavoro | Debito, namespace esterno, UI disallineata |
| Composer packagist Spatie ufficiale | Upstream | Stesso namespace, meno controllo tenant |
| **Internalizzazione nativa** | Controllo, Laraxot conventions, UI | Effort multi-sprint |
| Comment system from scratch | Design pulito | Rischio regressioni, reinvent wheel |

## Conseguenze

- Epic STORY-158 multi-fase (workflow `/internalize-spatie-comments`)
- Consumer aggiornano import trait/model
- Documentazione owner in `Modules/Comment/docs`
- Miglioramenti: Filament moderation, tema Sixteen, Notify IT

## Compliance regole progetto

- Actions con `QueueableAction` — no Services
- PHPStan L10
- Migration owner module — no destructive DB
- BMAD story + GitHub issue base + module

## Backlink

- [Architettura](../concepts/native-comments-architecture.md)
- [Inventario](../concepts/spatie-package-inventory.md)
