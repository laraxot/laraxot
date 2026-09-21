---
id: story-template-method-final-table-form
title: "Template Method Pattern — form() e table() devono essere final"
descript_type: bmad
scope: module:Xot
status: superseded
github: {issues: "https://github.com/laraxot/module_xot_fila5/issues/115", discussions: "https://github.com/laraxot/module_xot_fila5/discussions/117"}
---

# Template Method Pattern: `form()` e `table()` devono essere `final`

**Perché non ci ho pensato prima:** non ho applicato il principio fondamentale del Template Method Pattern — il metodo scheletro (`table()`/`form()`) deve essere `final` per impedire che una sottoclasse rompa il contratto di delega. Se `table()` può essere sovrascritto, non è più un Template Method ma solo ereditarietà libera.

**Le classi che violano il pattern:**
- `ManageMailTemplates` sovrascrive `table()` per inserire `modifyQueryUsing` e ricostruire le azioni
- `ManageSurveyPdfQuestionCharts` sovrascrive `table()` per modificare colonne e azioni

**Fix immediato:** `final` su `table()` e `form()` in `XotBaseManageRelatedRecords.php`. Le sottoclassi devono usare gli hook (`getTableColumns()`, `getTableHeaderActions()`, `getTableFilters()`) per estendere, non sovrascrivere il metodo core.

## SUPERSEDED (2026-09-11, sera)

Duplica (con meno dettaglio) lavoro già fatto e già committato: `form()`/
`table()` sono `final` dal commit `1aa0a20f` (modulo Xot), con `getFormSchema()`
+ `modifyRelatedQuery()` come hook aggiuntivi, le 3 pagine reali (non solo
le 2 elencate qui — manca `ManageRolePermissions`) migrate e verificate
(PHPStan 0 errori, 6/6 regression test). Trattamento completo:
`xotbasemanagerelatedrecords-convention-over-configuration.story.md`
(sezione finale) e docblock di classe in
`XotBaseManageRelatedRecords.php`. Non cancellata per policy repo (mai
eliminare story esistenti) — vedi [[xotbasemanagerelatedrecords-quarto-contratto-getTableColumns]]
per lo stesso pattern di duplicazione già visto oggi.
