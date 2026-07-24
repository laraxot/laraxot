---
title: "Porting gestionale_commesse — Vehicle: test migrati dal sorgente"
type: concept
tags: [import, gestionale-commesse, vehicle, testing, pest]
created: 2026-07-24
updated: 2026-07-24
qmd: "porting Vehicle test migrazione sorgente gestionale_commesse pest media documento"
related:
  - "./import-status.md"
  - "./README.md"
  - "../../../../docs/wiki/concepts/laraxot-module-canonical-structure.md"
---

# Porting gestionale_commesse — Vehicle

## Test migrati dal sorgente

Sorgente: `_bases/gestionale_commesse/tests/Feature/Vehicles/*`.

| Test sorgente | Esito | Note |
|---|---|---|
| `Feature/Vehicles/VehicleCrudTest.php` | Portato (`tests/Feature/VehicleCrudTest.php`) | Adattamento solo di namespace (`Modules\Vehicle\Models\Vehicle` invece di `Modules\Vehicles\Infrastructure\Models\Vehicle`); logica CRUD/soft-delete identica. |
| `Feature/Vehicles/AddVehicleDocumentActionTest.php` | Portato (`tests/Feature/AddVehicleDocumentActionTest.php`) | Adattamento di namespace; a differenza di `Signature`, `VehicleDocument` **mantiene** una vera relazione `belongsTo` verso `Modules\Document\Models\Media` (non è l'approccio "vecchio" da escludere secondo il playbook: qui la FK a Media è l'implementazione corrente del modulo, non quella superata). |

## Debito infrastrutturale risolto in questa sessione

La fixture sqlite condivisa dei test (`laravel/database/fixcity_data.sqlite`, vedi `Modules/Xot/docs/wiki/concepts/fixcity-data-sqlite-pest-bootstrap.md`) non conteneva la tabella `media` del modulo `Document` (`2026_07_23_180010_create_media_table.php`): la migrazione fallisce con `php artisan migrate` per un bug di `XotBaseMigration::getModelClass()` che risolve il nome tabella `media` nel modello inesistente `Modules\Document\Models\Medium` invece di `Media` (bug nel modulo `Document`, fuori scope/lock di questa sessione — non modificato). La tabella `media` è stata creata manualmente (stesso schema della migrazione, forward-only, nessun dato esistente toccato) e registrata in `migrations` per non essere ritentata da un futuro `artisan migrate`.

## Verifica

```
cd laravel && ./vendor/bin/pest Modules/Vehicle/tests --no-coverage
```

6 test, 14 assertion, tutti verdi (2026-07-24).
