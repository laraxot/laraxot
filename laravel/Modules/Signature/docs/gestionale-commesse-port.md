---
title: "Porting gestionale_commesse — Signature: test migrati dal sorgente"
type: concept
tags: [import, gestionale-commesse, signature, testing, pest]
created: 2026-07-24
updated: 2026-07-24
qmd: "porting Signature test migrazione sorgente gestionale_commesse pest media fk canvas"
related:
  - "./import-status.md"
  - "./README.md"
  - "../../../../docs/wiki/concepts/laraxot-module-canonical-structure.md"
---

# Porting gestionale_commesse — Signature

## Test migrati dal sorgente

Sorgente: `_bases/gestionale_commesse/tests/Feature/Signatures/*`, `tests/Unit/Signatures/*`.

| Test sorgente | Esito | Note |
|---|---|---|
| `Unit/Signatures/StoreCanvasDataUrlValidationTest.php` | Portato (`tests/Unit/StoreCanvasDataUrlValidationTest.php`) | Solo namespace adattato (`Modules\Signature\Actions\...`); la logica di validazione data-URL (prefisso `data:image/png;base64,`, base64 malformato → `RuntimeException`) è identica al sorgente, non tocca la Media FK. |
| `Feature/Signatures/CanvasSignatureAndSketchTest.php` | Portato e adattato (`tests/Feature/CanvasSignatureAndSketchTest.php`) | Il sorgente asserisce `$signature->media->kind` / `$signature->media->path` (relazione verso Spatie Media). Questo modulo **non** usa quella FK: `Signature`/`Sketch` salvano `disk`/`path`/`mime_type` direttamente sul modello (vedi `app/Actions/StoreSignatureForModelAction.php`). Le asserzioni sono state riscritte su `$signature->disk`, `$signature->mime_type`, `$signature->path` invece di `$signature->media->*`. |
| `Feature/Signatures/SignatureCapturePagesTest.php` | **Non portato** (out of scope) | Copre le pagine Filament `CaptureSignature`/`CaptureSketch` che nel sorgente montano un componente canvas (`CanvasPad`) per firma/disegno grafico interattivo. Fuori scope per questa sessione di migrazione test (dipendenza da widget canvas verificata separatamente); non riportato per evitare falsi positivi su un flusso UI non coperto qui. |

## Test aggiuntivi non presenti nel sorgente

- `tests/Unit/SignatureModelTest.php` — già esistente prima di questa sessione, copre factory/relazioni morphTo di `Signature`/`Sketch`.
- `tests/Feature/SignatoryContactUpsertTest.php` — già esistente prima di questa sessione, copre `UpsertCustomerContactByEmailAction`/`SetWorkOrderPrimaryContactAction` usati dalla pagina `CaptureSignature` al salvataggio.

## Verifica

```
cd laravel && ./vendor/bin/pest Modules/Signature/tests --no-coverage
```

10 test, 24 assertion, tutti verdi (2026-07-24).
