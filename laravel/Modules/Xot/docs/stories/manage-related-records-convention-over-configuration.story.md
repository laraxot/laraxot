---
title: "Xot: XotBaseManageRelatedRecords — getFormSchema()/getTableColumns() risolti per convenzione (implementato)"
type: story
module: Xot
epic: null
story_id: null
slug: manage-related-records-convention-over-configuration
status: superseded
cold_gate: passed
created: 2026-09-11
updated: 2026-09-11
status_note: "Duplicato dello stesso github_issue/discussion (#115/#117) e owned_scope della story canonica xotbasemanagerelatedrecords-convention-over-configuration.story.md, che ha continuato ad evolvere (settima direzione) ben oltre questa 'done' (getFormSchema()/getTableColumns() per convenzione, mai la delega totale poi decisa). Marcato superseded per non fuorviare chi legge questo slug prima dell'altro."
repository: "https://github.com/laraxot/module_xot_fila5"
github_issue: "https://github.com/laraxot/module_xot_fila5/issues/115"
github_discussion: "https://github.com/laraxot/module_xot_fila5/discussions/117"
estimated_effort: "analisi 3-4h + implementazione conservativa 1-2h"
blocked_by: []
blocks: []
supersedes: []
owned_scope:
  - laravel/Modules/Xot/app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php
  - laravel/Modules/Xot/app/Actions/Filament/GetRelatedResourceClassAction.php
  - laravel/Modules/Quaeris/app/Filament/Resources/PdfStyleResource.php
  - laravel/Modules/Quaeris/app/Filament/Resources/SurveyPdfResource/Pages/ManageContacts.php
  - laravel/Modules/Quaeris/app/Filament/Resources/SurveyPdfResource/Pages/ManageNotifyThemes.php
  - laravel/Modules/Quaeris/app/Filament/Resources/SurveyPdfResource/Pages/ManageCharts.php
  - laravel/Modules/Quaeris/app/Filament/Resources/SurveyPdfResource/Pages/ManagePdfStyle.php
  - laravel/Modules/Quaeris/app/Filament/Resources/SurveyPdfResource/Pages/ManageMailTemplates.php
  - laravel/Modules/Quaeris/app/Filament/Resources/SurveyPdfResource/Pages/ManageQuestionCharts.php
related:
  - Modules/Xot/docs/stories/_TEMPLATE.story.md
---

# Story: XotBaseManageRelatedRecords — convenzione implementata (direzione conservativa)

## Story

Come manutentore Xot, voglio capire se `XotBaseManageRelatedRecords` puo'
ereditare la stessa filosofia convention-over-configuration di
`XotBaseResource` (che deriva `{Model}Form`/`{Model}sTable` per convenzione
via `getFormClass()`/`getTableClass()`), cosi' che pagine come
`ManageContacts` smettano di ripetere a mano
`app(ContactForm::class)->getFormSchema()` /
`app(ContactsTable::class)->getTableColumns()`.

**Nota di stato (aggiornata)**: la ricognizione (sotto) e' stata analisi
pura per tutta la sessione, su richiesta esplicita e ripetuta dell'utente.
La direzione **conservativa** (solo `getFormSchema()`/`getTableColumns()`,
mai `form()`/`table()` per intero — vedi Dependency Maps) e' stata poi
implementata e verificata; vedi Dev Agent Record in fondo.

## Baseline

`grep -rl "extends XotBaseManageRelatedRecords" Modules --include="*.php"`
→ 9 classi (8 applicative + 1 fixture di test). Nessuna segue oggi la
convenzione: ognuna ripete a mano la delega, dove presente, o eredita un
default vuoto (`ManagePdfStyle`/`ManageCharts` mostrano oggi tabella
vuota, bug esistente scoperto durante questa ricognizione).

## Acceptance Criteria

<!-- LOCKED. External dev tools must not edit Acceptance Criteria. -->

1. Le 8 pagine reali censite una per una contro model/migration/Resource,
   non genericamente.
2. Ogni claim quantificabile verificato dal vivo (tinker/vendor), non
   ipotizzato — richiesto esplicitamente dall'utente ("percentuali").
3. Causa tecnica del gap identificata con riferimento a riga di codice
   precisa (`HasXotForm::form()`/`HasXotTable::table()` vs
   `InteractsWithRelationshipTable` nativo Filament).
