---
name: wts-coverage
description: PHPStan/PHPMD/Pest gate status for Modules/Wts — 2026-09-07 final cleanup
metadata:
  type: quality-gate
---

# Wts — quality gate status (2026-09-07)

Most of this module's PHPStan errors (487 originally) were fixed by other agents in
earlier sessions this same day. This entry covers the last 2 remaining errors.

## PHPStan

- Before: 2 errors — `chillerlan\QRCode\QRCode::VERSION_AUTO`/`OUTPUT_MARKUP_SVG`
  deprecated class constants in `app/Actions/RenderWtsQrSvgAction.php`.
- Fix: `Version::AUTO` + direct `QRMarkupSVG::class` output interface reference
  (`outputType => 'custom'`, avoiding the also-deprecated `CUSTOM` constant).
- **Runtime regression caught by testing, not PHPStan**: `chillerlan/php-qrcode` 5.x
  defaults `QROptions::$outputBase64` to `true`. Without `'outputBase64' => false`,
  `execute()` silently returned a base64 data URI instead of raw SVG, breaking
  `dataUri()`'s own wrapping. Added `'outputBase64' => false` to restore intended
  behavior — see second-brain
  `project_chillerlan_qrcode_v5_output_base64_default_true.md`.
- After: **0 errors** (`phpstan analyse Modules/Wts`, clean cache).

## PHPMD

No findings on the touched file.

## Pest

`RenderWtsQrSvgAction` had **zero** test coverage before this fix. Added
`tests/Unit/Actions/RenderWtsQrSvgActionTest.php` with 3 tests (renders SVG markup,
accepts a custom size, encodes correctly as a base64 data URI) — all passing. This
is exactly the test that caught the `outputBase64` runtime regression above; without
it the PHPStan-clean fix would have shipped broken.

Coverage: 0 -> 3 tests for this action (net increase, not just maintained).
