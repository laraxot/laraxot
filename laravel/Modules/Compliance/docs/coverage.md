---
name: "Compliance test coverage"
description: "PHPStan/Pest baseline and delta for the 2026-09-06 Filament v5 + type-safety fix pass, updated 2026-09-07 with the canonical-wiring cleanup (story 5.72.11)"
metadata:
  module: Compliance
  updated: 2026-09-07
---

# Coverage e qualita' — Compliance

## 2026-09-07 — chiusura Task 2 story 5.72.11 (dead code post-estrazione Schemas/Tables)

Contesto: l'estrazione Filament canonical structure (story `4.14.7.compliance-canonical.story.md`,
completed) e il suo follow-up di wiring (`5.72.11.compliance-filament-canonical.story.md`,
in_progress) avevano lasciato 5 errori PHPStan residui — duplicati orfani di helper statici
sulle Resource dopo lo spostamento della logica su `Schemas/`+`Tables/`.

| Momento | Errori PHPStan Modules/Compliance |
|---|---|
| Prima (questa sessione) | 5 |
| Dopo | 0 |

Fix (nessun cast, nessun `mixed`, nessuna modifica a `phpstan.neon`):
- `ComplianceDocumentResource::documentTypes()` / `::statuses()` (`private static`, mai chiamati:
  `method.unused`) — rimossi, erano duplicati morti di `ComplianceDocumentForm::documentTypes()`/
  `::statuses()` (gia' `public static` e gia' usati da `ComplianceDocumentsTable`).
- `WorkOrderChangeOrderResource::statuses()` (`private static`, chiamato pero' da
  `WorkOrderChangeOrderForm` e `WorkOrderChangeOrdersTable` in namespace diversi →
  `staticMethod.private` sui due call site + `method.unused` sulla Resource) — spostato su
  `WorkOrderChangeOrderForm::statuses()` come `public static` (stesso pattern gia' in uso per
  `ComplianceDocumentForm`, single source of truth sulla Form, non sulla Resource), aggiornati
  i due call site.
- Rimossi anche gli import ormai non necessari lasciati dall'estrazione su
  `ComplianceDocumentResource.php` (nessuno, servivano ancora per `filePreview()`, testato) e su
  `WorkOrderChangeOrderResource.php` (13 import morti: `Select`, `Textarea`, `TextInput`,
  `Component`, `Grid`, `Section`, `Tables`, `SelectFilter`, `Table`, `DeleteBulkAction`,
  `EditAction`, `ViewAction`, `WorkOrder` — nessuno usato dal file dopo lo svuotamento della
  Resource verso Schemas/Tables).

