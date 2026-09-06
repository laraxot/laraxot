---
title: "Coverage — PublicProcurement"
type: concept
tags: [publicprocurement, testing, phpstan, coverage]
created: 2026-09-06
updated: 2026-09-06
qmd: "PublicProcurement coverage pest phpstan"
related:
  - "./README.md"
  - "./factories-seeders-status.md"
---

# Coverage — PublicProcurement

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
