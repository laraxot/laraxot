---
id: IM-SIXTEEN-001
title: "Liste e dettaglio blog/eventi nel tema Sixteen"
type: story
theme: Sixteen
epic: "III Presentazione front-office"
status: done
priority: P1
owner: theme:Sixteen
scope: "laravel/Themes/Sixteen/, laravel/Modules/Blog/, laravel/Modules/Cms/"
created: 2026-10-15
updated: 2026-10-15
tags: [bmad, sixteen, blog, event, wcag]
qmd: "Sixteen lista dettaglio blog eventi Design Comuni responsive WCAG"
related: [IM-BLOG-002, IM-CMS-001, IM-UI-001, IM-SEO-001, IM-SIXTEEN-002]
---

# Liste e dettaglio blog/eventi nel tema Sixteen

## Stato

- **Novità `/blog`**: done —
  [inbound-fo-blog-listing.story.md](../bmad/stories/inbound-fo-blog-listing.story.md).
- **Eventi `/events`**: done —
  [inbound-fo-events-listing.story.md](../bmad/stories/inbound-fo-events-listing.story.md).

## Acceptance Criteria

- [x] Sixteen legge dati dal Blog e non inventa contenuti.
- [x] Lista Novità su `/blog` con card Design Comuni e dati `content_type=post`.
- [x] Dettaglio novità su `/blog/{slug}` con SEO title Article.
- [x] Lista Eventi su `/events` (`content_type=event`, badge calendario + evidenza).
- [x] Dettaglio evento su `/events/{slug}` con SEO title; post su `/events` → 404.
- [ ] Responsive 320/768/1440 + WCAG 2.2 AA formalizzato.
- [x] Header/footer `<x-section>` JSON (Novità/Eventi già presenti).

## Dipendenze

Nav JSON pronta. Authoring Filament: Blueprint Blog `content_type=post|event`.
`ResolvePageAction`: `/events/{slug}` → `findPublishedEventBySlug`.
