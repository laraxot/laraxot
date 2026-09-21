---
id: story-xot-manage-related-records-getmodelclass-uses-owner-not-relation
slug: manage-related-records-getmodelclass-uses-owner-not-relation
title: "STORY — XotBaseManageRelatedRecords: getModelClass() risolveva il model della Resource proprietaria invece del model della relazione mostrata"
description: "survey-pdfs/{id}/contacts vuota/500: getDefaultTableSortColumn() usava getModelClass() che, su pagine ManageRelatedRecords, tornava SurveyPdf invece di Contact, producendo 'order by survey_pdfs.id' su una query su contacts (colonna inesistente)."
document_type: story
category: bmad
scope: module:Xot
status: verified
version: 1.0.0
language: it-IT
ecosystem: Laraxot
priority: high
created_at: '2026-09-10'
updated_at: '2026-09-10'
tags: [bmad, story, xot, quaeris, filament, manage-related-records, getModelClass, table-sort]
related:
  - ../../../Quaeris/app/Filament/Resources/SurveyPdfResource/Pages/ManageContacts.php
  - app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php
  - app/Filament/Traits/HasXotTable.php
  - app/Filament/Traits/HasXotForm.php
github:
  repository: https://github.com/laraxot/module_quaeris_fila5
  issues: https://github.com/laraxot/module_quaeris_fila5/issues
---

# STORY — getModelClass() su ManageRelatedRecords torna il model della relazione, non della Resource

## Contesto (BMAD — Ricognizione)

Segnalato dall'utente: `/quaeris/admin/gaia/survey-pdfs/5/contacts` doveva
mostrare i contatti del `SurveyPdf` id=5 (57.592 righe in DB, relazione
`SurveyPdf::contacts()` corretta) ma la pagina falliva.

Log (`storage/logs/laravel.log`, piu' occorrenze fino a `2026-09-10 22:39:02`):

```
SQLSTATE[42S22]: Column not found: 1054 Unknown column 'survey_pdfs.id' in 'ORDER BY'
SQL: select * from `contacts` where `contacts`.`survey_pdf_id` = 5 ...
  order by `survey_pdfs`.`id` desc limit 10 offset 0
```

## Causa radice

`HasXotTable::getDefaultTableSortColumn()` costruisce l'ordinamento di
default cosi':

```php
$modelClass = $this->getModelClass();
$model = app($modelClass);
return $model->getTable().'.id';
```

Su `ManageContacts extends XotBaseManageRelatedRecords`, il trait
`HasRelationshipModelClass` (composto con precedenza esplicita:
`use HasXotTable { HasRelationshipModelClass::getModelClass insteadof HasXotTable; }`)
risolveva `getModelClass()` al model della **Resource proprietaria**
(`SurveyPdf`, da `static::$resource`), non al model della **relazione
mostrata in tabella** (`Contact`, da `static::$relationship = 'contacts'`).

Risultato: `getDefaultTableSortColumn()` tornava `survey_pdfs.id` mentre la
query di riga e' su `contacts` (join a `survey_pdfs` presente solo dentro un
`exists(...)` di scoping tenant, non nella query principale) → colonna non
trovata in `ORDER BY`.

## Fix (gia' presente in working tree, non committato all'apertura di questa story)

`HasXotTable::getModelClass()` ora, quando il metodo `getRelationship()`
esiste sull'istanza (fornito da
`Filament\Resources\Concerns\InteractsWithRelationshipTable`, presente su
tutte le pagine `ManageRelatedRecords`), risolve il model **dalla
relazione**:

```php
if (method_exists($this, 'getRelationship')) {
    $relationship = $this->getRelationship();
    $related = $relationship instanceof Builder ? $relationship->getModel() : $relationship->getRelated();
    return $related::class;
}
```

`XotBaseManageRelatedRecords` non usa piu' `HasRelationshipModelClass` (che
tornava sempre il model della Resource): trait rimosso dalla composizione,
`HasXotTable::getModelClass()` e' l'unica fonte. Spostati anche
`schema(Schema $schema)` (da `XotBaseManageRelatedRecords` a `HasXotForm`,
dove gia' vive `getFormSchema()`) e aggiunto `TransTrait` per
`getNavigationLabel()`.

## Verifica

Non riproducibile via HTTP in sessione (utenti applicativi vivono su
connessione DB `user` separata da `quaeris`, mapping id non risolto in
tempo utile — non bloccante per la verifica). Verificato via reflection
diretta sull'istanza reale della pagina:

```
getModelClass():              Modules\Quaeris\Models\Contact   (era SurveyPdf)
getDefaultTableSortColumn():  'contacts.id'                    (era 'survey_pdfs.id')
getRelationship():            HasMany -> Modules\Quaeris\Models\Contact
```

`SurveyPdf::find(5)->contacts()->count()` = 57592: i dati ci sono sempre
stati, il bug era solo nell'ORDER BY generato.

## Acceptance criteria

- [x] AC1 — `getModelClass()` su `ManageContacts` (istanza con record=SurveyPdf#5) torna `Contact::class`, non `SurveyPdf::class`.
- [x] AC2 — `getDefaultTableSortColumn()` torna `contacts.id`.
- [x] AC3 — Nessuna modifica a `phpstan.neon`, nessun ignore/cast per aggirare l'errore: fix alla radice in `getModelClass()`.
- [ ] AC4 — Verifica end-to-end via browser autenticato su `/quaeris/admin/gaia/survey-pdfs/5/contacts` (bloccata in questa sessione da mapping utente/tenant cross-connessione; da fare al prossimo giro con sessione browser disponibile).

## Owned file/module scope

- `laravel/Modules/Xot/app/Filament/Traits/HasXotTable.php` (fix `getModelClass()`)
- `laravel/Modules/Xot/app/Filament/Traits/HasXotForm.php` (spostato `schema()`)
- `laravel/Modules/Xot/app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php` (rimosso `HasRelationshipModelClass`, aggiunto `TransTrait`)

Nessuna modifica applicata da questa story: il fix era gia' nel working
tree (non committato) all'apertura dell'indagine. Questa story documenta
causa radice e verifica; non e' stato riscritto codice gia' corretto.

## Testing

```
php artisan tinker --execute="<reflection su ManageContacts::getModelClass()/getDefaultTableSortColumn()/getRelationship()>"
→ Contact::class / 'contacts.id' / HasMany->Contact
```

## Dev Agent Record

### Agent Model Used

Claude Sonnet 5

### File List

Nessuno modificato da questa story (solo verifica + documentazione).
Modifiche pre-esistenti (non committate, autore non attribuibile in questa
sessione — working tree condiviso multi-agente):

- `laravel/Modules/Xot/app/Filament/Traits/HasXotTable.php`
- `laravel/Modules/Xot/app/Filament/Traits/HasXotForm.php`
- `laravel/Modules/Xot/app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php`
