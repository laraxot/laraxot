---
name: coverage
description: PHPStan/PHPMD/Pest quality-gate snapshot per il modulo Quotation, storia 2.8 (constantTypeCoverage)
metadata:
  module: Quotation
  story: 2.8.phpstan-constant-type-coverage
  updated: 2026-09-07
---

# Coverage — Modulo Quotation

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
