---
id: story-table-setter-deprecation-handling
title: "Handling Deprecated Table Setter Methods in XotBaseManageRelatedRecords"
descript_type: bmad
scope: module:Xot
status: ready-for-dev
github: {issues: "https://github.com/laraxot/module_xot_fila5/issues/115", discussions: "https://github.com/laraxot/module_xot_fila5/discussions/117"}
---

# Handling Deprecated Table Setter Methods

## Context
In `XotBaseManageRelatedRecords::table()` method, we use deprecated Filament Table setter methods:
- `->columns()`
- `->headerActions()`
- `->recordActions()`
- `->toolbarActions()`
- `->filters()`

These are marked with `// @phpstan-ignore method.deprecated` to suppress PHPStan warnings.

## CORREZIONE (2026-09-11, verificato sul sorgente vendor, non solo dichiarato)

La sezione originale sotto ("Why These Are Necessary" / "configurator
pattern `$table->configure(fn(Table $table) => {...})`") citava un'API
che **non esiste**: `grep -rn "function configure(" vendor/filament/tables/src/Table.php`
non trova nulla, e nessun metodo `HasColumns`/`HasFilters`/`HasActions` è
marcato `@deprecated` nel senso descritto. Verificato invece il vero punto
di origine: `vendor/filament/tables/src/Concerns/HasColumns.php:124-128`
(e l'equivalente in `HasFilters`, `HasActions`, `HasRecords`) marca
`protected function getTableColumns(): array` **deprecato con messaggio
esplicito**: `@deprecated Override the \`table()\` method to configure the
table.` — cioè Filament raccomanda di NON avere più un hook
`getTableColumns()` separato, ma di configurare tutto direttamente dentro
`table()`.

Il deprecation warning quindi non riguarda i setter di `Table`
(`->columns()`, `->headerActions()`, ecc. — quelli non sono deprecati)
ma le NOSTRE 5 dichiarazioni di metodo (`getTableColumns()`,
`getTableHeaderActions()`, ecc. su `XotBaseManageRelatedRecords`), che
hanno lo stesso nome dei metodi deprecati ereditati dalla catena
`ManageRelatedRecords`/`InteractsWithTable`/`Contracts\HasTable` — un
override di un metodo deprecato resta un metodo deprecato agli occhi di
PHPStan, anche se il nostro contenuto non lo è.

**Perché la manteniamo comunque** (motivazione corretta, non quella
originale): la raccomandazione di Filament — tutto dentro `table()`, zero
hook separati — è pensata per Resource/pagine con UNA sola
configurazione. Qui `table()` è sigillato (`final`) apposta perché serve
un punto di estensione PER SOTTOCLASSE (`ManageContacts` vuole azioni
header diverse da `ManagePdfStyle`) — i 5 hook SONO quel punto di
estensione, la stessa "religione" già in uso su `XotBaseResourceTable`
(vedi [[xot-baseresourcetable-no-table-override]]). Accettare 5 ignore
mirati per riga è il costo di tenere un Template Method component-per-riga
invece di una configurazione monolitica per pagina — trade-off
deliberato, non un'incapacità di rimuoverli.

## Alternative Approaches Considered
1. **Remove the ignore comments and fix PHPStan errors** → non esiste un modo di farlo senza rinominare i 5 hook (rompendo la convenzione condivisa con `XotBaseResourceTable`) o abbandonare gli hook per sottoclasse (regredendo alla "delega totale" già scartata più volte oggi)
2. **Create wrapper methods that avoid deprecated calls** → Would add indirection for no gain
3. **Wait for Filament to remove the methods** → Not proactive; current approach is valid

## Decision
Keep the `// @phpstan-ignore method.deprecated` comments with clear justification:
- They suppress known, intentional usage of deprecated-but-stable APIs
- The deprecation warns about future direction, not current brokenness
- Our usage pattern is consistent with the rest of the Xot codebase
- Migrating would require significant refactoring with minimal functional gain

## Acceptance Criteria
- [ ] Document rationale in code comments if not already present
- [ ] Ensure all similar usages in Xot module follow same pattern
- [ ] Track Filament version to revisit when deprecated methods are actually removed
- [ ] No PHPStan errors from these specific lines when ignores are present

## Related Files
- `Modules/Xot/app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php` - Primary usage
- `Modules/Xot/app/Filament/Traits/HasXotTable.php` - Similar usage in trait methods