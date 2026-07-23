# Argomento Structure Report

Date: 2026-04-03
Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/argomento.html
Local: http://127.0.0.1:8000/it/tests/argomento
Blade entrypoint: [slug].blade.php
JSON source: tests.argomento.json

## Summary

The local `argomento` page is renderable again and returns HTTP 200, but the HTML structure inside `body > main` is not close to the reference.

Estimated structural parity is well below the 90% threshold. A realistic reading is below 40%, because the local page reproduces only a simplified breadcrumb + hero + two editorial blocks, while the reference page is a topic-detail landing page with a completely different hero shell and a much broader section inventory.

## Source Chain

- Blade entrypoint: `/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`
- Rendering wrapper: `/var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen/resources/views/components/layouts/app.blade.php`
- Content JSON: `/var/www/_bases/base_fixcity_fila5/laravel/config/local/fixcity/database/content/pages/tests.argomento.json`

`tests.argomento.json` currently declares only four blocks:
1. breadcrumb
2. hero (`pub_theme::components.blocks.hero.default`)
3. cards grid (`pub_theme::components.blocks.cards.grid`)
4. links list (`pub_theme::components.blocks.links.list`)

## Structural Comparison

### Reference `main`

The reference page has this high-level sequence:
1. `main`
2. oversized hero wrapper with image background and overlapping card shell: `div.it-hero-wrapper.it-wrapped-container#main-container`
3. breadcrumb embedded inside the hero card
4. topic heading and summary on the left
5. "Questo argomento e gestito da" teaser cards on the right
6. `section#novita`
7. `section#amministrazione`
8. `section#servizi`
9. `section#documenti`
10. follow-up editorial/rating/contact content later in the page

### Local `main`

The local page has this high-level sequence:
1. `main#main-container[data-page="argomento"]`
2. standalone breadcrumb block
3. standalone generic hero block
4. generic services cards section (`Servizi correlati`)
5. generic links list section (`Documenti utili`)

## Concrete Differences

- The reference uses the hero image wrapper as the page shell and places `#main-container` on that wrapper. The local page places `#main-container` directly on `<main>`.
- The reference breadcrumb is inside the overlapping hero card. The local breadcrumb is a standalone block before the hero.
- The reference hero is split into two columns: topic intro on the left and managing-office teaser cards on the right. The local hero is a generic single-column title/subtitle block.
- The reference page title is a real topic label (`Sport`). The local page title is generic (`Dettaglio argomento`).
- The reference contains several domain sections with dedicated IDs and card ecosystems: `novita`, `amministrazione`, `servizi`, `documenti`. The local page has only two generic sections and no matching section IDs.
- The reference has many more section headings and editorial zones. Measured counts from the current pages: `reference_sections=4`, `local_sections=3`, `reference_h2=11`, `local_h2=4`.
- The local JSON content model is not rich enough to express the reference structure; this is the main blocker, more than CSS.

## Screenshots

- Reference full: `./screenshots/argomento/reference-full-2026-04-03.png`
- Local full: `./screenshots/argomento/local-full-2026-04-03.png`

## Saved HTML Artifacts

- Reference page: `./argomento-parity/reference-page.html`
- Reference body: `./argomento-parity/reference-body.html`
- Local page: `./argomento-parity/local-page.html`
- Local body: `./argomento-parity/local-body.html`

## Decision

Do not move to CSS/JS parity yet.

The structure is not within the requested 90% band. The next correct step is to redesign the local `argomento` content model and block composition so that the page can express:
- topic hero with image background and overlap card
- managing-office teaser cards
- dedicated `novita`, `amministrazione`, `servizi`, `documenti` sections
- matching section order and IDs

Only after that should page-scoped CSS/JS parity work start inside `laravel/Themes/Sixteen`.
