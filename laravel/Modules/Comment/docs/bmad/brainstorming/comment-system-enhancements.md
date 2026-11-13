---
title: "BMAD Brainstorming — Comment system: replies, rich editor, moderation"
type: brainstorming
status: superseded
tags: [comment, brainstorming, reply, editor, dcp-273]
created: 2026-10-26
updated: 2026-10-26
qmd: "brainstorming   comment system enhancements comment"
---

> **Superato (2026-10-26).** Il lavoro e' stato fatto e provato in [cmt-022](../../stories/cmt-022.fo-comments-write-reply-markdown-editor.story.md) e documentato in [../fo-comments/decision-log.md](../fo-comments/decision-log.md): il registro delle decisioni del 2026-10-26. Questo testo resta come cronologia.

# Brainstorming — Comment system enhancements

## Contesto
Il sistema commenti usa Filament Livewire widgets (`CommentsWidget`, `CommentWidget`). Il modello `Comment` supporta il threading (`parent_id`). Ma in FO:
1. **Reply is disabled in blog/events** (`noReplies => true` hardcoded in themes)
2. **No rich editor** — plain textarea, nessuna toolbar markdown
3. **No inline moderation form action** per approvazione/rifiuto rapido

## Opzioni esplorate
1. **Reply on/off per contento** → configurabile in JSON CMS (`commentConfig`) — scelta
2. **Editor** → `Filament\Forms\Components\MarkdownEditor` o TinyMCE via `ResolveEditorAction` config — scelta
3. **Moderation inline** → `Actions\ApproveCommentAction` in widget (esistente) + pulsante in form schema — miglioramento

## Decisioni
- Il reply deve essere abilitato per default in blog/events (era solo ticket)
- L'editor deve supportare markdown con anteprima live
- La moderazione stay widget-level (non admin form), keep Filament way

Vedi: `wiki/concepts/comment-threading.md`, `wiki/concepts/comment-rich-editor.md`.
