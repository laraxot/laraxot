---
title: "Front office detail 404 and site map page"
type: story
status: review
module: Sixteen
created: 2026-10-26
updated: 2026-10-26
tags: [bmad, story, sixteen, seo, soft-404, sitemap, breadcrumb]
qmd: "soft 404 services view blog view events view detail template sitemap.xml mappa-sito breadcrumb novita eventi servizi categorie"
related:
  - front-office-completeness-audit.story.md
  - ../../../../Modules/Seo/docs/stories/dcp-273-sitemap-con-le-pagine-di-dettaglio-di-blog-ed-eventi.story.md
  - ../../../../Modules/Seo/docs/stories/dcp-274-sitemap-completeness-verification.story.md
issues: []
discussions: []
---

# Front office detail 404 and site map page

Status: **review**

## Why

A browser audit of the front office found three problems. Each one was checked on disk and with a Pest HTTP test before any change.

## Findings and fixes

| # | Finding | Verified | Fix |
|---|---|---|---|
| 1 | `sitemap.xml` lists `/blog/view`, `/events/view`, `/services/view` and no real posts or events | **Already fixed at HEAD** (commit ce04aee28e). `BuildSitemapAction` skips `*.view` slugs, and Blog adds posts and events through `SitemapPathsContract`. The audit ran on an older build. | No code change. New regression test in Seo. |
| 2 | Soft 404: `/it/services/servizio-inesistente` and `/it/services/view` answer 200 | **True, and wider**: `/it/blog/<unknown>`, `/it/blog/view`, `/it/events/<unknown>` and `/it/events/view` also answered 200. The blog and event blocks render nothing when there is no record, but they do not 404. | The shared Folio page `pages/[container0]/[slug0]/index.blade.php` now aborts with 404 when there is no record and the page resolved to the `{container0}.view` template. |
| 3 | `/it/mappa-sito` has no breadcrumb and leaves out Novita, Eventi, Servizi and Categorie | **True** | `components/pages/sitemap.blade.php` uses the shared `content-page.partials.breadcrumb` (as `components/pages/categories` does). A first group links the main sections through `FrontofficeRoutes`. |

## Acceptance

- Unknown or template detail slugs under `blog`, `events` and `services` answer 404. Published posts and events still answer 200.
- `/it/mappa-sito` shows `data-element="breadcrumb"` and links to `FrontofficeRoutes::blogIndex()`, `eventsIndex()`, `servicesIndex()` and `index('lista-categorie')`.
- `sitemap.xml` has no `*/view` URL and lists published posts and events in every language.

## Tests

- `Themes/Sixteen/tests/Feature/Pages/DetailNotFoundTest.php`
- `Themes/Sixteen/tests/Feature/Pages/SitemapPageTest.php`
- `Modules/Seo/tests/Feature/SitemapDetailTemplatesTest.php`

## Open (other owners)

- The header partials `user-dropdown` and `personal-area-guest-parity` call `route('services.categories')`. That name is only declared in `pages_old/`, and `Route::has('services.categories')` is false in tests. The categories page lives at `/{lang}/lista-categorie`.
- `pages/ricerca.blade.php` breaks the theme page rule (`NoSemanticFolioPageDirectoriesTest` fails on it). It came in with ce04aee28e.
- On dev, the sitemap still cannot list articles until `articles.content_type` is migrated (see the audit story, open item 1).
