---
created: 2026-09-26
updated: 2026-09-26
issues: []
discussions: []
title: Widget FO — no render custom, empty in Blade
type: rule
tags: [widget, blade, xotbase, spatie-data]
qmd:
  index: true
created_at: 2026-06-10
updated_at: 2026-06-10
status: draft
---

# Widget FO — no render custom

## Stato attuale

- **No** `render()` su `CommentWidget` / `CommentsWidget` / `MentionSearchWidget`.
- **No** `$view` attivo — riga **commentata** come promemoria pigro.
- View auto: `comment::filament.widgets.{domain}.{kebab}` via `GetViewByClassAction`.
- `@if(! $comment instanceof Comment)` in Blade, non PHP.

## UI

Un bag Spatie: `CommentWidgetUiData`, `CommentsWidgetUiData`, `MentionSearchWidgetUiData`.

Dettaglio: [widget-ui-spatie-data](../concepts/widget-ui-spatie-data.md)
