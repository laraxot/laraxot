---
title: "Porting gestionale_commesse — seconda tranche (delta post 2026-07-22)"
type: concept
tags: [porting, gestionale-commesse, delta, tranche-2, inventory]
created: 2026-08-29
updated: 2026-08-29
qmd: "delta seconda tranche porting gestionale commesse anchor 15f07e80 inventario moduli"
related:
  - "./porting-final-status.md"
  - "./copy-reshape-playbook.md"
  - "./module-mapping.md"
  - "./multitenant-plan-for-review.md"
---

# Seconda tranche di porting — inventario delta (fonte di verità)

> **Anchor**: commit sorgente `15f07e80` (2026-07-22 16:41), ultimo commit coperto dalla prima tranche.
> **HEAD sorgente**: `f5936f8` (2026-08-28). Delta = `git -C ~/gestionale_commesse diff 15f07e80..HEAD`.
> ~132 commit, ~37k righe fuori `Modules/`, ~19k dentro.

## Regole di questa tranche

- Ciclo per modulo: `lock.sh` → diff → **reuse-check** (cosa copre già la base?) → test Pest prima (RED) →
  port+reshape (GREEN) → PHPStan zero → docs `import-status.md` → commit atomico → `unlock.sh`
- **Multitenant: NON in questa tranche.** Solo piano in [multitenant-plan-for-review.md](./multitenant-plan-for-review.md)
  (revisione: Marco Sottana)
- Decisione architetturale: **`TimberBilling` → rename `Timber`** (bounded context completo del verticale legno,
  non solo fatturazione; non si spezza — la fatturazione timber-specifica resta sotto-dominio interno)

## Delta per modulo sorgente (file toccati `15f07e80..HEAD`)

| Sorgente | Dest | File delta | Note |
|---|---|---:|---|
| WorkOrders | WorkOrder | 46 | |
| Quotations | Quotation | 28 | dest era "no test reali" |
| Platform | Platform | 25 | |
| AiAssistant | AiAssistant | 25 | storico conflitto multi-agente: verificare stato prima |
| Inventory | Inventory | 23 | dormant (fix TestCase Catalog) |
| WhatsApp | WhatsApp | 22 | grosso delta funzionale (de-approvazione, comandi interattivi) |
| Customers | Customer | 22 | |
| Compliance | Compliance | 17 | gap, disabilitato |
| EnergyBroker | EnergyBroker | 16 | gap, disabilitato |
| Production | Production | 14 | dormant |
| Vehicles | Vehicle | 13 | |
| Email | Email | 11 | |
| PublicProcurement | PublicProcurement | 11 | gap, disabilitato |
| Billing | Billing | 30+ | cash-flow, aging, job margins, payment tracking |
| HR | HR | 30 | |
| Auth | User | 19 | reuse-check forte: la base ha già spatie roles/auth |
| Bom | Bom | 12 | dormant |
| Signatures | Signature | 7 | |
| Catalog | Catalog | 6 | |
| Documents | Document | 6 | |
| TimberBilling | Timber (rename) | 6 | |
| Interventions | Intervention | 25 | tariffe manodopera, pricing, recap materiali |

## Moduli sorgente MAI portati (nuovi in questa tranche)

| Sorgente | Dest | File | Dominio |
|---|---|---:|---|
| MetalWorks | **MetalWork** | 52 | preventivi da disegno CAD, distinta dai solidi |
| Signage | **Signage** | 60 | segnaletica |
| Costing | **Costing** | 8 | costificazione |

Struttura sorgente DDD-lite (`Domain/Application/Infrastructure` in root) → reshape sotto `app/`
secondo [canonical-module-structure.md](./canonical-module-structure.md).

## Codice `app/` sorgente mai portato

