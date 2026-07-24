---
title: "Debito D2 — VehicleDocument e Media di dominio"
type: how-to
tags: [technical-debt, vehicle, document, media, fk]
created: 2026-07-24
updated: 2026-07-24
qmd: "debt vehicle document media_id fk document media spat ie"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
related:
  - "./gestionale-technical-debt.md"
  - "./debt-document-media-conflict.md"
---

# Debito D2 — VehicleDocument ↔ Media

## Perché

`VehicleDocument` rappresenta un documento del mezzo (scadenze, libretto, assicurazione). Nel SRC punta all’allegato di dominio Document. In Laraxot il model fa:

```php
return $this->belongsTo(Media::class); // Modules\Document\Models\Media
```

e la migrazione storica aveva `foreignId('media_id')->constrained('media')` → FK verso **qualsiasi** riga della tabella `media` (oggi Spatie).

## Dipendenza

**D1 prima di D2.** Senza tabella/domain Media corretta, ogni fix Vehicle è cosmetico.

## Come risolvere (dopo D1 Opzione A)

1. Confermare che `document_media` (o nome scelto) esiste e ha PK bigint.
2. Migrazione Vehicle (edit + bump timestamp se già Ran):
   - Se FK DB desiderata: `constrained('document_media')`.
   - Se si preferisce soft-ref (come users UUID): `unsignedBigInteger('media_id')->index()` senza constrained.
3. Model: `belongsTo(Document\Models\Media::class)` resta valido se `$table` Document è corretto.
4. Se si sceglie **D1 Opzione B (Spatie)**:
   - Cambiare relazione a `Spatie\MediaLibrary\MediaCollections\Models\Media` **oppure** morph/custom.
   - Aggiornare form Filament upload a Spatie.
5. Seeder/factory: creare Media dominio prima del VehicleDocument.
6. Test Pest: create vehicle + document + assert path/kind.

## Come risolvere (contenimento senza D1)

- Nascondere Resource/relation documenti veicolo in Filament.
- Non promettere feature “documenti mezzo” in UAT.
- Documentare in `Modules/Vehicle/docs/import-status.md` lo stato “blocked by D1”.

## DoD

- [ ] Insert VehicleDocument + Media dominio funziona end-to-end
- [ ] Nessuna lettura colonne Spatie via Document Media
- [ ] PHPStan Vehicle 0 errori reali
- [ ] Link story a D1 nell’Issue

## Non fare

- Puntare `media_id` a Spatie “perché la FK passa” sapendo che il model Document aspetta `kind`/`path`.
- Drop `vehicle_documents` con dati reali senza piano.
