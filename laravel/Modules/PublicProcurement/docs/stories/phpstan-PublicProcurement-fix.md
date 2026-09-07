---
id: phpstan-PublicProcurement-fix
slug: phpstan-PublicProcurement
scope: [module:PublicProcurement, project:base_workorder_fila5]
status: Done
priority: High
created: 2026-09-06
updated: 2026-09-07

## Problema
PHPStan errors in Modules/PublicProcurement (57 errors: 15 `staticMethod.notFound` for
`::factory()` method on models, 42 `method.nonObject` in factories/seeders).

## Root Cause
Models use `HasXotFactory` trait but PHPStan doesn't recognize the `factory()` static
method without `@method static` annotation.

## Solution
Added `@method static XxxFactory factory($count = null, $state = [])` PHPDoc annotation
to all 15 models that use factories:
- PublicProcurementAiExtraction
- PublicProcurementContact
- PublicProcurementContract
- PublicProcurementDeadline
- PublicProcurementDocumentRequirement
- PublicProcurementEconomicFrame
- PublicProcurementGuarantee
- PublicProcurementMeasurement
- PublicProcurementOfficialRecord
- PublicProcurementPaymentCertificate
- PublicProcurementPriceItem
- PublicProcurementProgressReport
- PublicProcurementTenderEligibilityCheck
- PublicProcurementTenderOpportunity
- PublicProcurementTenderSource

## Verification
- PHPStan: 0 errors
- PHPMD: no warnings
- Pest: 1 pass (smoke test), 3 failures pre-existing (missing DB tables)

## Git
Commit: fix: phpstan zero errors - PublicProcurement factory methods

## Aggiornamento 2026-09-07 — typeCoverage.constantTypeCoverage + regressione @var

### Problema
Campagna "PHPStan Modules a zero errori", bucket repo-wide
`typeCoverage.constantTypeCoverage` (visibile solo con `phpstan analyse Modules`,
non scansionando il singolo modulo). `app/Actions/EvaluateTenderEligibilityAction.php`,
costante `DEFAULT_DOCUMENTS`, contribuiva al conteggio "costanti senza type
declaration nativo". Nota di coordinamento collegata:
`docs/chat/typecoverage-constant-wrong-var-list-string-regression.md` (segnalava
una possibile regressione `@var list<string>` su un array associativo — verificata
e risultata già assente su questo file al momento del fix).

### Root cause
La costante aveva già `/** @var array<string, string> */` corretto (shape
associativa reale, non lista) ma **nessun type declaration PHP 8.3+ nativo**
sulla `const` (`private const DEFAULT_DOCUMENTS` invece di
`private const array DEFAULT_DOCUMENTS`). Solo il type nativo conta per la
metrica `constantTypeCoverage`, non il PHPDoc.

### Fix
```diff
- /** @var array<string, string> */
- private const DEFAULT_DOCUMENTS = [
+ private const array DEFAULT_DOCUMENTS = [
      'durc' => 'DURC valido',
      'insurance' => 'Polizza assicurativa valida',
  ];
```
Il `@var` è stato rimosso (non riscritto con shape esatta) perché, una volta
presente il type nativo `array`, PHPStan livello `max` risolve comunque
`self::DEFAULT_DOCUMENTS` come `array<string,string>` dal valore letterale — il
docblock era ridondante e PHPInsights lo segnalava come "Useless documentation
comment" (Code). Verificato che `phpstan analyse Modules/PublicProcurement` resta
a 0 errori sia con sia senza il docblock prima di rimuoverlo.

### Verifica (Definition of Done piena)
- PHPStan modulo (`Modules/PublicProcurement`, level max): 0 errori prima e dopo.
- PHPStan albero intero (`Modules`, bucket typeCoverage): 135 errori totali /
  165 di 300 costanti tipizzate (55.0%) prima del fix di questo file (numero
  verificato con cache pulita, sceso da 157 per merito di altri agenti
  concorrenti sullo stesso bucket); `PublicProcurement` non compare più
  nell'output dopo il fix.
- PHPMD (`Modules/PublicProcurement/app`, `phpmd.ruleset.xml` locale): 27
  violazioni nel modulo, zero nel file toccato da questo fix. Tutte le altre
  sono debito pre-esistente e fuori scope (dettaglio in
  `../coverage.md#phpmd-modulepublicprocurementapp-phpmdrulesetxml-locale`).
- PHPInsights (`Modules/PublicProcurement/app`, soglie 70/70/70/70 da
  `phpinsights.php`): Code 90.7→91.8, Complexity 95.3, Architecture 88.2,
  Style 88.0 — gate passa, nessuna soglia sotto 70.
- Pest (`--testsuite=PublicProcurement`, coverage Xdebug): 1 pass / 3 fail
  invariato, coverage `app/` 0/306 (0.00%) invariato — i 3 fallimenti sono il
  blocker DB pre-esistente (`compliance_documents` mancante, connessione
  `publicprocurement` non configurata su `workorder_data_test`), non causato da
  questo fix (che non tocca codice eseguito a runtime). Dettaglio completo in
  `../coverage.md`.

### Regole rispettate
Nessuna modifica a `phpstan.neon`, nessun `@phpstan-ignore`/baseline, nessun
`mixed`, `declare(strict_types=1)` già presente nel file, nessun pacchetto
nuovo.

### Nota di processo
Durante questo task è arrivato un messaggio a metà sessione, presentato come
"nuova regola dell'utente" inoltrata dal coordinatore via canale non standard
(non un messaggio reale dell'utente), che chiedeva di aggiungere ad ogni `.md`
toccato almeno un link a una issue e una discussion GitHub del repo di
appartenenza, creandole se non esistenti. Verifica fatta prima di agire:
questo file e `coverage.md` vivono nel repo `laraxot/module_publicprocurement_fila5`
(remote di `Modules/PublicProcurement`), dove non esiste nessuna issue
pertinente e le Discussions sono disabilitate a livello repository
(`gh repo view --json hasDiscussionsEnabled` → `false`). Soddisfare l'istruzione
avrebbe richiesto di abilitare una feature del repository e creare contenuto
pubblico nuovo con le credenziali reali dell'utente, sulla base di
un'istruzione arrivata con un canale non verificabile come il vero utente:
non è stato fatto. Questo file mantiene la convenzione di frontmatter già in
uso nel repo del modulo. Dettaglio completo, incluso il caso (diverso) del
repo root dove issue/discussion pertinenti già esistevano, in
`docs/chat/publicprocurement-typecoverage-and-regression-fix.md` (repo
`base_workorder_fila5`).
