---
title: Layout body page contract
type: architecture
status: active
tags: [layout, css, design-comuni, folio]
updated: 2026-10-22
qmd: "layout body page contract sixteen"
---

# Layout body page contract

The shared `components/layouts/main.blade.php` owns the document shell and
receives `bodyPage` from the public page layouts. It must mirror that value on
`<body data-page="…">`.

Page-scoped Design Comuni rules use this attribute to select the correct
spacing, grid, header, footer, ticket, service, and responsive treatments.
Dropping it does not break Blade rendering, but makes the page appear
unstyled or inconsistent because the CSS contract silently stops matching.

When changing the layout shell:

1. Keep `bodyPage` declared in `@props` and pass it through unchanged.
2. Keep page-specific `main[data-page]` contracts in the page component.
3. Verify the generated HTML at 375px, 768px, and 1440px.
4. Build assets from `laravel/Themes/Sixteen`; run PHP tests from `laravel`.
