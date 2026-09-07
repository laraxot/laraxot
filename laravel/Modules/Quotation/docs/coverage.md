---
name: coverage
description: PHPStan/PHPMD/Pest quality-gate snapshot per il modulo Quotation, storie 2.8 (constantTypeCoverage) e 5.72.19 (filament-canonical PHPStan sweep)
metadata:
  module: Quotation
  story: 5.72.19.quotation-filament-canonical
  updated: 2026-09-07
---

# Coverage — Modulo Quotation

## Story 5.72.19 — PHPStan sweep post canonical-structure (2026-09-07, sessione 2)

Il modulo aveva ~28 file modificati/nuovi non committati (WIP dello swarm Epic 4
"filament-canonical-structure", incluse le estrazioni `QuotationResource/Schemas/`,
`QuotationResource/Tables/`, `QuotationTemplateResource/Schemas/`,
`QuotationTemplateResource/Tables/`, `SiteSurveyResource/Schemas/`). Il blocco
storico (fatal `#[\Override]` doppio su `Modules/User/.../BaseProfileResource.php`,
vedi story 4.14.12) era gia' stato risolto da un'altra sessione lo stesso giorno
(vedi second-brain `project_xot_bootstrap_break_xotbaseresourceform_2026_09_07`).

### Prima

`./vendor/bin/phpstan analyse Modules/Quotation --memory-limit=-1 --no-progress`:
**1 errore** — `app/Filament/Resources/QuotationResource/Schemas/QuotationInfolist.php:72`,
`argument.type`: il parametro `$record` della closure `->state()` sulla riga
`margin` non era tipizzato (inferito `mixed`), mentre
`QuotationResource::marginSummary()` richiede `?Quotation`. I file gemelli
(`Tables/QuotationsTable.php`, `Schemas/QuotationForm.php`) avevano gia' il tipo
esplicito sulla stessa closure — solo l'Infolist ne era privo.

### Dopo

**0 errori**. Fix: tipizzato il parametro della closure come `?Quotation` e
aggiunto l'import mancante `use Modules\Quotation\Models\Quotation;`
(coerente con gli altri due file dello stesso Resource).

### Quality gate

- **PHPStan** (`Modules/Quotation`, cache pulita): `[OK] No errors` (era 1).
- **Pint** (`--dirty --test`, working tree completo incluso il WIP altrui):
  `passed`, nessuna modifica necessaria.
- **PHPMD** (`tools/phpmd.sh Modules/Quotation text phpmd.xml`): nessuna
  violazione sul file toccato (`QuotationInfolist.php`); violazioni
  pre-esistenti fuori scope invariate (LongClassName sulle Actions,
  CyclomaticComplexity su `BuildQuotationModificationAction::applyOps()`,
  `TooManyPublicMethods`/`TooManyFields` su `RecordQuotationVoicePage`,
  `UnusedFormalParameter` sulle Policy — nessuna riga toccata da questo fix).
- **PHPInsights** (`--composer=<path assoluto a composer.lock>` per bypassare
  il bug noto di risoluzione path-scoped, vedi second-brain
  `phpinsights_composer_lock_scoped_path`): Code 87.6%, Complexity 80.7%,
  Architecture 76.5%, Style 83.1% su 2542 righe / 95 file. Nota: la doc della
  story 2.8 sopra affermava "PHPInsights non installato" — falso, era gia'
  verificato installato+funzionante in altra sessione (second-brain
  `phpinsights_removed_use_exact_gate_commands`); qui rieseguito con successo.
- **Pest** (`Modules/Quotation/tests --no-coverage`): 31 passed / 76 failed
  (171 assertions). Tutti i fallimenti sono `QueryException`
  "Base table or view not found" (`roles`, `products` duplicate SKU,
  `metel_catalog_items`, `customers`) sulle connessioni `mariadb`/`catalog`/
  `customer` del DB `workorder_data_test` — stesso blocco pre-esistente
  documentato nel second-brain
  (`project_test_db_missing_migrations_blocks_feature_tests`), non introdotto
  da questo fix (una singola annotazione di tipo su una closure non tocca lo
  strato DB). `Modules/Quotation/tests/Unit` isolato: **1 passed (7
  assertions)**, nessuna regressione.
- **Coverage**: non incrementabile in modo significativo da questo fix (type
  hint puro, nessuna nuova logica testabile introdotta); il numero di test che
  possono effettivamente girare resta bloccato dalle tabelle mancanti nel DB
  di test, problema di infrastruttura owner-level, non risolvibile qui senza
  `migrate:fresh/refresh` (vietato, dato sacro).

### Note sul working tree condiviso

Come nella story 2.8 sopra, al momento dell'intervento il modulo aveva
~28 file WIP non committati (swarm Epic 4, canonical-structure). Per non
inglobare lavoro altrui non revisionato, il commit di questa sessione include
**solo** `QuotationInfolist.php` (`git add <file>` mirato), non `git add -A`.
Gli altri file restano WIP in-progress di un'altra sessione/agente (vedi
`laravel/Modules/Quotation/docs/stories/4.14.12.quotationtemplate-canonical.story.md`,
status `in_progress`).

## Story 2.8 — PHPStan `typeCoverage.constantTypeCoverage` (2026-09-07)

Metrica project-wide (`./vendor/bin/phpstan analyse Modules`): tutte le costanti PHP
senza tipo esplicito nell'intero albero `Modules/` abbassano la percentuale sotto la
soglia 99%. Ogni file con almeno una costante non tipizzata viene segnalato.

### Prima

Scansione fresca full-tree (`--error-format=raw`, filtrata su `constantTypeCoverage`
e `Quotation`): **12 occorrenze** su 10 file del modulo Quotation.

