# CSS/JS Parity Audit - 2026-04-04

## Scope

Representative pages checked with real screenshots:

- `lista-risorse`
- `amministrazione`
- `mappa-sito`

Screenshots were generated with:

- [../../../../bashscripts/design-comuni/capture-selected-page-comparisons.mjs](../../../../bashscripts/design-comuni/capture-selected-page-comparisons.mjs)
- [../../../../bashscripts/docs/design-comuni-selected-page-comparisons.md](../../../../bashscripts/docs/design-comuni-selected-page-comparisons.md)

Artifacts:

- [screenshots/lista-risorse/README.md](./screenshots/lista-risorse/README.md)
- [screenshots/amministrazione/README.md](./screenshots/amministrazione/README.md)
- [screenshots/mappa-sito/README.md](./screenshots/mappa-sito/README.md)

## Findings

### lista-risorse

Structure is not identical, but it is close enough to benefit from CSS-only work.

Main visual gaps seen in screenshots:

- search-results area lacked the light grey background present in the reference
- result cards were still rendered in a 2-column desktop layout instead of 3 columns
- card rhythm, title sizes, badge styling and image treatment were weaker than the reference
- the feedback section rendered an extra large second card that makes the page much taller than the reference
- the bottom support block is still structurally wrong because the current rendered markup does not match the reference contact card

### amministrazione

CSS/JS alone is not enough.

The reference contains:

- a featured evidence section
- a larger exploration grid
- feedback and contact blocks

The local page currently renders only hero + one small cards row + one links list, so the missing visual pieces are caused by block structure/content, not styling.

### mappa-sito

CSS/JS alone is not enough.

The reference is a long textual sitemap with grouped lists and anchors. The local page is a card-grid landing page. This is a structural mismatch rooted in the rendered blocks.

## Changes Applied

First CSS-only pass on `lista-risorse` in [../resources/css/app.css](../resources/css/app.css):

- stronger heading hierarchy and summary styling
- grey background and spacing alignment for featured and search-result sections
- card border, image sizing, badge/date and copy rhythm improvements
- desktop override to show 3 search-result cards per row
- feedback section compressed by hiding the second card and restyling the first one
- lower utility section spacing tuned to reduce page-height drift

## Next Steps

1. Continue CSS/JS parity on pages that already have near-matching rendered blocks.
2. Move `amministrazione`, `mappa-sito`, and the other low-score pages back to Blade/JSON parity work before more CSS polish.
3. After each CSS pass, rebuild theme assets and refresh the screenshot set for the same slugs.

## Related

- [BATCH_BODY_PARITY_REPORT.md](./BATCH_BODY_PARITY_REPORT.md)
- [REPLICATION_MASTER_PLAN.md](./REPLICATION_MASTER_PLAN.md)
