---
type: note
tags: [documentation]
updated: 2026-09-26
qmd: "audit migration status"
issues: []
discussions: []
title: "comment module migration audit — internalizzazione spatie completata"
created: 2026-06-08
last_updated: 2026-06-11
phase: "cutover"
status: stale
---

> Nota 2026-10-14 (docs-audit): Dichiara l'internalizzazione di spatie/laravel-comments completata e 'packages/spatie/ eliminata'; il 2026-10-14 laravel/Modules/Comment/packages/spatie/ esiste ancora (tracciata in git, ultimo commit 2026-09-28), l'autoload del progetto la risolve (vendor/composer/autoload_psr4.php) e app/Actions/SanitizeCommentTextAction.php importa Spatie\Comments\Support\CommentSanitizer. Contenuto non riscritto, verificare prima di usarlo.

# Comment module — audit post-internalizzazione

## Executive summary

**Stato:** cutover completato (STORY-158). Motore commenti nativo `Modules\Comment\*`; `packages/spatie/` eliminata.

| Area | Stato |
|------|--------|
| Models + Concerns | ✅ standalone |
| Actions (QueueableAction) | ✅ |
| Livewire `comments` / `comments-comment` | ✅ |
| Notifiche + signed routes | ✅ |
| Consumer Fixcity/Blog/User | ✅ import nativi |
| Composer `spatiex/*` | ✅ rimosso |
| Pest | ✅ 5/5 (Comment + TicketCommentsTest) |
| PHPStan L10 `app/` | ⏳ 69 errori (`ConfigCommenti` legacy) |
| Mention autocomplete | ⏳ out of scope v1 |

## Cosa resta (v2)

- `ResolveMentionsAutocompleteAction` + `MentionSearchComponent`
- PHPStan L10 su tutto `Modules/Comment/app`
- Docs storiche con riferimenti Spatie → aggiornamento progressivo

## Riferimenti

- [native-comments-architecture.md](./wiki/concepts/native-comments-architecture.md)
- [adr-internalize-spatie-comments.md](./wiki/decisions/adr-internalize-spatie-comments.md)
- [STORY-158](../../../../docs/stories/STORY-158-native-comments-internalization.md)
