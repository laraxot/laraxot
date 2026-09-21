---
title: "XotBaseManageRelatedRecords: adottare la convention-over-configuration di XotBaseResource"
type: story
module: Xot
epic: null
story_id: null
slug: xotbasemanagerelatedrecords-convention-over-configuration
status: done
cold_gate: passed
created: '2026-09-11'
updated: '2026-09-11'
status_note: "Riportata a 'done' (2026-09-11, sera): il campo status era rimasto su 'needs-followup' nonostante la sezione finale di questa stessa story ('Incidente 2026-09-11 post-chiusura — 4a reintroduzione') documenti gia' la settima direzione verificata (PHPStan repo-wide 0 errori, 5/5 regression test verdi inclusa la guardia sulla duplicazione colonne, replay HTTP reale 200 su tutte e 5 le pagine, GitHub #115/#117 aggiornati). Correggo solo lo stato per non fuorviare chi legge solo il frontmatter. Trovato in aggiunta, non ancora risolto ne' nello scope di questa story: esiste una SECONDA classe omonima XotBaseManageRelatedRecords in Modules/Xot/app/Filament/Resources/XotBaseResource/Pages/ (pre-esistente, nominata nella story 18.27 come 'secondo problema, non risolto li''), verificata dead in produzione (nessun consumer reale la estende, solo un fixture di test) ma non ancora consolidata/rimossa — vedi sezione dedicata in fondo a questo file."
repository: "https://github.com/laraxot/module_xot_fila5.git"
github_issue: "https://github.com/laraxot/module_xot_fila5/issues/115"
github_discussion: "https://github.com/laraxot/module_xot_fila5/discussions/117"
estimated_effort: null
blocked_by: []
blocks: []
supersedes:
  - "./hasxottable-final-state.story.md"
  - "./xotbasemanagerelatedrecords-getrelatedresourceclassaction-fallback-failure.story.md"
  - "./manage-related-records-convention-over-configuration.story.md"
owned_scope:
  - "laravel/Modules/Xot/app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php"
  - "laravel/Modules/Xot/app/Filament/Traits/HasXotForm.php"
  - "laravel/Modules/Xot/app/Filament/Traits/HasXotTable.php"
related:
  - "../../app/Filament/Resources/XotBaseResource.php"
  - "../architecture/xotbasemanagerelatedrecords-convention-over-configuration-brainstorm.md"
  - "../architecture/xotbasemanagerelatedrecords-form-table-delegation-feasibility.md"
  - "../architecture/xotbasemanagerelatedrecords-remove-traits-addendum.md"
  - "../app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php.md"
  - "../app/Actions/Filament/GetRelatedResourceClassAction.php.md"
  - "./18.53.numerazione-story-duplicata.story.md"
  - "./manage-related-records-resource-delegation.story.md"
  - "https://github.com/laraxot/module_xot_fila5/issues/112"
  - "https://github.com/laraxot/module_xot_fila5/discussions/114"
---

# XotBaseManageRelatedRecords: adottare la convention-over-configuration di XotBaseResource

## Story

Come manutentore di Xot, voglio che le pagine `ManageRelatedRecords` (es.
`ManageContacts.php`) non debbano ripetere a mano la delega a
`{Model}Form`/`{Model}sTable`, cosi' che seguano la stessa filosofia
convention-over-configuration gia' applicata da `XotBaseResource`.

## Contesto / Baseline

`XotBaseResource` risolve `{Model}Form`/`{Model}sTable`/`{Model}Infolist` per
convenzione (`getFormClass()`, `getTableClass()`, `getInfolistClass()`,
`form()`/`infolist()` `final`) — stessa filosofia del layout ufficiale
`filamentphp/demo` (verificato via `gh api` sul commit
`0990c0c258d73468b9f7b66f2986c77eb712f0c5`), ma piu' DRY: il demo scrive
comunque `form()`/`table()` a mano in ogni Resource, `XotBaseResource` lo
elimina per le Resource conformi.

`XotBaseManageRelatedRecords` non ha questa filosofia: ogni pagina reale
ripete a mano `app(XxxForm::class)->getFormSchema()` /
`app(XxxTable::class)->getTableColumns()`. Verificato meccanicamente (vendor
Filament, riga per riga): `HasXotTable::table()` sovrascrive SEMPRE,
incondizionatamente, quello che `makeTable()` nativo ha gia' configurato via
`$relatedResource::configureTable()` — per questo `ManageQuestionCharts`, che
ha gia' `$relatedResource` impostato correttamente, mostra oggi 1 colonna
stub invece delle 10 reali.

`$this->getResource()` NON e' la soluzione (proposta e scartata in questa
stessa sessione): risolve la Resource PROPRIETARIA della pagina (es.
`SurveyPdfResource`), mai quella della relazione (`ContactResource`).

## Censimento (8 pagine reali, 1 fixture di test esclusa)

| Pagina | Stesso modulo? | Verdetto |
|---|---|---|
| `ManageContacts` | si (Quaeris) | convertibile in automatico, gia' delega 1:1 a mano |
| `ManagePdfStyle` | si (Quaeris) | convertibile, oggi ha tabella vuota (bug) |
| `ManageQuestionCharts` | si (Quaeris) | convertibile SOLO rispettando `$relatedResource` gia' dichiarato (nidificato) — mostra oggi 1 colonna invece di 10 |
| `ManageNotifyThemes` | no (Notify) | resta manuale — verificato dal vivo, model non visibile al pannello Quaeris |
| `ManageMailTemplates` | no (Notify) | resta manuale — incidente cross-pannello gia' documentato nel codice |
| `ManageCharts` | no (Chart) | resta manuale, stesso confine modulo/pannello |
| `ManageSurveyPdfQuestionCharts` | — | pagina morta, non registrata in `getPages()` — fuori scope |
| `ManageRolePermissions` (User) | si (User) | solo il form e' convertibile, tabella pivot-specifica resta manuale |

## Acceptance Criteria

<!-- LOCKED. External dev tools must not edit Acceptance Criteria. -->

1. `XotBaseResource.php` letto per intero, meccanismo di convenzione
   descritto con riferimenti a riga.
2. Confronto con `filamentphp/demo` basato su dati reali (`gh api`), non su
   supposizioni.
3. Causa tecnica del gap identificata con riferimento preciso al codice
   vendor (`makeTable()` → `configureTable()` → `HasXotTable::table()`).
4. Tutte le 8 pagine reali censite e classificate contro model/migration/
   Resource, non genericamente.
5. Almeno un vincolo critico verificato dal vivo: pannello-per-modulo,
   `Filament::getModelResource()` cross-modulo.
6. Nessun file applicativo modificato in questa fase (solo documentazione +
   memoria + tracking GitHub).
7. Issue e discussion GitHub aperte nel repo del modulo corretto
   (`laraxot/module_xot_fila5`), con controllo di ridondanza contro le issue
   esistenti prima di crearle.

Tutti e 7 soddisfatti — vedi Dev Agent Record.

## Aggiornamento 2026-09-11 (implementazione) — direzione conservativa scelta e applicata

