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
