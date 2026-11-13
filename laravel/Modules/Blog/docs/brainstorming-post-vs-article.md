---
title: "Brainstorming — post agnostico vs Article / content_type"
type: brainstorming-report
status: decision
created: 2026-10-15
updated: 2026-10-15
tags: [bmad, blog, post, article, content-type, parental, ponytail]
qmd: "post agnostico Article content_type post event Parental type STI"
issues: []
discussions: []
---

# Domanda

«Article» è troppo specifico. **Post** è più agnostico: può essere novità,
guida, caso studio o evento. Come modellarlo senza rompere Parental né
rinominare tutto il modulo?

# Tre piani (non confonderli)

| Piano | Nome | Ruolo |
|---|---|---|
| **Linguaggio prodotto / FO** | post | Unità editoriale agnostica (Design Comuni: Novità / Eventi) |
| **Modello Eloquent** | `Modules\Blog\Models\Article` | Classe esistente (tabelle, Resource, morph Comment) |
| **Discriminatore di genere** | `content_type` | `post` \| `event` — **mai** la colonna Parental `type` |

```text
Post (concetto)
├── content_type = post     → novità / guida / storytelling (FO: Novità)
└── content_type = event    → appuntamento datato (FO: Eventi)
```

# Opzioni discusse

| Opzione | Pro | Contro | Esito |
|---|---|---|---|
| A. Rinominare model `Article` → `Post` ora | Naming perfetto | Breaking: morph, Filament, factory, STI Parental, docs | **NO ora** (story dedicata) |
| B. Solo linguaggio «post», lasciare `content_type=article` | Zero codice | Vocabolario DB ancora «article» | Debole |
| C. `content_type=post\|event` + model `Article` | Agnostico nel dato; Ponytail sul codice | Classe ancora chiamata Article | **GO** |
| D. Usare Parental `type=article\|event` | Un solo campo | `type` è STI → FatalError `Class "article"` | **VIETATO** |

# Perché «post» batte «article» come valore

- *Article* suggerisce pezzo giornalistico; *post* include guida, FAQ lunga,
  caso prima/dopo, briefing.
- La coppia **post | event** è simmetrica e leggibile in query/Filament.
- Il FO resta Design Comuni: etichette **Novità** / **Eventi**, path `/blog` /
  `/events`.

# Perché non rinominare subito la classe

Existing Assets First: `ArticleResource`, morph Comment, seeders, Parental
`HasChildren` sulla stessa tabella. Un rename è epica a sé (IM-BLOG-008), non
bloccante per demo/inbound.

# Decisione (GO)

1. Nel dominio e nei docs dire sempre **post**.
2. In DB/API Filament: `content_type` ∈ {`post`,`event`}.
3. Migrare i seed/demo da `article` → `post` (idempotente).
4. Model PHP resta `Article` finché non esiste story di rename.
5. Colonna `type` = solo STI Parental; non usarla come tassonomia editoriale.

# Criterio di upgrade (rename Article → Post)

Aprire story solo se: (a) zero child STI sulla tabella, o mappa Parental
allineata; (b) piano morph/Comment; (c) finestra senza demo live.

# Collegamenti

- [brainstorming-event-module-vs-article-type.md](../../Fixcity/docs/bmad/inbound-marketing/brainstorming-event-module-vs-article-type.md)
- [brainstorming-demo-blog-eventi.md](../../Fixcity/docs/bmad/inbound-marketing/brainstorming-demo-blog-eventi.md)
- [blueprints/inbound-blog-events.md](./blueprints/inbound-blog-events.md)
- [parental-sti-architecture.md](./parental-sti-architecture.md)

## Risposta a «article = post con type=article»

L’intuizione è giusta sul **concetto** (la novità è una specie di post), ma
il nome del campo è sbagliato:

| Interpretazione | Esito |
|---|---|
| `Article.type = 'article'` (colonna Parental) | **VIETATO** — STI cerca la classe PHP `article` |
| `content_type = 'article'` (tassonomia) | **NO** — asimmetrico vs `event`, suona giornalismo, doppio vocabolario |
| Concetto «novità ⊂ post» + `content_type = 'post'` | **SÌ** — post agnostico; FO etichetta Novità |

Quindi: non «post con type=article», ma **post con `content_type=post`**
(e evento con `content_type=event`). La classe PHP può ancora chiamarsi
`Article` senza contraddire il dominio.

```text
cittadino vede: Novità / Eventi
dominio dice:   post  / event   (agnostico)
PHP oggi:       Article (rename = IM-BLOG-008)
DB editoriale:  content_type
DB STI:         type (solo FQCN o null)
```
