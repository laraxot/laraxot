---
title: "Filament Resource — Schemas/Tables Zen pattern"
type: concept
module: Comment
tags: [filament, resource, xot, form, infolist, table, zen, moderation]
created: 2026-06-10
updated: 2026-06-10
qmd: "Filament Resource Schemas CommentForm CommentInfolist CommentsTable XotBaseResource zen"
issues:
  - "https://github.com/laraxot/module_comment_fila5/issues/20"
discussions:
  - "https://github.com/laraxot/module_comment_fila5/discussions/14"
related:
  - ./filament-widgets-subject-subfolders.md
  - ./xot-base-filament-widgets.md
---

# Filament Resource — religione Schemas/Tables

## Perché esistono tre file

`XotBaseResource` **risolve automaticamente** le classi per convenzione:

| Artefatto | Path | Classe |
|-----------|------|--------|
| Form | `{Resource}/Schemas/{Model}Form.php` | `CommentForm` |
| Infolist | `{Resource}/Schemas/{Model}Infolist.php` | `CommentInfolist` |
| Table | `{Resource}/Tables/{Plural}Table.php` | `CommentsTable` |

Senza questi file il resource usa fallback inline (`getFormSchema()` vuoto) o colonne duplicate nella Page — **violazione DRY** e drift tra lista/edit/view.

## Filosofia (zen)

1. **Resource** = routing, model, navigation — niente schema inline
2. **Schemas/** = forma dati (form + infolist read-only)
3. **Tables/** = colonne + filtri — **no** azioni dominio (approve/reject restano in `ListComments`)
4. **Pages/** = orchestrazione: `(new CommentsTable)->getTableColumns()` + azioni QueueableAction
5. **Label** → LangServiceProvider (`comment::comment-resource.fields.*`) — no `->label()` salvo azioni con `trans()`

## CommentResource (canon)

```
CommentResource.php          # model + pages
Schemas/CommentForm.php      # moderazione read-only
Schemas/CommentInfolist.php  # vista record
Tables/CommentsTable.php     # lista moderazione
Pages/ListComments.php       # approve/reject + bulk
```

## Anti-pattern

- Schema Filament nel resource `getFormSchema()` quando esiste `CommentForm`
- Duplicare colonne in Page e Table
- `->label()` hardcoded su TextColumn (usa lang espansa)
- Azioni approve nel Table class (dominio → Page + Action)

## Checklist nuovo resource modulo

- [ ] `{Model}Form extends XotBaseResourceForm`
- [ ] `{Model}Infolist extends XotBaseResourceInfolist`
- [ ] `{Plural}Table extends XotBaseResourceTable`
- [ ] List page delega colonne/filtri al Table
- [ ] Lang `fields.*` per ogni chiave colonna
