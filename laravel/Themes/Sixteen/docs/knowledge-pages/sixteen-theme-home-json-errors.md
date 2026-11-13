---
id: "kp-e6f7a8c9d0e1f2g3h4i5j6k7l8m9n0o1p2"
type: "knowledge-page"
title: "Sixteen Theme — Home JSON Errors and Missing Components"
description: "Runtime errors when rendering home JSON: SvgNotFound for blade-ui-kit icons and view not found for how-it-works component. Documented for BMAD + second brain. No implementation: only component location analysis."
frontmatter:
  github_issue: "#333"
links:
  github_issue: "#333"
status: draft
updated: 2026-10-11
tags: [docs, sixteen, knowledge-pages]
qmd: "sixteen theme home json errors sixteen"
---
## Runtime Errors (2026-10-09)

Due to the extended `home.json` structure now including `how_it_works` and `quick_actions` sections (to match `index.blade.php.old`), two runtime errors appear:

### Error 1: SvgNotFound
```
BladeUI\Icons\Exceptions\SvgNotFound - Svg by name "map-pin" from set "default" not found.
```
**Location**: `Themes/Sixteen/resources/views/components/blocks/features/grid.blade.php:26`
**Cause**: The `features` section in `home.json` references icons (`map-pin`, `bell`, `chart-bar`, `users`, `shield-check`, `device-phone-mobile`) via the `icon` field. The Blade UI Kit icons are not installed/provisioned in the Sixteen theme.
**Fix**: Either install the blade-ui-kit icon packs or replace `icon` field values with existing icon names, or use `heroicon` equivalents.

### Error 2: view not found
```
view not found: pub_theme::components.blocks.how-it-works
```
**Location**: `laravel/Themes/Sixteen/resources/views/pages/index.blade.php` line 7 (via `pub_theme::layouts.app`)
**Cause**: The `home.json` now includes `how_it_works` section but there's no corresponding Blade component at `pub_theme::components.blocks.how-it-works`. Same for `quick_actions` — no component defined.
**Fix**: Create the missing Blade components or remove these sections from `home.json` until components exist.

## BMAD Decisions

1. **home.json extended** with `how_it_works` and `quick_actions` to match `index.blade.php.old` pattern — accepted for data completeness.
2. **Components needed**:
   - `pub_theme::components.blocks.how-it-works` — renders 3-step process
   - `pub_theme::components.blocks.quick-actions` — renders CTA with single button
   - `pub_theme::components.blocks.features.grid` — already exists but needs icon packs
3. **No implementation** per BMAD instructions — only analysis and documentation recorded.
4. **Pending**: Story `theme-sixteen-nine11-homepage-json-alignment.story.md` tracks the alignment; component creation is a separate task.

## Second Brain Notes

- Hindsight initiative: `kp-0f0a55f6caae40179f5fe5d26e093f92`
- Story: `theme-sixteen-twentyone-json-alignment.story.md` (epic) + `theme-sixteen-nine11-homepage-json-alignment.story.md` (nine11 alignment)
- Decision log: `docs/decision-log.md` entry dated 2026-10-09
- Next: Component creation requires `ctx_index` + Blade component scaffolding; outside current scope.