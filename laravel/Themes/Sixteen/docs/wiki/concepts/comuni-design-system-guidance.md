---
title: "comuni design system guidance"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-09-27
qmd: "comuni design system guidance"
issues:
  - https://github.com/laraxot/base_fixcity_fila5/issues/383
discussions:
  - https://github.com/laraxot/base_fixcity_fila5/discussions/392
related:
  - ../../bmad/services-catalog-expected-2026-09-27.md
  - ../../../../../Modules/Fixcity/docs/bmad/services-catalog-gap-analysis-2026-09-27.md
  - ../../../../../Modules/Fixcity/docs/bmad/stories/STORY-519-design-comuni-services-entry.md
status: draft
---

# Design System Reference for Sixteen Theme
## Comuni Italia Design System Alignment
This project aligns with Design Comuni governance through:

### Component Standards
- **Header Structure**: Follows conquer visual reference [graduatoria-area-personale.html]
  - Guest state: "Accedi all'area personale" button only
  - Authenticated state: avatar + full name + dropdown with area personalizzata
- **Color Palette**: 
  - Slim header background: `@design-comuni-palette-primary` (#0066CC)
  - Dropdown menu: white background with `#0066CC` link color
  - Icon consistency: `icon-white` for all navigation elements
- **Typography**: 
  - Clear visual hierarchy with semantic heading tags
  - Responsive spacing following Design Comuni guidelines
- **Accessibility**: 
  - Aria attributes aligned with WCAG 2.1 AA standards
  - Focus states verified through keyboard navigation

### Implementation Guidelines
- **Component Extraction**: All reusable header elements are now in `sections/header/partials/`
- **Alpine.js State**: Maintains x-data pattern for dropdown states
- **Design System Tokens**: Use CSS variables from `design-comuni-tokens.css`

### Best Practices
- Never duplicate partials across modules
- Follow one source of truth pattern
- Use `pub_theme::components.sections.header.partials.*` notation
- Test component consistency with reference designs

## Compliance Verification
- [ ] Guest/Customised states rendered correctly
- [ ] SVG assets sourced locally (no unpkg)
- [ ] Arguments passed via Blade @include
- [ ] No inline SVG dimensions
- [ ] Mobile responsiveness verified

__NOTE__: This component complies with BMAD principles and Design Comuni governance. All implementation follows documented patterns in:
- `docs/wiki/concepts/header-section-owner-rule.md`
- `docs/wiki/concepts/blade-component-extraction-governance.md`
- `docs/wiki/concepts/header-section.md`

## Service catalogue: only verified pathways

The Design Comuni site index and service-workflow index describe distinct
surfaces. Do not infer that a municipality offers all sample catalogue entries
or the 44 example workflow templates. In FixCity, publish a category or service
only when its tenant-owned content, responsible office and destination are
verified. A category link must resolve to records filtered for that category
or to a clearly scoped workflow; linking every category to the same generic
list is misleading. Keep the FixCity report detail, report list, creation and
tracking pathways localized and accessible. The current supported directory
group is public reports; broader municipal services require separate
content and integration work.

For mobile service entry pages, task navigation links must stack at narrow
widths instead of shrinking into a shared row. Keep labels centered and fully
visible and provide at least 44 px of target height; preserve the compact
wrapping row at larger widths. Document and test the rendered geometry, not
just page-level overflow.

The working comparison, expected state and correction plan are maintained in
[the Sixteen service contract](../../bmad/services-catalog-expected-2026-09-27.md),
[the FixCity gap analysis](../../../../../Modules/Fixcity/docs/bmad/services-catalog-gap-analysis-2026-09-27.md)
and [STORY-519](../../../../../Modules/Fixcity/docs/bmad/stories/STORY-519-design-comuni-services-entry.md).

## Publish navigation destinations only when their page exists

The tenant `database/content/sections/header.json` remains the source of menu
items and publication flags. Keep planned sections there with `enabled: false`
until a Folio page or published CMS page exists; show the topics link only when
`topics_enabled` is explicitly true. A generic `[container0]/index` request
without either an owner utility page or CMS page returns 404 instead of a blank
200 shell. This keeps FixCity truthful while allowing administrators to publish
future municipality-owned content through the existing configuration workflow.
See [STORY-527](../../bmad/stories/STORY-527-public-navigation-published-pages.md)
and its expected/comparison/correction records.