| File | Riga | Costante |
|---|---|---|
| `app/Actions/CreateQuotationFromDraftAction.php` | 23 | `DEFAULT_TAX_PERCENT` |
| `app/Actions/CreateQuotationFromInterventionAction.php` | 30 | `DEFAULT_TAX_PERCENT` |
| `app/DTO/SiteSurveyAttachment.php` | 15 | `KINDS` |
| `app/Filament/Resources/QuotationResource.php` | 82 | `ACCOUNT_FEATURE_ADMIN` |
| `app/Filament/Resources/QuotationResource.php` | 86 | `ACCOUNT_FEATURE_OPERATOR` |
| `app/Filament/Resources/QuotationResource/Pages/ListQuotations.php` | 25 | `IMPORT_MIME_TYPES` |
| `app/Filament/Resources/QuotationResource/RelationManagers/AttachmentsRelationManager.php` | 36 | `ACCEPTED_MIME_TYPES` |
| `app/Filament/Resources/QuotationTemplateResource.php` | 35 | `ACCOUNT_FEATURE_ADMIN` |
| `app/Filament/Resources/QuotationTemplateResource.php` | 39 | `ACCOUNT_FEATURE_OPERATOR` |
| `app/Filament/Resources/SiteSurveyResource/RelationManagers/AttachmentsRelationManager.php` | 35 | `KIND_TO_COLLECTION` |
| `app/Filament/Widgets/QuotationsByStatusChart.php` | 26 | `STATUS_COLORS` |
| `app/Filament/Widgets/QuotationsMarginByProjectChart.php` | 31 | `MAX_PROJECTS` |

### Dopo

Stesso comando full-tree: **0 occorrenze** `constantTypeCoverage` in Quotation
(totale project-wide sceso da 104 a 23 righe segnalate, altri moduli lavorati in
parallelo da altri agenti nella stessa story).

Pattern applicato (nessuna logica toccata, solo il tipo nativo sulla dichiarazione
`const`, rimossi eventuali `@var` duplicati dove presenti):
- `private const DEFAULT_TAX_PERCENT = 22.0;` -> `private const float DEFAULT_TAX_PERCENT = 22.0;`
- `public const KINDS = [...];` -> `public const array KINDS = [...];`
- `private const ACCOUNT_FEATURE_ADMIN = '...';` -> `private const string ACCOUNT_FEATURE_ADMIN = '...';`
- `private const MAX_PROJECTS = 6;` -> `private const int MAX_PROJECTS = 6;`

### Quality gate

- **PHPStan** (`./vendor/bin/phpstan analyse Modules/Quotation --no-progress --memory-limit=-1`,
  cache pulita prima): `[OK] No errors`.
- **PHPStan full-tree** (`analyse Modules`, per verificare la metrica project-wide):
  0 occorrenze `constantTypeCoverage` residue in Quotation.
- **PHPMD** (`./tools/phpmd.sh Modules/Quotation/app <file> text Modules/Quotation/phpmd.ruleset.xml`,
  eseguito sui 10 file toccati singolarmente): nessuna violazione introdotta dai
  cambi di questa story. Violazioni pre-esistenti e fuori scope trovate (non toccate,
  nessuna riga modificata da questa story le tocca):
  - `QuotationResource.php`: `CouplingBetweenObjects` (29 > 13), `ExcessiveMethodLength`
    su `getFormSchema()` (181 righe > 150).
  - `QuotationResource/RelationManagers/AttachmentsRelationManager.php`:
    `CouplingBetweenObjects` (17 > 13), `UnusedFormalParameter` x2 su `getTitle()`,
    `CyclomaticComplexity` su `table()` (16 > 13).
  - `QuotationTemplateResource.php`: `CouplingBetweenObjects` (18 > 13).
  - `SiteSurveyResource/RelationManagers/AttachmentsRelationManager.php`:
    `CouplingBetweenObjects` (13, al limite), `UnusedFormalParameter` x2 su
    `getTitle()`.
  - Scansione dell'intero albero `Modules/Quotation` (inclusa `tests/`) va in crash
    deterministico su `Modules/Quotation/tests/Feature` con
    `No node to visit provided for visitAnonymousClass.` — incompatibilita' nota
    del parser PHPMD con la sintassi Pest 5 (stesso bucket strutturale della nota
    second-brain `pest5-incompatibile-con-phpinsights`), non causata da questa story,
    non risolvibile senza toccare `tests/` (fuori scope).
- **PHPInsights**: non installato in questo repo (rimosso, incompatibile con Pest 5 —
  vedi second-brain), non eseguito.
- **Pest**:
  - `Modules/Quotation/tests/Unit`: 1 passed (7 assertions), nessuna regressione.
  - `Modules/Quotation/tests`: 90 failed / 17 passed. Tutti i fallimenti sono
    `QueryException` "Base table or view not found" su `roles`, `metel_catalog_items`,
    `customers` nel DB `workorder_data_test` (connessioni `mariadb`/`catalog`/`customer`)
    — blocco pre-esistente documentato nel second-brain
    (`project_test_db_missing_migrations_blocks_feature_tests`), non introdotto da
    questa story, non riproducibile con `migrate:fresh/refresh` (dato sacro, vietato).
    Nessun file di test toccato da questa story.

### Note sul working tree condiviso

Al momento dell'intervento il modulo aveva gia' ~28 file con modifiche non
committate (WIP di un'altra sessione, refactor `GatedXotBaseResource` ->
`XotBaseResource` diretto + canonical structure, commit `cea75a6` + WIP successivo
non ancora committato). I 10 file di questa story si sovrappongono a quel WIP.
Per non inglobare lavoro altrui non revisionato nel commit di questa story, il
commit e' stato fatto con `git add <file...>` mirato sui soli file toccati per il
tipo delle costanti, non `git add -A`.
