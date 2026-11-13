---
title: "BMAD Planning — Comment system enhancements"
type: planning
status: superseded
tags: [comment, planning, reply, editor]
created: 2026-10-26
updated: 2026-10-26
qmd: "planning   comment enhancements comment"
---

> **Superato (2026-10-26).** Il lavoro e' stato fatto e provato in [cmt-022](../../stories/cmt-022.fo-comments-write-reply-markdown-editor.story.md) e documentato in [../fo-comments/epics.md](../fo-comments/epics.md): l'Epic E. Questo testo resta come cronologia.

# Planning — Comment enhancements

## Fase 1: Reply enablement
- File: `blog/detail.blade.php`, `events/detail.blade.php`
- Change: `'noReplies' => true` → `'noReplies' => false`
- Verifica: reply a un commento esegue submit (`CommentWidget::reply()`), `parent_id` popolato

## Fase 2: Rich editor (markdown)
- File: `resources/views/filament/widgets/commentable/partials/new-comment.blade.php`
- Change: `<textarea wire:model="ui.text">` → `<x-filament.forms.field-wrapper><textarea class="markdown-editor"...>` + toolbar markdown
- File: `CommentWidget.php` sezione reply/edit
- Backend: `ProcessCommentAction` usa `ResolveMarkdownParserAction` — niente da cambiare, input markdown → output HTML

## Fase 3: Test
- `grep` SeoLinksTest / Comment tests per reply e editor
- Pest: reply crea nested comment con parent_id corretto

> Priority: Fase 1 (reply) — 2 file, 2 righe; Fase 2 (editor) — 1-2 file blade
