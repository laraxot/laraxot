---
id: phpstan-Signature-fix
slug: phpstan-Signature
scope: [module:Signature, project:base_workorder_fila5]
status: Done
priority: High
created: 2026-09-06
updated: 2026-09-06
---

## Problema
PHPStan errors in Modules/Signature (21 errori: `cast.int`/`cast.string` su
`mixed` in `CaptureSignature.php`, `CaptureSketch.php`,
`CanvasSignatureAndSketchTest.php`).

## Solution
1. Analyze with phpstan — done, 21 errori (non 33 come stimato inizialmente).
2. Fix pattern errors — done, vedi dettaglio completo in
   `01.Signature-phpstan-fix.story.md` (stessa scope, story duplicata non
   rimossa per policy no-delete; questa e collegata a quella).
3. Verify with phpmd + pest (phpinsights non installato in questo repo,
   rimosso per incompatibilita' con Pest 5) — done, 0 errori PHPStan,
   phpmd solo warning pre-esistenti, pest 11 passed/3 failed pre-esistenti
   fuori scope.
4. Git sync — done (vedi commit nel repo indipendente Modules/Signature).

## Esito
0 errori PHPStan (da 21). Dettagli completi: `01.Signature-phpstan-fix.story.md`
e `docs/coverage.md`.
