# Segnalazioni Elenco - Structure Analysis

- **Batch score**: 32.4% (see `laravel/Themes/Sixteen/docs/design-comuni/BATCH_BODY_PARITY_REPORT.md`). Both reference and local endpoints return HTTP 200, but the ordered tag sequence diverges heavily.
- **Reference**: rich hero + breadcrumb overlay, tabs/mappa/lista con filters, CTA, contact list, footer sections.
- **Local**: simplified hero, placeholder scenario block, governance alert, CTA link, and unchanged global footer. No map, no filters or tabs structure.

## Reference body (key sections)
1. `main > div.it-hero-wrapper.it-wrapped-container`: full-width hero image with overlapping card. Breadcrumb is nested inside hero card.
2. `section#novita`, `section#amministrazione`, `section#servizi`, `section#documenti`: each with card grids, CTA button rows, and action links.
3. Tabs/map/list component for location-based filtering.
4. CTA to signalare disservizio plus pagination structure for events/services.

## Local body (actual layout)
- `main#main-container[data-page="ticket-list"]` contains:
  - `section.py-12`: heading block with title/subtitle from `heading` block.
  - `section.py-8`: “Scenario di conversione” text only (no dynamic filters or map).
  - Alert box referencing governance copy keys.
  - Link to the reference page as CTA.
- The JSON `content_blocks` (breadcrumb + heading + sidebar filters + tabs map list + feedback + contacts) is currently not rendered into the structure seen by the reference: only hero text and informational sections are visible, no actual filter UI, map/list components, or CTA conversions.
- Global modal/search + footer are identical, so the delta is confined to the main content.

## Gaps to close before CSS/JS parity
1. **Map/list component** – the reference uses a `tabs-map-list` block with results, map view, CTA, and issue list; the JSON defines `tabs-map-list` but the Blade output now exposes only static global content (the block likely not rendering because the block routine is still placeholder). Need to ensure that block actually produces the tab-list + cards + map markup that matches the reference.
2. **Sidebar filters + CTA** – reference has sidebar filters alongside list/map; local hero lacks columns entirely. The `sidebar-filters` block in JSON should be rendered inside the page structure with the actual filter markup rather than being ignored.
3. **Governance alert & scenario section** – currently textual placeholders; the reference comprises multiple sections (novità, amministrazione, servizi, documenti) arranged in card grids. Must decide whether to recreate those sections via CMS content or restructure to fit the reference scaffolding (e.g., add new blocks for `sections` or multiple `cards-grid` instances).

## Visual references
- Reference screenshot: `laravel/Themes/Sixteen/docs/design-comuni/screenshots/ticket-list/reference-full.png`
- Local screenshot: `laravel/Themes/Sixteen/docs/design-comuni/screenshots/ticket-list/local-full.png`

## Next steps
1. Verify `pub_theme::components.blocks.tabs.map-list` and `filters.sidebar` produce the expected DOM; update Blade templates if they currently output placeholder text (see `resources/views/components/blocks/filters/sidebar.blade.php` and `tabs/map-list`).
2. Reintroduce the map/list cards, CTA, and issue list sections that appear in the reference (they can be backed by the existing `items` array in JSON).
3. Rerun `bashscripts/design-comuni/batch-body-parity.php` after structural action to confirm readiness before investing in CSS/JS parity.

Tickets/Discussions (manually open as needed) should reference this doc and the batch report so that each page’s structural debt is clear prior to styling.
