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
