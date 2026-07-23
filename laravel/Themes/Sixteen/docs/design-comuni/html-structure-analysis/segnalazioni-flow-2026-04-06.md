# Segnalazione Flow Structural Parity – 2026-04-06

## Method
- Ran the existing batch body parity audit (`bashscripts/design-comuni/batch-body-parity.php`) and captured full `<body>` fragments for every segnalazione slug, storing them under `docs/design-comuni/batch-body-parity/<slug>/`.
- Captured local/reference screenshots with `bashscripts/design-comuni/capture-selected-page-comparisons.mjs`; the gallery for each slug lives under `docs/design-comuni/screenshots/<slug>/README.md`.

## Current structure mismatch (reference vs local)
| Slug | Match % | Reference tags | Local tags | Structural gap |
| --- | --- | --- | --- | --- |
| `segnalazione-disservizio` | 23.8% | 19 | 24 | Reference is almost empty (404) while local renders the entire flow; parity needs the reference state to exist before CSS/JS work can continue.
| `segnalazione-01-privacy` | 57.9% | 430 | 384 | Reference adds accordion sections, CTA and feedback, which the JSON currently omits; once those blocks are declared the layout is within CSS reach.
| `segnalazione-02-dati` | 46.0% | 559 | 384 | Local wraps the answers in extra card containers and lacks the accordion/summary structure seen in the reference; adjust the JSON block order to match.
| `segnalazione-03-riepilogo` | 48.7% | 523 | 384 | Reference includes progress steps plus a richer CTA band; the local page needs the same `cmp-steps-rating` sequence and contact link list to align.
| `segnalazione-04-conferma` | 44.2% | 551 | 384 | Reference shows multi-column confirmation info and a CTA grid that are absent locally; this structural gap must be closed before styling.$
| `segnalazione-area-personale` | 19.2% | 886 | 384 | Local is placeholder-heavy while the reference renders the full “area personale” dashboard; rebuild the JSON content blocks to emit those sections.
| `ticket-list` | 32.4% | 775 | 384 | Local now renders the map/list tabs but some wrappers (filters panel, CTA band, rating band) still diverge; CSS/JS can finish the parity once the block nesting matches.
| `segnalazione-dettaglio` | 31.1% | 804 | 384 | Reference detail page has extensive accordion sections, metadata rows, and image gallery; local uses a simplified card layout that must be replaced with the same `cmp-info-button-card` + `cmp-info-summary` hierarchy.

## Action items
1. **JSON parity** – Update `laravel/config/local/fixcity/database/content/pages/tests.<slug>.json` so each slug emits the same block order as the reference (e.g., `cmp-steps-rating`, `cmp-rating`, `cmp-contacts`, `links.list`, `cmp-info-button-card`). Reuse existing Tailwind/Alpine components (e.g., `blocks/feedback/survey`, `blocks/listing`, `blocks/search/support-links`).
2. **Blade compliance** – `resources/views/pages/tests/[slug].blade.php` must simply render the JSON-defined blocks; if new wrappers (tabs, filter sections, CTA rows) are required for parity, add them as reusable components under `resources/views/components/blocks/` rather than inline markup.
3. **Document DOM diff** – Link to each slug’s `docs/design-comuni/batch-body-parity/<slug>/reference-body.html` vs. `.../local-body.html` so future agents can see exactly which container is missing.
4. **CSS/JS scope** – After structure reaches ≥90% match, continue refining `resources/css/app.css` and `resources/js/app.js` (Tailwind + Alpine) to adjust spacing, tabs, map controls, and rating steps.
5. **Verify** – After each structural change run `bashscripts/design-comuni/batch-body-parity.php`, rebuild assets (`npm run build && npm run copy`), then rerun `node bashscripts/design-comuni/capture-selected-page-comparisons.mjs <slugs>` so the documentation always matches the live bundle.

## Build reminder
```
cd /var/www/_bases/base_fixcity_fila5/laravel/Themes/Sixteen
npm run build
npm run copy
```

## References
- Blade: `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`
- JSON: `laravel/config/local/fixcity/database/content/pages/tests.<slug>.json`
- Screenshots: `laravel/Themes/Sixteen/docs/design-comuni/screenshots/<slug>/README.md`
- Body artifacts: `laravel/Themes/Sixteen/docs/design-comuni/batch-body-parity/<slug>/`
