---
qmd: "INDEX"
issues: []
discussions: []
title: "concepts index — Sixteen"
type: index
tags: [concepts, Sixteen]
created: 2026-05-11
updated: 2026-06-10
status: draft
---

# concepts Index — Sixteen

Concetti specifici del tema Sixteen. Carica on-demand via `qmd search` o consulta il [trigger map root](../../../../../../docs/wiki/rules/00-TRIGGER_MAP.md).

- [footer-partner-sponsorship-marketing.md](./footer-partner-sponsorship-marketing.md) — partner/sostenitori sì, banner commerciali no (BMAD discovery).
- Sostenibilità FO: [project-sustainability-front-office.md](../../project-sustainability-front-office.md) — vestito CTA/partner; modello economico nel dossier BMAD.
- Inbound blog/eventi: [inbound-marketing-blog-eventi.md](../../inbound-marketing-blog-eventi.md) — pattern AFC → Design Comuni FO.
- [folio-route-params-mount.md](./folio-route-params-mount.md) — params Folio in `mount()`, mai `request()->route()` in `@php`.
- Folio page shell (no `@props`/`@extends`) — canon root: [architecture-folio-page-shell.md](../../../../../../docs/wiki/bmad/architecture-folio-page-shell.md) · guida: [folio-page-pattern.md](../../folio-page-pattern.md)
- [fo-folio-named-routes-header.md](./fo-folio-named-routes-header.md) — dropdown: `route('services.categories')` ecc., verificato con `folio:list`.
- [fo-folio-routing-zen](fo-folio-routing-zen.md)
- Cms: [folio-list-vs-route-list](../../../../../Modules/Cms/docs/wiki/concepts/folio-list-vs-route-list.md) — audit `folio:list` — file = rotta, mai web.php
- [fo-folio-links-multilingua.md](./fo-folio-links-multilingua.md) — link FO CMS: `FrontofficeUrl::fromStoredUrl`, no path inventati.
- [fo-header-url-and-translation-contract.md](./fo-header-url-and-translation-contract.md) — no `getLocale()`/`profilo/*`; chiavi `pub_theme::header.user.dropdown.*.label`.
- [frontend-design-fixcity-overlay.md](./frontend-design-fixcity-overlay.md) — plugin Anthropic frontend-design adattato PA/Fixcity (SSoT wiki; skill in `bashscripts/ai/skills/frontend-design/`).
- [map-lit-reconstruction-hub.md](../../../../../../docs/wiki/memories/map-lit-reconstruction-hub.md) — hub root progetto
- [geo-map-lit-reconstruction-guide.md](../../../../../Modules/Geo/docs/wiki/concepts/geo-map-lit-reconstruction-guide.md) — ricostruire mappa `/it` da documentazione
- [geo-map-fixes-registry.md](../../../../../Modules/Geo/docs/wiki/concepts/geo-map-fixes-registry.md) — registro correzioni INC-1…8 (marker + popup + build).
- [geo-map-marker-civic-pin-theme-boundary](./geo-map-marker-civic-pin-theme-boundary.md) — override tema su civic pin (no transform, glifo 22px).
- [geo-map-popup-leaflet-boundary](./geo-map-popup-leaflet-boundary.md) — confine popup/marker Leaflet vs CSS parity.
- [marker-cluster-hover-stability](./marker-cluster-hover-stability.md) — root cause + fix CSS: mai `transform`/`transition: transform` su `.leaflet-marker-icon`, altrimenti i cluster "scappano" al hover (STORY-123).
- [leaflet-no-transform-on-marker-icon](./leaflet-no-transform-on-marker-icon.md) — lezioni trasversali STORY-123: cascade light-DOM Lit vs CSS tema bundlato `!important`, e disciplina git multi-repo (come sono stati risolti gli errori di processo).
- [leaflet-z-index-layering](./leaflet-z-index-layering.md) — pane z-index Leaflet sulla scala delle centinaia (`200/400/600/700`); marker mai sotto le tile.
- [livewire-alpine-esm-order](./livewire-alpine-esm-order.md) — `@vite` (ES module) dopo `@livewireScripts`: bootstrap Alpine header/manifest vite in **`public_html`** e `npm run build:with-webroot`.
- Wiki root (permalink memoria vite + webroot): [sixteen-vite-public-path-alpine-livewire-order.md](../../../../../../docs/wiki/memories/sixteen-vite-public-path-alpine-livewire-order.md)


## Folio FO (tema vestito)

- [fo-folio-routing-zen](fo-folio-routing-zen.md) — file = rotta, `route()` non definisce
- [fo-folio-named-routes-header](fo-folio-named-routes-header.md) — dropdown: mapping `route()` verificato
- [fo-folio-links-multilingua](fo-folio-links-multilingua.md) — nav CMS: `FrontofficeUrl::fromStoredUrl`
- [personal-area-routes](personal-area-routes.md) — redirect canon (deprecato)
- Troubleshooting: [route-not-found-view-cache](../troubleshooting/route-not-found-view-cache.md)
- Cms mount: [folio-filesystem-routing-no-web-php](../../../../../Modules/Cms/docs/wiki/concepts/folio-filesystem-routing-no-web-php.md)
- Cms audit: [folio-list-vs-route-list](../../../../../Modules/Cms/docs/wiki/concepts/folio-list-vs-route-list.md)

- [Metodo di parity Design Comuni](design-comuni-parity-method.md): gate, JSON, blocchi, CSS estratto, cascata (2026-10-13)
- [CSS di terzi sulle pagine data-dc](vendor-css-on-data-dc-pages-needs-guard-exemption.md): il guard PostCSS spegne anche il vendor; esenzione per nome, trappole misurate (2026-10-26)
- [Audit di accessibilita (DCP-143)](accessibility-audit-dcp.md): axe, tastiera, regole ARIA dei blocchi, limiti (2026-10-13)
