---
title: "No Bootstrap Runtime Assets Rule"
type: concept
confidence: high
created: 2026-05-26
updated: 2026-05-26
tags: [sixteen, vite, tailwind, alpine, lit, bootstrap, runtime]
related:
  - ../../../../../docs/wiki/memories/sixteen-tailwind-vite-runtime-boundary.md
  - ../../../../../Modules/Fixcity/docs/wiki/concepts/frontoffice-wizard-theme-runtime-boundary.md
---

# No Bootstrap Runtime Assets Rule

Il runtime del tema Sixteen non deve caricare Bootstrap o Bootstrap Italia tramite CDN, `asset()` manuale o script separati fuori dal grafo Vite.

## Regola

- JS applicativo: una sola entry pubblica nel layout, `@vite(['resources/js/app.js'], 'themes/Sixteen')`.
- JS di feature: import dentro `resources/js/app.js`, per esempio `import { initHeaderMobileNav } from './theme/header-mobile-nav.js';`.
- CSS runtime: `@vite(['resources/css/app.css'], 'themes/Sixteen')`; niente `bootstrap-italia.min.css` da CDN.
- Icone SVG Design Comuni: sprite locale pubblicato dal tema, non CDN.
- HTML di reference sotto `docs/` o `Main_files/` puo' contenere asset Bootstrap Italia per confronto upstream, ma non e' codice runtime.

## Perche'

Vite e' la source of truth per ordine, hash, manifest e copia in `public_html/themes/Sixteen`. Aggiungere entry separate o asset manuali produce drift tra sorgente, manifest e HTML servito.

## Header mobile

Il menu mobile dell'header e' gestito dall'Alpine component `mobileMenu()` (5 template con `x-data="mobileMenu()"`), registrato inline su `alpine:init` nel `<head>` PRIMA di `@livewireScripts`. La factory `mobileMenu` esiste anche in `resources/js/components/mobile-menu.js` (registrata in `app.js` come fallback per Livewire navigate).

I template che usano ancora markup Bootstrap Italia (`.it-header-navbar-wrapper`, `data-bs-toggle="navbarcollapsible"`) sono gestiti da uno shim vanilla JS in `app.js` (linee ~245-306). Questi 8-9 template non sono ancora migrati ad Alpine — separare in nuova storia.
