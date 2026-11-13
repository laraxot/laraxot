---
title: "Sitemap + Folio routing per blog (`/it/blog/{slug}`)"
type: concept
status: active
tags: [blog, sitemap, folio, dcp-273]
created: 2026-10-26
updated: 2026-10-26
qmd: "blog - perché `/it/blog/{slug}` è nella sitemap blog"
---

# Blog - Perché `/it/blog/{slug}` è nella sitemap

Il modulo Blog non possiede una pagina Folio. Il dettaglio di un articolo/pubblicazione è servito dalla stessa `pages/[container0]/[slug0]/index.blade.php` del tema Sixteen, con `container0=blog`.

`ThemeArticleQueries::publishedPostSlugs()` (o `publishedEventSlugs()`) restituisce i `slug` pubblici. `ListPublishedArticlePathsAction::execute()` li trasforma in `blog/{slug}` o `events/{slug}`.

Il contratto `SitemapPathsContract` è registrato nel `BlogServiceProvider::register()` con `tag('seo.sitemap.paths')`.