| Sorgente | Dest | Note |
|---|---|---|
| `app/Wts/**` (11 modelli) + `app/Filament/Wts` + `WtsPanelProvider` | **nuovo modulo `Wts`** | conferimenti/ECOS/audit-trail; zero accoppiamenti con Timber (verificato) |
| `app/Timber` + `app/Filament/Timber` | **Timber** (delta-align) | già assorbito in dest; `app/Timber` ha 0 file delta dall'anchor — verificare solo parity |
| `app/Filament/EnergyBroker` | **EnergyBroker** | Filament del modulo |
| `app/Filament/Thermo` + panel | nuovo modulo **Thermo** | verticale demo solo-Pages |
| `app/Filament/GivebackPlatform` + panel | nuovo modulo **Giveback** | idem |
| `app/Filament/Dds` + panel | nuovo modulo **Dds** | idem |
| `app/Filament/AsdSoci` + panel | nuovo modulo **AsdSoci** | idem |
| `MetalWorksPanelProvider` | **MetalWork** | |
| Panel Admin/Operator/Customer/Supplier | modulo proprietario, riscritti `XotBasePanelProvider` | niente `base_path()` hardcoded |
| `app/Filament/{Widgets,Support,Forms}`, `GatedPage/GatedResource`, `NavigationClassifier` | reuse-check UI/Xot | portare solo il mancante |
| `RoleResource`, `Pages/Auth` | reuse-check User | base ha già spatie roles |

## Delta root sorgente

| Area | File | Destinazione |
|---|---:|---|
| `tests/` | 166 | test nel modulo di pertinenza (Pest); smoke rotte 5xx → Xot/Cms |
| `lang/` | 37 | lang del modulo pertinente, struttura espansa it/en |
| `database/` | 15 | migration/seeder nel modulo pertinente (XotBaseMigration) |
| `config/` | 7 | config modulo o `config/` per-dominio base |
| `scripts/` | 13 | `bashscripts/` o Console command di modulo |
| `routes/` | 2 | routes modulo |

## Ordine di esecuzione

0. Baseline + questo doc + doc multitenant (fatto)
1. **Fase 1a**: rename `TimberBilling` → `Timber`
2. **Fase 1**: delta moduli già portati (ordine: dal più piccolo per validare il ciclo — Catalog, Document,
   Signature → poi i grossi WorkOrder, Quotation, WhatsApp, Billing, HR, Intervention…)
3. **Fase 2**: MetalWork, Signage, Costing
4. **Fase 3**: `app/` → moduli (Wts, verticali demo, panel, condivisi)
5. **Fase 4**: riabilitazione gap (Compliance, EnergyBroker, Timber, PublicProcurement) + fix TestCase
   Bom/Inventory/Production + testsuite in `phpunit.xml`
6. **Fase 5**: validazione finale (PHPStan Modules = 0, Pest suite, `php artisan about`, aggiornamento
   `porting-final-status.md`)

## Baseline (registrata a inizio tranche)

Vedi sezione aggiornata a fine Fase 0 con i numeri di `vendor/bin/pest` e `phpstan analyse Modules`.

## Stato sessione 2026-08-29 (checkpoint)

### Fatto
- **Fase 0** completa: questo doc + [multitenant-plan-for-review.md](./multitenant-plan-for-review.md) (per Marco)
- **Fase 1a** completa: rename `TimberBilling` → `Timber` (namespace, provider, alias, connection
  `timber`, phpunit, statuses, docs; tabelle DB invariate)
- **Ambiente sbloccato su questa macchina** (prima nessun vendor/, nessun DB test):
  - `composer update` necessario (lock privo di testbench/pest — documentato); dev-deps
    incompatibili PHP8.4/L13 rimossi da AI/Seo (psalm, enlightn, phpinsights)
  - `pest-plugin-laravel` riabilitato (era `require-dev_comment`); `PestStubs.php` rimosso
    (shadowava il plugin a runtime)
  - fixture `database/fixcity_data.sqlite` migrata forward-only: **188 tabelle**
  - fix sqlite-compat: XotBaseMigration fallback default-conn, team_user guard integer,
    Inventory FK check driver-agnostico; XotBaseTestCase fallback conn `user`→sqlite
  - config: `main_module` TechPlanner→WorkOrder (workorder+localhost), APP_URL→workorder.local
- Suite Timber: da 1/169 a **129 passed / 40 failed**; PHPStan Modules/Timber = 0

