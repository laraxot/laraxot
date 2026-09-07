---
title: "Coverage — PublicProcurement"
type: concept
tags: [publicprocurement, testing, phpstan, coverage]
created: 2026-09-06
updated: 2026-09-07
qmd: "PublicProcurement coverage pest phpstan"
related:
  - "./README.md"
  - "./factories-seeders-status.md"
  - "./stories/phpstan-PublicProcurement-fix.md"
---

# Coverage — PublicProcurement

## Aggiornamento 2026-09-07 — typeCoverage.constantTypeCoverage + regressione @var

Campagna "PHPStan Modules a zero errori", bucket `typeCoverage.constantTypeCoverage`
(percentuale REPO-WIDE su `Modules/**`, non visibile analizzando solo
`Modules/PublicProcurement`). File: `app/Actions/EvaluateTenderEligibilityAction.php`,
costante `DEFAULT_DOCUMENTS`.

Stato di partenza reale (verificato, non presunto): la costante aveva già
`/** @var array<string, string> */` (shape corretta, associativa) ma **nessun type
declaration nativo** sulla `const` stessa — quindi non contava per la metrica. La
regressione `@var list<string>` descritta nella nota di coordinamento
`docs/chat/typecoverage-constant-wrong-var-list-string-regression.md` risultava già
assente su questo file al momento della verifica (probabile fix di un altro agente
concorrente nello stesso arco temporale, o falso allarme preventivo).

Fix applicato:
```diff
- /** @var array<string, string> */
- private const DEFAULT_DOCUMENTS = [
+ private const array DEFAULT_DOCUMENTS = [
```
Il PHPDoc è stato rimosso (non aggiunto con shape `array{durc: string, insurance:
string}`) perché PHPInsights lo segnalava come "Useless documentation comment"
(Code/UselessConstantTypeHint): con il type nativo `array` già presente, PHPStan
livello `max` risolve comunque `self::DEFAULT_DOCUMENTS` come
`array<string,string>` letto dal valore literal, senza bisogno del docblock —
verificato con `phpstan analyse Modules/PublicProcurement` a 0 errori sia con
sia senza il docblock. Rimuoverlo elimina un warning senza perdere precisione.

### PHPStan — modulo (`Modules/PublicProcurement`, level max)

| Momento | Errori |
|---|---|
| Prima (oggi, prima del fix) | 0 (nessun errore locale: il bucket typeCoverage non è visibile scansionando solo il modulo) |
| Dopo | **0** |

### PHPStan — albero intero (`Modules`, bucket `typeCoverage.constantTypeCoverage`)

| Momento | Errori totali albero | Costanti tipizzate |
|---|---|---|
| Prima (rilevato in sessione, prima del fix su questo file) | 135 | 165/300 (55.0%) |
| Dopo | vedi story per il numero esatto post-fix | 166/300 atteso (+1 da questo fix; soglia richiesta 99%+, resta debito repo-wide su altri moduli) |

Nota: 135 (non 157) perché altri agenti concorrenti hanno già ridotto il bucket in
parallelo nello stesso arco di tempo — numero verificato con
`phpstan analyse Modules --no-progress --memory-limit=-1` fresco (cache pulita),
non preso per fede da documentazione precedente.

### PHPMD (`Modules/PublicProcurement/app`, `phpmd.ruleset.xml` locale)

27 violazioni totali nel modulo, **zero nel file toccato da questo fix**
(`EvaluateTenderEligibilityAction.php`). Tutte pre-esistenti e fuori scope:
`UnusedFormalParameter` su `$ownerRecord`/`$pageClass` nei RelationManager (firma
imposta da Filament, non rimuovibile senza rompere il contratto),
`CouplingBetweenObjects` su 3 Resource, `LongClassName` su 3 Action/Resource,
`NumberOfChildren` su `BaseModel` (15 figli), `MissingImport` su
`ApplyTenderOpportunityDraftAction.php`. Debito strutturale documentato, non
toccato per non collidere con modifiche in corso di altri agenti sullo stesso
albero (vedi `git status` con molti file Filament/RelationManager già in WIP al
momento di questo fix).

### PHPInsights (`Modules/PublicProcurement/app`, soglie da `phpinsights.php`: min 70 su tutte e 4)

| Metrica | Prima del fix @var-cleanup | Dopo | Soglia |
|---|---|---|---|
| Code | 90.7 | **91.8** | 70 |
| Complexity | 95.3 | 95.3 | 70 |
| Architecture | 88.2 | 88.2 | 70 |
| Style | 88.0 | 88.0 | 70 |