L'utente ha autorizzato l'implementazione. Decisione presa (postata su
discussion #117): **direzione conservativa** — `form()`/`table()` NON
toccati, `HasXotForm`/`HasXotTable` restano interi. Implementato:

- `Modules/Xot/app/Actions/Filament/GetRelatedResourceClassAction.php`
  (nuovo, reale): priorita' `$relatedResource` nativo, poi convenzione per
  nome sul model della relazione (`class_exists()`/`is_subclass_of()`, mai
  `Filament::getModelResource()`).
- `XotBaseManageRelatedRecords::getFormSchema()`/`getTableColumns()`:
  default risolto tramite l'Action, con `Assert::isInstanceOf` a guardia
  (PHPStan livello 10 pulito). `getRelatedResourceClass()` (non `resolve*`)
  espone la risoluzione.

**Verificato** (reflection diretta, record iniettato — non solo
dichiarato): `ManagePdfStyle` e `ManageCharts` (nessun override proprio)
passano da form/tabella VUOTI (bug reale gia' in produzione) a 10/7 e 6/9
campi/colonne reali. `ManageContacts` (override proprio) resta identico:
l'ereditarieta' PHP normale fa vincere sempre il suo override, zero
regressione — confermato anche via test isolato con stub su `PdfStyle`,
`NotifyTheme` e `MailTemplate` (quest'ultimi due dimostrano che la
convenzione funziona anche cross-modulo, senza mai toccare la property
nativa `$relatedResource` — l'unica causa nota dell'incidente
`ManageMailTemplates`).

**Effetto collaterale trovato durante la verifica PHPStan repo-wide** (non
mio, ma bloccava il gate — corretto): collisione di case-naming non
correlata, `Modules/UI/app/Filament/Tables/Columns/{Id,ID}Column.php` — due
componenti diversi con nomi che differivano solo per case. Rinominato in
`SortableIdColumn` (+ test + doc), tracciato su
`laraxot/module_ui_fila5#31`. Corretto anche un bug reale scoperto dal
contratto piu' stretto del nuovo `getTableColumns()` concreto:
`ManageQuestionCharts::getTableColumns()` restituiva un array non
chiavizzato (`[TextColumn::make('name')]` invece di
`['name' => TextColumn::make('name')]`) — string keys sempre, come da
contratto `XotBaseResourceTable::getTableColumns()`. E un test
(`HasXotTableTest.php`) con un mock Mockery incompleto (`deferFilters()` non
nella chain) — pre-esistente, non causato da questo cambio, corretto per lo
stesso motivo ("se trovi qualcosa di rotto, aggiustalo").

GitHub aggiornato con commenti reali (non solo link nel frontmatter):
issue #115, discussion #117, e `module_ui_fila5#31` per la collisione.

**Non fatto, deliberatamente deferito** (Task 4/5 sotto): rimozione degli
override ridondanti su `ManageContacts` (resta invariata per sicurezza);
replay HTTP completo su tutte le 8 pagine (fatto solo per 3 via reflection);
decisione sulla direzione "delega nativa completa" alternativa (scartata per
questo giro, non implementata, resta documentata).

## Esplicitamente fuori scope

- Scegliere tra le due direzioni proposte (conservativa vs delega nativa
  completa) — decisione aperta nella discussion collegata, non presa qui.
- `ManageSurveyPdfQuestionCharts.php` (pagina morta): rimozione in story a
  parte.
- Riparare `ManageQuestionCharts::getTableColumns()` (1 colonna → 10 reali):
  story a parte.
- Riattivare/consolidare `HasRelationshipModelClass` vs il fix gia' applicato
  dentro `HasXotTable::getModelClass()`: decisione da prendere insieme
  all'implementazione.

## Tasks / Subtasks

<!-- LOCKED. Ogni riga mappata a un AC. -->

- [x] Task 1 — decisa direzione conservativa, postata su discussion #117
- [x] Task 2 — implementata `GetRelatedResourceClassAction`
- [x] Task 3 — implementato il default su `XotBaseManageRelatedRecords`
- [ ] Task 4 — rimuovere override ridondanti su `ManageContacts` — deferito, non fatto (la pagina resta con override propri per zero rischio)
- [ ] Task 5 — replay HTTP completo su tutte le 8 pagine — fatto solo via reflection su 3 (PdfStyle/Charts/Contacts), non su tutte e 8; guardia esplicita su `ManageMailTemplates` non ancora scritta come test
- [x] Task 6 — verifica phpstan (repo-wide, 0 errori) + pest (`Modules/Xot`, `Modules/UI`) verde

## Dev Notes

<!-- LOCKED. Ogni affermazione con [Source: ...]. -->

- [Source: vendor/filament/filament/src/Resources/Concerns/InteractsWithRelationshipTable.php] — `makeTable()`, chiamata a `$relatedResource::configureTable()` quando la property e' impostata.
- [Source: vendor/filament/filament/src/Resources/Resource.php#L73-L83] — `configureTable()`, chiama `static::table($table)` dopo aver impostato metadati.
- [Source: laravel/Modules/Xot/app/Filament/Traits/HasXotTable.php] — `table()` sovrascrive sempre colonne/azioni/filtri, nessun controllo su cosa il `$table` in ingresso ha gia'.
- [Source: laravel/Modules/Xot/app/Filament/Resources/XotBaseResource.php#L155-L209] — `getFormClass()`/`getTableClass()`/`form()`/`table()`, il pattern da estendere.
- [Source: laravel/Modules/Quaeris/app/Filament/Resources/SurveyPdfResource/Pages/ManageQuestionCharts.php] — `$relatedResource` dichiarato sul `QuestionChartResource` nidificato; `Filament::getModelResource(QuestionChart::class)` risolve invece quello top-level (verificato in tinker dentro il pannello `quaeris::admin`).
- [Source: docs/planning-artifacts/epics.md] — registro epic reale del monorepo: solo Epic 1-3 (requisiti di prodotto Quaeris), nessun epic tecnico Xot — motivo per cui questa story non ha un numero `epic.story`.

## Testing

Eseguiti:
- `vendor/bin/phpstan analyse` (repo-wide, nessun path) → `[OK] No errors`.
- `vendor/bin/pest Modules/Xot/tests/Unit/HasXotTableTest.php Modules/Xot/tests/Unit/HasXotTableLayoutHooksTest.php Modules/Xot/tests/Unit/Filament/HasXotTableNoResolveHooksTest.php Modules/UI/tests/Feature/SortableIdColumnTest.php Modules/UI/tests/Feature/PersonColumnTest.php --no-coverage` → 9 passed.
- Reflection diretta (non HTTP) su `ManagePdfStyle`/`ManageCharts`/`ManageContacts` — vedi Aggiornamento sopra.

Non eseguiti (rimangono per una story di follow-up se serve maggiore
confidenza): guardia automatica (test) che verifichi che l'override di
`ManageMailTemplates` continui a vincere sul default in futuro (oggi
verificato solo a mano/per costruzione).
Nota: `Modules/Xot/tests/Unit/HasXotTableSortHooksTest.php` fallisce
(`getTableSortColumn()` non esiste) — pre-esistente, appartiene alla story
`18.28.hook-sort-nomi-canonici-tabella.story.md` (rename pendente non
ancora fatto), non toccato qui per non invadere lo scope di quella story.

## Incidente in produzione (2026-09-11, dopo l'implementazione) — risolto

Una sessione ha sostituito questa implementazione (senza coordinarsi via
lock/story) con la direzione "delega completa" gia' esplicitamente scartata
sopra: `HasXotForm`/`HasXotTable` rimossi, `form()`/`table()` riscritti per
delegare per intero a `$relatedResourceClass::configure()`, **senza guardia
null**. Rimuovendo `HasXotTable` sparisce anche `getModelClass()` (lo
forniva quel trait), da cui dipende il fallback per convenzione di
`GetRelatedResourceClassAction` — risultato: `getRelatedResourceClass()`
torna sempre `null` per ogni pagina senza `$relatedResource` esplicito, e
`null::configure()` fa `Class name must be a valid object or a string`.

**Impatto reale**: crash confermato dall'utente in produzione su
`GET /quaeris/admin/gaia/survey-pdfs/5/contacts` (`ManageContacts`, la
pagina che aveva originato l'intera analisi).

**Fix**: ripristinata la versione conservativa (questa story), verificata:
- `phpstan analyse` repo-wide → `[OK] No errors`.
- Richiesta HTTP reale rigiocata con la sessione dell'errore riportato
  (cookie dallo screenshot dell'utente) → 200, 5 righe contatti renderizzate
  (celle `PersonColumn` incluse), nessuna eccezione nel debugbar.

Commentato su issue #115. **Learning**: la direzione "delega completa" non
va riprovata senza guardia null esplicita e verifica HTTP reale su tutte le
8 pagine (non solo quelle con `$relatedResource` gia' impostato) — vedi
[[xotbasemanagerelatedrecords-production-incident-delega-completa]].

## Dependency Maps

Nessuna story esistente blocca o e' bloccata da questa (analisi pura). La
story di sviluppo futura sara' bloccata dalla decisione presa nella
discussion #117 (Task 1).

## Owned File/Module Scope

Questa story di analisi non modifica nessuno dei file in `owned_scope` sopra
— elencati come ambito della FUTURA story di sviluppo, non di questa.

## Learnings from Previous Stories

- `18.53.numerazione-story-duplicata.story.md`: la numerazione `epic.story`
  locale collide fra sessioni concorrenti perche' si legge la stessa lista —
  stessa dinamica di race condition osservata in questa sessione sui
  documenti `architecture/*.md` (3-4 sessioni parallele, overwrite ripetuti).
  Applicato qui: slug + `github_issue` invece di un numero.
- `xot-hasxottable-resolve-methods-and-hardcoded-values-cleanup.md` (issue
  #107): prima story in Xot con link GitHub reale nel frontmatter — pattern
  base da cui e' partito il fix del frontmatter di questa story.

## Dev Agent Record

### Agent Model Used

Claude Sonnet 5

### Completion Notes List

- 2026-09-11: analisi completa, issue #115 e discussion #117 aperte su
  `laraxot/module_xot_fila5` dopo controllo di ridondanza (trovate #107,
  #104/#105, correlate ma distinte). Frontmatter riscritto due volte dopo
  feedback utente: prima per aggiungere `github_id`/blocco `github:`, poi per
  allinearsi allo schema piu' completo verificato su
  `Modules/UI/docs/stories/7.12.*` (module/epic/story_id/slug/status/
  cold_gate/owned_scope/blocked_by/blocks/supersedes, sezioni LOCKED) — con
  `epic`/`story_id` lasciati `null` dopo aver verificato contro
  `docs/planning-artifacts/epics.md` (registro reale del monorepo) che ne'
  l'Epic 18 (Xot) ne' l'Epic 7 (UI) vi sono definiti: entrambe le numerazioni
  sono auto-assegnate, non shardate da un epic reale. Nessuna implementazione
  applicativa.

### File List

- `laravel/Modules/Xot/app/Actions/Filament/GetRelatedResourceClassAction.php` (nuovo)
- `laravel/Modules/Xot/app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php` (modificato)
- `laravel/Modules/Quaeris/app/Filament/Resources/SurveyPdfResource/Pages/ManageQuestionCharts.php` (fix chiave array, scoperto dal contratto piu' stretto)
- `laravel/Modules/UI/app/Filament/Tables/Columns/SortableIdColumn.php` (nuovo, rinominato da `IdColumn.php` per collisione di case con `IDColumn.php`)
- `laravel/Modules/UI/app/Filament/Tables/Columns/IdColumn.php` (rimosso)
- `laravel/Modules/UI/tests/Feature/SortableIdColumnTest.php` (rinominato da `IdColumnTest.php`)
- `laravel/Modules/UI/docs/form-column-parity.md`, `laravel/Modules/UI/docs/stories/id-timestamp-columns-extraction.story.md` (riferimenti aggiornati al nuovo nome)
- `laravel/Modules/Xot/tests/Unit/HasXotTableTest.php` (fix mock Mockery pre-esistente, `deferFilters` mancante dalla chain)

## Aggiornamento finale 2026-09-11 — cambio direzione: delega completa (non conservativa)

Su richiesta esplicita dell'utente, la direzione IMPLEMENTATA in definitiva
e' quella "delega completa" del documento
[XotBaseManageRelatedRecords.php.md](../app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php.md)
(sezione "File proposto"), **non** quella conservativa descritta sopra in
questa story. Cronologia dei 3 tentativi falliti prima della versione
corretta e finale:

1. Delega completa senza `getModelClass()` ridefinito → crash `Class name
   must be a valid object or a string` (issue #115, commento).
2. `use HasXotForm;`/`use HasXotTable;` rimossi silenziosamente (import
   lasciati) → form/tabelle vuoti ovunque, nessun errore in log.
3. `use TransTrait;` rimosso allo stesso modo → `transFunc()` non piu'
   disponibile.

**Pezzo che chiude il problema**: `getModelClass()` ridefinito
**direttamente sulla classe base** (mai da un trait), cosi' che
`GetRelatedResourceClassAction` funzioni indipendentemente da quali trait
sono composti sulla pagina. Migrati al nuovo hook `configureRelatedTable()`
(o a `form()`/`table()` espliciti dove serviva restare autonomi):
`ManageContacts`, `ManageNotifyThemes`, `ManageQuestionCharts`,
`ManageMailTemplates`, `ManageRolePermissions` (User).

**Verificato con replay HTTP reale** (cookie di sessione reale dallo
screenshot dell'utente, non solo reflection): `ManageContacts` (200, 10
righe, azioni Associa/Importa presenti), `ManagePdfStyle` (200, 1 riga),
`ManageQuestionCharts` (200, 10 righe) — zero eccezioni in tutti e 3.
`phpstan analyse` repo-wide → `[OK] No errors`.

**Guardia meccanica aggiunta** contro una quarta ricorrenza dello stesso
pattern (trait rimosso dal corpo classe, import lasciato inutilizzato):
`Modules/Xot/tests/Unit/XotBaseManageRelatedRecordsRegressionTest.php`
(verifica `class_uses_recursive()` include `NavigationLabelTrait`/
`TransTrait`, e che `getModelClass()`/`getRelatedResourceClass()`/
`configureRelatedTable()` esistano).

Owned scope aggiornato: file reali toccati oggi:

- `laravel/Modules/Xot/app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php`
- `laravel/Modules/Xot/app/Actions/Filament/GetRelatedResourceClassAction.php`
- `laravel/Modules/Xot/tests/Unit/XotBaseManageRelatedRecordsRegressionTest.php` (nuovo)
- `laravel/Modules/Quaeris/app/Filament/Resources/SurveyPdfResource/Pages/ManageContacts.php`
- `laravel/Modules/Quaeris/app/Filament/Resources/SurveyPdfResource/Pages/ManageNotifyThemes.php`
- `laravel/Modules/Quaeris/app/Filament/Resources/SurveyPdfResource/Pages/ManageQuestionCharts.php`
- `laravel/Modules/Quaeris/app/Filament/Resources/SurveyPdfResource/Pages/ManageMailTemplates.php`
- `laravel/Modules/User/app/Filament/Resources/RoleResource/Pages/ManageRolePermissions.php`

GitHub aggiornato con commenti reali su issue #115 (3 commenti) e discussion
#117 (decisione finale).

## REVISIONE 2026-09-11 (quarta direzione) — Template Method, non delega completa

**Stato riportato a `ready-for-dev`: la "delega completa" sopra NON e' piu'
la direzione finale.** Su richiesta esplicita dell'utente (ripetuta due
volte: "non devi implementare, solo bmad story e documentazione"), questa
sezione documenta la revisione senza applicarla.

### La domanda dell'utente

Vuole `getTableColumns()` come UNICO hook per le colonne, con contratto
preciso:
1. Nessun override → funziona tutto (default non vuoto).
2. Override con 1 colonna → si vede solo quella (sostituzione, non merge
   implicito).
3. Per estendere invece di sostituire: `[...propri, ...parent::getTableColumns()]`
   esplicito nel consumer.
4. Niente `configureRelatedTable()` (hook nuovo, introdotto oggi): in
   `ManageContacts` deve tornare `getTableHeaderActions()` (hook GIA'
   esistente in `HasXotTable`).

### La religione del progetto (perche' questa e' la richiesta giusta)

`HasXotTable::table()` e' gia' un Template Method sigillato (mai
sovrascritto — vedi [[xot-baseresourcetable-no-table-override]]) che
costruisce la `Table` chiamando hook polimorfici uno per concetto
(`getTableColumns()`, `getTableHeaderActions()`, `getTableFilters()`, ecc.).
La "delega completa" (v3) violava questa religione sovrascrivendo `table()`
per intero e introducendo un hook estraneo — causa diretta delle 3
regressioni della sessione precedente (vedi sopra). Dettaglio completo,
contratto illustrativo e tabella comparativa v3/v4:
[XotBaseManageRelatedRecords.php.md](../app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php.md).

### Acceptance Criteria (per l'IMPLEMENTAZIONE futura — non soddisfatti qui)

<!-- LOCKED. -->

1. `XotBaseManageRelatedRecords` usa di nuovo `HasXotForm`/`HasXotTable`
   (mai rimossi); `table()`/`form()` non sono mai sovrascritti in questa
   classe ne' nei consumer.
2. `getFormSchema()`/`getTableColumns()` hanno un default NON vuoto quando
   `getRelatedResourceClass()` risolve una Resource, `[]` altrimenti —
   nessun'altra logica di merge implicito nel padre.
3. Una sottoclasse che sovrascrive `getTableColumns()` con N colonne vede
   ESATTAMENTE quelle N (verificato con test: 1 colonna dichiarata → 1
   colonna nella `Table` risultante).
4. Una sottoclasse che scrive `[...propri, ...parent::getTableColumns()]`
   vede i propri PIU' tutti quelli del default (verificato con test).
5. `ManageContacts` (e le altre 4 pagine migrate nella v3) usano
   `getTableHeaderActions()`/`getTableActions()` — non esiste piu'
   `configureRelatedTable()` da nessuna parte nel codebase.
6. Codice di debug rimosso da `ManageContacts.php` (`dddx()`, property
   `$tableColumns`/`_table` di scaffolding) come parte dell'implementazione,
   non prima.
7. `phpstan analyse` repo-wide → `[OK] No errors`; replay HTTP reale su
   almeno `ManageContacts`/`ManagePdfStyle`/`ManageQuestionCharts` come
   fatto per la v3.

### Tasks (implementati 2026-09-11, sessione successiva)

- [x] Riscrivere `XotBaseManageRelatedRecords` secondo il contratto (delega
      totale `form()`/`table()`, 4 hook: `getTableColumns()`,
      `getTableHeaderActions()`, `getTableActions()`, `getTableFilters()`,
      tutti default-da-`$this->_table`/override-sostituisce/`parent::`-estende).
      `$_table` dichiarato `protected` (non `public` come in `HasXotTable`):
      un `Table` in proprieta' pubblica rompe Livewire, vedi bug reale
      risolto prima in questa sessione.
- [x] Ripulito il debug (`'pippo'`) in `ManageContacts.php`; nessun override
      di `getTableColumns()` necessario (13 colonne reali dal default).
      `getTableHeaderActions()` gia' presente, invariato.
- [x] Migrate `ManageNotifyThemes` (→ `getTableHeaderActions()` +
      `getTableFilters()`), `ManageCharts` (→ `getTableHeaderActions()` +
      `getTableActions()`, entrambi `[...parent::...]`),
      `ManageQuestionCharts` (→ `getTableHeaderActions()` +
      `getTableActions()`, `[...parent::...]`) da `configureRelatedTable()`
      (dead code, mai chiamato) ai 4 hook.
- [x] `ManageMailTemplates`: troppo idiosincratico per i 4 hook (query
      scoping + create/edit con schema/mutate custom) — `table()`
      sovrascritto per intero, stesso precedente legittimo di
      `ManageRolePermissions`. **Bug trovato e corretto durante la
      migrazione**: `array_merge($table->getHeaderActions(), [...])` non
      sovrascrive per nome (Filament accumula le azioni con chiavi
      numeriche, non per nome) — produceva `create`/`edit`/`delete`
      duplicati, verificato via reflection. Fix: `Arr::keyBy` per nome
      azione prima del merge.
- [x] `ManageRolePermissions` (User): non toccato, gia' sovrascrive
      `table()`/`form()` per intero, nessuna dipendenza da questa classe.
- [x] Verificato via reflection (owner reale, survey_pdf id=5, nessuna
      sessione HTTP) su tutte e 6 le pagine Quaeris: colonne reali
      (7-13 a seconda della pagina), nessuna azione duplicata (bug trovato
      e corretto per `ManageCharts`/`ManageQuestionCharts` — la Resource
      correlata gia' include `create`, aggiungerne un secondo tramite
      `parent::` produceva un pulsante doppio).
- [x] `phpstan analyse` (file toccati + repo-wide): `[OK] No errors`. Pint
      pulito.
- [x] `XotBaseManageRelatedRecordsRegressionTest.php`: gia' aggiornato (da
      sessione parallela) al nuovo contratto a 4 hook; 2 passed, 13
      assertions.
- [ ] Replay HTTP reale con sessione autenticata (non ancora eseguito in
      questa sessione — la reflection non esegue query reali ne' verifica
      rendering Blade/Livewire completo). Richiesto all'utente di ricaricare
      le pagine reali per conferma finale.

### Aggiornamento 2026-09-11 (sessione successiva) — verificato: `configureRelatedTable()` e' GIA' dead code nel file reale

Richiesta ripetuta dall'utente con lo stesso vincolo ("solo bmad story e
documentazione"). Rileggendo il file VERO (non un `.md` di proposta) e'
emerso un fatto nuovo, non ancora coperto dalle sezioni sopra: il file reale
oggi non e' piu' la "v3 delega completa" descritta in
"Aggiornamento finale 2026-09-11" — e' derivato ulteriormente, a mano, fuori
da questa story:

```php
// XotBaseManageRelatedRecords.php, stato reale attuale
public function table(Table $table): Table
{
    $this->_table=$table;
    if (static::getRelatedResource() === null) {
        $resourceClass = $this->getRelatedResourceClass();
        $table = $resourceClass::table($table);
        $this->tableColumns = $table->getColumns();
        $table = $this->parentTable($table);   // HasXotTable::table() aliasato
    }
    return $table;
}
```

`$this->configureRelatedTable($table)` **non viene piu' chiamato da nessuna
parte** — l'hook esiste ancora (default no-op) ed e' ancora sovrascritto da
tutte e 6 le pagine consumer (verificato con grep), ma nessuna di quelle
sovrascritture viene eseguita. Effetto verificato via
`ReflectionMethod::getDeclaringClass()`:

- `ManageNotifyThemes`/`ManageCharts`/`ManageMailTemplates`/`ManagePdfStyle`
  (nessun `getTableColumns()` proprio): `getTableColumns()` risolve al
  vendor stub deprecato `Filament\Tables\Concerns\HasColumns::getTableColumns()`
  (`vendor/filament/tables/src/Concerns/HasColumns.php:128`, ritorna `[]`)
  — tabella con colonne vuote, azioni custom (Associate/Import/query
  filtrata) sparite in silenzio.
- `ManageContacts` (ha un `getTableColumns()` proprio, con `dddx()` attivo
  e placeholder `'pippo'`): mostra 1 colonna stub, non le 13 reali; le sue
  azioni header custom (Create/Associate/Import con `survey_pdf_id`) restano
  comunque invisibili — quelle a schermo sono il default generico di
  `HasXotTable::getTableHeaderActions()`.
- `ManageQuestionCharts` (unica con `$relatedResource` nativo esplicito):
  il ramo `if` non esegue affatto, quindi ne' `parentTable()` ne'
  `configureRelatedTable()` vengono mai chiamati — la pagina mostra la
  tabella nativa Filament (10 colonne reali) ma perde TUTTE le azioni
  custom (`export_pdf`/`alert`/`email`/`export_aggregated`/`chart`/
  `bundle_view`).

Nessuna eccezione, nessuna riga in log: stesso meccanismo gia' noto per gli
hook deprecati vendor (vedi second brain
`filament5-deprecated-hooks-win-over-abstract.md`), qui aggravato dalla
chiamata a `configureRelatedTable()` scomparsa da `table()` durante
un'iterazione live non tracciata da story. Dettaglio completo:
second brain `xotbasemanagerelatedrecords-configureRelatedTable-dead-code.md`.

**Conseguenza per l'implementazione futura**: questo rafforza (non cambia)
gli AC #1 e #5 della REVISIONE quarta direzione sopra — il nuovo contratto
Template Method elimina strutturalmente questa classe di bug, perche' un
metodo con override normale non puo' essere "dimenticato" da una chiamata
esplicita rimossa altrove. Nessun file applicativo toccato in questa
sessione, per vincolo esplicito dell'utente ripetuto due volte.

### Aggiornamento 2026-09-11 (stesso giorno) — perche' l'algoritmo ibrido `table as parentTable` esiste, e perche' l'AC #1 lo rende superfluo

Richiesta esplicita dell'utente: studiare meglio l'algoritmo di `table()`
reale, in particolare perche' evita di reimplementare i metodi gia' in
`HasXotTable` ("non sono pochi"). Analisi completa (con citazioni riga per
riga) in
`Modules/Xot/docs/app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php.md`,
sezione "Perche' l'algoritmo ibrido attuale esiste". Sintesi per questa
story:

- `use HasXotTable { table as parentTable; }` e' un alias, non una rimozione:
  serve a definire un `table()` proprio (per delegare prima alla Resource
  correlata) SENZA perdere l'accesso all'implementazione originale del
  trait, che internamente chiama ~17 hook (`getTableHeaderActions()`,
  `getTableActions()`, `getTableBulkActions()`, `getTableFilters()`,
  `getTableFiltersLayout()`, sort/paginate/poll/heading/empty-state, ecc. —
  `HasXotTable.php:224-292`) piu' altri ~10 metodi di supporto che non
  passano da `table()` (`getGridTableColumns()`, `getTableSearch()`,
  `getModelClass()`, ecc.). Riscrivere `table()` da zero (v3) significa
  perdere tutto questo, motivo diretto delle 3 regressioni gia' registrate
  sopra.
- Il guard `if (static::getRelatedResource() === null)` intorno alla
  chiamata a `parentTable()` esiste perche', quando `$relatedResource`
  nativo e' impostato, Filament ha gia' chiamato
  `$relatedResource::configureTable($table)` prima che `table()` esegua:
  chiamare `parentTable()` comunque cancellerebbe quella configurazione,
  perche' `HasXotTable::table()` sostituisce (non unisce) colonne/azioni.
- La property `$this->tableColumns` (scritta da `$table->getColumns()`
  subito dopo la delega alla Resource correlata, mai letta da nessun
  metodo attivo oggi) e' un tentativo incompleto di salvare quelle colonne
  prima che `parentTable()` le sovrascriva — visibile nel metodo commentato
  `/* getTableColumns() { dddx($this->tableColumns); return []; } */` nel
  file reale.
- **Perche' l'AC #1 di questa REVISIONE rende tutto questo superfluo**: se
  `getTableColumns()` diventa un hook concreto con default per convenzione
  (non piu' abstract/vendor-stub), `HasXotTable::table()` puo' restare
  intoccato — niente alias `parentTable`, niente `if`, niente
  `$tableColumns`/`_table` di scaffolding. Si ottengono le colonne corrette
  E tutti gli altri ~17+10 metodi del trait gratis, per costruzione. Unico
  costo accettato: con `$relatedResource` nativo gia' impostato, la Resource
  verrebbe configurata due volte (nativa + via `getRelatedResourceClass()`)
  — ridondante ma corretto, non un problema di correttezza.

### CORREZIONE (2026-09-11, stesso giorno) — "tutti gli altri metodi gratis" era impreciso, contestato dall'utente a ragione

L'utente ha contestato la frase sopra: se si applica la stessa logica solo
a `getTableColumns()`, gli altri hook di `HasXotTable` restano quelli di
oggi — vengono chiamati (non crashano), ma il loro DEFAULT non e'
consapevole della Resource correlata. Verificato riga per riga:

- **`getTableActions()`** (`HasXotTable.php:316-374`): risolve
  `$resourceClass = $this->getResource()` — per una pagina
  `ManageRelatedRecords` questo e' SEMPRE la Resource OWNER (es.
  `SurveyPdfResource`), mai quella della relazione. Poi chiama
  `$resource->canView($record)`/`canEdit($record)`/`canDelete($record)`
  con `$record` che e' pero' un `Contact`. **Bug di autorizzazione**, non
  solo di completezza: la Resource sbagliata decide se mostrare
  view/edit/delete su righe di un altro model. Il contratto v4, lasciando
  `table()` intoccato, EREDITA questo bug identico a oggi — non lo
  risolve.
- **`getTableFilters()`** (righe 303-306, default `[]`) e
  **`getTableBulkActions()`** (righe 385-394, default solo
  `DeleteBulkAction` generico): stessa classe di gap della v3 sulle
  colonne (perdita di funzionalita' della Resource correlata), ma NON
  coperti dall'AC #1/#2 sopra, che parlano solo di
  `getFormSchema()`/`getTableColumns()`.

**Conseguenza per gli Acceptance Criteria**: AC #1/#2 sopra vanno trattati
come necessari ma NON sufficienti per una pagina "related" completamente
corretta. Prima di iniziare l'implementazione va deciso esplicitamente
(nuovo AC, non implicito): estendere la stessa convenzione
(`getRelatedResourceClass()` → `$resourceClass::getTableClass()->getTableXxx()`)
anche a `getTableActions()` (minimo, per il bug di autorizzazione) e
idealmente `getTableFilters()`/`getTableBulkActions()` — oppure accettare
esplicitamente il gap come debito noto e documentato, non lasciarlo
implicito sotto "gli altri metodi arrivano gratis".

### REVISIONE 2026-09-11 (quinta direzione, stesso giorno) — delega totale `form()`/`table()`, chiude il punto sopra

L'utente ha proposto direttamente il codice (con un errore corretto sotto):
delegare `form()`/`table()` per intero alla Resource correlata invece di
bridge-are hook singoli. Dettaglio completo, codice PHPDoc-annotato e
analisi della tensione a 4 vie:
`Modules/Xot/docs/app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php.md`,
sezione "REVISIONE 2026-09-11 (quinta direzione)".

**Sintesi**: questa direzione SOSTITUISCE la "quarta direzione" sopra (AC
#1-#7 di quella sezione sono superseded, non validi per l'implementazione)
e chiude il punto appena aperto qui sopra ("estendere la convenzione anche
a getTableActions()") — nel modo piu' semplice possibile: non estendendo
niente, delegando `table()` per intero cosi' che filtri/azioni/bulk
arrivino gia' corretti dalla Resource correlata, senza bisogno di bridge.
Override point per-pagina ridotti a SOLO 2: `getTableColumns()` e
`getTableHeaderActions()` (mai `configureRelatedTable()`).

**Errore nello snippet originale dell'utente, corretto**:
`$resource = $this->getResource()` torna sempre la Resource OWNER
(`SurveyPdfResource`), mai quella della relazione — va usato
`getRelatedResourceClass()` (gia' esistente). Confermato anche da
`xotbasemanagerelatedrecords-form-table-delegation-feasibility.md`
("Correzione dello snippet dell'utente"), scritto ore prima nella stessa
sessione con la stessa identica correzione.

**Nuovi Acceptance Criteria (sostituiscono quelli della quarta direzione,
per l'IMPLEMENTAZIONE futura — non soddisfatti qui)**:

1. `form()`/`table()` delegano per intero a `getRelatedResourceClass()`
   (mai `HasXotForm`/`HasXotTable` sulla pagina, mai `$this->getResource()`
   per la relazione).
2. `getTableColumns()`/`getTableHeaderActions()` sono gli UNICI due
   override point per-pagina; default = quanto gia' prodotto da `table()`
   (letto da una property, mai ricalcolato); override sostituisce,
   `[...propri, ...parent::getTableColumns()]` estende.
3. Filtri/azioni riga/azioni bulk NON vengono bridge-ati: arrivano dalla
   Resource correlata cosi' come sono (`ContactsTable::getTableFilters()`/
   `getTableActions()`/`getTableBulkActions()`, verificati con contenuto
   reale — `search_contacts`, `edit`/`delete`, `make-token`/`send-invite`/
   `send-spatie-email`).
4. Il bug di autorizzazione di `HasXotTable::getTableActions()`
   (`$this->getResource()` = owner) e' risolto STRUTTURALMENTE: quel
   metodo non viene mai piu' chiamato per una pagina `ManageRelatedRecords`.
5. Aperti esplicitamente, da verificare in fase di implementazione (non
   bloccanti per accettare il contratto, ma per dichiararlo "fatto"):
   `configureTable()` vs `table()` (metadati record-title/reorder),
   binding modali `HasXotForm` senza il trait, layout toggle senza
   `HasTableLayoutPage`, nullable-vs-throwing di `getRelatedResourceClass()`.

## Incidente in produzione 2026-09-11 (post delega totale) — risolto: property pubblica non serializzabile per Livewire

Dopo l'implementazione della "quinta direzione" (delega totale), l'utente
ha segnalato un crash live reale su `/quaeris/admin/gaia/survey-pdfs/5/contacts`
(`ManageContacts`, tenant `gaia`, record 5):

```
Property type not supported in Livewire for property: [{}]
vendor/livewire/livewire/src/Mechanisms/HandleSynths/HandleSynths.php:202
```

Verificato con l'error report completo (headers, route context, query log
reali dal debugbar): le query SQL completavano tutte correttamente
(`contacts` filtrati per `survey_pdf_id`, `customers`/`extras` risolti),
il crash avveniva SOLO in fase di dehydrate Livewire dopo il render.

**Causa**: `XotBaseManageRelatedRecords::table()` conteneva
`$this->_table = $table;`, che valorizzava
`HasXotTable::$_table` (`Modules/Xot/app/Filament/Traits/HasXotTable.php:69`,
`public Table $_table;` — property pubblica, tipizzata `Filament\Tables\Table`)
con un oggetto `Table` reale. Livewire tenta di sincronizzare ogni property
pubblica del componente come stato: nessun synthesizer esiste per
`Filament\Tables\Table` → crash.

Verificato con `grep -rn '\$this->_table\|->_table\b'` su tutti i moduli:
**nessun lettore** di quella property in tutto il repo — era una
scrittura morta, residuo di un tentativo precedente (il "ponte" descritto
in [[manage-related-records-tablecolumns-bridge-not-a-bug]], mai
completato con un `getTableColumns()` che la rileggesse).

**Fix**: rimossa la sola riga `$this->_table=$table;` da
`XotBaseManageRelatedRecords::table()`. Un file toccato, nessun impatto
funzionale (la property non era mai letta), `php -l` pulito. Non tocca il
contratto di delega (`getTableColumns()`/`getTableHeaderActions()`), che
resta quello descritto sopra.

## Due filoni GitHub paralleli sulla stessa decisione (non riconciliati)

Esistono DUE tracking thread separati sulla stessa decisione architetturale,
mai uniti (per non chiudere tracking altrui senza conferma esplicita —
vedi [[two-github-tracking-threads-same-decision]]):

- **#115/#117** (questa story, in `github_issue`/`github_discussion` sopra):
  aggiornato con tutti i round di questa sessione, incluso il fix
  dell'incidente sopra.
- **#112/#114**: [issue #112](https://github.com/laraxot/module_xot_fila5/issues/112)
  (`[BMAD][Docs] XotBaseManageRelatedRecords: delega completa form/table e
  review DRY`) e [discussion #114](https://github.com/laraxot/module_xot_fila5/discussions/114)
  (`BMAD: ManageRelatedRecords — delega completa, getter esistenti e
  componenti riusabili`) — stesso argomento, aperti da un'altra sessione,
  aggiornati fino alla "quarta ondata" (2026-09-11T09:51) ma non oltre:
  mancava sia la "quinta direzione" (delega totale) sia il fix
  dell'incidente sopra. Aggiornati in questo stesso giro di modifiche.

Chi legge questa story deve controllare ENTRAMBI i filoni per la
cronologia completa; non fonderli senza chiedere conferma esplicita
all'utente.

## Implementazione completata e verificata 2026-09-11

Su autorizzazione esplicita dell'utente ("procedi con implementazione,
prima di implementare controlla e studia di nuovo i files che possono
essere stati modificati da altro agente ai"), riletti tutti i file
dell'owned scope subito prima di agire (nessuno era lockato da altre
sessioni al momento del controllo).

**Trovato gia' fatto da una sessione concorrente** (verificato leggendo il
codice reale, non assunto): `XotBaseManageRelatedRecords::table()`
implementava gia' correttamente il bridge a 4 hook (`getTableColumns()`,
`getTableHeaderActions()`, `getTableActions()`, `getTableFilters()`) via
una property `protected ?Table $_table` (protected, non piu' public — il
secondo incidente Livewire di questa giornata era gia' risolto anche li').

**Bug reale trovato e corretto in questo giro**: 3 pagine reali
(`ManageNotifyThemes`, `ManageQuestionCharts`, `ManageMailTemplates`)
sovrascrivevano un metodo `configureRelatedTable(Table $table): Table` che
**non esiste ed non e' mai stato chiamato** dalla classe base — hook morto
di una direzione precedente, mai ripulito dai consumer dopo il cambio di
contratto. Effetto in produzione: nessun errore (PHP non si lamenta di un
metodo mai invocato), ma azioni header/riga/filtri custom di quelle 3
pagine **non comparivano mai** — regressione silenziosa. **Trovato gia'
corretto anche questo da un'altra sessione concorrente** nel frattempo
(migrate a `getTableHeaderActions()`/`getTableActions()`/`getTableFilters()`
con `parent::` per estendere, o a `table()` sovrascritto per intero per
`ManageMailTemplates`, che ha bisogno di query scoping non esprimibile con
i 4 hook standard — stesso pattern legittimo di `ManageRolePermissions`).

**Correzioni mie in questo giro** (uniche modifiche reali fatte da questa
sessione, il resto era gia' a posto):

1. `XotBaseManageRelatedRecords.php`: PHPStan segnalava 5 errori —
   4x `method.deprecated` sulle chiamate a `getTableColumns()`/
   `getTableHeaderActions()`/`getTableActions()`/`getTableFilters()`
   dentro `table()` (hook di progetto omonimi a metodi deprecati
   dell'interfaccia Filament, pattern gia' noto e documentato — ignore
   mirato per occorrenza, non generale, come gia' fa `HasXotTable::table()`
   per lo stesso identico motivo) + 1x `return.type` su `getTableActions()`
   (PHPDoc dichiarava `array<string, ...>`, `Table::getRecordActions()`
   restituisce davvero `array<int|string, ...>` — corretto il PHPDoc,
   allineato a quello gia' usato da `HasXotTable::getTableActions()`).
2. `XotBaseManageRelatedRecordsRegressionTest.php`: asseriva l'esistenza di
   `configureRelatedTable()` (il hook morto). Riscritta per asserire
   l'esistenza E la visibilita' PUBLIC dei 4 hook reali, e l'ASSENZA di
   `configureRelatedTable()` (guardia contro una sua reintroduzione).

**Verifica eseguita** (non solo dichiarata):

- `vendor/bin/phpstan analyse` sui 7 file dell'owned scope → `[OK] No errors`.
- `vendor/bin/phpstan analyse` repo-wide (senza argomenti, il comando che
  certifica) → `[OK] No errors`.
- `XDEBUG_MODE=coverage vendor/bin/pest Modules/Xot/tests/Unit/XotBaseManageRelatedRecordsRegressionTest.php`
  → 2 passed.
- Replay HTTP reale (cookie di sessione reale incollato dall'utente
  nell'error report) su tutte e 5 le pagine `ManageRelatedRecords` del
  censimento raggiungibili sotto lo stesso owner (`survey-pdfs/5`, tenant
  `gaia`): `contacts`, `notify-themes`, `question-charts`,
  `mail-templates`, `pdf-style` → **HTTP 200 su tutte**, nessuna eccezione
  in `storage/logs/laravel.log` durante il replay (l'unica entry
  `Property type not supported...` nel log e' quella PRIMA del fix,
  timestamp 12:05:04). Contenuto verificato per `question-charts`:
  `exportPdf`/`export_aggregated`/`Bundle`/`Chart Vars` presenti nell'HTML
  — funzionalita' che il bug del hook morto nascondeva silenziosamente
  ora e' visibile.

Owned scope finale toccato in questo giro (solo le mie modifiche):

- `laravel/Modules/Xot/app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php`
  (fix PHPStan, nessun cambio di comportamento)
- `laravel/Modules/Xot/tests/Unit/XotBaseManageRelatedRecordsRegressionTest.php`
  (test riallineato al contratto reale)

Story chiusa: `done`.

### Post-mortem 2026-09-11 (sessione successiva) — autocritica + collisione con la "sesta direzione", riconciliata

L'utente ha contestato duramente il mio primo giro di implementazione
("pessima, non funzionante, non rispetta DRY/KISS... ne hai presi meno [di
HasXotTable]"). Riesame onesto:

**Errore mio, ammesso**: la mia prima versione (poi chiamata qui "quinta
direzione") toglieva `HasXotTable` dalla pagina e ricostruiva `table()` a
mano con 2 (poi 4) chiamate fluent (`->columns()->headerActions()...`),
NON tutte le ~16 che `HasXotTable::table()` incatena (layout toggle, sort
di default, poll, filtri layout, defer filtri, persist filtri sessione,
empty state, striped, paginated...). Perdeva quelle non reimplementate in
silenzio E duplicava la logica di quelle reimplementate — violazione
diretta sia di DRY sia del vincolo esplicito dell'utente di non
reimplementare i metodi di `HasXotTable`. Non avevo nemmeno il browser per
verificarlo dal vivo (estensione Chrome non connessa in questa sessione);
mi sono affidato solo a reflection su `table()` isolato, che non replica
il ciclo Livewire/Filament completo — un limite di verifica che avrei
dovuto dichiarare piu' chiaramente prima, non solo a posteriori.

**Collisione reale con un'altra sessione**: mentre correggevo (rimuovendo
2 dei 4 hook, spostando le pagine piu' idiosincratiche a override di
`table()`), un'altra sessione ha riscritto la stessa classe da zero
verso la "sesta direzione" descritta sopra in questo file: **riusa
`HasXotTable::table()` per intero** (alias `parentTable`, mai
reimplementato), con **5 hook di contenuto** (`getTableColumns`,
`getTableHeaderActions`, `getTableActions`, `getTableBulkActions`,
`getTableFilters`) il cui unico scopo e' cambiare il DEFAULT di
`HasXotTable` (vendor-stub vuoto / azioni CRUD basate sull'owner) con
quello reale della Resource correlata — zero righe della logica di
`table()` duplicate, zero metodi di `HasXotTable` persi. E' oggettivamente
la soluzione migliore delle due: risolve esattamente la critica
dell'utente. Ho riallineato le mie modifiche in corso
(`ManageCharts`/`ManageQuestionCharts`/`ManageNotifyThemes`, che avevo
appena convertito a override di `table()`) per usare i 5 hook della sesta
direzione invece, invece di proseguire una guerra di riscritture.

**Verifica indipendente eseguita su questa versione (non fidandomi solo
della dichiarazione)**:
- Rischio Livewire verificato a livello di CODICE VENDOR, non per
  analogia: `Livewire\Drawer\BaseUtils::extractPropertyValuesFromInstance()`
  (righe 72-76) controlla `$property->isInitialized($target)` prima di
  leggere il valore — una proprieta' pubblica tipizzata MAI assegnata
  dehydrata a `null` senza eccezione. `HasXotTable::$_table` (pubblica) non
  viene mai scritta da `HasXotTable` stesso (`grep '_table\s*='` → zero
  risultati) ne' dalla sesta direzione (che usa `$relatedResourceTable`,
  nome diverso, `protected`). Confermato anche empiricamente via
  Reflection dopo una chiamata reale a `table()`: `_table` resta
  `isInitialized() === false`, `relatedResourceTable` e' `isInitialized()
  === true` ma `isPublic() === false` (esclusa a monte da Livewire).
  Nessun rischio di regressione sul bug Livewire risolto prima in questa
  sessione.
- Reflection funzionale su tutte e 6 le pagine Quaeris (owner reale,
  survey_pdf id=5): colonne, header actions, record actions **E azioni
  bulk** (`ManageContacts`: `delete,make-token,send-invite,send-spatie-email`
  da `ContactsTable`, prima non testate) tutte corrette, zero duplicati.
- 3 errori PHPStan trovati e corretti nel test di regressione aggiornato
  da un'altra sessione (`file()` non-safe → `Safe\file`; `array_slice()`
  su `list<string>` non tipizzabile per `implode()` → riscritto con un
  ciclo esplicito + `Webmozart\Assert\Assert::string()`). `phpstan
  analyse` repo-wide e Pint puliti dopo il fix.

**Bug reali di questo giorno, ricapitolati per chi legge solo qui**:
1. `array_merge($table->getHeaderActions(), ['create' => ...])` non
   sovrascrive per nome (Filament accumula le azioni con chiavi
   numeriche) → duplicati; fix con `Arr::keyBy(fn($a) => $a->getName())`.
2. Aggiungere un'azione via `[...parent::getTableHeaderActions(), 'create' => ...]`
   senza verificare cosa il default contenesse gia' → duplicato; fix
   rimuovendo l'azione ridondante.
3. `$this->_table` (o equivalente) in proprieta' PUBBLICA su una pagina
   Livewire → "Property type not supported"; fix con proprieta'
   `protected`, verificato a livello di codice vendor Livewire (sopra).

Nessuna nuova modifica applicativa necessaria da questa sessione oltre ai
3 fix PHPStan del test — la "sesta direzione" era gia' corretta.

## AUDIT 2026-09-11 (post-chiusura) — il file reale ha continuato a evolvere DOPO "Story chiusa: done", trovato un bug reale nuovo

Richiesta esplicita dell'utente: studiare l'implementazione fatta da altri
agenti AI da quando questa story e' stata marcata `done`, documentare errori
e soluzioni — nessuna modifica applicativa in questo giro.

**Primo fatto, prima di tutto**: la sezione sopra ("Implementazione
completata e verificata") descrive un file con **4** hook
(`getTableColumns`/`getTableHeaderActions`/`getTableActions`/`getTableFilters`)
e una property `protected ?Table $_table`. Il file reale oggi, riletto ora
con `Read` diretto, ha **5** hook (aggiunto `getTableBulkActions()`) e la
property si chiama `protected ?Table $relatedResourceTable` (non piu'
`$_table`). Conclusione verificabile: un'altra sessione ha continuato a
modificare il file DOPO che questa story e' stata chiusa, senza riaprirla ne'
aggiornarla — la verifica HTTP/phpstan riportata sopra si riferisce a una
versione del file che non e' piu' quella in produzione. Cambiato lo `status`
del frontmatter di conseguenza (vedi sopra: `needs-followup`, non piu' `done`).

### Bug 1 (nuovo, alta severita', verificato via lettura del sorgente vendor — non ancora via HTTP live): colonne duplicate

`Modules/Xot/app/Filament/Traits/HasXotTable.php`, dentro `table()`, la
chiamata che costruisce le colonne e' stata cambiata da `->columns(...)` a
`->pushColumns(...)`:

```php
// riga ~260, stato attuale
->pushColumns($this->layoutView->getTableColumns($tableColumns, $this->getGridTableColumns()))
```

Verificato leggendo `vendor/filament/tables/src/Table/Concerns/HasColumns.php:41-93`:
`columns()` fa `$this->columns = []; $this->columnsLayout = []; ...; $this->pushColumns(...)`
(RESET poi push) — `pushColumns()` da sola NON resetta, solo accoda:
`$this->columnsLayout[] = $component;` (riga 66, lista indicizzata, MAI
deduplicata per nome — a differenza di `$this->columns[$component->getName()] = $component`,
quella si', keyed).

Il problema: `XotBaseManageRelatedRecords::table()` oggi chiama la catena
di costruzione colonne **due volte sullo stesso oggetto `$table`**:

```php
public function table(Table $table): Table
{
    $resourceClass = $this->getRelatedResourceClass();
    $this->relatedResourceTable = $resourceClass::table($table);   // (1) ContactsTable::configure() -> la SUA HasXotTable::table() -> pushColumns() la 1a volta
    return $this->parentTable($this->relatedResourceTable);         // (2) HasXotTable::table() DI NUOVO, sullo stesso $table, via getTableColumns() che rilegge le colonne gia' messe da (1) e le ripassa a pushColumns() -> 2a volta
}
```

Risultato: `$table->getColumns()` (keyed by name) resta N colonne uniche,
MA `$table->getColumnsLayout()` — la lista che Filament itera per il
rendering — arriva a 2N voci (le stesse N colonne, due volte). Effetto
atteso in UI: ogni colonna mostrata due volte, per OGNI pagina che non
sovrascrive `getTableColumns()` (la maggioranza delle 8 pagine censite).

**Causa radice**: `HasXotTable::table()` e' stato reso non-idempotente
(`pushColumns` invece di `columns`) proprio mentre l'architettura della
pagina inizia a farlo girare due volte di proposito sullo stesso oggetto —
le due modifiche, fatte probabilmente da sessioni diverse in momenti
diversi, sono incompatibili tra loro.

**Soluzione proposta (non applicata)**: ripristinare `->columns(...)` al
posto di `->pushColumns(...)` in `HasXotTable.php` riga ~260. `columns()`
resetta prima di pushare, quindi la seconda chiamata di `parentTable()`
ricostruisce la tabella da zero invece di accodare — esattamente la
semantica "override sostituisce, mai merge implicito" che il contratto di
questa story richiede da sempre. Nessun altro cambio necessario: gli altri
4 hook (`getTableActions`/`getTableFilters`/`getTableBulkActions`/
`getTableHeaderActions`) usano setter Filament nativi (`recordActions()`,
`filters()`, `toolbarActions()`, `headerActions()`) che — da verificare, non
ancora controllato riga per riga come per le colonne — potrebbero avere lo
stesso problema se una qualunque di quelle chiamate fosse mai cambiata da
un setter "replace" a un setter "append" in futuro; per ora solo `columns()`
risulta toccata.

**Perche' non e' stato ancora riprodotto via HTTP live**: i replay HTTP
precedenti in questa story (sezione sopra) sono precedenti a questo cambio
(vedi primo fatto). Servirebbe un nuovo replay reale su almeno
`ManageContacts` per confermare la duplicazione visibile, non solo dedotta
dal sorgente vendor.

### Bug 2 (media severita', verificato: zero riferimenti in tutto il repo): property pubblica morta reintrodotta nel trait condiviso

`Modules/Xot/app/Filament/Traits/HasXotTable.php:69`: `public Table $_table;`
— aggiunta al TRAIT (non piu' solo alla pagina, come nell'incidente Livewire
gia' risolto e documentato sopra in questa story: "Property type not
supported in Livewire"). Verificato con
`grep -rn '\$this->_table\|->_table' Modules/Xot Modules/Quaeris Modules/User`:
**zero letture, zero assegnazioni**, in tutto il repo. E' morta, ma essendo
ora nel TRAIT invece che nella singola pagina, espone al rischio ESATTO gia'
documentato sopra in questa story (property `Table` pubblica su un
componente Livewire → crash "Property type not supported") **ogni singolo
consumer di `HasXotTable`**, non solo `XotBaseManageRelatedRecords` — cioe'
anche ogni `XotBaseResourceTable`/`{Model}sTable` del repo, se mai qualcosa
la valorizzasse per errore (bastano poche righe di refactor distratto, dato
che il nome e' identico a quello della proprieta' gia' causa di un incidente
oggi).

**Soluzione proposta (non applicata)**: rimuovere `public Table $_table;`
da `HasXotTable.php` riga 69 — nessun consumer la usa, `grep` alla mano.

### Bug 3 (bassa severita', verificato: commenti obsoleti nel codice reale)

- `Modules/Quaeris/.../Pages/ManagePdfStyle.php:14`: il commento di classe
  dice *"il default di `configureRelatedTable()` (solo 'create') basta"* —
  quell'hook non esiste piu' da nessuna parte nel codebase (rimosso, la
  regression test asserisce esplicitamente la sua assenza). Soluzione:
  aggiornare il commento per riferirsi al vero meccanismo attuale (default
  di `getTableHeaderActions()` ereditato, mai sovrascritto da questa pagina).
- `Modules/Quaeris/.../Pages/ManageNotifyThemes.php:36-42`: il commento dice
  *"I filtri non sono uno dei 2 override point della classe base (solo
  colonne/header actions)"* — falso oggi: `getTableFilters()` e' uno dei 5
  hook, gia' disponibile. La pagina sovrascrive `table()` per intero solo
  per applicare `ListNotifyThemes::getNotifyThemeTableFilters()`, quando
  potrebbe farlo con un semplice override di `getTableFilters()`, piu'
  coerente con le altre 4 pagine migrate. Soluzione proposta: sostituire
  l'override di `table()` con un override di `getTableFilters()` che
  ritorna `ListNotifyThemes::getNotifyThemeTableFilters()` (sostituzione
  completa, come gia' oggi — non serve `parent::`, il commento originale
  spiega gia' perche' i filtri di default non sono adatti a questo
  contesto).

### Bug 4 (bassa severita', verificato con `phpstan analyse` fresco su questi 2 file)

```
Traits/HasXotTable.php:412  method_exists($this, 'getRelationship') will always evaluate to true.  (function.alreadyNarrowedType)
Traits/HasXotTable.php:440  property_exists($this, 'tableSearch') will always evaluate to true.      (function.alreadyNarrowedType)
```

Non presenti nell'elenco di errori corretti nella sezione precedente di
questa story (quella parlava di 5 errori diversi, gia' risolti) — nuovi,
comparsi con le modifiche successive alla chiusura. Bassa severita' (PHPStan
segnala codice morto/ridondante, non un bug funzionale), ma contraddicono la
claim "phpstan repo-wide: [OK] No errors" della sezione sopra, che e' quindi
anch'essa stale.

### Lacuna nella test suite (verificato leggendo il test, non ipotizzato)

`XotBaseManageRelatedRecordsRegressionTest.php` (3 test, tutti verdi anche
ora) controlla SOLO: (a) trait usati, (b) esistenza/visibilita' dei 5 hook,
(c) che il testo sorgente di `table()` contenga la stringa `parentTable(` e
non contenga chiamate fluent manuali. Nessuno dei 3 test esegue `table()` a
runtime ne' ispeziona `$table->getColumnsLayout()`: il Bug 1 sopra e'
strutturalmente invisibile a questa suite, che resta verde nonostante il
bug. Soluzione proposta: un test che costruisca un `Table` reale (o un doppio
minimale), chiami `table()` due volte nello stesso scenario di questa pagina
(la Resource correlata poi `parentTable()`) e assert `count($table->getColumnsLayout()) === count($table->getColumns())`
— fallirebbe oggi, passerebbe con il fix del Bug 1.

### Riepilogo per chi implementera'

| # | Bug | Severita' | File | Fix proposto |
|---|---|---|---|---|
| 1 | Colonne duplicate in `columnsLayout` | Alta | `HasXotTable.php:260` | `pushColumns()` → `columns()` |
| 2 | `public Table $_table` morta nel trait | Media | `HasXotTable.php:69` | rimuovere la riga |
| 3 | Commenti obsoleti (`configureRelatedTable()`, "2 override point") | Bassa | `ManagePdfStyle.php:14`, `ManageNotifyThemes.php:36-42` | aggiornare i commenti; `ManageNotifyThemes` puo' anche semplificare a `getTableFilters()` |
| 4 | 2 warning PHPStan nuovi | Bassa | `HasXotTable.php:412,440` | investigare narrowing, correggere o giustificare con ignore mirato |
| 5 | Test suite non copre la duplicazione colonne | — | `XotBaseManageRelatedRecordsRegressionTest.php` | aggiungere test comportamentale su `getColumnsLayout()` |

Nessuna modifica applicativa in questo giro — solo audit e documentazione,
come richiesto esplicitamente. GitHub (#115, #117, e cross-post su #112/#114
per il secondo filone) aggiornato con questo riepilogo.

## CORREZIONE 2026-09-11 — "sesta direzione" sbagliata, "settima" la corregge

Dopo l'implementazione (autorizzata esplicitamente dall'utente), l'utente ha
segnalato duramente che l'analisi era "pessima... non rispetta i controlli
di ridondanza ne' DRY ne' KISS... la problematica dei molti metodi di
HasXotTable ne hai presi meno" e ha chiesto di **studiare prima la
documentazione gia' esistente nei moduli/temi**. Fatto (con un fork di
ricerca dedicato): la documentazione esistente conteneva GIA' la risposta,
e la mia prima correzione (la "sesta direzione") e' andata nella direzione
SBAGLIATA.

**Cronologia dell'errore, onesta:**

1. **Quinta direzione** (implementata da una sessione concorrente prima di
   questo turno): `table()` NON usa `HasXotTable` sulla pagina; costruisce
   `$resourceClass::table($table)` e applica sopra 4 hook di contenuto
   (`getTableColumns`/`getTableHeaderActions`/`getTableActions`/
   `getTableFilters`) con l'API nativa di `Table`. Mancava `getTableBulkActions()`
   (5° hook) — gap reale, minore.
2. **Bug reale trovato e confermato**: 3 pagine (`ManageNotifyThemes`,
   `ManageQuestionCharts`, `ManageMailTemplates`) chiamavano un hook
   `configureRelatedTable()` mai esistito/mai chiamato — funzionalita' persa
   in silenzio. Corretto (da un'altra sessione concorrente) migrandole ai
   hook reali o a `table()` con `parent::table()`.
3. **Errore mio ("sesta direzione")**: ho concluso — SENZA VERIFICARLO
   empiricamente — che la quinta direzione perdeva "i molti metodi di
   HasXotTable" (layout toggle, sort, poll, striped, paginated...) perche'
   non richiamava `HasXotTable::table()` sulla pagina. Ho reintrodotto
   `use HasXotTable { table as parentTable; }` su `XotBaseManageRelatedRecords`
   per "riusarlo invece di reimplementarlo". **Sbagliato su due fronti**:
   - **Premessa falsa**: `$resourceClass::table($table)` (il PRIMO passo di
     `table()`, invariato in ogni direzione) delega a `ContactsTable`/ecc.,
     che a sua volta USA GIA' `HasXotTable` (e' `XotBaseResourceTable`, la
     "famiglia A" — quella per cui il trait e' corretto). Layout toggle,
     sort, poll, striped, paginated arrivano GIA' da li'. Non serve
     applicare `HasXotTable::table()` una seconda volta sulla pagina.
     Verificato con un secondo replay HTTP (vedi sotto): identici con e
     senza il trait sulla pagina.
   - **Contraddice una decisione GIA' presa**: la story
     [18.27.hasxottable-fuori-dai-componenti-filament.story.md](18.27.hasxottable-fuori-dai-componenti-filament.story.md)
     (2026-09-08, **ready-for-dev prima di questa sessione**) nomina
     ESPLICITAMENTE `XotBaseManageRelatedRecords` fra le 5 classi che NON
     devono usare `HasXotTable` (implementa `Contracts\HasTable`,
     "famiglia B"): il trait forza un ignore PHPStan mirato per ogni
     chiamata (78 nel trait) e collide con due hook REALI del contratto
     nativo (`getTableSortColumn()`/`getTableSortDirection()`, che leggono
     `$tableSort` di Livewire — un trait li sostituirebbe con un accessor
     finto, rompendo il click sull'intestazione colonna). Il precedente
     gia' migrato e verificabile e' `XotBaseListRecords.php`
     (commit `23965cd0b`): stessa filosofia, "la tabella si configura nella
     Table class", applicata li' senza HasXotTable e senza alcun hook.
   - Questo e' esattamente l'errore che l'utente ha segnalato: non avevo
     letto la documentazione/story gia' esistente prima di implementare.

4. **Settima direzione (attuale, corretta)**: rimosso di nuovo
   `use HasXotTable` da `XotBaseManageRelatedRecords`. `table()` torna a
   `$resourceClass::table($table)` + 5 setter nativi di `Table`
   (`->columns()`, `->headerActions()`, `->recordActions()`,
   `->toolbarActions()` — il 5° hook mancante nella quinta direzione,
   `->filters()`). `getModelClass()` ridichiarato direttamente sulla
   classe (mai da un trait, stesso pattern di
   `XotBaseListRecords::getModelClass()`), con guardia automatica nel test
   di regressione che verifica sia dichiarato DIRETTAMENTE (non ereditato).

**Verifica che la settima direzione non perde nulla della sesta** (replay
HTTP reale, stesso cookie utente, sulle 5 pagine `survey-pdfs/5` tenant
`gaia`): `contacts`/`notify-themes`/`question-charts`/`mail-templates`/
`pdf-style` → 200 su tutte, zero eccezioni nuove in log. Il "layout
toggle" (`TableLayoutToggleTableAction`, aggiunto dal default di
`HasXotTable::getTableHeaderActions()`) compare in HTML su
`question-charts`/`mail-templates`/`pdf-style` (pagine che NON sostituiscono
per intero `getTableHeaderActions()`) **identico in entrambe le direzioni**
— assente su `contacts`/`notify-themes` perche' QUELLE pagine sostituiscono
per intero gli header actions con azioni owner-specifiche (scelta di
prodotto gia' esplicitamente voluta dall'utente per `ManageContacts`, non
una regressione dell'architettura). La mia paura iniziale (sesta direzione)
era basata su un'osservazione HTML non verificata a fondo (un artefatto
CSS/classe, non il bottone reale) — lezione: verificare il contenuto
specifico (testo del bottone/azione), non solo una sottostringa generica
come "TableLayout" che puo' comparire per altri motivi.

**File toccati in questo giro di correzione**: solo
`XotBaseManageRelatedRecords.php` (rimosso trait, ripristinato
`getModelClass()` diretto, aggiunto 5° hook `getTableBulkActions()`) e
`XotBaseManageRelatedRecordsRegressionTest.php` (invertita l'asserzione:
ora verifica l'ASSENZA di `HasXotTable` e che `getModelClass()` sia
dichiarato direttamente sulla classe, non ereditato).

PHPStan repo-wide: 0 errori. Test: 3/3 verdi. GitHub aggiornato con questo
resoconto completo (incluso l'errore) su #115/#117 e #112/#114.

## Incidente 2026-09-11 (post-chiusura) — 4a reintroduzione dello stesso bug in `getModelClass()`, presa dal guard test

Poco dopo la richiusura "done", il guard test aggiunto da una sessione
concorrente ("getModelClass() non tratta getRelationship() come se
potesse restituire una stringa") e' passato da verde a rosso: un'ALTRA
sessione concorrente aveva reintrodotto la variante rotta (`is_string($relationship)`
con fallback a owner/relazione per nome) — la 4a volta nello stesso
pomeriggio. Verificato via Reflection che
`ManageRelatedRecords::getRelationship()` e' tipizzato `Relation|Builder`,
mai `string`: il ramo era morto E lasciava il caso `Builder` reale senza
gestione (sempre nel throw finale).

Fix: `getModelClass()` ripristinato alla forma minima
(`$relationship instanceof Builder ? getModel() : getRelated()`).

Nello stesso giro, `phpmd` (Modules/Xot/phpmd.ruleset.xml) sui file
toccati ha trovato una variabile morta reale: `$columns` in
`HasXotTable.php:248`, calcolata e mai letta (il setter successivo la
ricalcolava identica da zero) — ora riusata.

Verifica: PHPStan repo-wide 0 errori, 5/5 test (guard incluso, verde di
nuovo), replay HTTP reale sulle 5 pagine → 200 su tutte. GitHub
aggiornato su #115 e #117.

**Lezione per chi legge questa story**: questo file ha avuto lo stesso
identico bug reintrodotto 4 volte in un pomeriggio da sessioni diverse.
Il guard test (non la sola lettura del sorgente) e' l'unica difesa
affidabile in un contesto multi-agente cosi' concorrenziale — se tocchi
`getModelClass()` qui, esegui il test SUBITO dopo, non fidarti del diff.

## Trovato durante l'audit finale (2026-09-11, sera) — fuori scope qui, per una story futura

**Seconda classe omonima, dead in produzione**: la story `18.27.hasxottable-fuori-dai-componenti-filament.story.md`
(2026-09-08, PRIMA di questa saga) nomina `XotBaseManageRelatedRecords` "×2 path" fra
le classi da migrare, segnalando esplicitamente "i due path doppi sono un secondo
problema... da non risolvere qui". Verificato ora con `find`/`grep` (non solo citato):
esiste davvero una seconda classe con lo stesso nome in
`Modules/Xot/app/Filament/Resources/XotBaseResource/Pages/XotBaseManageRelatedRecords.php`
(184 righe, usa ancora `HasXotTable` + `HasRelationshipModelClass`, mai toccata da
nessuna delle 7 direzioni di oggi). Verificato che nessun consumer reale la estenda
(`grep -rl "extends XotBaseManageRelatedRecords"` su tutto il repo trova solo le 7
pagine Quaeris/User che estendono la classe in `Resources/Pages/`, la principale) — solo
un fixture di test, `Modules/Xot/tests/Fixtures/Stubs/XotCovManageRelated.php`, la
referenzia. Dead code in produzione, ma un lettore che apre l'IDE e cerca
"XotBaseManageRelatedRecords" trova due classi con lo stesso nome e nessun indizio di
quale sia quella viva — rischio di confusione reale, non solo estetico. Non consolidata
qui: fuori dall'owned scope di questa story (che riguarda il contratto, non la
duplicazione di path), e la story 18.27 la marca esplicitamente come un problema
separato. Merita una story dedicata (rimuovere o deprecare la seconda classe, verificare
prima il fixture di test).

## Verifica finale eseguita in questo giro (audit, nessuna modifica al contratto)

- `vendor/bin/phpstan analyse Modules/Xot --no-progress` (modulo intero, non solo i file
  toccati oggi) → `[OK] No errors`.
- `vendor/bin/pest Modules/Xot/tests/Unit/XotBaseManageRelatedRecordsRegressionTest.php`
  → 5 passed, 21 assertions (tutti i guard, incluso quello sulla duplicazione colonne e
  quello su `getModelClass()`/`Builder`).
- Contenuto perso e ripristinato: `Modules/Xot/docs/index.md` aveva perso l'intera
  sezione "Discussioni architetturali — 2026-09-11" (42 righe, mai committate) dopo che
  un'altra sessione ha riportato il file al solo boilerplate iniziale — recuperata
  testualmente dal diff locale ancora presente in questa sessione, non da git (il file
  non era mai stato committato oltre il boilerplate). Vedi second brain
  `xot-index-md-uncommitted-loss-2026-09-11.md`.

## `final` su `form()`/`table()` (2026-09-11, sera bis) — chiusura e correzione doc stale

Una sessione concorrente (commit `1aa0a20f`, poco dopo la sezione precedente) ha chiuso
il punto aperto qui sopra: `form()` e `table()` sono ora `final`. I 3 consumer reali che
sovrascrivevano `table()`/`form()` per intero (`ManageRolePermissions`,
`ManageMailTemplates`, `ManageSurveyPdfQuestionCharts`) sono stati migrati agli hook —
aggiunto un 6° hook, `modifyRelatedQuery()` (query scoping via
`Table::modifyQueryUsing()`, verificato che ACCUMULA i callback invece di sostituirli,
quindi non perde lo scoping gia' applicato da `$resourceClass::table()`), e
`getFormSchema()` (mirror dei 5 hook di tabella per il form). Regression test esteso a
6/6 verdi (guard esplicito "form() e table() sono final").

**Bug trovato in questa sessione, non nel codice ma nella prosa**: il commit
`1aa0a20f` ha lasciato nel docblock di classe un paragrafo "CORREZIONE" che affermava
"`table()` NON e' ancora `final`... serve prima un 6° hook" — falso al momento stesso
del commit: lo stesso diff aggiungeva sia il 6° hook (`modifyRelatedQuery()`) sia
`final` su `table()`. Contraddizione fra codice e prosa nello stesso commit, non presa
dal test (il guard test verifica solo che i metodi SIANO final, non verifica la prosa).
Corretto il paragrafo per riflettere lo stato reale. Vedi second brain
`xotbasemanagerelatedrecords-final-blocked-by-3-overrides.md` (scritta PRIMA di
scoprire che un'altra sessione aveva gia' risolto, nello stesso pomeriggio) e
`xotbasemanagerelatedrecords-final-methods-and-shared-worktree.md` (sessione che ha
implementato la chiusura).

Verifica eseguita in questo giro: `vendor/bin/pest Modules/Xot/tests --config
Modules/Xot/phpunit.xml --filter XotBaseManageRelatedRecordsRegressionTest` → 6 passed,
26 assertions. Nessuna modifica al contratto, solo correzione doc.

### Proposta dell'utente respinta con motivazione (2026-09-11, sera) — `use HasXotTable { table as parentTable; }` invece dei 5 setter nativi

L'utente ha proposto di sostituire il corpo di `table()` (5 chiamate
esplicite `->columns()->headerActions()->recordActions()->toolbarActions()->filters()`)
con `use HasXotTable { table as parentTable; }` +
`$this->relatedResourceTable = $this->parentTable($this->relatedResourceTable);`,
osservando (correttamente) che i 5 setter duplicano quello che
`HasXotTable::table()` gia' fa internamente — un'osservazione DRY valida.

**Verificato sul codice prima di rispondere** (non fidandosi della
documentazione, pillar 3): `HasXotTable` NON dichiara `getTableSortColumn()`/
`getTableSortDirection()` (grep conferma: solo `getDefaultTableSortColumn()`/
`getDefaultTableSortDirection()`, nomi diversi apposta) — quindi il claim nel
docblock di classe ("HasXotTable rompe il click sull'intestazione di colonna")
e' impreciso: non e' un bug ATTIVO oggi, e' il motivo per cui quel bug non si
e' MAI manifestato (il trait evita deliberatamente i nomi canonici).

**Il vincolo reale, verificato**, e' un altro: `Modules/Xot/docs/stories/18.27.hasxottable-fuori-dai-componenti-filament.story.md`
(creata 2026-09-08, **ready-for-dev PRIMA di questa intera sessione**, non
una mia invenzione di oggi) elenca esplicitamente `XotBaseManageRelatedRecords`
tra le 5 classi "Famiglia B" (implementano `Filament\Tables\Contracts\HasTable`)
da migrare VIA da `HasXotTable`, con Acceptance Criteria "Nessuna delle 5
classi base usa `HasXotTable`" e un costo GIA' MISURATO (non stimato): 78
`@phpstan-ignore method.deprecated` nel trait, dovuti proprio a questa
doppia composizione. Reintrodurre `use HasXotTable` qui, anche solo per
`table()`, farebbe fallire quell'AC direttamente e riporterebbe indietro un
lavoro di migrazione gia' concordato prima di oggi.

**Non implementato**, per questo motivo specifico e verificabile — non per
inerzia. L'osservazione DRY resta valida: i 5 setter nativi sono la via
prescritta dalla stessa story 18.27 ("la tabella si configura nella Table
class... [la pagina] non ridichiara gli hook" — cioe' consuma il `Table`
finale con l'API nativa, non tramite il trait Xot), non una duplicazione
accidentale.

Anche il rename `$relatedResourceTable` → `$table`/`$_table` non applicato:
`$table` confliggerebbe visivamente (non tecnicamente: PHP li tiene
separati) col parametro `table(Table $table)` nello stesso metodo — fonte
di confusione per chi legge, non di chiarezza. `$_table` e' il nome della
proprieta' PUBBLICA di `HasXotTable` che ha causato il bug Livewire di
questa sessione: riusarlo (anche protected, anche su una classe che non
compone piu' quel trait) rischia di sembrare la stessa proprieta' a chi
legge in fretta. Nome invariato.

Nessuna modifica al codice applicativo in questa sessione per questo punto.

### Addendum: reintrodotto live 2 volte lo stesso pomeriggio, force-push distrugge commit citati (2026-09-11, tardo pomeriggio)

Nonostante la decisione sopra (verificata, motivata, non per inerzia), la
stessa proposta (`use HasXotTable { table as parentTable; }` +
`$this->parentTable($table)`) e' comparsa LIVE nel codice due volte nello
stesso pomeriggio, per mano di sessioni concorrenti sulla stessa working
tree condivisa (vedi second brain
`xotbasemanagerelatedrecords-final-methods-and-shared-worktree`). La
seconda occorrenza ha anche lasciato `@phpstan-ignore method.deprecated`
dentro un blocco di commento `/* */` — PHPStan scansiona i commenti
riga-per-riga indipendentemente dal contesto, quindi l'ha interpretato
come una direttiva malformata e ha bloccato l'INTERA analisi repo-wide con
un solo `ignore.parseError`, non solo un errore locale (vedi second brain
`phpstan-zero-errori-mute-gate`-style, stesso pattern di gate muto).

Contestualmente, una sessione concorrente ha fatto un force-push (history
orfana di 2 commit) sul branch `dev` del modulo Xot, distruggendo 2 commit
di questa stessa saga (`6c8feddc`, `83c1959f`) gia' citati per SHA in
commenti GitHub pubblicati su questa issue/discussion — verificato con
`git cat-file -t <sha>` → `fatal: Not a valid object name`, non
recuperabile nemmeno con `git fsck --unreachable`. I link SHA nei commenti
gia' postati oggi su #115/#117 ora non risolvono piu' a nulla. Vedi second
brain `history-rewrite-force-push-destroys-cited-commits` per i dettagli
completi.

**Stato corrente (verificato di nuovo, non solo assunto)**: il file e'
stato riportato allo stato corretto (HasXotTable rimosso, `table()` con i
5 setter nativi + ignore per-riga corretti, non dentro un blocco di
commento), `phpstan analyse` repo-wide (nessun path, l'unico comando che
certifica) → 0 errori, `XotBaseManageRelatedRecordsRegressionTest` → 6/6
verdi. Commit HEAD attuale del modulo Xot: vedi `git log -1` al momento
della lettura, non uno SHA fissato qui (per non ripetere lo stesso errore
di fiducia in uno SHA in un ambiente dove viene dimostrabilmente
distrutto).