### Baseline PHPStan post-upgrade toolchain (phpstan 2.2.9 / larastan 3.10 / pest 4.7.8)
- **Codice (non-tests): 0 errori** (i 2 trovati — Inventory, Job — corretti in sessione)
- **tests/: 2448 errori** nuovi da drift toolchain (variable.undefined/method.nonObject su
  closure Pest) — workstream separato, non blocca il porting; ignore chirurgico
  `method.internalClass` per Expectation Pest già in phpstan.neon

### Prossimi passi (in ordine)
1. Timber: reshape XotBase* delle 62 pagine Filament (causa principale dei 40 failed) + policy/permessi
2. Fase 1: delta moduli già portati (partire da Catalog/Document/Signature)
3. Fase 2-5 come da piano sopra

## Checkpoint 2026-08-30

- **Document/Signature/Customer**: collisioni tabelle risolte (opzione 2, issue #14):
  `media`→`document_media`, `contacts`→`customer_contacts`; FK ritargetizzate in 14
  migration; fixture rigenerata forward-only. Suites verdi. Commit `f1ddb3f5`.
- **Vehicle**: delta = solo gating → `GatedXotBaseResource`. Verde.
- **Intervention**: delta completo portato (tariffa manodopera, rapportino vocale v2,
  recap extra-materiali, preventivo-da-intervento, gating). 11/11 calendario verdi.
- **Quotation (parziale)**: portate le 2 action preventivo-da-intervento + test nuovo.
- **Panel trasversali (anticipo Fase 3)**: `OperatorPanelProvider` in Platform
  (id `operator`); `XotBasePanelProvider` con `$panelId/$panelPath` overridabili;
  panel root `admin` e panel WorkOrder scoprono i moduli operativi.
- **Infra test**: `withoutVite()` in XotBaseTestCase (niente build Vite nei test;
  in questa base si compilano solo i temi). Asset `intervention-calendar.js` in
  `Modules/Intervention/resources/assets/js/` — TODO integrazione build tema.
- **Debito noto**: 14 errori PHPStan pre-esistenti in `app/` (Application, User,
  FilamentOptimizationServiceProvider) — fuori perimetro delta, da workstream dedicato.

## Checkpoint 2026-08-30 (sera)

- **Email**: delta completo (guard AI catch, pagina gated). 6/6 verdi. Commit `2672d25c…`.
- **WhatsApp**: delta completo (deapprove/delete/mark-processed + comandi console,
  guard AI, pagine gated, +6 test). 42/42 verdi.
- **Fix trasversali scoperti in questo giro**:
  - `use App\Models\User` = classe ORFANA della base → instanceof/Gate false
    silenziosi. Bonificati Email/Intervention/WhatsApp (11 file). Check obbligatorio
    a ogni ciclo (memoria `app-models-user-orphan`).
  - `RouteServiceProvider` senza `$module_dir` → routes modulo mai caricate
    (Intervention, Email, WhatsApp). Webhook esterni ora sotto `/api/...`.
  - `AccountFeatures::canAccessPanel`: default panel-modulo → semantica admin.
  - Sweep uuid `int→int|string`: punch chain (Start/Stop/Finalize),
    AddWorkOrderActivity, ChangeWorkOrderStatusByCode, CreateAiActionProposal,
    eccezioni punch. `(int) Auth::id()` residui in Resource = debito registrato.
  - Pattern PHP: `uses(TestCase::class)` PRIMA dell'import non risolve (gli
    `use` valgono dal punto di dichiarazione) → sempre FQCN nei test Pest.
  - Scanner comandi Xot: `_components.json` congela la discovery.
- **Regressioni**: Email+Intervention 159 pass/0 fail; Vehicle/Customer/Document/
  Signature/Quotation invariati verdi.

## Checkpoint 2026-08-30 (notte) — WorkOrder + anticipi Quotation

Delta portato: **"distinta base da preventivo, margini, SAL e consegne"** +
**"resoconto periodico"**.

- **Migrazioni** (bump del `create_` con timestamp nuovo, vecchia rimossa, guard
  `hasColumn` nel `tableUpdate`): `work_orders` +`progress_percent`/+`delivery_due_at`;
  nuova `work_order_materials`; `quotations` +`project`/`cost_total`/`delivery_at`/
  `lead_time_days`. Fixture sqlite condivisa rigenerata.
