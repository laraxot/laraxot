---
title: "Filament list page e ResourceTable: confine di ownership"
description: "Regola canonica Laraxot per evitare getTableColumns morto nelle pagine XotBaseListRecords."
module: Xot
status: active
updated_at: '2026-09-10'
---

# Filament list page e ResourceTable: confine di ownership

## Contratto canonico

Una classe che estende `XotBaseListRecords` non deve dichiarare `getTableColumns()`. La pagina non compone `HasXotTable`; la tabella è costruita dalla Resource e delegata alla classe attinente che estende `XotBaseResourceTable`.

```text
ListRecords page
  -> XotBaseResource::table()
  -> XotBaseResource::getTableClass()
  -> <Resource>/Tables/<PluralModel>Table
  -> HasXotTable::table()
  -> getTableColumns()
```

## Perché

Un `getTableColumns()` scritto nella list page è un hook morto: Filament può mostrare la pagina senza usare quelle colonne. La duplicazione tra pagina e Table crea due fonti di verità e rende possibili regressioni silenziose durante migrazioni o refactoring.

## Regole operative

1. La list page contiene soltanto responsabilità di pagina.
2. Colonne, filtri e azioni tabellari vivono nella ResourceTable attinente.
3. `XotBaseResource::getTableClass()` deve risolvere una sottoclasse di `XotBaseResourceTable`.
4. La migrazione deve preservare l'intero schema; non basta creare una Table vuota.
5. Relation manager, manage-related page e table widget sono contesti distinti perché possono comporre `HasXotTable` direttamente.

## Quality gate

- `ListPageHasTableClassTest` impedisce list page senza Table attinente.
- `ListRecordsPagesDeclareColumnsTest` impedisce colonne dichiarate nella pagina.
- Ogni modulo deve aggiungere un test sul contenuto della propria Table quando sposta uno schema esistente.

## Esempio

`Modules/Quaeris/Filament/Resources/SurveyPdfResource/Pages/ListSurveyPdfs` delega a `SurveyPdfResource/Tables/SurveyPdfsTable` e il test del modulo verifica wiring, assenza dell'hook nella pagina e ordine delle colonne.
