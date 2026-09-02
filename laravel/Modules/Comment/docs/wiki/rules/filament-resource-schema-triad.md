---
title: "Filament Resource — triade Schemas/Tables"
type: rule
module: Comment
tags: [filament, resource, xot, schema, table, infolist, bmad]
created: 2026-06-10
updated: 2026-06-10
qmd: "comment filament resource CommentForm CommentsTable CommentInfolist XotBaseResource triad"
issues:
  - "https://github.com/laraxot/module_comment_fila5/issues/9"
discussions:
  - "https://github.com/laraxot/module_comment_fila5/discussions/11"
related:
  - ../../../../docs/wiki/guidelines/xotbase-extension-rules.md
  - CommentResource.php
---

# Filament Resource — triade Schemas/Tables

## Perché (business)

`CommentResource` modera i commenti FO: lista, approva/rifiuta, bulk. Lo schema UI non va nel `Resource` né nella `Page` — va in classi dedicate riusabili e testabili.

## Regola (religione Laraxot)

Per ogni `XotBaseResource` con modello `Comment`:

| Artefatto | Path | Base class |
|-----------|------|------------|
| Form | `CommentResource/Schemas/CommentForm.php` | `XotBaseResourceForm` |
| Infolist | `CommentResource/Schemas/CommentInfolist.php` | `XotBaseResourceInfolist` |
| Table | `CommentResource/Tables/CommentsTable.php` | `XotBaseResourceTable` |

`XotBaseResource::form()` / `infolist()` **auto-discovery** via convenzione nome:

`{Resource}\Schemas\{Model}Form` · `{Model}Infolist`

## Delegazione Page

`ListComments` delega alla tabella (pattern `ListTickets`):

```php
return (new CommentsTable)->getTableColumns();
```

Mai duplicare colonne/azioni nella Page.

## Resource

`getFormSchema()` delega a `CommentForm::getFormSchema()` (fallback se discovery assente).

Traduzioni: `CommentResource::trans()` — mai `->label()` hardcoded.

## Test

`tests/Feature/Filament/Pages/ListCommentsTest.php` — schema non vuoti + azioni moderazione.

## Collegamenti

- [comment-policy-blade-commentator.md](../concepts/comment-policy-blade-commentator.md)
- [widgets-subject-subfolders](../../../../docs/wiki/rules/widgets-subject-subfolders.md)
