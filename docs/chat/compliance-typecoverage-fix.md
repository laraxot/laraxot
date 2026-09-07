---
title: "Compliance — typeCoverage.constantTypeCoverage fix (story 2.8, wave C)"
type: handoff
tags: [phpstan, constantTypeCoverage, compliance, story-2.8]
created: 2026-09-07
---

# Compliance — constantTypeCoverage fix (story 2.8)

Esecuzione wave C della story `docs/stories/2.8.phpstan-constant-type-coverage.story.md`
per il modulo Compliance.

## Esito

Tipizzate 8 costanti di classe (nessuna aveva il tipo nativo `const TIPO NOME = ...`,
solo `@var` docblock o niente) in 6 file puliti (nessun'altra modifica in corso su
quei file al momento del fix):

- `app/Actions/EvaluateWorkOrderReadinessAction.php` — `DEFAULT_REQUIRED_DOCUMENTS` -> `const array`
- `app/Application/Queries/GetHeatingComplianceOverviewQuery.php` — `STATUS_RANK` -> `const array`
- `app/Actions/RegisterMaintenanceControlAction.php` — `MAINTENANCE`, `EFFICIENCY` -> `const string`; `FIELDS` -> `const array`
- `app/DTO/FGasObligation.php` — `EXEMPT_BELOW_THRESHOLD`, `EXEMPT_HERMETIC`, `UNKNOWN_REFRIGERANT` -> `const string`
- `app/DTO/ValueObjects/Refrigerant.php` — `GWP` -> `const array`
- `app/DTO/ValueObjects/ThermalPlantRegistry.php` — `BY_REGION` -> `const array`

Commit `e8d110a` (fix) + `5a7f5bc` (docs), pushati su `laraxot dev` del repo
indipendente `Modules/Compliance`. Dettaglio completo in
`Modules/Compliance/docs/coverage.md`.

## Non toccato — coordinamento necessario

Altre 4 costanti non tipizzate restano in:
- `app/Filament/Resources/CustomerSystemResource.php:34,36`
- `app/Filament/Resources/CustomerSystemResource/RelationManagers/SchemasRelationManager.php:35,42`
- `app/Filament/Resources/HeatingSystemResource.php:37,40`

Questi 3 file erano, al momento del fix, dentro un WIP non committato molto piu'
ampio (refactor `Resource` -> `XotBaseResource`, `form()` -> `getFormSchema()`,
rimozione `->label()` espliciti, ~120 righe totali) di un'altra sessione
concorrente sullo stesso modulo — non mio, non locked. Aggiungere solo il tipo
alle costanti in quei file avrebbe richiesto `git add -A`, che avrebbe inglobato
nel commit di questa story anche quel refactor non verificato. Chiunque stia
portando avanti quel lavoro (Filament canonical structure su Compliance) puo'
aggiungere il tipo nativo alle 4 costanti quando lo committa, oppure lasciare
un segnale qui/in INDEX per chi riprende la wave C.

**Update 2026-09-07 (sessione successiva, PHPStan swarm):** quel WIP era
la story `docs/stories/5.72.11.compliance-filament-canonical.story.md` (Task 1 +
Task 2). Le 4 costanti qui sopra risultano gia' tipizzate nel WIP trovato
all'avvio della sessione successiva (nessun'azione necessaria su quel fronte).
Il WIP aveva pero' lasciato 5 errori PHPStan residui (dead code duplicato
post-estrazione Schemas/Tables, non le costanti), chiusi separatamente — vedi
`2026-09-07-compliance-phpstan-zero-canonical-wiring-fix.md`.

## Blocker Pest non correlato (gia' noto)

`./vendor/bin/pest Modules/Compliance/tests --no-coverage` -> 25 failed, 1 passed.
Tutti i 25 fallimenti sono lo stesso errore di bootstrap Filament,
`Class "Modules\Xot\Filament\Resources\XotBaseResourceForm" not found`, causato
da `Modules/Quotation/app/Filament/Resources/SiteSurveyResource/Schemas/SiteSurveyForm.php:18`
che importa la classe dal vecchio path (`Modules\Xot\Filament\Resources\XotBaseResourceForm`)
mentre oggi vive in `Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm`. Stesso
blocker gia' tracciato in
`docs/chat/2026-09-07-gatedxotbaseresource-migration-bootstrap-break.md` ("urgente, non
risolto"). Fuori scope Compliance — non toccato. L'unico test Compliance che non fa
boot del panel Filament, `Unit/ComplianceModuleSmokeTest.php`, passa verde.

## Correzione a una nota precedente

`Modules/Compliance/docs/coverage.md` (voce 2026-09-06) riportava "phpinsights non e'
installato in questo repo (rimosso, incompatibile con Pest 5)". Verificato oggi:
**e' installato e funzionante** (`laravel/vendor/bin/phpinsights` via
`tools/phpinsights.sh`). L'unico problema noto e' che scoping a un singolo modulo fa
fallire l'insight `ForbiddenSecurityIssues` (cerca `composer.lock` a partire dal path
analizzato); bypassabile con un config-path effimero che rimuove solo quell'insight,
senza toccare `phpinsights.php` condiviso. Per Compliance con quel workaround: Code
89.7, Complexity 88.4, Architecture 88.2, Style 89.2 — tutte sopra soglia 70.