- **WorkOrder**: modello `WorkOrderMaterial` + relazioni; `CreateWorkOrderFromQuotationAction`
  (commessa da preventivo con distinta copiata e data consegna dedotta),
  `BuildPeriodicSummaryAction`, `GeneratePeriodicSummaryPdfAction`;
  `MaterialsRelationManager`, `WorkOrderStatusResource` + 3 pagine, `PeriodicSummaryPage`
  gated + 2 blade; `WorkOrderResource` con SAL, consegna e `deliveryColour`.
- **Quotation (anticipi)**: `marginSummary`/`marginColour` su `QuotationResource`,
  widget `QuotationsMarginByProjectChart`, casts+PHPDoc sul modello.
- **Test**: +3 file (23 test). Suite modulo da **16 failed → 0**: 50 passed, 16 skipped.

### Debiti sistemici della base emersi qui

1. **Chiavi lang senza namespace**: 242 riferimenti `__('work_orders.…')` in WorkOrder
   mentre `lang/` di root non esiste → ogni etichetta del modulo rendeva la chiave
   grezza. Corretti in `workorder::…` (hint = `nameLower`). Restano cross-modulo:
   `compliance.` (37) e `public_procurement.` (17) — quei moduli hanno solo
   `module.php`, le chiavi non esistono affatto; `interventions.` (6) — il modulo
   espone `intervention.php` singolare; `quotations.` (2). → Fase 4.
2. **UUID troncati**: altre 4 Actions WorkOrder con `int $userId` / `?int $…ByUserId`
   (memoria `uuid-int-cast-truncation`). Il grep va rifatto a **ogni** modulo.
3. **`DB::table('users')->insertGetId()`** in 7 test WorkOrder: `users.id` è varchar
   UUID, non autoincrement → violazione NOT NULL. Sostituito con `UserFactory`.
4. **Lang auto-generati**: il LangServiceProvider crea file con `label` = nome chiave
   (`'code' => ['label' => 'code']`). Vanno riscritti a mano in it **e** en.
5. **pint riconverte** `uses(\Modules\X\Tests\TestCase::class)` in forma corta →
   il re-FQCN va rifatto **dopo** ogni pint (script idempotente).

PHPStan WorkOrder+Quotation: **zero errori sul codice di produzione**; 68 residui
tutti test-only (pattern `mixed` di Pest/Livewire, debito toolchain condiviso).

## Checkpoint 2026-08-30 (notte) — Quotation + Billing

### Quotation (delta chiuso)
Margini, progetto/brand, consegne (`delivery_at`/`lead_time_days`), gating delle 4
superfici Filament, `ViewQuotation` che crea la commessa portandosi dietro la distinta.
Coperto da `WorkOrder/JobCostingUiTest`. PHPStan produzione: 0.

