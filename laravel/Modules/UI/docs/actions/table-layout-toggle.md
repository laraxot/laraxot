---
title: "TableLayoutToggleTableAction"
type: concept
tags: [table, layout, toggle]
created: 2026-07-14
updated: 2026-07-14
qmd: "table-layout-toggle tablelayouttoggletableaction"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./table-layout-toggle-1.md"
---

# TableLayoutToggleTableAction

## Scopo

Bottone **Cambia layout** (lista ↔ griglia) sulle `XotBaseListRecords` con `HasXotTable`. Solo UX: stessi record, presentazione diversa.

## Flusso attuale (corretto)

```
click → toggleLayout()
  → getCurrentLayout() + toggle()
  → setTableLayout()        // sessione
  → resetTable()            // invalida tabella Filament
  → js('$wire.$refresh()')  // remount layoutView da sessione
```

`HasTableLayoutPage::mountTableLayoutFromSession()` (in `bootHasXotTable`) riallinea `$layoutView` dopo il refresh. `HasXotTable::table()` legge `$layoutView`.

## Componenti

| File | Ruolo |
|------|-------|
| `TableLayoutToggleTableAction` | Toggle sessione + refresh UI |
| `TableLayoutTrait` | `getCurrentLayout` / `saveLayout` su sessione |
| `HasTableLayoutPage` | `$layoutView` + mount da sessione |
| `HasXotTable` | Render colonne/griglia da `$layoutView` |

## Cosa non toccare

- **Non** spostare il toggle in `HasTableLayoutPage` — duplicazione inutile.
- **Non** rimuovere `TableLayoutTrait` dall’Action — è il punto che scrive sessione al click.
- **Non** refactorare icona/tooltip in closure se non c’è bug visivo.

## Fix doppio click

Vedi [bugfix](../bugfix-table-layout-toggle-not-working.md): bastano `resetTable()` + `$wire.$refresh()` dopo `setTableLayout()`.

## Collegamenti

- [Bugfix doppio click](../bugfix-table-layout-toggle-not-working.md)
- [Disciplina agente](../../../docs/wiki/memories/agent-table-layout-toggle-discipline.md)
- [Contratto Xot](../../Xot/docs/filament/table-layout-toggle-contract.md)
- [Tema One — tabelle](../../../Themes/One/docs/filament-resource-schemas-tables.md)
