---
title: "Spatie laravel-comments v2 — Analisi Gap Residuo"
type: concept
module: Comment
tags: [comment, spatie, gap-analysis, parity, moderation, mentions, reactions]
created: 2026-06-10
updated: 2026-06-10
qmd: "spatie laravel-comments v2 residual gap analysis comment module parity filament moderation mentions syntax highlighting test coverage"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/319"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/320"
related:
  - ../wiki/concepts/spatie-v2-parity-gap-analysis.md
  - ../wiki/concepts/spatie-package-inventory.md
  - ../migrate-spatie-comments-to-custom.md
---

# Spatie laravel-comments v2 — Analisi Gap Residuo

## Contesto

`Modules\Comment` internalizza `spatie/laravel-comments` v2. Fasi 1‑4 DONE (models, actions, transformers, notifications, Livewire, migrations, policies, sanitizer).

Reference: [Spatie laravel-comments v2](https://spatie.be/docs/laravel-comments/v2/introduction).

## Matrice feature → stato (2026-06-10)

| Feature spatie v2 | Stato | Note |
|---|---|---|
| Livewire comments component | ✅ | `CommentsComponent`, `CommentComponent` |
| Markdown → HTML + sanitization | ✅ | `MarkdownToHtmlTransformer`, `CommentSanitizer` |
| Reactions | ✅ | `react()`, `deleteReaction()`, `ReactionCollection::summary()` |
| Approval flow + signed routes | ✅ | Folio `signed/approval/*` |
| **Filament moderation resource** | ✅ | `CommentResource` + `ListComments` (bulk approve/reject) |
| Notifications pending/approved | ✅ | Jobs + mail views |
| Subscription opt-out | ✅ | `CommentNotificationSubscription` |
| @mentions | ⚠️ | Infra pronta; `mentions.enabled=false` — decisione #320 |
| Syntax highlighting | ❌ out-of-scope | Raro su ticket civic; decisione #320 |
| **Copertura test** | ✅ | 17 test Pest (`CommentEngineParityTest` + unit esistenti) |
| **Docs tema FO** | ✅ | `Themes/docs/concepts/comment-module-fo-integration.md` |

## Gap aperti

### G2 — @mentions (Media)

`MentionsTransformer` + `MentionSearchComponent` registrati; opt-in tenant via `config/comments.php`.

### G3 — Syntax highlighting (Bassa / out-of-scope)

Nessun integratore Shiki/Torchlight. Valutare solo se emerge richiesta reale.

### Conflitto noto Ticket::subscribers()

`Ticket` espone `subscribers()` BelongsToMany che **ombra** `HasComments::subscribers()` — le notifiche approved su ticket FO possono richiedere rename/alias (backlog separato).

## Tracciamento

- Epic: [#319](https://github.com/laraxot/base_fixcity_fila5/issues/319)
- Discussion: [#320](https://github.com/laraxot/base_fixcity_fila5/discussions/320)
- Story: [STORY-291](../../../../docs/stories/STORY-291-comment-spatie-v2-parity.md)
