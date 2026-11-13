---
title: "Filament Blueprint — inbound Blog ed Eventi"
type: blueprint
status: draft
created: 2026-10-15
updated: 2026-10-15
module: Blog
theme: Meetup
tags: [bmad, blueprint, filament, inbound-marketing, blog, eventi, ponytail]
related:
  - ../../../../Fixcity/docs/bmad/inbound-marketing/README.md
  - ../../../../Fixcity/docs/bmad/inbound-marketing/purpose.md
  - ../../../../Fixcity/docs/bmad/design-comuni-static-pages-study.md
  - ../../../../Fixcity/docs/bmad/inbound-marketing/afc-formazione-pattern-analysis.md
  - ../inbound-marketing.md
  - ../afc-pattern-adaptation.md
  - ./inbound-blog-events-ponytail-audit.md
qmd: "obiettivo blog"
---

# Obiettivo

Rendere gestibile in Filament il percorso inbound di FixCity: articoli educativi
e appuntamenti pubblici. Il riferimento AFC Formazione mostra il valore di una
promessa verticale, articoli tematici, risorse gratuite e CTA progressive; non
si copiano WordPress, plugin, tracking o copy commerciale.

**Scopo di prodotto (prima del form):** fiducia e adozione B2G, non traffico ads —
vedi [purpose.md](../../../../Fixcity/docs/bmad/inbound-marketing/purpose.md).
**Contratto FO Design Comuni (prima di abilitare):**
[design-comuni-static-pages-study.md](../../../../Fixcity/docs/bmad/design-comuni-static-pages-study.md)
— etichette Novità/Eventi in header/footer JSON; path `/blog`/`/events`.

## Scopo prima del form

Questo Blueprint esiste solo perché lo scopo richiede blog **e** eventi
senza ads. Ogni campo aggiunto deve servire fiducia/capacità/adozione;
altrimenti YAGNI. Dogmi e divieti: purpose SSoT.

## Decisione Ponytail

Riutilizzare `Modules\\Blog\\Models\\Article`, `ArticleResource`, le categorie,
i blocchi editoriali e i campi già presenti `type`, `event_start_date` e
`event_end_date`. Non creare un modulo Eventi, una tabella duplicata o una nuova
Resource finché il modello esistente copre il pilota.

Audit ladder vs codice: [inbound-blog-events-ponytail-audit.md](./inbound-blog-events-ponytail-audit.md).

## Gap vs codice attuale (verificato 2026-10-15)

| Layer | Realtà oggi | Piano Blueprint |
|---|---|---|
| Model / DB | `content_type`, `event_*` fillable/migrati; Parental `type` STI | Nessuna migration nuova |
| Form `ArticleFormMainGridSchema` | Select `content_type=post\|event` + date condizionali | Completato (GO) |
| Table `ArticlesTable` | SelectFilter `post`/`event` | Completato (GO) |
| Infolist | non usare Parental `type` come tassonomia | Ok |
| `ThemeArticleQueries` | `whereIn(post,event)`; comingSoon = event + date | Completato (GO) |

## Stato e limiti

Questo è un piano Filament Blueprint, non codice applicativo. Nessuna migrazione
o Resource nuova è autorizzata. L'iscrizione agli eventi, la newsletter, i
pagamenti, gli sponsor e il tracking sono fuori scope.

## 1. Commands

Nessun comando di scaffolding: la Resource esistente va modificata, non generata.
Prima dell'implementazione verificare dal pannello attivo il namespace e le
policy effettive di `ArticleResource`.

## 2. Models

### Modello esistente

**Model**: `Modules\\Blog\\Models\\Article`  
**Migration**: `Modules/Blog/database/migrations/2024_01_01_000011_create_articles_table.php`  
**Docs**: https://laravel.com/docs/eloquent

Nessun attributo nuovo. Il contratto del pilota è:

| Attributo | Uso inbound | Regola |
|---|---|---|
| `content_type` | `post` oppure `event` | tassonomia editoriale; nullable per compatibilità |
| `Article.type` | `NULL` oppure FQCN figlia | discriminatore Parental STI, mai tassonomia |
| `title`, `slug`, `excerpt`, `description` | discovery e SEO | localizzati secondo il contratto Article esistente |
| `content_blocks` | testo, immagini, CTA e programma | nessun HTML/script arbitrario |
| `published_at`, `closed_at`, `status` | ciclo editoriale | un evento non pubblicato non è pubblico |
| `event_start_date`, `event_end_date` | calendario evento | obbligatori solo quando `content_type = event`; fine >= inizio |
| `category_id`, `author_id` | tassonomia e responsabilità | riusare relazioni esistenti |

La modalità e il luogo dell'evento restano nel contenuto strutturato già
disponibile finché una decisione di dominio non dimostri che servono colonne
separate. Questo evita schema duplicato e migrazioni premature.

### Regola di coerenza

`content_type` è la fonte della decisione editoriale: `post` non mostra un calendario;
`event` richiede intervallo valido e una sezione accessibilità. La stessa regola
vale nel form, nella query pubblica, nel JSON-LD e nei test. Un articolo legacy
con `type` nullo resta leggibile come articolo, senza essere convertito.

## 3. Resources

### Resource esistente

**Resource**: `Modules\\Blog\\Filament\\Resources\\ArticleResource`  
**Location**: `laravel/Modules/Blog/app/Filament/Resources/ArticleResource/`  
**Docs**: https://filamentphp.com/docs/5.x/resources/overview

Modificare solo gli schemi e la tabella necessari:

#### Field: tipo contenuto

**Field**: `Filament\\Forms\\Components\\Select`  
**Docs**: https://filamentphp.com/docs/5.x/forms/select  
**Validation**: nullable; valori editoriali ammessi `post`, `event`; preservare valori legacy (`article`) in sola lettura durante l'edit  

**Config**: `Select::make('content_type')->options(['post' => 'Post / Novità', 'event' => 'Evento'])->live()`;
non usare `reactive()`. **Non** bindare la colonna Parental `type`.
Dominio: **post** agnostico; `content_type=post|event` (vedi
[brainstorming-post-vs-article.md](../brainstorming-post-vs-article.md)).

**Imports:** `Filament\\Schemas\\Components\\Utilities\\Get` e
`Filament\\Schemas\\Components\\Utilities\\Set` soltanto se la visibilità
del campo dipende da stato reattivo; in quel caso usare `->live()`.

#### Field: inizio evento

**Field**: `Filament\\Forms\\Components\\DateTimePicker`  
**Docs**: https://filamentphp.com/docs/5.x/forms/date-time-picker  
**Validation**: nullable per `blog`; required e `date` per `event`  
**Config**: `DateTimePicker::make('event_start_date')->visible(fn (Get $get): bool => $get('content_type') === 'event')`.

#### Field: fine evento

**Field**: `Filament\\Forms\\Components\\DateTimePicker`  
**Docs**: https://filamentphp.com/docs/5.x/forms/date-time-picker  
**Validation**: nullable per `blog`; required, `date` e non precedente a `event_start_date` per `event`  
**Config**: visibilità uguale a `event_start_date`; validazione server-side oltre alla UI.

#### Field: contenuto editoriale

**Field**: componente esistente `Modules\\Blog\\Filament\\Fields\\ArticleContent`  
**Docs**: https://filamentphp.com/docs/5.x/forms/overview  
**Validation**: required secondo il contratto Article esistente  
**Config**: riusare `content_blocks`; i blocchi CTA devono puntare a destinazioni approvate e non contenere tracker.

#### Layout

La griglia principale resta a 2 colonne come l'attuale `ArticleFormMainGridSchema`.
La sezione contenuto resta full-width come l'attuale `ArticleFormSectionsSchema`.
Non annidare due griglie a 2 colonne senza `ColumnSpan: full`.

