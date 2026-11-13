---
title: "BMAD Architecture — Comment system: reply, editor, moderation"
type: architecture
status: superseded
tags: [comment, reply, editor, architecture, dcp-273]
created: 2026-10-26
updated: 2026-10-26
qmd: "architettura   comment enhancements comment"
---

> **Superato (2026-10-26).** Il lavoro e' stato fatto e provato in [cmt-022](../stories/cmt-022.fo-comments-write-reply-markdown-editor.story.md) e documentato in [fo-comments/architecture.md](fo-comments/architecture.md): ADR-006, ADR-007 e ADR-008. Questo testo resta come cronologia.

# Architettura — Comment enhancements

## Componenti coinvolti
| Component | File |
|-----------|------|
| Model: threading | `Modules/Comment/app/Models/Comment.php` (parent_id, nestedComments) |
| Widget: list | `Modules/Comment/app/Filament/Widgets/Commentable/CommentsWidget.php` (getUiConfig) |
| Widget: per-comment | `Modules/Comment/app/Filament/Widgets/Comment/CommentWidget.php` (reply/edit/approve/delete) |
| Widget view: per-comment | `resources/views/filament/widgets/comment/comment.blade.php` (wire:submit="reply") |
| Widget view: new | `resources/views/filament/widgets/commentable/partials/new-comment.blade.php` |
| Theme: blog detail | `Themes/Sixteen/resources/views/components/blocks/blog/detail.blade.php` (noReplies => true) |
| Theme: events detail | `Themes/Sixteen/resources/views/components/blocks/events/detail.blade.php` (noReplies => true) |
| Theme: ticket detail | `Themes/Sixteen/resources/views/components/blocks/ticket-detail/design-comuni.blade.php` (noReplies => false) |
| Admin form | `app/Filament/Resources/CommentResource/Schemas/CommentForm.php` (Textarea, disabled) |

## Design decisions
1. Default: `noReplies => false` (reply enabled) — override via `buildUiConfig` or theme prop
2. Editor: replace `<textarea>` con `<x-filament::forms.components.markdown-editor>` o resolve via `ResolveEditorAction` — keep backend markdown parser pipeline (`ProcessCommentAction`)
3. Schema: extend `CommentForm` con `MarkdownEditor` se si aggiunge pagina admin edit (opzionale)
4. Backward compat: `noReplies` stays as prop, just default `false`
