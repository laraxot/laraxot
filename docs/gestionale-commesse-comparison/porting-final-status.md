---
title: "Porting gestionale_commesse — stato finale verificato (fonte unica di verità)"
type: concept
tags: [porting, laraxot, gestionale-commesse, audit, phpstan, status]
created: 2026-07-24
updated: 2026-07-24
qmd: "porting gestionale commesse stato finale audit verificato modulo per modulo"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/7"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/8"
related:
  - "../wiki/concepts/porting-external-module-playbook.md"
  - "./EPIC.md"
  - "./module-mapping.md"
---

# Porting gestionale_commesse — stato finale verificato (2026-07-24)

> **Leggi questo file per primo** invece di ricostruire lo stato da 16+ doc sparsi nei moduli. Ogni riga sotto è
> stata **ri-verificata contro il codice reale** in questa sessione (`find`, `vendor/bin/pest`,
> `vendor/bin/phpstan analyse`), non copiata dai doc esistenti. Dove un doc di modulo affermava qualcosa non più
> vero, è stato corretto in loco e il dettaglio è riportato qui nella colonna "Note".
>
> **Nota tecnica sul PHPStan scoped**: eseguire `phpstan analyse Modules/{Nome}` su un singolo modulo genera
> sempre un errore rumore aggiuntivo `Ignored error pattern ... was not matched in reported errors` (1 o 2, a
> seconda se scatta anche il pattern `Cannot cast mixed to ...`), perché gli `ignoreErrors` globali di
> `phpstan.neon` non trovano match quando si scansiona un solo modulo isolato. **Questo non è un errore del
> modulo** — la colonna "PHPStan errori reali" sotto è già epurata da questo rumore.

## Tabella di stato — 16 moduli portati

| Modulo | Status | Modelli | Migrazioni | Test (file/pass) | PHPStan errori reali | Docs aggiornati |
|---|---|---|---|---|---|---|
| Customer | done | 6 | 5 | 2 file — **6 passed** | 0 | Y |
| WorkOrder | done | 6 | 5 | 2 file — **6 passed** | 0 | Y |
| Intervention | done (no test reali) | 9 | 8 | 0 file (solo scaffold TestCase/Pest.php) | 0 | Y (non oversold) |
| Catalog | done | 7 | 7 | 0 file (solo scaffold) | 6 (`larastan.noEnvCallsOutsideOfConfig` in `config/metel.php`, noto/accettato) | Y (doc già accurato) |
| Quotation | done (no test reali) | 6 | 5 | 0 file (solo scaffold, doc già corretto il 2026-07-24 con nota esplicita "No tests found") | 0 | Y |
| Billing | done | 7 | 7 | 2 file — **5 passed** | 62 (debito noto: `property.notFound` su model `$guarded=[]` codebase-wide + `barryvdh/laravel-dompdf` non installato) | Y |
| Fiscal | done (pure calc engine, 0 modelli by design) | 0 | 0 | 4 file — **6 passed** | 0 (era segnato "bloccato": **corretto**, non si riproduce più) | **N → fissato in questa sessione** (lang claim errato rimosso, blocco PHPStan rimosso) |
| Inventory | dormant (test rotti in isolamento) | 5 | 5 | 1 file — **3 failed** (`QueryException: no such table: products`) | 0 bloccanti | **N → fissato**: aggiunta nota "Verificato 2026-07-24" nel port doc |
| Bom | dormant (test rotti in isolamento) | 2 | 2 | 1 file — **2 failed** (stessa causa: `Product` di Catalog non registrato) | 0 bloccanti | **N → fissato** |
| Production | dormant (test rotti in isolamento) | 2 | 2 | 1 file — **2 failed** (stessa causa) | 0 bloccanti | **N → fissato** |
| Signature | done | 3 | 2 | 1 file — **4 passed** | 0 (era segnato "55 errori pre-esistenti" sulle Filament Pages: **non riproducibile, corretto**) | **N → fissato**: link rotto a `gestionale-commesse-port.md` rimosso, claim 55 errori rimossa |
| Vehicle | done | 3 | 2 | 1 file — **4 passed** | 0 (solo rumore) | **N → fissato**: link rotto a `gestionale-commesse-port.md` rimosso |
| Compliance | gap (disabilitato) | 9 | 9 | 1 file — **1 passed** (smoke test) | 16 | Y (checkbox `PHPStan L10` già segnata non fatta, coerente) |
| PublicProcurement | gap (disabilitato) | 15 | 16 | 1 file — **1 passed** (smoke test) | 0 | Y |
| EnergyBroker | gap (disabilitato) | 6 | 6 | 1 file — **1 passed** (smoke test) | 47 | Y (checkbox coerente, non oversold) |
| TimberBilling | gap (disabilitato) | 4 | 4 | 1 file — **1 passed** (smoke test) | 38 | Y (checkbox coerente, non oversold) |

**Legenda status**: `done` = reshape XotBase completo, registrato `true` in `modules_statuses.json`, test reali
verificati passanti. `dormant` = reshape fatto ma test/dipendenze cross-modulo rotte, non eseguibili in CI.
`gap` = modulo esiste, reshape fatto, ma `modules_statuses.json` lo ha `false` (disabilitato) e/o PHPStan non
ancora ripulito.

