---
title: "Segnalazioni elenco — tab Design Comuni su /it (Alpine.js)"
type: concept
status: active
created: 2026-05-28
<<<<<<< HEAD
updated: 2026-05-29
tags: [filament, tabs, ticket-list, design-comuni, alpine, segnalazioni-elenco]
=======
<<<<<<< Updated upstream
tags: [filament, tabs, ticket-list]
=======
updated: 2026-05-29
tags: [tabs, design-comuni, alpine, segnalazioni-elenco]
>>>>>>> Stashed changes
>>>>>>> laraxot/dev
related:
  - ../../../../../../docs/stories/STORY-065-it-segnalazioni-filament-tabs.md
  - ../../../../../Modules/Fixcity/docs/wiki/concepts/ticket-list-map-architecture.md
---

# Tab Design Comuni su `/it`

## Regola classi

**NO feature-prefixed class names.** Non usare `segnalazioni-tabs-bar` o `ticket-tabs-bar`.
Usare solo classi CSS standard di [Design Comuni](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazioni-elenco.html):
- `.nav.nav-tabs` (tab bar)
- `.nav-item` (wrapper singolo tab)
- `.nav-link` (link tab)
- `.active` (stato attivo)
- `.tab-content` / `.tab-pane` (pannelli)

## Implementazione

- Componente: `resources/views/components/blocks/segnalazioni/tabs.blade.php`
- HTML puro con classi Design Comuni (no `x-filament::tabs`)
- Interattività: Alpine.js (`activeTab` + `x-on:click.prevent`), no Bootstrap JS
- Pannelli: `x-show`, `x-bind:class` con `active show`

## CSS

<<<<<<< HEAD
Skin Design Comuni in `style-apply.css` — selettori `.ticket-list .segnalazioni-fi-tabs` e `.segnalazioni-elenco .nav-tabs`.
=======
<<<<<<< Updated upstream
Skin Design Comuni in `style-apply.css` — selettore `.ticket-list .segnalazioni-fi-tabs`.
=======
Skin Design Comuni in `style-apply.css` — selettore `.segnalazioni-elenco .nav-tabs`.
>>>>>>> Stashed changes
>>>>>>> laraxot/dev

## Riferimenti

- https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazioni-elenco.html
- [STORY-065](../../../../../../docs/stories/STORY-065-it-segnalazioni-filament-tabs.md)
- [filament-first-rule.md](../../../../../../docs/rules/filament-first-rule.md)
- `tailwindcss + alpinejs + lit + daisyui + filament`
