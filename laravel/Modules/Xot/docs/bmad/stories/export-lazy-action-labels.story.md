---
<<<<<<< HEAD
title: "Story — ExportXlsLazyAction: label degradate a path tecnico"
=======
tags: [documentation]
created: 2026-09-26
updated: 2026-09-26
issues: []
discussions: []
title: "Story: ExportXlsLazyAction: label degradate a path tecnico"
>>>>>>> laraxot/dev
type: story
module: Xot
epic: export
story_id: "export-lazy-action-labels"
status: ready
track: feature/export
qmd: "ExportXlsLazyAction label degradate path tecnico intestazioni xls limite noto"
related:
  - ../../../app/Filament/Actions/Header/ExportXlsLazyAction.php
  - ../../../app/Exports/CollectionExport.php
  - ../../../docs/bmad/stories/collection-export-intestazioni-esplicite.story.md
---

# export-lazy-action-labels

## Perche'

Canale lazy/streaming (`ExportXlsLazyAction`) estrae solo i percorsi da
`getXlsFields()`: le label esplicite (`path => title`) degradano a path
<<<<<<< HEAD
tecnico nell'header — stessa UX rotta risolta altrove per
=======
tecnico nell'header: stessa UX rotta risolta altrove per
>>>>>>> laraxot/dev
`CollectionExport`/`ExportXlsByCollection`.

## Task

- [ ] Applicare il formato misto anche al canale lazy (stessa logica di
      `resolvePathFields`/`normalizeField` gia' estratta)
- [ ] O dichiararlo deprecato se non usato: grep caller, se zero → rimuovere

## AC

- [ ] Header lazy = title rating, mai path tecnico; oppure action rimossa

## Priorita'

<<<<<<< HEAD
Bassa — canale secondario. Verificare prima se qualcuno lo monta.
=======
Bassa: canale secondario. Verificare prima se qualcuno lo monta.
>>>>>>> laraxot/dev
