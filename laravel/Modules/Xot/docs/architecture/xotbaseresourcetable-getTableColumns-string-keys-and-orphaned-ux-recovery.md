---
id: xotbaseresourcetable-gettablecolumns-string-keys-and-orphaned-ux-recovery
title: "XotBaseResourceTable: getTableColumns() a chiavi stringa + metodo di recupero UX da codice orfano"
document_type: architecture
category: bmad
scope: cross-module (owner Xot, consumer tutti i moduli con Filament Resource Tables)
status: verified
created_at: '2026-09-10'
updated_at: '2026-09-10'
tags: [xot, filament, tables, getTableColumns, ux, git-archaeology]
---

# Regola

Ogni `getTableColumns()` di una classe che estende
`Modules\Xot\Filament\Resources\Tables\XotBaseResourceTable` deve tornare un
array con **chiavi stringa** (`'colonna' => Column::make('colonna')`), mai
lista posizionale (`[Column::make('colonna'), ...]`). Le chiavi stringa sono
lo stesso pattern gia' obbligatorio per `getTableHeaderActions()` /
`getTableActions()` / `getTableBulkActions()` in questo codebase.

## Perche' (non solo stile)

Le chiavi stringa identificano stabilmente la colonna a runtime (dependency
tracking Livewire, toggle colonne, override da classi figlie). Chiavi
numeriche posizionali si rompono silenziosamente se l'ordine cambia o se una
sottoclasse aggiunge/rimuove elementi.

## Verifica repo-wide (2026-09-10)

Audit su tutte le classi `extends XotBaseResourceTable` (`grep -rl` su
`Modules/*/app/Filament/Resources/**/Tables/*.php`, ~96 file):

```bash
for f in $(grep -rl "extends XotBaseResourceTable" Modules --include="*.php" | grep -v /tests/); do
  awk '/function getTableColumns/,/^    }/' "$f" > /tmp/_body.txt
  bad=$(grep -E "^\s*[A-Za-z_\\\\]+::make\(" /tmp/_body.txt | grep -v "=>" | wc -l)
  [ "$bad" != "0" ] && echo "$f"
done
```

Risultato finale: **0 file non conformi**. Al momento del primo audit ne
risultavano 3 (`Chart/MixedChartsTable`, `Job/JobBatchesTable`,
`Gdpr/ConsentsTable`, quest'ultimo con una sola colonna posizionale
`treatment.name` in mezzo ad altre gia' a chiave stringa); risultavano gia'
sistemati al secondo giro, corretti da sessioni parallele sullo stesso
lavoro (vedi nota "lavoro concorrente" sotto).

## Metodo per il miglioramento UX: git archaeology, non invenzione

Il caso concreto che ha innescato questa nota:
`Modules/Quaeris/app/Filament/Resources/ContactResource/Tables/ContactsTable.php`
aveva solo `id/created_at/updated_at` — tecnicamente conforme (chiavi
stringa) ma UX povera per un modello con 20+ colonne utili.

`git log --follow` su
`ContactResource/Pages/ListContacts.php` porta al commit
`ee9731ee1` ("refactor(filament): remove orphaned table column definitions
from List pages"): un refactor precedente aveva rotto la sintassi di
`getTableColumns()` su 22 pagine `List*` in piu' moduli (frammento
`{ return [...]; }` senza firma di metodo, residuo di uno split
List-page/Table-class fatto a meta'), quel commit ha rimosso il frammento
rotto **senza migrare il contenuto** nella `Tables/XxxTable.php`
corrispondente. Risultato: colonne pensate e gia' scritte (spesso con
accessor dedicati sul model, es. `Contact::getEmailCellAttribute()` /
`getSmsCellAttribute()` / `getInfoCellAttribute()`) sono sparite dalla UI.

Metodo applicato:

1. `git log --follow --oneline -- <List page>` → trovare il commit che ha
   rimosso il frammento orfano.
2. `git show <commit> -- <file>` → leggere il contenuto perso.
3. **Non ricopiarlo alla cieca**: verificare ogni colonna contro il model
   attuale (`@property` nel docblock, accessor `getXxxAttribute()` presenti
   davvero) e contro la migration (colonna esiste ancora, tipo coerente).
   Caso di falso positivo trovato: `Xot/LogResource` — il vecchio frammento
   orfano di `ListLogs.php` descriveva colonne `message/level/level_name/context`
   di un modello Log persistito su DB che **non esiste piu'**; l'attuale
   `Modules\Xot\Models\Log` e' un modello Sushi virtuale sui file in
   `storage/logs/*.log` con solo `id/name/size` — ricopiare l'orfano avrebbe
   reintrodotto colonne inesistenti. `LogsTable.php` e' stato lasciato
   invariato.
4. Applicare solo le colonne verificate, con keys stringa e stile Filament
   coerente al resto del repo (badge per enum/stato, `IconColumn::boolean()`
   per flag, `dateTime()` + `placeholder('—')` per timestamp nullable,
   `toggleable(isToggledHiddenByDefault: true)` per colonne secondarie,
   `copyable()` su id).

## Esito

- `Quaeris/ContactResource/Tables/ContactsTable.php`: colonne
  `first_name/last_name/email_cell/sms_cell/info_cell/token` + timestamp
  toggleable ripristinate (gia' presenti in working tree quando verificato,
  vedi nota sotto); `Quaeris/SurveyPdfResource/Pages/ManageContacts.php`
  delega a `app(ContactsTable::class)->getTableColumns()` invece di
  duplicare la definizione.
- Verificati senza necessita' di intervento (gia' allineati a model +
  migration, spesso gia' migliori dell'orfano): `FailedJobsTable`,
  `TranslationFilesTable`, `MenusTable`, `RolesTable`, `PermissionsTable`,
  `TenantsTable` (User), `SocialProvidersTable` (x2, cluster e non),
  `SsoProvidersTable` (x2), `QuestionChartsTable` (x3 varianti Quaeris).
- Gap reale trovato e corretto: `Xot/ExtraResource/Tables/ExtrasTable.php`
  mancava la colonna `extra_attributes` (colonna JSON reale via
  `$table->schemalessAttributes('extra_attributes')`, migration
  `2024_01_01_000015_create_extra_table.php`). Aggiunta come
  `TextColumn::make('extra_attributes')->label('Extra')->limit(50)->toggleable(isToggledHiddenByDefault: true)`.
- Falso positivo scartato: `Xot/LogResource/Tables/LogsTable.php` (vedi
  punto 3 sopra).

## Nota — lavoro concorrente sullo stesso repo

Al momento di questa verifica, `git status` mostrava ~90 file
`Tables/*.php` gia' modificati non committati in tutto il repo, con lo
stesso stile coerente (`copyable()`, `placeholder('—')`,
`toggleable(isToggledHiddenByDefault: true)`) applicato ovunque: un'altra
sessione/swarm stava eseguendo la stessa richiesta in parallelo. Vedi
[[multi-agent-same-repo-race]] e [[misurare-mentre-un-altro-scrive]]. Questa
nota documenta il metodo e i due esiti (fix reale su Extra, non-fix motivato
su Log) verificati da questa sessione; non rivendica autorship sulle altre
~90 modifiche gia' presenti in working tree.
