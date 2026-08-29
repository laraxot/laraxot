---
title: "Piano multitenant row-level per il gestionale — bozza da revisionare"
type: concept
tags: [multitenant, tenant-id, tenant-scope, plan, review]
created: 2026-08-29
updated: 2026-08-29
status: draft-for-review
reviewer: "Marco Sottana"
qmd: "piano multitenant row level tenant_id TenantScope InteractsWithTenant revisione marco sottana"
related:
  - "./delta-second-tranche.md"
  - "./porting-final-status.md"
---

# Piano multitenant (SOLO piano — nessuna implementazione in questa tranche)

> **Da revisionare con Marco Sottana prima di scrivere qualsiasi riga di codice tenancy.**
> Decisione già presa: multitenancy **row-level** (`tenant_id` + global scope), **solo dove serve** —
> non su tutte le tabelle.

## Meccanismo (riuso infrastruttura esistente, niente da ricreare)

La base ha già tutto nel modulo **User**:

| Componente | File | Comportamento |
|---|---|---|
| Trait modello | `laravel/Modules/User/app/Models/Traits/InteractsWithTenant.php` | relazione `tenant()` via `tenant_id`, tenant corrente da `Filament::getTenant()`, modello tenant configurabile via `config('tenant.tenant_model')` |
| Global scope | `laravel/Modules/User/app/Models/Scopes/TenantScope.php` | `where tenant_id = Filament::getTenant()->getKey()` — **no-op se nessun tenant Filament attivo** (console/test/seeding restano funzionanti) |
| Pivot utenti-tenant | `laravel/Modules/User/app/Models/TenantUser.php` | appartenenza utente↔tenant |
| Base tenant | `laravel/Modules/User/app/Models/BaseTenant.php` + `Modules/Tenant/app/Models/Tenant.php` | anagrafica tenant |

Il tenant corrente è risolto dal **Filament panel tenancy** (`Filament::getTenant()`): ogni panel di dominio
andrà configurato con `->tenant(Tenant::class)` quando si attiverà la tenancy.

## Classificazione tabelle — "solo dove serve"

Criterio: `tenant-scoped` = dati operativi di una singola azienda; `globale` = lookup, configurazione,
anagrafiche di piattaforma; `da decidere` = dipende dal modello di business (domande sotto).

### Tenant-scoped (proposta)
Dati operativi per modulo — la lista tabella-per-tabella verrà generata a piano approvato, la logica è:

- **WorkOrder**: commesse, righe, stati
- **Customer**: clienti, contatti, note
- **Quotation**: preventivi e righe
- **Billing**: fatture attive/passive, DDT, scadenze, incassi
- **Intervention**: interventi, rapportini, note giornaliere, tariffe manodopera
- **HR**: dipendenti, documenti, visite, formazione, richieste assenza
- **Inventory / Bom / Production / Catalog** (prodotti propri): giacenze, distinte, ordini di produzione
- **Timber** (ex TimberBilling): lotti, essiccazioni, movimenti, listini, fatture timber
- **Wts**: conferimenti, lotti in ingresso, non conformità, audit
- **Vehicle**: mezzi aziendali
- **Document / Signature**: documenti e firme legati a entità tenant-scoped
- **MetalWork / Signage / Costing / Compliance / EnergyBroker / PublicProcurement**: dati operativi dei verticali

### Globali (nessun tenant_id)
- Utenti (`users`) — cross-tenant via pivot `TenantUser`, NON colonna diretta (un utente può stare in più tenant)
- Ruoli/permessi spatie (salvo si voglia ruolo-per-tenant → domanda 3)
- Lookup/nomenclature condivise: specie legno? codici EER/ECOS? aliquote fiscali? unità di misura?
  (→ domanda 4: alcune potrebbero essere per-tenant)
- Config di piattaforma, moduli demo (Thermo/Giveback/Dds/AsdSoci) finché restano showcase

### Da decidere (→ domande)
- `Catalog` listini fornitori condivisi vs per-tenant
- `Platform`, `AiAssistant` (thread AI per-tenant? probabile sì)
- `Email` / `WhatsApp`: account di invio per-tenant (probabile sì: credenziali per azienda)

## Strategia migration (regole repo)

- MAI migration `add_*`: si **copia la `create_<table>_table` esistente con timestamp nuovo** e si aggiunge
  `tenant_id` dentro `tableUpdate` con guard `if (! $this->hasColumn('tenant_id'))`
- Colonna: `$table->foreignIdFor(Tenant::class)->nullable()->index()` — nullable in rollout per non rompere
  dati esistenti; backfill con seeder/command dedicato; poi eventuale `NOT NULL` in step successivo
- Nessun `down()`

## Rollout proposto (per modulo, non big-bang)

1. **Pilota**: 1 modulo piccolo e centrale (proposta: `Customer`) — trait + scope + migration + test isolamento
2. Verticale principale commesse: WorkOrder → Quotation → Billing → Intervention
3. Magazzino/produzione: Catalog → Inventory → Bom → Production
4. Verticali: Timber, Wts, MetalWork, Signage, HR, resto
5. Attivazione tenancy sui panel Filament (per panel, dietro verifica)

Ogni step: test Pest di isolamento **prima** (2 tenant, 1 record ciascuno → query e List page Filament
vedono solo il proprio), poi migration+trait, PHPStan zero, commit atomico.

## Test di isolamento (schema standard)

```php
it('scopes records to the current tenant', function (): void {
    [$a, $b] = Tenant::factory()->count(2)->create();
    $recA = Customer::factory()->create(['tenant_id' => $a->id]);
    Customer::factory()->create(['tenant_id' => $b->id]);
    actingAsTenant($a); // helper da creare: imposta Filament::setTenant($a)
    expect(Customer::query()->pluck('id'))->toEqual(collect([$recA->id]));
});
```

## ⚠️ Rischi noti da valutare in revisione

1. `TenantScope` è un no-op fuori da Filament (console, code, API): le query CLI vedono TUTTI i tenant.
   Serve decisione: accettabile (job di piattaforma) o serve un resolver tenant anche fuori da Filament?
2. Unique constraint esistenti (es. `code` univoco su customers) vanno rivisti in `unique(tenant_id, code)`
3. Relazioni cross-modulo: entrambi i lati devono essere dello stesso tenant — valutare validazione a livello
   di Action (non solo scope in lettura)
4. Backfill dati esistenti: a quale tenant si assegnano i dati già presenti?

## Domande aperte per Marco

1. **Cos'è un tenant?** Un'azienda cliente del gestionale (SaaS multi-azienda) o una sede/divisione interna?
2. **Utenti cross-tenant**: un utente può operare su più tenant (consulente, commercialista)? → conferma
   modello pivot `TenantUser` vs colonna diretta
3. **Ruoli per-tenant**: i ruoli spatie devono essere scoping-ati per tenant (`team_id` di spatie/permission)
   o globali?
4. **Lookup condivise**: specie legno, codici ECOS/EER, tariffe — globali o personalizzabili per tenant?
5. **Verticali demo** (Thermo/Giveback/Dds/AsdSoci): restano showcase globali o diventano prodotti per-tenant?
6. **Dati storici**: a quale tenant assegnare i dati esistenti al momento dell'attivazione?
7. **Fuori-Filament** (rischio 1): serve isolamento anche per comandi console/API/job?