Debito lasciato: 14 test media rossi **pre-esistenti** (verificato con stash). Il
sorgente non usa Spatie MediaLibrary ma un modello `Media` custom; il porting ha
giustamente riusato Spatie ma i test sono stati copiati senza adattare le asserzioni
(`disk` di destinazione ≠ di origine; `name` che ricade su un path già hashato →
l'utente vedrebbe `ECJHc5zZ...` come nome allegato). Ciclo dedicato al layer media.

### Billing (delta chiuso)
Controllo di gestione completo: aging crediti, proiezione di cassa, margine per
commessa (costi separati per famiglia), pagina gated + blade, payment tracking su
fatture cliente e fornitore, gating delle 4 resource.
**Da 30 test rossi a 80 verdi. PHPStan produzione da 62 errori a 0** (il debito
storico del modulo era proprio questo).

### Difetti sistemici della base emersi in questi due cicli

1. **Policy che esplodono su permesso mancante** — `hasPermissionTo()` di spatie
   **lancia** `PermissionDoesNotExist`. Una sola resource nel panel con permesso non
   seedato manda in 500 il rendering dell'intera sidebar Filament. Corretto in
   `VehicleBasePolicy` con un `allows()` che cattura e risponde `false`.
   **Il pattern è in decine di policy** (Cms, Activity, Gdpr, Geo, Lang, Job…):
   la correzione generale richiede `checkPermissionTo()` su `UserContract` (Xot)
   + sweep → ciclo di hardening dedicato.
2. **`skip('...')` non esiste in Pest**: 22 occorrenze usate come marcatori di debito
   facevano *fallire* i test. Cercarle a tappeto: `grep -rn "^\s*skip(" Modules/*/tests`.
3. **TestCase di modulo senza `prepareSharedFixcitySqliteForTesting()`**: il modulo
   gira su uno sqlite senza tabelle. Da verificare in ogni modulo prima di dare per
   buono un "rosso pre-esistente".
4. **Chiavi lang senza namespace**: terzo modulo di fila (WorkOrder, Quotation,
   Billing). Il sintomo peggiore non è l'etichetta brutta ma i **parametri non
   interpolati** (`:amount` mai sostituito).
5. **Lang auto-generati dal LangServiceProvider** con `label` = nome chiave: vanno
   riscritti a mano in it **e** en (già capitato per `material`, `work_order_status`,
   `work_order`, `site_survey`).
6. **`uses(TestCase::class)` prima dell'import** non risolve e pint riconverte alla
   forma corta: sanato a tappeto su 678 file (commit `3e43324f`).
7. **Suite in parallelo sulla stessa fixture sqlite** danno falsi rossi
   (WhatsApp 2 fail in parallelo, 42/42 da solo). Lanciarle in serie.

## Checkpoint 2026-08-30 (notte, 2) — Compliance, Platform, AiAssistant, dormienti

- **Compliance**: F-Gas (circuiti refrigeranti, controlli fughe, obbligo calcolato dal
  CO2 equivalente) + registri regionali. PHPStan 16→0, 21 test verdi.
  Corretto `$connectionsToTransact` che includeva `mariadb`: DatabaseTransactions
  apriva una connessione MySQL reale e *ogni* test del modulo moriva prima di partire.
- **Platform**: 3 seeder demo, incluso lo scenario della commessa che si mangia il
  margine (tre sopralluoghi invece di uno) — il caso che il controllo di gestione
  deve saper mostrare.
- **Inventory/Production**: gating + TestCase senza fixture condivisa. 11 rossi → 12 verdi.
- **HR**: i 5 test marcati come bloccati da debiti (factory utente con ruoli, conflitto
  media) passano **senza toccare il codice**: i debiti erano già chiusi di sponda.
  3 verdi → 8.
- **WorkOrder**: ripristinato il test PDF, era vuoto perché dompdf mancava (c'è).
- **AiAssistant**: guardie di sicurezza (allow-list azioni, tetto proposte per utente,
  redazione a due livelli). Corretto il test del logging LLM che usava la firma di
  `Log::listen()` a tre argomenti (abbandonata da Laravel 9) e restituiva l'array per
  valore quando i log arrivano dopo. 2 rossi → 20 verdi.

### Auth → User: rimandato alla Fase 3 → **chiuso con Wts**

Il delta del modulo Auth è **interamente** logica del panel WTS (`isWtsPanel()`,
ruoli `wts_admin`/`wts_operator`, `AccountFeatures::isWtsAdmin`). Il panel e il
modulo Wts non esistevano ancora nella base: il delta è stato portato **insieme** a
Wts, altrimenti sarebbe stato codice morto che referenzia classi assenti.

Nel sorgente quel comportamento stava nell'unica `UserResource` dell'applicazione,
con dei `if (isWtsPanel())` sparsi nei quattro metodi di autorizzazione. Qui
`Modules/User` è un modulo condiviso e non deve sapere che esiste un WTS: la
resource ristretta è `Modules\Wts\Filament\Resources\UserResource`, che estende
quella di User, e il pannello monta quella. Sparisce il branch: nella resource del
pannello di impianto non c'è nessun «se siamo nel pannello WTS», perché ci siamo
per costruzione.

## Checkpoint 2026-08-31 — Fase 2 completa: tre moduli nuovi

### Costing (nuovo)
Centri di lavoro e ricarichi condivisi. Signage e MetalWork avevano ciascuno la
propria tabella con le stesse colonne e la stessa aritmetica duplicata: ora una sola
tabella separata per contesto e una sola implementazione della regola («vince il
primo candidato non nullo, dal più specifico al più generico»). La decisione porta
con sé **la provenienza**, non solo la percentuale: all'operatore mostriamo il calcolo
di ogni riga e deve distinguere «il centro di lavoro dice 40%» da «non l'ha detto
nessuno». Uno zero impostato di proposito vince su un valore ereditato: è una scelta,
non un vuoto. 10 test, PHPStan pulito.

### MetalWork (nuovo, ex MetalWorks)
Preventivi carpenteria dai disegni STEP. Un STEP è una rappresentazione a contorno,
non un mesh: il volume esatto non è calcolabile senza un tassellatore. Perciò quando
i solidi si distinguono il pezzo si costa riga per riga (ogni riga è un acquisto reale
a un prezzo fatturato) e solo altrimenti si ricade sul peso derivato dall'ingombro per
un fattore di riempimento che l'operatore corregge a schermo.
Rinominato al singolare con tutto quello che ne segue: tabelle, config, path del
pannello, ruoli, e la voce corrispondente in `AccountFeatures`.
42 test, PHPStan da 165 errori a **zero** sul codice di produzione.

### Signage (nuovo)
Preventivi insegne dal file grafico .ai (contenitore PDF). Estrae il testo con le
coordinate, lo rimette in righe per linea di base, riconosce i componenti, li prezza
dal listino fornitori col ricarico di Costing.
52 test, PHPStan da 129 errori a **zero** sul codice di produzione.

### Nuova dipendenza
`smalot/pdfparser` — serve a MetalWork per leggere le fatture fornitore in PDF.

### Lezioni di questo giro

1. **Il reshape automatico non tocca le dichiarazioni `namespace`**: rimappa gli
   `use` ma lascia `namespace Modules\X\Infrastructure\Models;`, e la classe risulta
   dichiarata due volte sotto nomi diversi. Verificare sempre con
   `grep "namespace.*Infrastructure\|namespace.*Application"` dopo un porting.
2. **Sostituire `use Illuminate\...\Model` con `XotBaseModel` a tappeto rompe i
   RelationManager**, dove `Model` è il tipo del metodo padre. La sostituzione va
   fatta solo dentro `app/Models/`.
3. **Le funzioni `Safe\*` cambiano la semantica dei parser**: non tornano mai `false`,
   quindi tutti i check `=== false` diventano morti, e i tipi di `$matches` si
   restringono al punto che PHPStan chiede guardie sugli offset. Vanno introdotte
   *insieme* alla ripulitura dei controlli, non prima.
4. **Una variabile riusata come array di match e come stringa** (`$reference`) passa
   inosservata con `preg_match` nativo e diventa un TypeError con quello Safe.
5. **XotBase registra i config di modulo come `{modulo}.{file}.*`**: se il codice legge
   `config('modulo.*')` serve l'alias esplicito nel provider (schema già usato da
   Catalog per `metel`).
6. **Un pannello senza pagina sulla radice** ricade sul redirect di Filament e manda
   l'utente sulla prima resource che capita: la dashboard va montata a mano se si
   dichiara `$isDiscovered = false`.

## Checkpoint 2026-08-31 — Fase 3: modulo Wts

Il verticale WTS (impianto di trattamento rifiuti) viveva in `app/Wts`,
`app/Filament/Wts` e `WtsPanelProvider`: 69 file mai portati. Ora è un modulo con
pannello proprio su `/wts`, 205 test verdi e PHPStan pulito. Dettaglio in
[Modules/Wts/docs/import-status.md](../../laravel/Modules/Wts/docs/import-status.md).

Quattro cose imparate qui, oltre a quelle già in elenco:

7. **Le pagine che si dichiarano `$isDiscovered = false` vanno montate tutte a mano**,
   non solo la dashboard: con il solo elenco parziale il pannello espone il login e
   ogni altra rotta risponde 404.
8. **Un modello con `SoftDeletes` e una migrazione senza `deleted_at` non fallisce
   subito**: fallisce alla prima query, con un 500 in pagina. Vale la pena derivare
   il flag della migrazione dai trait del modello invece che a memoria.
9. **I `@property` generati dallo schema mentono se il modello ha dei `casts()`**: una
   colonna JSON è `string` su disco e `array` in PHP, e un docblock che dice `string`
   rende morto ogni `is_array()` a valle. Il generatore ora legge prima i cast.
10. **Uno stub di comodo sopravvive al pacchetto che sostituiva**: `Barryvdh\DomPDF\PDF`
    era stubbato in `Modules/Intervention` da quando dompdf non c'era; ora che c'è,
    lo stub nascondeva l'API reale e faceva sbagliare l'analisi statica in un altro
    modulo. Rimosso.

## Checkpoint 2026-08-31 — Fase 3 completa

Portato tutto il codice che nel sorgente viveva in `app/` e non era mai arrivato qui.

| Cosa | Dove | Test |
|---|---|---|
| `app/Wts` + `app/Filament/Wts` + panel | nuovo modulo **Wts** | 205 |
| `app/Filament/EnergyBroker` + panel | **EnergyBroker** (pannello `/energy-broker`) | 22 |
| `app/Filament/{Thermo,GivebackPlatform,Dds,AsdSoci}` + panel | 4 moduli nuovi | 37 |
| `app/Filament/Widgets` (5 widget + trait) | Platform, Intervention, WorkOrder, Quotation | 5 |
| panel Customer/Supplier | **Platform** | 6 |

Due difetti veri trovati dai test scritti per l'occasione, entrambi invisibili a
occhio:

- **Il pannello cliente esponeva l'audit log.** `XotBasePanelProvider` scopre le
  resource del modulo che ospita il pannello, e Platform contiene `AuditLogResource`:
  `/customer/audit-logs` rispondeva 200 a un cliente qualunque. Il gate della resource
  non poteva chiuderlo senza chiudere anche commesse e fatture. Nato
  `$discoverModuleComponents`, spento sui pannelli esterni.
- **La legenda del calendario perdeva operatori.** `(int)` su un id UUID: due
  operatori diversi collassavano nella stessa voce. Intermittente — dipende da come
  inizia l'UUID — quindi il test rosso compariva a caso.

Altre lezioni, oltre alle dieci gia' in elenco:

11. **Un file di lingua generato non e' un file di lingua tradotto**: il generatore
    riempie le chiavi mancanti col nome della chiave, e in pagina si legge
    `export_xls`. Vanno tradotti prima del commit, non "poi".
12. **Le migrazioni delle colonne autore**: `foreignId` verso `users.id` non regge un
    id UUID. Servono `string(36)`, in ogni modulo, comprese quelle condivise
    (`document_media.uploaded_by_user_id` perdeva chi aveva caricato il file).

### Stato del piano

- **Fase 1** (delta moduli portati): **completa** — Auth→User chiuso insieme a Wts.
- **Fase 2** (moduli nuovi): **completa** — MetalWork, Signage, Costing.
- **Fase 3** (codice `app/` → moduli): **completa**.
- **Fase 4** (gap): Compliance, EnergyBroker, PublicProcurement hanno PHPStan pulito
  e test verdi; testsuite di tutti i moduli aggiunte a `phpunit.xml`.
- **Fase 5** (validazione finale): da fare.

### Debito registrato

- Quotation: 14 test media rossi (Spatie vs modello Media custom), preesistenti.
- Xot: 82 test rossi sulla connessione `user` verso MySQL — verificato che sono
  precedenti a questa tranche (stesso conteggio con il provider di prima).
- Intervention: 3 punti che troncano ancora l'id utente, elencati nel suo
  `import-status.md`; il primo passo e' allargare `StoreSignatureForModelAction`.
- `CanvasPad` sta in `app/Filament/Forms/Components` invece che nel modulo UI.
- **PHPStan sui test**: `phpstan analyse Modules` supera i 1000 errori, ma sono tutti
  nelle suite di test, mai nel codice di produzione. Il profilo e' identico nei moduli
  storici mai ripuliti (Notify 18, Intervention 7, Activity 6) e nei nuovi
  (Signage, MetalWork): accesso nullable su `->first()` e tipi Pest/Livewire che
  PHPStan non risolve. Ogni modulo toccato in questa tranche e' a **zero errori su
  `app/`**, che e' il criterio tenuto per tutta la migrazione.