## Fatti strutturali verificati

- **Nessuno dei 16 moduli è registrato in `phpunit.xml` root** (nessuna `<testsuite>` con questi nomi): i test,
  anche quando esistono e passano in isolamento, **non girano mai nella suite CI standard** (`composer test` /
  `vendor/bin/pest` senza path esplicito). Azione consigliata per una prossima sessione: aggiungere le 16
  testsuite mancanti a `phpunit.xml`.
- **`modules_statuses.json`**: `Compliance`, `EnergyBroker`, `PublicProcurement`, `TimberBilling` sono `false`
  (disabilitati); i restanti 12 dei 16 sono `true`.
- **Bug cross-modulo condiviso da Bom/Inventory/Production**: le factory di questi 3 moduli creano
  `Modules\Catalog\Models\Product`, ma i rispettivi `tests/TestCase.php` registrano solo il proprio
  `ServiceProvider` (non anche `CatalogServiceProvider`). Risultato: `vendor/bin/pest Modules/{Bom,Inventory,Production}/tests`
  fallisce sempre in isolamento con `QueryException: no such table: products`. Fix identico per i tre: aggiungere
  `CatalogServiceProvider::class` a `getPackageProviders()` nel `TestCase` di ciascun modulo.

## Tabella di stato — 6 moduli gap-fill (User, Employee, Media, Notify, AI, Tenant)

| Modulo | Doc `gestionale-commesse-port.md` esiste? | Claim principale verificato | Esito |
|---|---|---|---|
| User | No (nessun doc di porting gestionale trovato in `User/docs/`) | — | Nessun claim da verificare |
| Employee | Sezione aggiunta 2026-07-24 in `docs/gestionale-commesse-port.md` (root) | Sistema `AbsenceRequest` (richieste ferie/permessi) | **RIFATTO 2026-07-24** dopo revert multi-agente: modello `AbsenceRequest`, migration, 3 Action (Create/Approve/Reject), `AbsenceRequestResource` con azioni approve/reject e scoping utente, `AbsenceRequestPolicy`, widget `PendingRequestsWidget`/`LeaveBalanceWidget` collegati a dati reali (non più mock), lang it/en, test Pest — vedi `laravel/Modules/Employee/app/Models/AbsenceRequest.php` |
| Media | No | — | Nessun claim da verificare |
| Notify | No | — | Nessun claim da verificare |
| AI | Sezione aggiunta 2026-07-24 in `docs/gestionale-commesse-port.md` (root) | Modelli `AiThread`/`AiMessage`/`AiActionProposal`/`AiToolLog` | **RIFATTO 2026-07-24** dopo revert multi-agente: 4 modelli + 4 migration, `AiActionHandlerContract`/`AiActionHandlerRegistry` (singleton in `AIServiceProvider`), Action Create/Confirm/Cancel proposal, `AiActionProposalResource` con azioni Confirm/Cancel, lang it/en, test Pest. `OperatorAssistantAgent` (tool-calling dominio) resta **deliberatamente non portato** — vedi `laravel/Modules/AI/docs/deferred-operator-assistant-agent.md` |
| Tenant | No | — | Nessun claim da verificare |

**Nota 2026-07-24 (redo dopo revert multi-agente)**: la riga precedente di questa tabella (verificata nella
sessione originale del 2026-07-23) segnalava correttamente che `AbsenceRequest` in Employee e i modelli AI erano
**assenti** nonostante fossero stati implementati in una sessione precedente — il lavoro era stato perso durante un
evento di revert multi-agente caotico. In questa sessione (2026-07-24) il lavoro è stato **rifatto da zero**
seguendo lo spec originale in `STORY-002-hr-vs-employee.md`/`STORY-003-aiassistant-vs-ai.md`, verificato con
`phpstan analyse Modules/Employee Modules/AI` (0 errori nuovi, solo debito preesistente non correlato su
`Modules\TechPlanner\Models\Profile`) e con Pest (9 test passati). Restano gap noti non in scope di questo redo:
`EmployeeResource` per l'anagrafica, documenti/visite mediche/formazione dipendente (vedi STORY-002 punti 2-6), e
il tool-calling di dominio dell'assistente AI (vedi STORY-003 e il doc `deferred-operator-assistant-agent.md`).

**Conclusione onesta (storica, sessione 2026-07-23)**: nessuno dei 6 moduli gap-fill aveva un doc
`gestionale-commesse-port.md` proprio (contrariamente a quanto la formulazione del task presupponeva). Il playbook
`docs/wiki/concepts/porting-external-module-playbook.md` menzionava `AbsenceRequest` in Employee e "modelli AI" in
AI come lavoro di gap-fill "già fatto" — **verificato falso all'epoca**: quel testo descriveva un obiettivo, non
uno stato di fatto. Il playbook era stato corretto in quella sessione con una nota esplicita e un addendum
"Re-verifica dopo revert multi-agente" — addendum la cui utilità è stata confermata proprio da questo redo.

