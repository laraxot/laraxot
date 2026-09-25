---
title: "Ratings table PDF — componente Blade riutilizzabile nel modulo Rating"
type: story
module: Rating
status: ready-for-dev
story_id: "5.226"
epic: "5"
created: 2026-09-23
updated: 2026-09-23
qmd: "ratings table pdf blade component reusable Rating module getTxtHtml getValueHtml getNoteHtml"
related:
  - ../../../Ptv/resources/views/scheda/index/pdf.blade.php
  - ../../../IndennitaResponsabilita/resources/views/scheda/index/pdf.blade.php
  - ../../../IndennitaResponsabilita/resources/views/indennita_responsabilita/show/pdf.blade.php
  - ../../../IndennitaResponsabilita/resources/views/indennita_responsabilita/index/pdf.blade.php
  - ../../../IndennitaResponsabilita/resources/views/scheda/show/pdf.blade.php
  - ../../../IndennitaResponsabilita/resources/views/scheda/index/pdf.blade.php
  - ../../../IndennitaResponsabilita/resources/views/actions/make-pdf.blade.php
  - ../../../IndennitaResponsabilita/resources/views/actions/make-pdf-by-record.blade.php
  - ../../app/Models/Traits/HasRatingsTrait.php
  - ../../app/Models/BaseRating.php
  - ../../../Ptv/resources/views/scheda/index/pdf.blade.php
---

# 5.226 — Ratings table PDF: estrarre in componente Blade riutilizzabile nel modulo Rating

## Sintomo

Lo stesso blocco HTML della tabella ratings è duplicato in **8 file PDF**
(spread tra Ptv e IndennitaResponsabilita):

| File | Note |
|------|------|
| `Ptv/resources/views/scheda/index/pdf.blade.php` | ha `getNoteHtml()` inline |
| `IndennitaResponsabilita/resources/views/scheda/index/pdf.blade.php` | colonna note a parte |
| `IndennitaResponsabilita/resources/views/scheda/show/pdf.blade.php` | colonna note a parte |
| `IndennitaResponsabilita/resources/views/indennita_responsabilita/index/pdf.blade.php` | colonna note a parte |
| `IndennitaResponsabilita/resources/views/indennita_responsabilita/show/pdf.blade.php` | colonna note a parte |
| `IndennitaResponsabilita/resources/views/actions/make-pdf.blade.php` | colonna note a parte |
| `IndennitaResponsabilita/resources/views/actions/make-pdf-by-record.blade.php` | colonna note a parte |
| `IndennitaResponsabilita/resources/views/indennita-responsabilita/show/pdf.blade.php` | colonna note a parte |

## Root cause

Nessun componente condiviso; ogni modulo ripete la stessa struttura HTML.
Viola il principio DRY e rende difficile mantenere coerenza (es. colonna
note con `rowspan` su `$loop->last` vs inline per riga).

## Fix

1. Crea componente Blade `rating-pdf-row` nel modulo Rating
   (`laravel/Modules/Rating/resources/views/components/rating-pdf-row.blade.php`)
2. Componente accetta: `$rating`, opzionale `$showNote` (default true),
   opzionale `$noteRowspan` (default false)
3. Sostituisce tutti i 8 blocchi duplicati con `<x-rating::pdf-row .../>`
4. Quando `$noteRowspan=true`, accumula note come nell'originale
   (`$loop->last` + `rowspan`)
5. Test Pest che verifica il rendering del componente

## AC

- [x] Componente `rating-table.blade.php` esiste (già presente) — PRIMA di modificare i file PDF
- [x] Fix `HaDirittoFilter` import mancante in `BaseSchedasTable.php` (errore Intero Server Error su `/indennitaresponsabilita/admin/scheda-dips`)
- [ ] Test Pest verde: rendering componente con rating foglia e con rating padre
- [ ] PHPStan scope Rating OK
- [ ] Sprint-status.yaml aggiornato

## Note tecniche

Il componente delega a:
- `$rating->getTxtHtml()` → txt (oppure child txt se rating ha figli)
- `$rating->getValueHtml()` → valore numerico (oppure txt child se rating ha figli)
- `$rating->getNoteHtml()` → note dal pivot

Per la colonna note con rowspan, il componente supporta modalità accumulo
(`$noteRowspan=true`) che concatena le note solo all'ultima riga.
