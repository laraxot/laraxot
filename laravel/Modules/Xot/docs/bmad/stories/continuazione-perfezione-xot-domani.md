---
title: "Xot — Perfezione Esportazione e Architettura"
type: module-fix
scope: Xot
epic: 5.124-bmad-user-module-perfection-study
bmad_version: v3.30.1
updated_at: '2026-09-22'
status: done
related:
  - ../docs/bmad/stories/cleanup-xot-2026-09-22.story.md
  - ../docs/bmad/stories/subagent-A-parallel-tasks-2026-09-22.story.md
---

# Xot — Stato Perfezione e Continuazione

## Stato Corrente (Domani)

- `CollectionExport.php`: corretto da Pint (`braces`, `unary_operator_spaces`, `not_operator_with_successor_space`)
- `XotBaseExporter.php`: funzionante (`getCachedColumns()` serializza `resource` + `tableFilters`)
- `XotBaseExportAction.php`: ripristinato (era stato cancellato per errore, ora presente)
- `XotBaseResourceTable.php`: nessun conflitto
- `XotBaseRelationManager.php`: nessun conflitto
- `HasXotTable.php`: nessun conflitto
- `phpstan analyse Modules/Xot`: [OK] No errors

## Continuazione Domani

1. Verificare che `CollectionExport` gestisca correttamente il formato misto (`path => label`) con dati reali (rating con titolo)
2. Testare `CollectionExportLabelledFieldsTest.php` con `labelledRows()` e verificare che `map()` restituisca il valore corretto (non il percorso)
3. Se `labelledRows()` fallisce, verificare il tipo `Collection<int|string, mixed>` (la chiave stringa è il percorso, il valore è il titolo; il `map()` legge il percorso e restituisce il valore)
4. Verificare `CollectionExport::headings()` per assicurarsi che il titolo (`Obiettivo A`) sia usato come etichetta colonna, non il percorso (`ratings_by_id.52.pivot.value`)
5. Aggiornare `docs/bmad/stories/` con i risultati dei test manuali