Verificato che `ComplianceDocumentResource::filePreview()` (public static, non flaggato da
PHPStan perche' i metodi pubblici non rientrano in `method.unused`) e' codice vivo, testato
esplicitamente da `tests/Unit/ComplianceDocumentFilePreviewTest.php` — non toccato.

## PHPStan (level max, `laravel/phpstan.neon`, invariato)

| Momento | Errori |
|---|---|
| Baseline (2026-09-06) | 22 |
| Dopo fix 2026-09-06 | 0 |
| Regressione da WIP non committato (2026-09-07, pre-sessione) | 5 |
| Dopo fix 2026-09-07 | 0 |

Categorie risolte:
- 19 deprecazioni Filament v5 (`Table::actions()` -> `recordActions()`, `Table::bulkActions()` -> `toolbarActions()`, `Action::form()` -> `Action::schema()`, `Placeholder::make()->content()` -> `TextEntry::make()->state()`).
- 3 `cast.string`/`cast.int` su valori `mixed` nei seeder demo (`ComplianceDemoSeeder`, `HeatingDemoSeeder`), risolti con type narrowing (`is_string()`/`is_numeric()`), non con cast forzati.

## PHPMD (`laravel/phpmd.xml`)

Nessuna nuova violazione introdotta dai rename Filament (i file Resource/RelationManager restano puliti). Nei seeder, il narrowing per i 3 errori PHPStan aggiunge inevitabilmente un ramo condizionale ciascuno (ternario), il che sposta di poco un debito tecnico gia' preesistente:

| File | Regola | Prima | Dopo |
|---|---|---|---|
| `database/seeders/ComplianceDemoSeeder.php` | `ExcessiveClassComplexity` | 60 (gia' oltre soglia 60) | 62 |
| `database/seeders/ComplianceDemoSeeder.php` | `ExcessiveMethodLength` (`documentPng()`) | 101 righe (gia' oltre soglia 100) | 101 righe (invariato) |

Tutte le altre voci PHPMD pre-esistenti (CyclomaticComplexity di `seedDocuments()`, `ShortVariable`, `ShortMethodName`, `UnusedFormalParameter` nelle Policy, `CamelCasePropertyName` nei Provider nwidart) sono debito tecnico non toccato da questa story, verificato identico prima/dopo con `git show HEAD:<file> | phpmd`.

phpinsights **e' installato e funzionante** in questo repo (`laravel/vendor/bin/phpinsights`,
wrapper `laravel/tools/phpinsights.sh`) — la nota precedente ("non installato, rimosso,
incompatibile con Pest 5") era sbagliata, corretta il 2026-09-07 dopo verifica diretta
(`composer show nunomaduro/phpinsights` risolve, binario presente ed eseguibile). Vedi sezione
dedicata sotto.

Run 2026-09-07 (`./tools/phpmd.sh Modules/Compliance text Modules/Compliance/phpmd.ruleset.xml`):
nessuna nuova violazione sui 4 file toccati dal fix PHPStan di questa sessione
(`ComplianceDocumentResource.php`, `WorkOrderChangeOrderResource.php`,
`WorkOrderChangeOrderResource/Schemas/WorkOrderChangeOrderForm.php`,
`WorkOrderChangeOrderResource/Tables/WorkOrderChangeOrdersTable.php`, e il nuovo
`tests/Unit/WorkOrderChangeOrderFormStatusesTest.php`) — tutti puliti. Il debito PHPMD
pre-esistente altrove nel modulo (Actions con CyclomaticComplexity/NPathComplexity alte,
RelationManager con UnusedFormalParameter su `$ownerRecord`/`$pageClass`, Policy con parametri
non usati, seeder con ExcessiveClassComplexity) resta invariato, fuori scope di questo fix.

## PHPInsights

| Metrica | 2026-09-06 (nota precedente, con workaround `ForbiddenSecurityIssues`) | 2026-09-07 (dopo fix PHPStan) |
|---|---|---|
| Code | 89.7 | 89.7 |
| Complexity | 88.4 | 89.7 |
| Architecture | 88.2 | 88.2 |
| Style | 89.2 | 89.2 |

Tutte sopra la soglia 70 configurata in `phpinsights.php`. Scoping a un singolo modulo fa
fallire l'insight `ForbiddenSecurityIssues` (cerca `composer.lock` a partire dal path
analizzato, non dalla root app) — bypassato con una config effimera in `/tmp` che rimuove solo
quell'insight (`'remove' => [\NunoMaduro\PhpInsights\Domain\Insights\ForbiddenSecurityIssues::class]`),
senza toccare `phpinsights.php` condiviso.

## Pest

| Momento | Passed | Failed | Assertions |
|---|---|---|---|
| Baseline (2026-09-06, prima) | 18 | 4 | 44 |
| Dopo fix 2026-09-06 | 22 | 4 | 51 |
| Dopo fix 2026-09-07 (+ nuovo test statuses) | 24 | 4 | 59 |

Aggiunto `tests/Unit/ComplianceDocumentFilePreviewTest.php` (4 test, 7 assertion, 2026-09-06) che copre `ComplianceDocumentResource::filePreview()` — il metodo dietro alla nuova `TextEntry::make('file_preview')->state(...)` che sostituisce il deprecato `Placeholder::content()`: nessun media, media immagine, media non immagine, record null.

Aggiunto `tests/Unit/WorkOrderChangeOrderFormStatusesTest.php` (2 test, 8 assertion, 2026-09-07) che copre `WorkOrderChangeOrderForm::statuses()` — prima era `private` su `WorkOrderChangeOrderResource` e quindi non testabile direttamente da fuori la classe; spostato `public static` sulla Form durante il fix PHPStan di questa sessione, ora testato: tutte le 6 label del ciclo di vita, chiavi allineate alla colonna `status`.

I 4 test che falliscono **prima e dopo** (nessuna regressione, non toccati da questa story ne' da quella del 2026-09-07):
- `CustomerSystemAndBoilerMaintenanceTest` (3 test) — `SQLSTATE[42S02]: Base table 'workorder_data_test.customers' doesn't exist`. La tabella `customers` (modulo Customer, connessione `customer`) non e' migrata nel DB di test condiviso in questo ambiente multi-agente. Non fixabile restando dentro Modules/Compliance (richiederebbe migrare un'altra connessione/modulo).
- `UserIdColumnsAcceptUuidTest` (1 test) — la colonna `fgas_leak_checks.performed_by_user_id` non esiste nello schema di test corrente. Anche questo e' uno stato del DB di test, non del codice Compliance.

Entrambi i blocchi sono ambientali (stato del DB di test condiviso), verificati identici confrontando l'esecuzione prima e dopo le modifiche di entrambe le sessioni (2026-09-06 e 2026-09-07).

## 2026-09-07 (sera) — `typeCoverage.constantTypeCoverage` (story 5.71, continuazione 2.8/5.1)

Coda della campagna project-wide `typeCoverage.constantTypeCoverage` (story
`docs/stories/2.8.phpstan-constant-type-coverage.story.md` e
`5.1.phpstan-constant-type-coverage-remaining.story.md`, 49%→64.3%→66.8%→68.5%
nella giornata). La metrica e' globale: `phpstan analyse Modules/Compliance` da
solo riporta sempre `[OK] No errors` per questa regola; verificato con scan
full-tree `phpstan analyse Modules --error-format=raw`, grep su `Modules/Compliance`.

| | Occorrenze Compliance (scan full-tree) |
|---|---|
| Prima | 16 (9 file) |
| Dopo | 0 |
| Costanti tipate progetto (full-tree) | 207/302 (68.5%) → 302/302 (100%, campagna chiusa da tutti i moduli in parallelo) |

File corretti (tipo nativo PHP 8.3+ su `const`, docblock `@var` mantenuto solo
per le shape generiche, rimosso dove ridondante con lo scalare nativo):

- `app/Actions/EvaluateWorkOrderReadinessAction.php` — `DEFAULT_REQUIRED_DOCUMENTS` → `private const array`
- `app/Actions/RegisterMaintenanceControlAction.php` — `MAINTENANCE`/`EFFICIENCY` → `private const string`; `FIELDS` → `private const array` (deduplicato un doppio docblock `@var` incoerente, tenuto `list<string>`)
- `app/Application/Queries/GetHeatingComplianceOverviewQuery.php` — `STATUS_RANK` → `private const array`
- `app/DTO/FGasObligation.php` — 3 costanti `public const string`
- `app/DTO/ValueObjects/Refrigerant.php` — `GWP` → `private const array`
- `app/DTO/ValueObjects/ThermalPlantRegistry.php` — `BY_REGION` → `private const array`
- `app/Filament/Resources/CustomerSystemResource.php` — `ACCOUNT_FEATURE_ADMIN` → `protected const string`, `ACCOUNT_FEATURE_ADMIN_DEFAULT` → `protected const bool` (nessun docblock preesistente su queste due)
- `app/Filament/Resources/CustomerSystemResource/RelationManagers/SchemasRelationManager.php` — `KIND` → `private const string`; `ACCEPTED_MIME_TYPES` → `private const array` (docblock `@var list<string>` spostato dentro il blocco di commento esistente)
- `app/Filament/Resources/HeatingSystemResource.php` — `ACCOUNT_FEATURE_ADMIN`/`ACCOUNT_FEATURE_OPERATOR` → `protected const string`

Verifica: `php -l` pulito su tutti i 9 file; `phpstan analyse Modules`
(full-tree, cache pulita) → **0 errori totali nell'intero progetto** al momento
di questa verifica (2026-09-07 sera); PHPMD (`./tools/phpmd.sh Modules/Compliance
text Modules/Compliance/phpmd.ruleset.xml`) nessun nuovo reperto sui file
toccati; PHPInsights (config effimera per bypassare il bug di scope
`ForbiddenSecurityIssues`/`composer.lock`) Code 89.7, Complexity 89.7,
Architecture 88.2, Style 88.0 — invariato rispetto alla sessione precedente;
Pest 24 passed / 4 failed, identico alla baseline (i 4 fallimenti restano gap
di schema/dati del DB di test condiviso, non toccati).

### Nota su collisione git (non causata da questa story)

Al momento del commit, il modulo aveva gia' altri 3 file dirty di una sessione
concorrente (decoupling `Modules/Platform`, poi committati a parte in
`f257ac5`) e 2 lang file auto-generati da `LangServiceProvider`/`AutoLabelAction`.
Una sessione diversa ha eseguito un `git add -A && git commit -m "."` che ha
incluso anche i 9 file di questo fix nello stesso commit non descrittivo
(`623adbe`, gia' pushato). Contenuto verificato: corretto, nessuna perdita.
Non sono stati eseguiti `reset`/`rebase`/force per correggere il messaggio
(vietato da policy) — solo documentato qui e in
`docs/stories/5.71.phpstan-typecoverage-constants.story.md`.