4. Nessun file applicativo modificato come conseguenza di questa story
   (eccetto la rimozione di un `dddx()` di debug trovato per errore nel
   file reale, non correlato alle proposte — fix ovvio, non una scelta di
   design).
5. Issue/discussion GitHub reali create per il tracking, non solo
   placeholder generici nel frontmatter.
6. `getFormSchema()`/`getTableColumns()` risolvono la Resource correlata
   tramite `GetRelatedResourceClassAction` (mai `$this->getResource()`,
   mai `Filament::getModelResource()`), con fallback `[]` invariato per le
   pagine cross-modulo.
7. Nessuna pagina con override esistente cambia comportamento (verificato,
   non solo dichiarato): `ManageContacts` deve continuare a mostrare i
   suoi 13 campi tabella/23 campi form propri.
8. Le pagine senza override (`ManagePdfStyle`, `ManageCharts`) devono
   mostrare contenuto reale invece di form/tabella vuoti.

## Tasks / Subtasks

<!-- LOCKED. External dev tools must not edit Tasks / Subtasks. -->

- [x] Leggere per intero `XotBaseResource.php` e descriverne la
      convenzione con riferimenti a riga. (AC: #3)
- [x] Confrontare con `filamentphp/demo` via `gh api` (dati reali, non a
      memoria). (AC: #2)
- [x] Censire le 8 pagine reali contro model/migration/Resource. (AC: #1)
- [x] Verificare dal vivo (tinker, pannello `quaeris::admin`) il confine
      pannello/modulo di `Filament::getModelResource()`. (AC: #2)
- [x] Tracciare riga per riga il vendor Filament
      (`makeTable()` → `configureTable()` → `Resource::table()`) per capire
      perche' `$relatedResource` gia' impostato (`ManageQuestionCharts`)
      non produce beneficio oggi. (AC: #3)
- [x] Trovare e correggere il `dddx()` di debug nel file reale. (AC: #4)
- [x] Aprire issue+discussion reali su `module_xot_fila5` (con controllo
      duplicati contro sessioni parallele). (AC: #5)
- [x] Implementare `GetRelatedResourceClassAction` ($relatedResource
      esplicito, poi convenzione per nome sul model della relazione,
      `class_exists()`/`is_subclass_of()`, mai `Filament::getModelResource()`).
      (AC: #6)
- [x] Wire `getFormSchema()`/`getTableColumns()` su
      `XotBaseManageRelatedRecords` a usare l'Action, con `Assert::isInstanceOf`
      a guardia del tipo risolto. (AC: #6)
- [x] Verificare via reflection (record iniettato, nessuna sessione HTTP
      necessaria) che `ManageContacts` non cambia comportamento. (AC: #7)
- [x] Verificare via reflection che `ManagePdfStyle`/`ManageCharts` ora
      risolvono contenuto reale. (AC: #8)
- [x] PHPStan repo-wide pulito dopo l'implementazione (0 errori). (AC: #6)

## Dev Notes

<!-- LOCKED. External dev tools must not edit Dev Notes. -->

- [Source: Modules/Xot/app/Filament/Resources/XotBaseResource.php:155-209]
  `getFormClass()`/`getTableClass()` + `form()`/`table()` finali/quasi-finali
  sono il pattern da estendere, non da reinventare.
- [Source: vendor/filament/filament/src/Resources/Concerns/InteractsWithRelationshipTable.php:184-189]
  `makeTable()` chiama gia' `$relatedResource::configureTable($table)` —
  ma `HasXotTable::table()`, eseguito dopo dalla pagina, sovrascrive
  incondizionatamente columns/headerActions/filters: per questo
  `ManageQuestionCharts` (che ha gia' `$relatedResource` impostato) mostra
  solo 1 colonna stub invece delle 10 reali.
- [Source: Modules/Quaeris/app/Filament/Resources/SurveyPdfResource/Pages/ManageMailTemplates.php,
  commento "Difetto 5"] Impostare `$relatedResource` su una Resource di un
  altro pannello rompe le azioni Create/Edit (URL costruito col pannello
  corrente) — vincolo reale gia' incontrato in produzione, non teorico.
- [Inference] `$this->getResource()` su una pagina `ManageRelatedRecords`
  ritorna sempre la Resource PROPRIETARIA (es. `SurveyPdfResource`), mai
  quella della relazione — confermato anche da `HasXotTable::getTableHeaderActions()`,
  che lo chiama solo dentro `if ($this instanceof ListRecords)`.

## Testing

<!-- LOCKED. External dev tools must not edit Testing. -->

```
cd laravel
php -l Modules/Xot/app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php
php -l Modules/Xot/app/Actions/Filament/GetRelatedResourceClassAction.php
./vendor/bin/phpstan analyse --no-progress --memory-limit=-1
# [OK] No errors (repo-wide)
./vendor/bin/pest Modules/Xot/tests/Unit/XotRelationManageStatesCoverageTest.php --no-coverage
# 2 passed (7 assertions)
```

Verifica funzionale via reflection (nessuna sessione HTTP necessaria,
`$record` iniettato direttamente su un'istanza di ogni pagina):

| Pagina | `getFormSchema()` | `getTableColumns()` |
|---|---|---|
| `ManagePdfStyle` (nessun override proprio) | 10 campi (`color`, `bg_color`, `font_family`, ...) — **prima: non testato/vuoto** | 7 colonne — **prima: `[]`, bug** |
| `ManageCharts` (nessun override proprio) | 6 campi (`type`, `color`, `width`, ...) — **prima: vuoto** | 9 colonne — **prima: `[]`, bug** |
| `ManageContacts` (override proprio, invariato) | 23 campi (`first_name`, ..., `sms_count`) — **identico a prima** | 13 colonne (`person`, `info_cell`, `email_cell`, ...) — **identico a prima** |

## Dependency Maps

Due proposte di design non riconciliate, entrambe derivate da questa
ricognizione:
- Conservativa: `Modules/Xot/docs/architecture/xotbasemanagerelatedrecords-form-table-delegation-feasibility.md`.
- Delega nativa completa: `Modules/Xot/docs/app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php.md`.

Nessuna story di sviluppo puo' partire finche' non si sceglie tra le due
(vedi discussion collegata).

## Owned File/Module Scope

Nessuno (story di sola analisi). File di documentazione toccati elencati
in `Modules/Xot/docs/index.md`, sezione "Discussioni architetturali —
2026-09-11".

## Learnings from Previous Stories

- [Source: memoria xotbaseresourcetable-orphaned-columns-git-archaeology]
  Stessa famiglia di regressione (colonne/schema perse in un refactor
  precedente, mai migrate) gia' vista in `ContactsTable.php` — qui si
  ripete per `ManageQuestionCharts::getTableColumns()` (1 colonna invece
  di 10).
- [Source: memoria multi-agent-same-repo-race] Piu' sessioni Claude Code
  hanno lavorato sullo stesso argomento in parallelo lo stesso giorno,
  producendo doc duplicati e — scoperto durante il tracking GitHub — anche
  issue/discussion duplicate (#113/#116, chiuse a favore di #115/#117
  gia' aperte da una sessione peer con censimento piu' completo).

## Dev Agent Record

### Agent Model Used

Claude Sonnet 5

### Implementazione

Fase di analisi (questa sessione): rimosso un `dddx()` di debug da
`XotBaseManageRelatedRecords.php` (litter di un'altra sessione, non
correlato alle proposte).

Fase di implementazione (sessione peer, verificata da questa sessione
invece di essere riscritta da capo — vedi [[multi-agent-same-repo-race]]):

- `Modules/Xot/app/Actions/Filament/GetRelatedResourceClassAction.php`
  (nuovo): `$relatedResource` esplicito se dichiarato, altrimenti
  convenzione per nome sul model della relazione via `getModelClass()`
  (mai `$this->getResource()`, mai `Filament::getModelResource()`).
- `XotBaseManageRelatedRecords::getFormSchema()`/`getTableColumns()`:
  default non piu' vuoto, risolvono tramite l'Action con
  `Assert::isInstanceOf` a guardia (`XotBaseResourceForm`/
  `XotBaseResourceTable`). `getRelatedResourceClass()` come thin wrapper
  di istanza sull'Action (nome `get*`, non `resolve*` — vedi
  [[feedback-no-resolve-prefix-check-sibling-naming]]).
- `//use HasRelationshipModelClass;` lasciato commentato: la stessa
  risoluzione vive gia' in `HasXotTable::getModelClass()`, riattivarlo
  duplicherebbe.

### Evidenze

- `gh api repos/filamentphp/demo/...` — confronto reale con l'ufficiale
  (fase di analisi).
- `Filament::getModelResource()` testato dal vivo in tinker per 4 model
  dentro il pannello `quaeris::admin` (fase di analisi).
- PHPStan repo-wide: **0 errori** dopo l'implementazione (nessuna
  regressione, incluso un case-collision non correlato in
  `Modules/UI/app/Filament/Tables/Columns/{Id,ID}Column.php` trovato
  durante questa verifica e gia' risolto da un'altra sessione — rinominato
  in `SortableIdColumn`, vedi story
  `Modules/UI/docs/stories/id-timestamp-columns-extraction.story.md`).
- `XotRelationManageStatesCoverageTest.php`: 2 passed, 7 assertions.
- Verifica via reflection (tabella sopra): `ManagePdfStyle`/`ManageCharts`
  ora popolate, `ManageContacts` invariata bit-per-bit.

### Incidente in produzione e correzione (post-implementazione)

Dopo il primo "done", un'altra sessione ha brevemente riscritto la classe
per delegare `form()`/`table()` per intero (rimuovendo `HasXotForm`/
`HasXotTable`) — la direzione "ambiziosa" gia' scartata in questa story
(vedi Dependency Maps). In produzione ha causato
`TypeError: Class name must be a valid object or a string` su OGNI pagina
senza `$relatedResource` esplicito, perche' rimuovere `HasXotTable` toglie
anche `getModelClass()`, da cui dipende `GetRelatedResourceClassAction`:
senza guardia null prima di `$class::configure()`, crash certo. Segnalato
dall'utente con lo stack trace reale (`XotBaseManageRelatedRecords.php:75`).
Ripristinata la versione conservativa (questa sessione, verificata di
nuovo: reflection su `ManagePdfStyle`/`ManageCharts`/`ManageContacts`,
stessi numeri di prima).

Il replay HTTP live (non solo reflection) con un cookie di sessione reale
ha poi scoperto un SECONDO problema, pre-esistente e non correlato:
`ManagePdfStyle` → 500, `LogicException: The model
[Modules\Quaeris\Models\PdfStyle] does not have a relationship named
[customer]`. Causa: `PdfStyleResource extends XotBaseResource` eredita lo
scoping tenant automatico di Filament (`BelongsToTenant`), che si aspetta
una relazione `customer()` sul model — `PdfStyle` non ne ha mai avuta una
(e' uno stile 1:1 di un `SurveyPdf`, mai listato per tenant
indipendentemente, `shouldRegisterNavigation = false`). Il bug esisteva
gia' prima di questa story (sarebbe scattato anche su
`PdfStyleResource\Pages\ListPdfStyles` se mai visitata) ma restava latente
perche' nessuno aveva mai fatto eseguire quella query. Corretto aggiungendo
`protected static bool $isScopedToTenant = false;` su `PdfStyleResource`
(PdfStyle e' sempre raggiunto tramite la relazione del SurveyPdf
proprietario, gia' tenant-scoped — nessun doppio scoping necessario).
Verificato dal vivo: `contacts`/`charts`/`pdf-style` tutti 200 dopo il fix,
PHPStan repo-wide 0 errori.

### Terzo tentativo — delega nativa completa, richiesta esplicita e ripetuta dall'utente, questa volta con migrazione completa

Dopo i 2 incidenti sopra, l'utente ha chiesto esplicitamente (due messaggi
di fila, il secondo indicando riga per riga `use HasXotForm;`/
`use HasXotTable;` da togliere) di procedere comunque con la direzione
"delega nativa completa". Differenza rispetto ai 2 tentativi falliti:
questa volta migrate anche le 5 pagine consumer con azioni/filtri/query
custom, non solo la classe base — causa esatta dell'incidente 2.

**Classe base** (`XotBaseManageRelatedRecords.php`, gia' riscritta da una
sessione peer con la correzione giusta, verificata qui): `HasXotForm`/
`HasXotTable` rimossi; `form()`/`table()` delegano a
`getRelatedResourceClass()` (non piu' nullable: `Assert::notNull`, fallisce
con messaggio leggibile invece di `TypeError` — fix dell'incidente 1);
`getModelClass()` ridichiarato direttamente (prima viveva solo in
`HasXotTable`, causa meccanica dell'incidente 1); nuovo hook
`configureRelatedTable(Table $table): Table` per le differenze di pagina,
applicato DOPO la configurazione nativa della Resource.

**5 pagine migrate** (questa sessione), ognuna da
`getTableHeaderActions()`/`getTableActions()`/`getTableQuery()` (non piu'
chiamati da nessuno) a `configureRelatedTable()`, con `array_merge($table->getHeaderActions(), [...])`/
`array_merge($table->getRecordActions(), [...])` per AGGIUNGERE senza
sostituire quanto la Resource correlata ha gia' impostato:

- `ManageContacts`: `getFormSchema()` rimosso (ridondante, `ContactResource`
  risolto per convenzione); `configureRelatedTable()` con Associate+Import
  (survey_pdf_id dell'owner).
- `ManageNotifyThemes`: `getFormSchema()` rimosso (ridondante, cross-modulo
  ma risolvibile per nome); filtri+create migrati.
- `ManageCharts`: dissociate/force_delete/restore migrati con merge sulle
  recordActions gia' presenti (view/edit/delete da `ChartResource::table()`);
  `shouldShowAssociateAction()` (hook morto, `HasXotTable` non piu'
  composto) sostituito da un'azione esplicita in header.
- `ManageMailTemplates`: create/edit con schema+mutate custom (slug
  calcolato dall'owner, appiattimento traduzioni) sostituiscono le stesse
  chiavi nell'array unito, non l'intera configurazione; query filtrata per
  slug via `modifyQueryUsing()` (additivo, non sostituisce la query di
  relazione). Il commento "Difetto 5" (mai impostare `$relatedResource`
  nativo per una Resource di un altro pannello) resta valido e rispettato:
  `getRelatedResourceClass()` non tocca mai quella property nativa.
- `ManageQuestionCharts`: `getFormSchema()`/`getTableColumns()` rimossi
  (con `$relatedResource` gia' esplicito sul nidificato, la base li risolve
  da sola — la colonna stub 'name' sparisce, tornano le 10 colonne reali);
  azioni chart/bundle_view/export migrate con merge.

**Bug PHPStan trovati e corretti durante la migrazione** (non presenti
nella versione peer iniziale): `disableCreateAnother()` deprecato →
`createAnother(false)`; `$this->getOwnerRecord()` tipizzato `Model`
generico, narrowing mancante per i 4 usi di `SurveyPdf` in
`ManageQuestionCharts` → aggiunto `Assert::isInstanceOf`.

**Verificato dal vivo (replay HTTP reale, tutte e 6 le pagine, non solo
reflection)**: `contacts`/`pdf-style`/`charts`/`notify-themes`/
`mail-templates`/`question-charts` → **tutti 200**, nessuna stringa di
errore nel body. Confermato nel contenuto reale: `question-charts` mostra
le colonne vere (`chart_type`, `generated_at`, `pos`, `subquestion` — non
piu' lo stub), `contacts` mostra l'azione Import, `mail-templates` filtra
per `survey-pdf-5`. PHPStan repo-wide: 0 errori. Test esistente
`XotRelationManageStatesCoverageTest.php`: 2 passed.

### Disposizione

Direzione "delega nativa completa" implementata e migrata per intero
(classe base + 5 pagine consumer), verificata dal vivo su tutte le 6
pagine reali. Sostituisce la direzione conservativa precedente (righe
sopra), che resta nella storia della story per il confronto. La direzione
"conservativa" nel documento
`Modules/Xot/docs/app/Filament/Resources/Pages/XotBaseManageRelatedRecords.php.md`
e' ora quella storicamente scartata; il documento e' stato aggiornato di
conseguenza.

### Nota 2026-09-11 (sessione successiva) — continua altrove, non qui

Questa story e una gemella quasi omonima
(`xotbasemanagerelatedrecords-convention-over-configuration.story.md`,
stessa cartella, biforcata da una sessione parallela lo stesso giorno)
raccontano la stessa cronologia. La "quarta direzione" (Template Method:
`getTableColumns()`+`parent::getTableColumns()`, niente
`configureRelatedTable()`, `getTableHeaderActions()` in `ManageContacts`),
richiesta due volte dall'utente dopo questo "done", e la sua verifica piu'
recente (`configureRelatedTable()` oggi dead code nel file reale) sono
documentate SOLO nell'altra story, che e' quella con
`status: ready-for-dev` e va trattata come canonica per qualunque sviluppo
futuro su questa classe. Questa resta come cronaca del terzo tentativo,
non va piu' aggiornata per la quarta direzione.
