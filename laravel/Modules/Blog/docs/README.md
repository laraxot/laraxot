---
title: "Blog: ingresso del modulo"
type: documentation
status: active
module: Blog
tags: [blog, module, docs]
created: 2026-09-26
updated: 2026-10-14
qmd: "blog modulo articoli categorie banner text widget blocchi filament twentyone cms limiti legacy predict"
issues: []
discussions: []
---

# Blog

Ingresso della documentazione del modulo. Ogni affermazione e' stata confrontata con il codice il 2026-10-14.

## Identita' e ownership

- Namespace `Modules\Blog`, alias `blog`, repository git separato. Descrizione in `module.json`: blog, articoli, categorie e contenuti editoriali.
- Provider in `module.json`: `BlogServiceProvider`, `Providers\Filament\AdminPanelProvider`; il primo e' anche in `laravel/config/app.php`. `Blog: true` in `laravel/modules_statuses.json` e nello stato del tenant Fixcity. Dipendenze dichiarate: Xot, UI, Lang, Cms.
- Il testo precedente di questo README ("Core module for the FixCity Platform") era un modello generico e non corrisponde all'uso: vedi sotto.

## Cosa contiene davvero

| Area | Contenuto verificato | Percorso |
|---|---|---|
| Modelli | `Article`, `Category`, `Banner`, `TextWidget`, `Tag`, `Profile`, `Transaction`, `UpvoteDownvote`, `PostView`, `Comment`, `Menu` e pivot | `app/Models/` |
| Actions | `Article/` (formattazione date e tempo di lettura, immagine e miniatura, traduzione, import da JSON), `Banner/`, `Category/GetBloodline`, `ImportFromNewsApi`, `ParentChilds/GetTreeOptions` | `app/Actions/` |
| Filament | risorse `Article`, `Banner`, `Category`, `Profile`, `TextWidget`; 11 blocchi (`ArticleCard`, `ArticleList`, `BannerAndSlides`, `Chart`, `Filter`, `Leaderboard`, `Search` ...); widget `LatestArticlesWidget` | `app/Filament/` |
| Livewire | `Article/Chunk`, `Article/Lists`, `Headernav/SearchBar`, `Profile`, `Profile/Setting` | `app/Http/Livewire/` |
| Comandi | `ShowArticleCommand`, `ShowArticleListCommand` | `app/Console/Commands/` |
| Dati | 18 file in `database/migrations/` (alcuni con estensione `.to_article`, `.to_cms`, `.to_article_model`: non eseguiti) | `database/migrations/` |
| Test | 1 file con 1 test | `tests/` |

## Chi lo usa davvero in questo repository

- Cms: `Modules/Cms/app/Config/xra.php` mappa `article` e `category` ai modelli Blog.
- Fixcity: solo `Modules/Fixcity/config/morph_map.php`, che elenca `Article` e `Banner`. Nessun codice di `Modules/Fixcity/app` importa Blog.
- Tema TwentyOne: `pages/articles/index`, `pages/articles/[slug]` e `pages/search` usano i modelli Blog. Il tema Sixteen, quello di Fixcity, ha solo stub in `pages_old/` (estensione `.stub`), non attivi.
- Blog usa a sua volta Comment (`Article` usa `HasComments`) e Rating (7 file importano classi Rating).
- Seo: `Blog\Actions\Article\ListPublishedArticlePathsAction` implementa `Seo\Contracts\SitemapPathsContract` ed e' registrata con tag in `BlogServiceProvider::register()` (DCP-273). Elenca `blog/{slug}` ed `events/{slug}` con le stesse query di `ThemeArticleQueries` usate dal dettaglio. La dipendenza va Blog -> Seo; Seo non importa Blog.

## Limiti

1. Rotte: `routes/web.php` e `routes/api.php` sono vuoti; `php artisan route:list --json` mostra 23 rotte, tutte del pannello Filament `blog/admin`. Le pagine Folio del modulo (`resources/views/pages/blog/[slug].blade.php` e `events/[slug].blade.php`, route name `blog.show` e `events.show`, visibili con `php artisan folio:list`, non con `route:list`) sono **oscurate** dalla pagina del tema Sixteen `[container0]/[slug0]/index.blade.php`, che serve il dettaglio di articoli ed eventi (misurato 2026-10-26: uno slug inventato risponde 200 «Novita'» dal tema, una pagina di questo modulo darebbe 404). Non vanno cancellate: `route('events.show', ...)` e' usata da due template del tema. Vedi [page-directory-structure del tema](../../../Themes/Sixteen/docs/page-directory-structure.md).
2. Residui di un altro progetto: `app/resources_to_delete/` contiene 112 file, tra cui viste `play_money_markets` (mercati predittivi), e il nome della cartella dice che vanno eliminati. Molti documenti rimandano a un modulo `Predict` che non esiste in `laravel/Modules`.
3. Duplicati dentro `app/`: `app/config/config.php` e `app/Config/config.php` sono identici; `app/lang/` convive con `lang/`; `app/Models/Article.bak`.
4. Documentazione: [ai.md](./ai.md), [comment.md](./comment.md) e [themes.md](./themes.md) sono elenchi di URL, non descrivono integrazioni.
5. Test: 6 file in `tests/` (misurato 2026-10-26). `BlogFrontOfficeNovitaTest` e `BlogFrontOfficeEventsTest` (10 test) sono **rossi** gia' sulla baseline: `DemoContentSeeder:28` fa `Category::firstOrCreate` senza `id` e `categories.id` e' `varchar(191)` senza default, quindi la seconda categoria da' `Duplicate entry ''`. Non dipende dalla sitemap (DCP-273). Da decidere chi possiede la forma di `categories` (la migration del Blog crea `id()` + `title`, il DB reale ha chiave stringa + `name`).

## Quick start

Da `laravel/`:

```bash
# rotte del modulo (verificato il 2026-10-14: 23, tutte del pannello Filament blog/admin)
php artisan route:list --name=blog
# test su database usa-e-getta (la variabile vuota evita `unbound variable` dello script)
DB_DATABASE_LIMESURVEY= bash ../bashscripts/tools/pest-scratch-db.sh Modules/Blog/tests
```

## Mappa della documentazione

Indice operativo: [00-INDEX.md](./00-index.md). Decisioni: [decision-log.md](./decision-log.md) (vuoto, solo intestazione). Registro: [wiki/log.md](./wiki/log.md). Story: [stories/](./stories/).

## Post agnostico

[brainstorming-post-vs-article.md](./brainstorming-post-vs-article.md) · rename deferred [IM-BLOG-008](./stories/inbound-rename-article-to-post.story.md).

## Story aperte sui contenuti demo (2026-11-12)

- [Catalogo demo: la sintesi e' una sola stringa italiana (F-08)](./stories/demo-catalog-summary-localization.story.md): decisione aperta, lato dati di [F-07 nel Cms](../../Cms/docs/stories/untranslated-detail-pages-indexing.story.md). Discussione con le decisioni: [#95](https://github.com/laraxot/theme_sixteen_fila5/discussions/95).

Nota: `index.md` e `00-index.md` si rimandano a vicenda come «canonico» (e `index.md` punta a `00-INDEX.md`, che con questo nome non esiste): l'ingresso reale e' questo README.
