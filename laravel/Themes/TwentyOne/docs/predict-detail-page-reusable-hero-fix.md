# Predict Detail Page Reusable Hero Fix

> Indice: [./00-INDEX.md](./00-INDEX.md)
> Modulo correlato: [../../Modules/Predict/docs/f1-world-champion-2026-detail-page-fix.md](../../Modules/Predict/docs/f1-world-champion-2026-detail-page-fix.md)

## Scopo

Introdurre un hero summary riusabile per i mercati predict nel tema TwentyOne, senza duplicare la logica tabellare degli outcome gia coperta dal widget Filament.

## Pattern UI

- hero editoriale con titolo, stato e metriche sintetiche
- outcome principali mostrati come hint visivi, non come lista interattiva sostitutiva
- CTA verso il contenuto principale del widget detail

## Componenti

- [../resources/views/components/blocks/predict/detail-summary.blade.php](../resources/views/components/blocks/predict/detail-summary.blade.php)
- [../resources/views/components/blocks/hero/predict-test.blade.php](../resources/views/components/blocks/hero/predict-test.blade.php)
