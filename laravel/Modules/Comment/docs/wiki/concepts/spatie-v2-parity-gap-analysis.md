---
title: "Gap analysis — Spatie laravel-comments v2 vs Modules Comment"
type: concept
module: Comment
tags: [comment, spatie, parity, gap-analysis, v2]
created: 2026-06-10
updated: 2026-06-10
qmd: "spatie laravel-comments v2 parity gap analysis Modules Comment native engine"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/317"
  - "https://github.com/laraxot/module_comment_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/module_comment_fila5/discussions/11"
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/318"
related:
  - spatie-package-inventory.md
  - native-comments-engine-workflow.md
  - spatie-to-laraxot-namespace-map.md
---

# Gap analysis — Spatie v2 vs motore nativo

Reference: [Spatie laravel-comments v2](https://spatie.be/docs/laravel-comments/v2/introduction).

## Matrice funzionalità

| Area Spatie v2 | Stato Laraxot | Gap | Priorità |
|---|---|---|---|
| HasComments trait + comment() | ✅ Models/Concerns/HasComments | — | — |
| Commenti annidati (1 livello) | ✅ parent_id | — | — |
| topLevel() scope | ✅ | — | — |
| original_text / text + transformers | ✅ ProcessCommentAction | Verificare Shiki/highlight | P2 |
| Reazioni react() / deleteReaction() | ✅ su Comment | — | — |
| ReactionCollection::summary() | ⚠️ parziale | Manca count; Reaction non usa collection custom | P1 |
| Notifiche subscribe/unsubscribe | ✅ InteractsWithComments | — | — |
| NotificationSubscriptionType | ✅ | — | — |
| Approval flow + signed routes | ✅ Folio moderation | — | — |
| shouldBeAutomaticallyApproved() granulare | ⚠️ solo flag config | Non verifica getApprovingUsers() | P1 |
| isPending() / isApproved() / scope | ✅ | — | — |
| Policies comment + reaction | ✅ | — | — |
| Livewire CommentsComponent | ✅ | — | — |
| Livewire CommentComponent | ✅ | — | — |
| Livewire MentionSearchComponent | ⚠️ non registrato | Boot provider | P1 |
| Mentions config | ⚠️ mentions.enabled = false | Opt-in tenant | P2 |
| Laravel Nova listing | ❌ N/A | Filament backlog | P3 |
| packages/spatie/ vendored | ⚠️ 1 file residuo | Eliminare | P1 |

## Gap P1 — STORY-291

1. ReactionCollection::summary() con reaction, count, commentator_reacted
2. Reaction::newCollection() → ReactionCollection
3. shouldBeAutomaticallyApproved() granulare
4. Registrare MentionSearchComponent Livewire
5. Rimuovere packages/spatie/

## Collegamenti

- [Workflow internalizzazione](./native-comments-engine-workflow.md)
- [Integrazione FO tema](../../../../Themes/docs/concepts/comment-module-fo-integration.md)
- [STORY-291](../../../../../docs/stories/STORY-291-spatie-v2-parity.md)
