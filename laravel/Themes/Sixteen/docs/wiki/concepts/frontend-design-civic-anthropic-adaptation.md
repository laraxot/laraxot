---
title: frontend design — adattamento plugin anthropic al tema sixteen
type: concept
module: Sixteen
confidence: high
created: 2026-06-03
updated: 2026-06-03
tags: [frontend, design-comuni, anthropic, ux, theme]
related:
  - ../rules/frontend-stack-canonical.md
  - ../../../../docs/wiki/skills/frontend-design-civic-pa.md
  - geo-map-popup-leaflet-boundary.md
  - ../../../../Modules/Geo/docs/wiki/concepts/geo-map-lit-reconstruction-guide.md
upstream:
  - https://github.com/anthropics/claude-code/tree/main/plugins/frontend-design
---

# Frontend design — Anthropic plugin × Sixteen

## Scopo

Il tema Sixteen implementa **Design Comuni** e bundle Vite condiviso con Geo (`map-lit`). Il plugin Anthropic `frontend-design` promuove estetica distintiva anti–AI-slop: qui definiamo **dove** il tema può essere audace e **dove** deve restare istituzionale.

## Responsabilità tema vs modulo Geo

| Layer | Estetica | File tipici |
|-------|----------|-------------|
| **Sixteen** | Parity CSS, header, griglia elenco, override Leaflet | `resources/css/app/*.css`, `design-comuni-visual-fix.css` |
| **Geo** | Markup popup, marker HTML, stili inline Lit | `popup-ticket.js`, `marker-config.js` |
| **Fixcity** | Dati `tickets.json`, filtri | Actions/ViewModels |

## Regole tema (civic parity)

1. **Non** sostituire font stack Comuni con Inter/Roboto «per bellezza».
2. **Non** `header { min-height: … }` globale — scope `header.it-header-wrapper`; vedi [global-header-css-leak-leaflet-popup.md](../troubleshooting/global-header-css-leak-leaflet-popup.md).
3. **Cluster/marker:** rinforzo in `07-map-clusters-and-leaflet.css` — allineato a `marker-config.js`, no transform su `.leaflet-marker-icon`.
4. **Build obbligatoria** dopo JS/CSS Geo: `npm run build` in `Themes/Sixteen`.

## Dove usare «distinctive» Anthropic

- Blocchi CMS homepage ancora fuori parity sheet
- Micro-copy empty state (con traduzioni in `lang/`)
- **Non** su: `ticket-list`, login, wizard crea

## Checklist UX pre-merge CSS

- [ ] Viewport 375×667 e 768×1024
- [ ] Contrasto link/CTA verde istituzionale
- [ ] Popup mappa: header compatto, footer solo in loaded
- [ ] `prefers-reduced-motion` rispettato

## Collegamenti

- Skill wiki: [frontend-design-civic-pa.md](../../../../docs/wiki/skills/frontend-design-civic-pa.md)
- [ticket-list-map-integration.md](./ticket-list-map-integration.md)
