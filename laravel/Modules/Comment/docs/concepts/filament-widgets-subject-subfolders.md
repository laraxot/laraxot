---
title: "Filament Widgets — sottocartelle per soggetto (Commentable ≠ Comment)"
type: concept
module: Comment
tags: [filament, widgets, naming, domain-folder, commentable, dry, kiss]
created: 2026-06-10
updated: 2026-06-10
qmd: "widgets subfolder subject Commentable CommentsWidget CommentableWidget alias naming philosophy"
related:
  - ./actions-domain-subfolders.md
  - ./has-comments-implements-commentable.md
  - ../../../../docs/wiki/rules/widgets-subject-subfolders.md
---

# Widgets — sottocartella = soggetto a cui il widget si lega

## Filosofia (lo zen del namespace)

Il namespace racconta **a cosa si aggancia** il widget, non ripete il modulo:

```
Modules\Comment\Filament\Widgets\
├── Comment\      CommentWidget        → opera su UN Comment (public ?Comment $comment)
├── Mention\      MentionSearchWidget  → autocomplete menzioni
└── Commentable\  CommentsWidget       → montato su un MODELLO HOST `implements Commentable`
```

`Comment\Filament\Widgets\Commentable\CommentsWidget` si legge: *modulo Comment →
widget legati a un Commentable → il widget lista+form dei commenti*. Non è
ridondanza: `Comment/` e `Commentable/` sono **soggetti diversi** (il commento
singolo vs il modello che riceve commenti). Stesso pattern delle Actions
([actions-domain-subfolders](./actions-domain-subfolders.md)) e mirror nelle view:
`resources/views/filament/widgets/commentable/comments.blade.php`.

## Decisione 2026-06-10

| Opzione valutata | Esito |
|------------------|-------|
| Appiattire in `Widgets/CommentsWidget` | ❌ perde il soggetto; rompe il mirror view e il pattern Actions |
| Fondere `Commentable/` in `Comment/` | ❌ conflazione di soggetti diversi (host ≠ commento) |
| Rinominare classe in `CommentableWidget` | ❌ il nome dice il soggetto ma non il contenuto (i commenti) |
| **Tenere `Commentable/CommentsWidget`** | ✅ soggetto in cartella, contenuto nel nome classe |

**Alias ritirato:** `CommentableWidget` (thin subclass di CommentsWidget, zero
usi nel codebase) → `CommentableWidget.php.old`. Era lui la fonte di ambiguità:
due nomi per lo stesso widget violano SSOT.

## Regola

1. Nuovo widget nel modulo Comment → sottocartella per **soggetto** esistente o nuova (vocabolario: contratti/modelli del dominio).
2. Nome classe = **cosa mostra/fa**, non il soggetto (già in cartella).
3. View mirror in `resources/views/filament/widgets/{soggetto-kebab}/`.
4. Mai alias/subclass "di cortesia" per dare un secondo nome: un widget, un nome.
