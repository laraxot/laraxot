# Structural Parity Plan – 2026-04-06

## Context
`lista-risorse` e `ticket-list` now sit in the CSS/JS perimeter, but `amministrazione`, `mappa-sito` and the other low-score slugs are still far from matching the reference because their rendered blocks differ (missing background/CTA/links groups). Before chasing more styling, the next step is to align their JSON/Blade output with the reference structure.

## Reference snapshot highlights

- **Amministrazione reference:** hero + cards grid + highlighted section + full links list + CTA band + contact block + dark footer. Local version currently renders hero + cards + simple links list, so the missing sections are evidence/cta/contacts/feedback.
- **Mappa del sito reference:** long textual sitemap organized in columns, with anchor links grouped by sections. Local version renders a card grid, so the structure needs to change to textual lists with headings.

## Action items
1. **`tests.amministrazione.json`**: add new `content_blocks` (e.g., `evidence_cards`, `links-list` expansions, `feedback_cta`, `cmp-contacts`) so the page renders the same number of thematic rows as the reference. Use the reusable `blocks/cards/grid`, `blocks/feedback`, `blocks/search/support-links` already available in the theme.
2. **Blade components**: ensure `[slug].blade.php` uses the existing block definitions without inline markup; if a new block type is needed (e.g., `cta-with-rating`), build it under `resources/views/components/blocks/` so other slugs can reuse it.
3. **`tests.mappa-sito.json`**: replace the card-grid data with grouped link lists (sections such as “Pagine principali”, “Servizi”, “Flussi”) and ensure the renderer outputs plain lists (no card wrappers) by picking the right block (`links.grid` vs `links.list`).
4. **Documentation**: capture the before/after DOM fragments and add them to `docs/design-comuni/html-structure-analysis` so other agents know where the gaps live.
5. **Verification**: rerun the batch body parity script after structural updates to confirm the match percentage climbs before reintroducing more CSS overrides.

## Next milestone
After completing the JSON/block updates, rerun `npm run build` + `capture-selected-page-comparisons` for `amministrazione`/`mappa-sito` to verify the structure first and then apply any remaining CSS refinements from the new markup.
