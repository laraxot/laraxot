---
id: Xot/xot-base-exporter
title: "XotBaseExporter: bridge getXlsFields() → ExportAction nativa Filament 5"
epic: "5"
story: "xot-base-exporter"
slug: xot-base-exporter
status: done
module: Xot
priority: P1
created: 2026-09-22
updated: 2026-09-22
related:
  - ../../../app/Exports/XotBaseExporter.php
  - ../../../app/Filament/Actions/XotBaseExportAction.php
  - ../../../app/Exports/CollectionExport.php
  - ../../../tests/Unit/Exports/XotBaseExporterTest.php
  - ../../../tests/Unit/Exports/ResourceWithXlsFieldsStub.php
  - ../../../../Ptv/app/Filament/Exports/SchedaExporter.php
  - ../../../../Ptv/docs/bmad/brainstorming/export-xls-native-vs-custom-comparison.md
  - ./collection-export-intestazioni-esplicite.story.md
qmd: "XotBaseExporter ExportAction Filament 5 export nativo getXlsFields tableFilters options job queue ExportColumn columnMap ratings title label esplicita SchedaExporter"
description: "Exporter astratto Xot che riusa il contratto getXlsFields() dei Resource per l'ExportAction nativa: colonne dinamiche (rating) anche nel job queued via options serializzati."
---

# Story Xot: XotBaseExporter — bridge getXlsFields → ExportAction nativa

Status: done

## Story

Come maintainer Xot,
voglio un exporter Filament 5 riusabile che legga le colonne dal `getXlsFields()`
del Resource corrente,
cosi' ogni lista scheda espone l'export nativo senza duplicare la definizione dei campi.

## Acceptance Criteria

- [x] `XotBaseExporter` estende `Filament\Actions\Exports\Exporter` (base Xot, mai
      istanze Filament dirette nei moduli).
- [x] `getColumns()` (dispatch) legge `tableFilters` dal ListRecords corrente
      (`app('livewire')->current()`).
- [x] `getCachedColumns()` (job queued) ricostruisce le colonne dagli `options`
      serializzati (`resource` + `tableFilters`) — nel job non esiste componente
      Livewire.
- [x] Chiave stringa in `getXlsFields()` = percorso `data_get`, valore = label
      esplicita (title rating); chiave intera = percorso tradotto via `TransArrayAction`.
- [x] Nomi colonna senza `.` (il columnMap Filament usa `data_get`): `state()`
      risolve il percorso reale.
- [x] `preventFormulaInjection()` su ogni colonna (CWE-1236).
- [x] `XotBaseExportAction` serializza resource+filtri negli options dell'export.
- [x] `SchedaExporter` (Ptv) e `IndennitaResponsabilitaExporter` (IR) concreti.
- [x] PHPStan max: 0 errori sul perimetro toccato.
- [x] Test unit `XotBaseExporterTest` + harness standalone verde (Pest non
      eseguibile: DB 10.100.200.53 irraggiungibile — blocco ambiente).

## Dev Notes

- `Exporter::getCachedColumns()` non e' `final`: l'override e' il punto di
  estensione corretto per le colonne dinamiche in queue.
- `getModel()` risolve dal Resource corrente: un solo exporter serve tutte le
  liste scheda (Dip/Po/Dirigente/Polizia/Regionale).

## Esito

Implementato 2026-09-22. Confronto votato custom vs nativo:
`Modules/Ptv/docs/bmad/brainstorming/export-xls-native-vs-custom-comparison.md`.
