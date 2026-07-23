---
title: "Header logged — dropdown UX spec"
type: concept
updated: 2026-06-05
tags: [header, design-comuni, dropdown, mobile, ux]
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/292"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/292"
related:
  - header-logged-in-parity-delta.md
  - ../../../../../../docs/stories/STORY-147-ux-design-header-logged-in.md
  - ../../visual-comparison/header/logged-dropdown.png
  - ../design-comuni/raw/cmp-header.hbs
qmd: "header logged dropdown mobile avatar only design comuni slim ux segnalazione area personale"
---

# Header logged — dropdown UX

## Perché

Lo slim header PA deve replicare [segnalazione-area-personale](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-area-personale.html): su **mobile** (<992px) l’utente vede **solo l’avatar** nel toggle; il menu dropdown è bianco con voci verdi DC.

## Target visivo

Screenshot canonico: `docs/visual-comparison/header/logged-dropdown.png`

- Toggle: avatar-only (nessun nome, nessun chevron)
- Menu aperto: 5 voci + **un** divider prima di Impostazioni; icona solo su Esci

## Breakpoint

| Viewport | Toggle slim |
|----------|-------------|
| ≥992px (`lg`) | `rounded-icon` + nome (`d-none d-lg-block`) + `it-expand` |
| <992px | **Solo** `rounded-icon` — nome e chevron nascosti |

### Evidenza DC live (Playwright 375px, 2026-06-05)

| Proprietà | Valore reference |
|-----------|------------------|
| `span.d-none.d-lg-block` | `display: none` |
| `svg.d-none.d-lg-block` | `display: none` |
| Toggle size | 40×48px (Fixcity 48×48 — touch WCAG) |
| Menu items | 5 voci + divider + Esci bold con icona |
| Menu align | `right: 0`, shadow `0 3px 15px rgba(0,0,0,0.1)` |
| Avatar | `img` 20×20 in `rounded-icon` |

## Owner

| File | Ruolo |
|------|-------|
| `partials/user-dropdown.blade.php` | Markup |
| `app/13-final-runtime-overrides.css` | Mobile avatar-only + dropdown `right: 0` |
| `app/08-layout-type-and-header.css` | Skin dropdown + toggle desktop |

## Pitfall CSS

`ticket-parity.css` forza `display: inline-block !important` su `span:not(.rounded-icon)` — layer 13 vince con `display: none !important` su `.d-none.d-lg-block` sotto 992px.

## Toggle chiuso — colore (STORY-156)

| Superficie | Token |
|------------|-------|
| Slim bar | `--dc-green-dark` (`#00402b`) |
| Bottone avatar+nome | `--dc-green` (`#007a52`) — **diverso** dalla slim |

Avatar: media locale → Gravatar MD5 `?s=40&d=mp` → iniziale.

## UX design completo

- [STORY-156-ux-design-header-dropdown-closed-open-parity.md](../../../../../../docs/stories/STORY-156-ux-design-header-dropdown-closed-open-parity.md)
- [STORY-147-ux-design-header-logged-in.md](../../../../../../docs/stories/STORY-147-ux-design-header-logged-in.md)
