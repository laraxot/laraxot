---
title: "Architecture — composite filter owns columnSpan"
type: architecture
module: Xot
status: decided
updated: 2026-09-16
qmd: "architecture HasXotTable composite filter columnSpan columns owns span"
related:
  - ../../../app/Filament/Traits/HasXotTable.php
  - ../../../../Ptv/docs/bmad/architecture/scheda-lista-filtri-layout.md
  - ../../../../../bashscripts/ai/wiki/memories/filament-composite-filter-owns-span.md
---

# Architecture — filtri composti: lo span vive sul filtro

## Problema

`HasXotTable::getTableFiltersFormColumns() = min(count(filters)+1, 6)` tratta ogni filtro come **una** cella. Un `Filter` con schema a N campi resta stretto se non dichiara `columnSpan`.

## Decisione

1. **Default Xot resta** (retrocompatibile).
2. Il **filtro composto** setta `->columnSpan(N)` (e `->columns(N)` interno) nel proprio `setUp()`.
3. La Table **non** overridea la griglia solo per allargare un filtro composto.
4. Euristiche magiche nel trait (“se schema count>1 allora span=2”) = no, senza ADR dedicato.

## Anti-pattern

Forzare `getTableFiltersFormColumns(): 6` + `columnSpan(3)` in `BaseSchedasTable` — già regredito una volta; conoscenza nel posto sbagliato.

## Caso Ptv

`AnnoValutatoreFilter`: `columns(2)+columnSpan(2)` → con 4 filtri e griglia 5, somma span = 5.
