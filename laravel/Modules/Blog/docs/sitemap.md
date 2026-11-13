---
title: "Sitemap — Blog"
type: documentation
status: active
tags: [blog, sitemap, dcp-273]
created: 2026-10-26
updated: 2026-10-26
qmd: "sitemap   blog blog"
---

# Sitemap — Blog

## Contenuto
La sitemap include:
- `/{lang}/blog/{slug}` per ogni articolo **pubblicato** (`content_type='post'`, `published()` + `publishedUntilToday()`)
- `/{lang}/events/{slug}` per ogni evento **pubblicato** (`content_type='event'`, `published()`)

## Fonti
`Blog\Actions\Article\ListPublishedArticlePathsAction` implementa `SitemapPathsContract`, registrato in `BlogServiceProvider::register()`.

## Vedi
- `Modules/Seo/docs/wiki/concepts/sitemap-coverage-contract.md`
- `Modules/Blog/docs/wiki/concepts/sitemap-blog-folio-routing.md`
