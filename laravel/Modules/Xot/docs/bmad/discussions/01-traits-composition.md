# GitHub Discussion — Architettura: composizione trait vs ereditarietà statica

**Repo:** `git@github.com:laraxot/module_xot_fila5.git`
**Category:** `Architecture / Refactor`
**Title:** `Xot: traits di composizione per Form/Table/Infolist (istanza non statica)`

## Contesto
Stiamo migrando la classe astratta `XotBaseResource*` da un modello a metodi statici a un modello a istanza con trait dedicati (`HasXotForm`, `HasXotInfolist`, `HasXotTable`). L'obiettivo è:

- Rimuovere dipendenze da `static::class` dentro i metodi pubblici
- Centralizzare in trait riutilizzabili la logica di `form()`, `infolist()`, `table()`
- Eliminare valori hardcoded (`->columns(2)`)
- Evitare conflitti di composizione (`TransTrait` dichiarato due volte)

## Stories collegate
1. [Story 01 — Rimuovere TransTrait](../stories/01-refactor-table-trans.md)
2. [Story 02 — HasXotForm: istanza + colonne](../stories/02-refactor-hasxotform.md)
3. [Story 03 — XotBaseResourceForm: use HasXotForm](../stories/03-refactor-resource-form.md)
4. [Story 04 — XotBaseResourceInfolist: istanza + trait](../stories/04-refactor-infolist.md)

## Issues collegate
- #01 — TransTrait ridondante
- #02 — HasXotForm: istanza + colonne
- #03 — XotBaseResourceForm: use HasXotForm
- #04 — XotBaseResourceInfolist: istanza + trait

## Domande aperte
- Conviene standardizzare un `XotBaseResource` con `XotBaseResourceForm`/`Infolist`/`Table` come *composition root* (usabile insieme)?
- Il default di `getFormColumns()` deve restare `2` o diventare `null` per delegare a Filament?
- Vogliamo mantenere `getSteps()` statico o migrare a istanza con Wizard Schema?

## Comandi
```bash
cd laravel/Modules/Xot
gh discussion create --title "Xot: traits di composizione per Form/Table/Infolist" \
  --category "Architecture" \
  --body-file docs/bmad/discussions/01-traits-composition.md
```

## Backlink
- Index: `docs/bmad/README.md`