## Temi (Themes/Sixteen/Zero) — `gestionale-panels-vs-themes.md`

Verificato: la sezione "Gap temi" nei 3 doc (`Themes/docs/`, `Themes/Sixteen/docs/`, `Themes/Zero/docs/`) era ed
è **ancora accurata** — nessun modulo tra i 16 ha widget/Folio page lato Operator/Customer, solo Filament Resource
su panel Admin (33 Resource totali contate su tutti e 16 i moduli). Aggiunta una sezione "Stato 2026-07-24" al
doc root con questo dato, senza cambiare la sostanza del gap descritto (resta un lavoro futuro non ancora
iniziato).

## Correzioni applicate in questa sessione (riepilogo file toccati)

- `laravel/Modules/Fiscal/docs/import-status.md` — rimossa claim falsa "lang scaffold" (cartella `lang/` non
  esiste), rimosso "PHPStan bloccato" (non si riproduce più).
- `laravel/Modules/Signature/docs/import-status.md` — rimossa claim "55 errori PHPStan pre-esistenti" (non
  riproducibile), rimosso link rotto a `gestionale-commesse-port.md` (file mai esistito in questo modulo).
- `laravel/Modules/Vehicle/docs/import-status.md` — rimosso link rotto a `gestionale-commesse-port.md`.
- `laravel/Modules/Bom/docs/gestionale-commesse-port.md`, `Inventory/docs/gestionale-commesse-port.md`,
  `Production/docs/gestionale-commesse-port.md` — aggiunta nota "Verificato 2026-07-24" sul fallimento reale dei
  test in isolamento (dipendenza `Catalog` non registrata nel `TestCase`).
- `laravel/Themes/docs/gestionale-panels-vs-themes.md` — aggiunta sezione "Stato 2026-07-24" (33 Resource Admin
  reali sui 16 moduli, gap FO/Themes confermato ancora aperto).
- `docs/wiki/concepts/porting-external-module-playbook.md` — corretta claim falsa su `AbsenceRequest`/modelli AI,
  corretti riferimenti a `WorkOrder/docs/gestionale-commesse-port.md` e `Billing/docs/gestionale-commesse-port.md`
  (file inesistenti, sostituiti con `import-status.md` reale), aggiunto addendum "Re-verifica dopo revert
  multi-agente" con procedura di verifica per sessioni future.
- Questo file (`porting-final-status.md`) — creato come nuova fonte unica di verità.

---

# Aggiornamento 2026-08-31 — seconda tranche completata

La prima tranche si era fermata allo snapshot `15f07e80` (2026-07-22). Questa tranche
copre il delta successivo (~132 commit), i tre moduli mai portati e — soprattutto —
tutto il codice che nel sorgente viveva in `app/` e non era mai arrivato qui.

Fonte di verità del lavoro: [delta-second-tranche.md](delta-second-tranche.md).

## Cosa è entrato

| Ambito | Esito |
|---|---|
| Delta dei moduli già portati (Billing, Bom, HR, Intervention, Email, Vehicle, Document, Compliance, WorkOrder, Quotation) | portato |
| `TimberBilling` → **Timber** | rinominato (bounded context) |
| Moduli nuovi: **MetalWork**, **Signage**, **Costing** | creati |
| `app/Wts` + panel → modulo **Wts** | creato, 205 test |
| `app/Filament/EnergyBroker` + panel | portato, 22 test |
| `app/Filament/{Thermo,GivebackPlatform,Dds,AsdSoci}` + panel → 4 moduli | creati, 37 test |
| `app/Filament/Widgets` (5 widget + trait) | distribuiti nei moduli proprietari |
| Panel Customer/Supplier | in Platform, 6 test |
| Delta `Modules/Auth` | assorbito in Wts (era logica del pannello WTS) |

51 pannelli Filament registrati, `php artisan about` pulito.

## Difetti reali trovati dai test (non dal porting)

1. **Il pannello cliente esponeva l'audit log**: `/customer/audit-logs` rispondeva 200
   a un cliente qualunque, perché `XotBasePanelProvider` scopriva le resource del
   modulo che ospita il pannello. Nato `$discoverModuleComponents`.
2. **La legenda del calendario perdeva operatori**: `(int)` su id UUID faceva
   collassare due operatori in una voce. Intermittente, quindi il rosso compariva a caso.
3. **`document_media.uploaded_by_user_id` perdeva l'autore**: colonna intera con id
   UUID; toccava HR, Compliance, EnergyBroker e gli allegati agli interventi.

## Criterio di qualità tenuto

PHPStan a **zero errori su `app/`** per ogni modulo toccato. Sull'intero `Modules` il
conteggio supera i 1000, ma sono tutti nelle suite di test — stesso profilo nei moduli
storici mai ripuliti. Elenco del debito residuo in fondo a
[delta-second-tranche.md](delta-second-tranche.md).

## Multitenant

Non implementato, come da accordo: esiste solo il documento di piano da revisionare
([multitenant-plan-for-review.md](multitenant-plan-for-review.md)).