Gate passa (tutte le metriche ≥ soglia). Il miglioramento Code (+1.1 pt) è dovuto
alla rimozione del docblock "useless" descritta sopra. Rimane, pre-esistente e
fuori scope, `20` complessità ciclomatica di classe su
`EvaluateTenderEligibilityAction` (metodi `execute()` 12, `requiredDocumentsFor()`
8) — non rifattorizzato in questo fix perché non richiesto dall'obiettivo
(type-coverage) e perché un refactor di logica business su un file toccato da
altri agenti in questo momento avrebbe rischio di conflitto sproporzionato al
beneficio.

### Pest (`--testsuite=PublicProcurement`, con coverage Xdebug)

| Momento | Pass | Fail | Coverage linee `app/` |
|---|---|---|---|
| Prima (invariato rispetto a 2026-09-06) | 1 | 3 | 0/306 (0.00%) — Feature test bloccati da DB |
| Dopo | 1 | 3 | 0/306 (0.00%) — invariato, nessuna regressione |

Coverage 0% confermato via report HTML Xdebug (`--coverage-html`, driver
disponibile in questa sessione — a differenza della nota del 2026-09-06 che lo
dava assente). Resta 0% perché i 3 Feature test falliscono prima di eseguire la
business logic (vedi sotto), non per assenza del driver. Il fix di questa
sessione è un cambio type-level su una dichiarazione di costante: non
tocca alcuna riga eseguita a runtime, quindi non può influire sulla coverage
misurata, prima o dopo.

I 3 fallimenti restano lo stesso blocker pre-esistente già documentato sotto
(`compliance_documents` mancante + connessione `publicprocurement` non
configurata sul DB `workorder_data_test`), non causato da questo fix. Vedi
memoria second-brain `project_test_db_missing_migrations_blocks_feature_tests.md`.

## Storico

## PHPStan (level max, `Modules/PublicProcurement`)

| Momento | Errori |
|---|---|
| Prima (2026-09-06, baseline riverificata) | 57 (30 `method.nonObject`, 15 `generics.notGeneric`, 12 `method.deprecated`) |
| Dopo (2026-09-06) | **0** |

Fix: vedi `./factories-seeders-status.md#aggiornamento-2026-09-06-fix-phpstan-modelfactory-mixed`
per il root cause (`newFactory()` generico non risolvibile da Larastan) e
rinomina `actions()`/`bulkActions()` → `recordActions()`/`toolbarActions()` (Filament
v4, 12 occorrenze in 9 file Resource/RelationManager).

## Pest (`Modules/PublicProcurement/tests`)

Nessun driver di code coverage disponibile nell'ambiente (Xdebug non in modalità
coverage, PCOV assente) — non è possibile produrre una percentuale di riga/branch.
Riportati conteggi pass/fail prima e dopo il fix, per verificare che le modifiche
(solo type-level: `newFactory()` concreto sui 15 modelli, rinomina metodi Filament
deprecati) non abbiano introdotto regressioni comportamentali.

| Momento | Pass | Fail | Note |
|---|---|---|---|
| Prima | 1 | 3 | stesso esito: tabelle mancanti su DB di test |
| Dopo | 1 | 3 | invariato — nessuna regressione introdotta dal fix PHPStan |

I 3 fallimenti (`Modules\PublicProcurement\tests\Feature\TenderEligibilityAndOpportunityTest`)
sono **pre-esistenti e fuori scope**: `SQLSTATE[42S02]: Base table or view not found` su
`compliance_documents` e `public_procurement_ai_extractions` — le migration esistono
(`Modules/Compliance/database/migrations/2026_07_24_000303_create_compliance_documents_table.php`,
`Modules/PublicProcurement/database/migrations/2026_07_24_000115_create_public_procurement_ai_extractions_table.php`)
ma non sono state applicate al database `workorder_data_test`. Non è stato eseguito
alcun `migrate:fresh`/`--force`/`RefreshDatabase` per rispettare il vincolo "dati
reali, mai reset distruttivo": il fix di questo blocker richiede un intervento
volontario del proprietario del DB di test (eseguire le migration pendenti), non
un'azione automatica dell'agente.

`Modules\PublicProcurement\tests\Unit\PublicProcurementModuleSmokeTest` passa invariato.