### Table

**Column**: `Filament\\Tables\\Columns\\TextColumn`  
**Docs**: https://filamentphp.com/docs/5.x/tables/columns/text  
**Config**: aggiungere `content_type` come badge leggibile, `event_start_date` come data
ordinabile e mantenere ricerca/titolo esistenti.

**Filter**: `Filament\\Tables\\Filters\\SelectFilter`  
**Docs**: https://filamentphp.com/docs/5.x/tables/filters  
**Config**: filtro `content_type` con `post`/`event`; non aggiungere una query globale
separata dal modello Article.

### Actions

Nessuna Action custom. Pubblicazione, modifica e cancellazione restano quelle
già governate da `ArticleResource` e dalla policy esistente. Non aggiungere
azioni di invio email o iscrizione in questo piano.

## 4. Authorization

**Policy**: riusare la policy effettivamente associata a
`Modules\\Blog\\Models\\Article`; non inventare un ruolo `marketer`.

- lettura pubblica: solo articoli con stato/pubblicazione già pubblicabili;
- creazione/modifica: stessi permessi del pannello Blog attuale;
- pubblicazione: non introdurre bypass rispetto alla policy esistente;
- contenuti tenant: filtrare secondo il confine già usato dal modulo;
- sponsor o link esterni: nessuna autorizzazione implicita derivata dalla CTA.

Se l'implementazione scopre che il modello Article non è tenant-scoped, il piano
si blocca prima del codice: la soluzione non può esporre contenuti tra Comuni.

## 5. Widgets

Nessun widget nuovo. La lista Article e i filtri esistenti sono sufficienti per
il pilota; un calendario o una dashboard inbound sarebbe YAGNI.

## 6. Pubblicazione nei temi

Il tema Meetup presenta gli eventi e Sixteen può mostrare collegamenti editoriali;
nessun tema inventa date, copy, sponsor o CTA. Le query riusano
`Modules\\Blog\\View\\Composers\\Support\\ThemeArticleQueries` e devono filtrare
`event_start_date` solo per contenuti `content_type = event`.

## 7. Tests

Usare il test harness esistente del modulo e un database isolato.

1. **Form**: `post` non richiede date; `event` richiede inizio e fine.
2. **Boundary**: fine uguale all'inizio è valida; fine precedente è rifiutata.
3. **Legacy**: `content_type = null` o legacy `article` resta modificabile; non entra in liste tipizzate senza fallback.
4. **Table/filter**: il filtro `event` non include i `post` e viceversa.
5. **Query**: un evento futuro compare negli eventi; un post con date evento legacy non compare.
6. **Authorization**: utente senza permesso non crea/modifica; il super-admin non bypassa tenant isolation.
7. **Accessibility**: titolo, date, luogo/modalità e CTA hanno nomi accessibili; reflow e tastiera verificati.
8. **Privacy**: nessun pixel, ad-server, cookie advertising o iscrizione automatica viene emesso dal contenuto.
9. **Inbound**: articolo → evento → contatto usa CTA distinta e verificabile, senza confondere sostegno e servizio civico.

## 8. Verifiche e fallback

Il Blueprint è stato scritto contro sorgente installato e convenzioni del modulo.
La ricerca MCP della documentazione Filament non è stata disponibile; sono stati
usati i file versionati di `vendor/filament/blueprint` e gli URL Filament 5 come
riferimento. Prima dell'implementazione verificare le firme esatte del progetto,
le policy e il comportamento tenant con `reviewing-filament-plans`.

## 9. Gate

Stato `CONCERNS / discovery`: nessuna implementazione finché non sono confermati
owner editoriale, tassonomia `type`, regole tenant e copy delle CTA. Dopo la
validazione, il piano può passare a story BMAD e revisione Blueprint; non si
creano migration o Resource parallele.
