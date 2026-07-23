# HTML Parity Analysis: `segnalazione-area-personale`

**Date:** 2026-04-08
**Reference:** https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-area-personale.html
**Local:** http://127.0.0.1:8000/it/tests/segnalazione-area-personale
**Target:** 90% HTML structure parity on `body` markup, excluding `script` and `style`

## Tooling

- Script: `bashscripts/html/html-structure-compare.sh`
- Bash comparator: `bashscripts/html/compare-html.sh`
- Python comparator: `bashscripts/html/compare-html-body.py`
- Output dir: `laravel/Themes/Sixteen/docs/prompts/segnalazione-area-personale/body-structure-comparison*`

## Current Measurements

- Pass 1 with generic JSON blocks: `54.4%`
- Pass 2 with `flow/area-personale/dashboard`: `51.1%`
- Pass 5 after restoring the generic `cms.view` wrapper philosophy: `44.4%`
- Pass 7 after normalizing framework wrappers in the bash comparator: `79.7%`
- Pass 8 after aligning latest messages and tab sequence: `80.5%`
- Pass 10 after adding missing filter search blocks: `89.3%`
- Pass 11 after aligning service-tab IDs/classes and repeated accordion identifiers: `91.9%`

## What Changed In This Session

- Fixed `bashscripts/html/compare-html.sh` so it no longer crashes on its own extractor output and now ignores framework-only wrappers that distorted the structural result.
- Added breadcrumb payload in the page JSON and rendered the breadcrumb block in the dedicated page replica.
- Aligned `latest-posts`, tab ordering, filter search blocks, dropdown controls, attachment list markup, repeated accordion identifiers, and service-tab classes with the reference structure.
- Kept the page on the shared `tests/[slug].blade.php` route with `<x-layouts.app>` and the same CMS-view philosophy as the generic Folio page.

## Current Findings

- The phase target is now reached in the bash structural comparator: `91.9%` similarity.
- The remaining delta is concentrated in shared layout/header markup and a few semantic IDs that belong outside the page-specific body replica.
- `data-element` coverage is close to the reference (`16` vs `15`).
- The local page still lacks `article` and `aside` semantic tags present in the reference, but these no longer block the phase threshold.

## Residual Gaps

1. Shared personal-area header/account dropdown in the global layout still differs from the reference.
2. Some comparator semantic IDs remain absent because they belong to other shared page areas, not to this specific test block.
3. The page-specific block is structurally aligned enough for this phase, but visual/function parity can still be improved later without re-opening the HTML parity phase.

## Artifacts

- `body-structure-comparison/` contains the initial full report.
- `body-structure-comparison-pass2/` contains the dashboard-based attempt.
- `body-structure-comparison-pass5/report_20260408_164500.md` contains the first stable post-reset baseline.
- `body-structure-comparison-pass11/report_20260408_172952.md` contains the successful parity report above threshold.
