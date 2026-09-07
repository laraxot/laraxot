---
id: phpstan-Wts-fix
slug: phpstan-Wts
scope: [module:Wts, project:base_workorder_fila5]
status: Done
priority: High
created: 2026-09-06
updated: 2026-09-07
---

## Problema (risolto)
Ultimi 2 errori residui (il grosso del modulo era gia' stato sistemato da altri
agenti in sessioni precedenti): `app/Actions/RenderWtsQrSvgAction.php` usava
`QRCode::VERSION_AUTO`/`QRCode::OUTPUT_MARKUP_SVG`, entrambe costanti deprecate in
`chillerlan/php-qrcode` 5.0.0.

## Fix
- `QRCode::VERSION_AUTO` -> `chillerlan\QRCode\Common\Version::AUTO`
- `QRCode::OUTPUT_MARKUP_SVG` -> riferimento diretto a `QRMarkupSVG::class` via
  `outputInterface` + `outputType => 'custom'` (anche `QROutputInterface::CUSTOM` e'
  deprecata senza sostituto, usata la stringa letterale `'custom'`)
- **Trovato runtime bug non catturabile da PHPStan**: la libreria v5.0.0 ha cambiato
  il default di `QROptions::$outputBase64` a `true` — senza `'outputBase64' => false`
  esplicito, `execute()` restituiva un data-URI invece dell'SVG grezzo, e `dataUri()`
  lo ri-wrappava producendo output corrotto. Scoperto solo scrivendo e lanciando un
  test Pest (0 test esistenti su questa action prima). Vedi second-brain
  `project_chillerlan_qrcode_v5_output_base64_default_true.md`.

## Verifica
- PHPStan: 2 -> 0 errori (`phpstan analyse Modules/Wts`, cache pulita)
- PHPMD: nessuna segnalazione sul file toccato
- Pest: **3 nuovi test aggiunti** (`tests/Unit/Actions/RenderWtsQrSvgActionTest.php`,
  0 -> 3, tutti verdi) — coverage aumentata, non solo mantenuta
- Dettaglio: `docs/coverage.md`
