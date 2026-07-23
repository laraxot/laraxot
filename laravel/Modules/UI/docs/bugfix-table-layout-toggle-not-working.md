---
module: UI
topic: table-layout-toggle
status: resolved
related_issue: provtv/base_ptv_fila5_mono
related:
  - "./00-index-1.md"
  - "./00-index.md"
  - "./04-datas.md"
  - "./advanced-form-components-1.md"
  - "./advanced-form-components.md"
  - "./agent-confidence-discipline.md"
  - "./agent-confidence-protocol.md"
  - "./agent-edit-discipline.md"
---

# Bug: «Cambia layout» richiedeva due click

## Sintomo

Click su **Cambia layout** → la tabella non cambiava al primo click.

## Causa

Dopo `setTableLayout()` la sessione era aggiornata, ma Filament/Livewire **non ricalcolava** `HasXotTable::table()` con il nuovo layout. `$layoutView` restava quello del mount finché la pagina non veniva forzata a riallinearsi.

## Fix minimo (quello che funziona)

In `TableLayoutToggleTableAction::toggleLayout()`, **dopo** `setTableLayout()`:

```php
$livewire->resetTable();
$livewire->js('$wire.$refresh()');
```

Opzionale ma presente nel codice: `$livewire->dispatch('$refresh')` prima delle due righe sopra.

### Perché funziona

1. `setTableLayout()` persiste in sessione (`table_layout_default`).
2. `resetTable()` invalida lo stato tabella Filament (cache record, pagina, filtri form).
3. `$wire.$refresh()` forza il re-render Livewire → `bootHasXotTable()` → `mountTableLayoutFromSession()` rilegge la sessione in `$layoutView`.
4. `HasXotTable::table()` usa il `$layoutView` aggiornato.

**Non serve** spostare logica su altri trait, rimuovere `TableLayoutTrait` dall’Action, né introdurre `toggleTableLayout()` parallelo.

## Anti-pattern agente (imperdonabile)

| Tentativo sbagliato | Perché è merda |
|--------------------|----------------|
| `HasTableLayoutPage::toggleTableLayout()` nuovo | Duplica responsabilità già nell’Action + trait sessione |
| Rimuovere `TableLayoutTrait` dall’Action | Rompe il contratto esistente senza motivo |
| `readLayoutFrom` / `applyLayoutTo` statici nell’Action | Indirection, viola KISS |
| Closure dinamiche su icona/tooltip | Refactor cosmetico non richiesto |
| Accusare `dispatch('$refresh')` come «cargo cult» | Il vero gap era **mancanza** di `resetTable()` + `$wire.$refresh()` |

**Regola:** prima di refactorare, verificare se **2 righe** dopo la persistenza risolvono il render. Se sì → KISS vince.

## Verifica

1. Lista `HasXotTable` (es. ASZ Progressioni).
2. Un click → lista ↔ griglia.
3. Reload → layout persistito in sessione.

## Collegamenti

- [Architettura azione](./actions/table-layout-toggle.md)
- [Disciplina agente — non over-engineerare](../../../docs/wiki/memories/agent-table-layout-toggle-discipline.md)
- [Contratto Xot](../../Xot/docs/filament/table-layout-toggle-contract.md)
