---
title: "Blog Article: content_type e Parental STI"
type: decision-record
status: superseded
updated: 2026-10-27
superseded_by: ../stories/article-type-parental-sti-post-event.story.md
tags: [blog, post, content-type, parental, event]
qmd: "content_type post event Parental type STI Article"
---

# Contratto dati Blog/Eventi

> **Superato (2026-10-27).** Il contratto qui sotto vuole `content_type` come unico campo editoriale e `Article.type` vuoto
> o col nome di una classe. Il codice fa il contrario, per scelta della story
> [article-type-parental-sti-post-event](../stories/article-type-parental-sti-post-event.story.md) (2026-10-26): `Article::$childTypes`
> mappa `post` e `event` su `Post` e `Event`, e ogni query, form, seeder e test usa `type`. Misurato sul DB di sviluppo il 2026-10-27:
> 29 righe, tutte con `type` valorizzato (`post` 18, `event` 11); `content_type` e' una colonna residua, a volte nulla, che il codice
> non legge. Il backfill che ha reso visibili 15 righe con `type` nullo e' in
> [front-office-demo-content-type-backfill](../stories/front-office-demo-content-type-backfill.story.md). Resta qui come cronologia:
> per scrivere codice vale la story Parental.

Il modello Eloquent resta `Modules\Blog\Models\Article`. Nel dominio il
concetto agnostico è **post**. Nel pilota non esiste un modello `Event`
separato.

| Campo | Contratto | Non confondere con |
|---|---|---|
| `content_type` | `post` = Novità/guida/story; `event` = Evento | `Article.type` |
| `Article.type` | `NULL` o FQCN classe figlia (Parental STI) | tassonomia editoriale |
| `event_start_date` / `event_end_date` | intervallo evento | discriminatore del contenuto |

`Article` usa `tightenco/parental` + `HasChildren`: `type` è il discriminatore
STI tecnico. **Non** deve contenere `post`, `event`, `article`, `blog`.
Form, seed e query usano **solo** `content_type`.

Decisione naming: [brainstorming-post-vs-article.md](../brainstorming-post-vs-article.md).

## Query e authoring

- lista Novità: `content_type=post` + stato/pubblicazione;
- lista Eventi: `content_type=event` + stato/pubblicazione;
- eventi futuri: `content_type=event` **e** `event_start_date > now()`;
- date richieste solo per `event`, con fine ≥ inizio;
- `content_type` nullo: non entrare in liste tipizzate senza fallback esplicito.

Filament: campo editabile `content_type`; `Article.type` fuori dal form
editoriale salvo necessità STI.
