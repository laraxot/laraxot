---
id: blog-events-listing-body-and-search
title: "Novità ed eventi raggiungibili, corpo completo, ricerca che invia"
type: story
status: done
created: 2026-10-26
updated: 2026-10-26
qmd: "blog events pagination article body search modal"
issues: []
discussions: []
related:
  - blog-public-index.story.md
  - events-public-index.story.md
---

# Story

Come cittadino voglio raggiungere ogni notizia ed evento pubblicato, leggere tutto il loro contenuto
e usare la ricerca del sito, così trovo le informazioni senza vicoli ciechi.

## Problemi verificati (audit)

1. `/blog`: `ThemeArticleQueries::paginatedPosts()` usava `simplePaginate` (niente `total`/`lastPage`) e il
   blocco `blocks/blog/index` tagliava la pagina a 6 voci (`take(6)`). Il pager mostrava solo la pagina 1.
2. `/events`: `paginatedEvents()` ordinava per data crescente (prima gli eventi passati più vecchi) e la
   pagina non aveva pager.
3. Dettaglio post ed evento: il corpo mostrava solo i blocchi `paragraph` (stampati senza sanificazione) e
   la figura cercava il tipo `image`, mentre l'editor (`ArticleContent`) salva `image_spatie`.
4. Modale di ricerca Design Comuni: `<form>` senza `action`, campo `name="autocomplete-two"`, bottone
   `type="button"`. Nessun invio possibile.

## Decisioni

- Paginazione length-aware (`paginate()`) per post ed eventi. Le voci in evidenza sono escluse nella query
  (`$excludeIds`), non dopo la pagina: pagine piene e totale coerente.
- Pager condiviso `Themes/Sixteen/.../blocks/partials/dc-pagination.blade.php`, markup Design Comuni
  `pagination-wrapper`, `aria-current`, `rel=prev/next`, bersagli 44px, etichette `blog::front_office.pagination.*`.
- Eventi: prima i prossimi o in corso (inizio più vicino prima), poi i passati (più recente prima), poi quelli
  senza data. Un evento è passato quando la fine (o, senza fine, l'inizio) è prima di adesso.
- Corpo: `Modules\Blog\Actions\Article\ResolveArticleBodyAction` normalizza tutti i blocchi; il partial
  `blocks/partials/article-body` li passa al renderer UI esistente `<x-render.blocks>`. Per `paragraph`,
  `title` e `image` la vista la sceglie il tema (paragraph sanificato via `ui::components.blocks.paragraph.simple`).
  Le immagini usano la chiave `src`: il renderer localizza le chiavi `url` come link di pagina.
- `<x-render.blocks>` da solo non bastava: richiede `view` e salta i blocchi senza `data.view` (l'editor non
  chiede la vista per `image_spatie`). Per questo i default per tipo stanno nel partial del tema.
- Ricerca: GET verso l'elenco pubblico delle segnalazioni (`FrontofficeRoutes::ticketsIndex()`, filtro `?q=` di
  `ListPublicTicketsAction`), come la modale non Design Comuni. Non esiste una pagina di ricerca globale.
  I commenti dei dettagli non sono stati toccati (owner diverso).

## Acceptance criteria

- [x] 15 post con 12 per pagina: totale 15, 2 pagine, la pagina 2 ha 3 voci.
- [x] Le voci escluse non compaiono e non contano nel totale.
- [x] Eventi: prossimi in ordine crescente, poi passati dal più recente.
- [x] Pager con `page=2`, `aria-current="page"`, `rel="next"` su Novità ed Eventi.
- [x] Corpo con titoli, paragrafi sanificati, immagini `image` e `image_spatie`, didascalia e credito.
- [x] Modale: `method="get"`, `action` elenco segnalazioni, `name="q"`, label, `type="submit"`.

## Verifica

- `PEST_OUT=... bash bashscripts/tools/pest-scratch-db.sh --test-directory=Modules/Blog/tests Modules/Blog/tests/Feature/ThemeArticleListingTest.php Modules/Blog/tests/Feature/ResolveArticleBodyActionTest.php`
- `PEST_OUT=... bash bashscripts/tools/pest-scratch-db.sh --test-directory=Themes/Sixteen/tests Themes/Sixteen/tests/Feature/Components/ArticleBodyAndSearchModalTest.php`
- PHPStan sui file PHP toccati: 0 errori.

## Note aperte

- Il database di sviluppo non ha la colonna `articles.content_type` (migrazione Blog `2026_10_15_000001` pendente):
  le liste pubbliche falliscono finché non si esegue `php artisan migrate`. Il test la applica sul DB scratch.
- `BlogFrontOfficeNovitaTest` e `BlogFrontOfficeEventsTest` falliscono già prima di questa story sul DB scratch
  (`DemoContentSeeder` scrive `categories` con chiave primaria uuid di Fixcity).
- L'etichetta della modale dice "Cerca nel sito" ma cerca solo nelle segnalazioni: serve una pagina risultati
  globale (riferimento `risultati-ricerca.html`) in una story futura.
