---
title: "InformationSchemaTable: il conteggio dei badge di navigazione resta congelato per sempre, mai il design documentato"
type: story
module: Xot
epic: null
story_id: null
slug: information-schema-table-badge-count-stale-cache
status: ready-for-dev
cold_gate: null
created: '2026-09-15'
updated: '2026-09-15'
repository: "https://github.com/laraxot/module_xot_fila5.git"
github_issue: "https://github.com/laraxot/module_xot_fila5/issues/124"
github_discussion: "https://github.com/laraxot/module_xot_fila5/discussions/125"
estimated_effort: null
blocked_by: []
blocks: []
supersedes: []
owned_scope:
  - "laravel/Modules/Xot/app/Models/InformationSchemaTable.php"
  - "laravel/Modules/Xot/app/Actions/ModelClass/CountAction.php"
  - "laravel/Modules/Xot/app/Actions/ModelClass/UpdateCountAction.php"
  - "laravel/Modules/Xot/app/Filament/Resources/XotBaseResource.php"
related:
  - "../models/information-schema-table.md"
---

# InformationSchemaTable: il badge di navigazione resta congelato per sempre

## Contesto

Trovato analizzando (su richiesta dell'utente) l'import contatti in Quaeris:
dopo aver importato un contatto/creato un job reale, il badge "Jobs" in
sidebar mostrava "2" mentre la lista filtrata mostrava correttamente "1
risultato". Non è un bug di conteggio nella query della lista — è la
sorgente del badge stesso a essere sbagliata.

## Come funziona oggi (letto il codice)

```
XotBaseResource::getNavigationBadge()
  → CountAction::execute($modelClass)
  → InformationSchemaTable::getModelCount($modelClass)
```

`getModelCount()` (`Modules/Xot/app/Models/InformationSchemaTable.php`):
```php
$record = static::firstOrCreate([...]);
if ($record->table_rows === null) {
    $record->update(['table_rows' => $model->count()]);
}
return (int) $record->table_rows;
```

Conta **una sola volta** (la prima volta che viene richiesto per quel
modello) e poi ritorna sempre lo stesso valore salvato, per sempre — non
c'è nessun `where`/TTL/invalidazione. Verificato il file fisico dove questo
viene salvato (`InformationSchemaTable` usa `SushiToJson`, quindi persiste
su JSON, non su una vera tabella):
`config/local/quaeris/database/content/information_schema_tables.json` ha,
per il modello `Job`, `"table_rows": 2`, `"updated_at":
"2026-09-10T11:07:53"` — cinque giorni prima del test che ha rivelato il
problema. Il valore reale della tabella `jobs` era nel frattempo cambiato
più volte, il badge no.

**Portata**: `getNavigationBadge()` è definito una sola volta in
`XotBaseResource`, quindi **ogni resource del progetto con un badge
numerico in sidebar** ha lo stesso problema, non solo `Job`.

## Perché non si aggiorna mai — l'azione di refresh esiste ma non è mai chiamata

`Modules/Xot/app/Actions/ModelClass/UpdateCountAction.php` esiste apposta
per aggiornare la cache (`InformationSchemaTable::updateModelCount()`), ma
verificato via grep su tutto il progetto: **l'unico chiamante è il proprio
test unitario** (`Modules/Xot/tests/Unit/Actions/ModelClass/
CountActionsTest.php`). Nessun comando schedulato, listener o observer la
richiama mai in produzione.

## Il design documentato è diverso, e non avrebbe questo problema

`Modules/Xot/docs/models/information-schema-table.md` descrive una classe
diversa da quella che esiste oggi:
```php
class InformationSchemaTable extends Model
{
    protected $table = 'information_schema.tables';
    protected $connection = 'mysql';
    public $timestamps = false;
}
```
Con `table_rows` documentato esplicitamente come "**numero approssimativo
di righe**" — cioè una query dal vivo sulla vista nativa
`information_schema.tables` di MySQL, il cui `TABLE_ROWS` è una stima che
**InnoDB aggiorna periodicamente da solo**, senza bisogno di nessun codice
applicativo che la rinfreschi. La doc non parla di nessun meccanismo di
refresh perché nel design che descrive non serve — ci pensa il database.

Il codice attuale (`extends BaseModel`, `use SushiToJson;`, persistenza su
file JSON scritto una tantum) è un'implementazione **completamente
diversa** da quella documentata — non un'estensione, una sostituzione che
ha perso la proprietà di auto-aggiornamento senza che la documentazione
venisse allineata, e senza che nessun meccanismo sostitutivo di refresh
venisse implementato. `git log` sul file non aiuta a capire quando/perché
(cronologia di commit generici, "." / "let's start").

## Acceptance Criteria

1. Il badge di navigazione di una resource riflette il conteggio reale (o
   un'approssimazione che si aggiorna in un tempo ragionevole, non
   congelata a tempo indefinito) dopo che righe vengono aggiunte/rimosse
   dalla tabella sottostante.
2. Nessuna resource esistente perde il badge o mostra errori.
3. Il fix non introduce una query `COUNT(*)` costosa a ogni caricamento di
   sidebar per tabelle molto grandi, se questo era il motivo originale
   della cache (da verificare in fase di implementazione: nessuna nota nel
   codice attuale conferma questa motivazione).
4. La documentazione (`information-schema-table.md`, in entrambe le copie
   `docs/models/` e `docs/wiki/models/`) viene allineata al comportamento
   reale scelto.
5. PHPStan pulito su `Modules/Xot`.

## Opzioni di fix (da decidere in fase di implementazione)

- **(a) Tornare al design documentato**: query diretta su
  `information_schema.tables` (approssimata, auto-aggiornata da MySQL) —
  fedele alla doc esistente, ma review necessaria per capire perché fu
  sostituita (multi-tenant? connessione `mysql` non sempre disponibile in
  ogni ambiente/tenant?).
- **(b) Contare sempre per davvero** (`$model->count()`, niente cache): più
  semplice, corretto sempre; da valutare il costo su tabelle molto grandi
  se ce ne sono nel progetto.
- **(c) Tenere la cache ma invalidarla davvero**: far chiamare
  `UpdateCountAction` da qualcosa di reale (observer sul model, comando
  schedulato periodico, o invalidazione al save/delete) — più lavoro, utile
  solo se (a)/(b) risultano davvero troppo costosi per qualche tabella
  specifica.

## Dev Notes

- Nessun codice toccato in questo passaggio — solo analisi + tracciamento,
  su richiesta esplicita dell'utente.
- Trovato mentre si verificava l'import contatti xls/csv di Quaeris (story
  `Modules/Quaeris/docs/stories/quaeris-verify-contact-import-xls-csv.md`),
  ma il difetto è di Xot, non di Quaeris — nessuna relazione funzionale con
  l'import in sé.

## References

- [Source: laravel/Modules/Xot/app/Models/InformationSchemaTable.php] — implementazione attuale (Sushi/JSON)
- [Source: laravel/Modules/Xot/docs/models/information-schema-table.md] — design documentato (query live su information_schema.tables)
- [Source: laravel/Modules/Xot/app/Actions/ModelClass/UpdateCountAction.php] — azione di refresh mai chiamata in produzione
- [Source: laravel/config/local/quaeris/database/content/information_schema_tables.json] — evidenza della cache congelata (Job: table_rows=2, updated_at 2026-09-10)
