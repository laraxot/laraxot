---
title: "Compliance — PHPStan zero + canonical Schemas/Tables wiring completion"
type: handoff
tags: [phpstan, compliance, filament-canonical, story-5.72.11]
created: 2026-09-07
---

# Compliance — PHPStan zero errori (claim)

Sessione dedicata a chiudere gli errori PHPStan residui sul modulo Compliance
(swarm PHPStan multi-modulo). In corso 2026-09-07 (claude sonnet 5).

## Contesto trovato all'avvio

Il working tree del modulo aveva gia' un WIP corposo, non committato, coerente
con la story `docs/stories/5.72.11.compliance-filament-canonical.story.md`
(status `in_progress`, "canonical wiring" post-estrazione Epic 4 Schemas/Tables
di `4.14.7.compliance-canonical.story.md`, gia' `completed`): Resource
dimagrite, `Schemas/`+`Tables/` gia' creati e funzionanti, costanti tipizzate.
Non un conflitto da un altro agente concorrente in questo momento — lock
`bashscripts/lock/check.sh Modules/Compliance` risultava FREE, `git log` locale
allineato 1:1 a `laraxot/dev` (nessun commit remoto in arrivo), coerente con
lavoro di una sessione precedente non ancora commit+push.

## Esito

5 errori PHPStan residui (esattamente la parte "Task 2" non chiusa di
5.72.11): duplicati orfani lasciati sulla Resource dopo l'estrazione verso
Schemas/Tables.

- `ComplianceDocumentResource::documentTypes()` / `::statuses()` — `private
  static` mai chiamati (i chiamanti usano gia' `ComplianceDocumentForm::`),
  rimossi come dead code duplicato.
- `WorkOrderChangeOrderResource::statuses()` — `private static`, chiamato pero'
  da `WorkOrderChangeOrderForm` e `WorkOrderChangeOrdersTable` (namespace
  diversi): spostato su `WorkOrderChangeOrderForm::statuses()` (`public
  static`), pattern allineato a `ComplianceDocumentForm` (single source of
  truth sulla Form, non sulla Resource). Aggiornati i due call site.

PHPStan Modules/Compliance: 5 -> 0 errori (cache pulita, verificato due volte).

Vedi anche coordinamento gia' noto in `compliance-typecoverage-fix.md` (story
2.8, wave C) — le 4 costanti li' segnalate come "in un WIP non committato di
un'altra sessione" sono le stesse di questo WIP 5.72.11, gia' tipizzate nel
frattempo.

## Status

Chiuso. Gate completo su Modules/Compliance dopo il fix (cache phpstan pulita,
pint scoped ai file toccati, phpmd con `Modules/Compliance/phpmd.ruleset.xml`,
phpinsights con config effimera per bypassare `ForbiddenSecurityIssues`, pest
sull'intero modulo):

| Gate | Prima (questa sessione) | Dopo |
|---|---|---|
| PHPStan Modules/Compliance | 5 | 0 |
| Pint (file toccati) | n/a | passed |
| PHPMD (file toccati) | n/a | nessuna nuova violazione |
| PHPInsights | 89.7/88.4/88.2/89.2 (nota 2026-09-06) | 89.7/89.7/88.2/89.2 |
| Pest Modules/Compliance | 22 passed / 4 failed | 24 passed / 4 failed |

I 4 test Pest falliti sono ambientali (gap DB di test condiviso, tabella
`customers` mancante + colonna `fgas_leak_checks.performed_by_user_id`
mancante), identici prima/dopo, gia' documentati in
`Modules/Compliance/docs/coverage.md` dal 2026-09-06 — non regressione,
non toccati.

Aggiunto 1 test nuovo (`WorkOrderChangeOrderFormStatusesTest`, 2 casi) per il
metodo reso pubblico durante il fix. Story `5.72.11.compliance-filament-canonical.story.md`
aggiornata a `status: done`. Story `2.8` (constant-type-coverage) segnalava un
WIP non committato su questo stesso modulo in `compliance-typecoverage-fix.md`:
aggiornata con nota di chiusura.

Commit `2a1cb1d` (include anche il resto del WIP canonical-structure gia'
presente e verificato: Schemas/+Tables/ per le 5 Resource, Pages su
`XotBaseViewRecord`, costanti tipizzate story 2.8 — nessuno di questi file
era in conflitto con altre sessioni al momento del commit, `git log` locale
era 1:1 con `laraxot/dev` sia prima che dopo). Push:
`laraxot dev` (`5a7f5bc..2a1cb1d`), unico remote configurato sul repo
indipendente `Modules/Compliance`.

Nessun lock attivo trovato, nessun conflitto, nessuna modifica fuori da
`Modules/Compliance`. `mixed` non introdotto. `phpstan.neon` non toccato.
