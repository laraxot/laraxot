---
id: story-xotbaseresourcetable-model-property-audit
title: "XotBaseResourceTable — Aggiunta $model e Audit getTableColumns()"
type: refactoring
scope: module:Xot
github: {issues: "https://github.com/laraxot/module_xot_fila5/issues/115", discussions: "https://github.com/laraxot/module_xot_fila5/discussions/117"}
---

# XotBaseResourceTable — Aggiunta `$model` e Audit `getTableColumns()`

## Contesto
`XotBaseResourceTable` è la classe base astratta per tutte le Table class dei moduli. Ogni sottoclasse deve implementare `getTableColumns()` per definire le colonne della tabella Filament.

## Obiettivi
1. Aggiungere `protected static string $model =` a ogni sottoclasse per rendere esplicito il modello a cui fa riferimento
2. Audit di `getTableColumns()` per verificare che tutti i campi esistano ancora sul modello
3. Migliorare UI/UX delle colonne (formattazione, icone, visibilità, ordinamento)

## Stato
- [ ] Individuare tutte le sottoclassi (trovate 80+)
- [ ] Per ognuna: aggiungere `$model` se mancante
- [ ] Audit colonne: verificare esistenza campi sul modello
- [ ] Migliorare UI/UX (icone, formattazione, toggleable, sortable)
- [ ] Verifica `php -l` su tutti i file toccati
- [ ] Creare BMAD stories per ogni modulo modificato
- [ ] Aggiornare GitHub Issue #115 / Discussion #117