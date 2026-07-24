---
title: "Priorità module.json e isolamento test — 16 moduli gestionale_commesse"
type: concept
tags: [import, gestionale-commesse, module-priority, nwidart, testing, sqlite]
created: 2026-07-24
updated: 2026-07-24
qmd: "module.json priority dependency chain 16 moduli gestionale_commesse Pest testing sqlite memory isolamento"
related:
  - "../wiki/concepts/laraxot-module-canonical-structure.md"
  - "../gestionale-commesse-comparison/import-status.md"
---

# Priorità `module.json` e isolamento test — moduli portati da `gestionale_commesse`

## 1. Grafo delle dipendenze (verificato via `use Modules\...\` nel codice, non solo dedotto)

Verifica fatta con grep reale delle `use` statement in `app/` di ciascun modulo (non solo sulla
descrizione nel prompt), per catturare dipendenze non documentate nei `docs/gestionale-commesse-port.md`
dei singoli moduli (es. `Production`/`Inventory` dipendono anche da `WorkOrder`, non solo da `Catalog`).

```
Livello 0 (nessuna dipendenza tra i 16 nuovi moduli):
  Catalog, Customer, Signature, Vehicle

Livello 1:
  WorkOrder      → Customer
  EnergyBroker   → Customer

Livello 2:
  Bom            → Catalog
  Quotation      → Customer, Catalog
  Production     → Catalog, WorkOrder            (⚠ non solo Catalog: usa Modules\WorkOrder\Models\WorkOrder)
  Inventory      → Catalog, WorkOrder             (⚠ non solo Catalog: usa Modules\WorkOrder\Models\WorkOrder)
  Compliance     → Customer, WorkOrder

Livello 3:
  Intervention   → WorkOrder, Signature, Catalog, Quotation, Media
  Fiscal         → Quotation (Money value object, per Modules/Fiscal/docs)

Livello 4:
  Billing        → Customer, WorkOrder, Fiscal
  TimberBilling  → Customer, Catalog, Fiscal, Quotation

Livello 5:
  PublicProcurement → Billing, Customer, WorkOrder
```

## 2. Convenzione `priority` osservata nel repo

Nessun file `docs/wiki/rules/module-providers-manifest.md` esiste effettivamente (citato come fonte in
`laraxot-module-canonical-structure.md` ma assente su disco — link rotto, non risolto in questa sessione
perché fuori scope). La convenzione reale si deduce dai valori esistenti nei `module.json`:

| Modulo | priority | Ruolo |
|---|---|---|
| `Xot` | 2 | fondazionale |
| `Tenant` | 2 | fondazionale |
| `Cms` | 3 | early |
| `Lang` | 4 | early |
| `Blog` | 10 | applicativo |
| `Job` | 20 | applicativo tardivo |
| `Comment` | 50 | tardivo, dipende da molti moduli |
| tutti gli altri (incl. i 16 nuovi) | 0 | nessun ordine esplicito |

`priority` basso = caricato prima (nwidart-laravel-modules ordina i provider in modo ascendente per
priority). Per non collidere con la fascia bassa già occupata (2–50, riservata a moduli
fondazionali/infrastrutturali), i 16 moduli sono stati piazzati in una fascia dedicata 100–150, un
livello per fascia di dipendenza:

| Priority | Moduli |
|---|---|
| 100 | Catalog, Customer, Signature, Vehicle |
| 110 | WorkOrder, EnergyBroker |
| 120 | Bom, Quotation, Production, Inventory, Compliance |
| 130 | Intervention, Fiscal |
| 140 | Billing, TimberBilling |
| 150 | PublicProcurement |

Applicato a tutti i 16 `module.json` (via lock/check/unlock protocol, uno alla volta, tutti risultati
`FREE` — nessuna collisione con altri agenti).

Verificato con `php artisan module:list` (priorità riflesse correttamente) e `php artisan about`
(bootstrap applicativo completo, nessuna eccezione, tutti i driver/pacchetti Filament/Livewire/Pulse
correttamente inizializzati) — la modifica **non rompe il boot dell'app**.

## 3. Test combinati: la causa reale dei 7 fallimenti "no such table: products"

### Ipotesi iniziale (da verificare)
L'ipotesi di partenza era che l'ordine di boot/migrazione dei moduli (`priority`) determinasse quali
migrazioni fossero disponibili durante l'esecuzione combinata di Pest su più `tests/` di moduli diversi,
e che assegnare priorità corrette a Catalog (< Bom/Production) avrebbe risolto il problema.

### Verifica sperimentale
Dopo aver assegnato le priorità corrette (Catalog=100 < Bom/Production=120), è stato rieseguito:

```bash
./vendor/bin/pest Modules/Customer/tests Modules/WorkOrder/tests Modules/Intervention/tests \
  Modules/Catalog/tests Modules/Quotation/tests Modules/Billing/tests Modules/Fiscal/tests \
  Modules/Inventory/tests Modules/Bom/tests Modules/Production/tests Modules/Signature/tests \
  Modules/Vehicle/tests --no-coverage
```

Risultato: **stessi 7 fallimenti**, identico errore `SQLSTATE[HY000]: no such table: products`.

Per isolare la variabile, è stato rieseguito **solo** `Modules/Bom/tests` (nessun altro modulo, nessuna
combinazione):

```bash
./vendor/bin/pest Modules/Bom/tests --no-coverage
# → 2 failed, stesso identico errore "no such table: products"
```

**Questo smentisce l'ipotesi di partenza**: se il problema fosse l'ordine di boot/migrazione in
esecuzione combinata, i test di `Bom` eseguiti da soli sarebbero dovuti passare (come indicato nel
prompt originale). Non è così nell'ambiente in cui è stata condotta questa verifica: falliscono anche
in isolamento.

### Causa reale identificata

Ispezionando la catena di bootstrap dei test:

1. `Modules/Xot/tests/CreatesApplication.php` carica l'applicazione Laravel **reale e completa** da
   `bootstrap/app.php` (non un'app minimale stile Orchestra Testbench) — quindi tutti i moduli
   `Enabled` vengono comunque registrati (i loro `ServiceProvider::boot()` chiamano
   `loadMigrationsFrom()`), indipendentemente da `priority` o da quali `tests/` vengono passati a Pest.
   Questo di per sé rende ininfluente la `priority` per l'esecuzione dei test (la `priority` conta per
   l'ordine di boot in produzione, non cambia quali migrazioni Laravel "conosce" durante i test).
2. `Modules\Xot\Tests\XotBaseTestCase` estende `Illuminate\Foundation\Testing\TestCase` **direttamente**
   (non `Orchestra\Testbench\TestCase`), pur avendo `orchestra/testbench` installato come dipendenza.
   Il metodo `getPackageProviders()` presente in `XotBaseTestCase`/nei `TestCase` di modulo (es.
   `Modules/Bom/tests/TestCase.php`) è una convenzione tipica di Orchestra Testbench ma **non viene mai
   invocato** da `Illuminate\Foundation\Testing\TestCase` — è codice morto in questo contesto.
3. Nessun meccanismo nella codebase esegue effettivamente `php artisan migrate` (o equivalente) contro
   la connessione di test. Verificato con grep esaustivo su:
   - tutti i `Providers/*ServiceProvider.php` dei moduli e di `Xot`/`Tenant`;
   - `phpunit.xml` (nessun `<extensions>`, solo `<env>` che forza
     `DB_CONNECTION=sqlite`, `DB_DATABASE=:memory:`);
   - `composer.json` (script `test` fa solo `artisan config:clear` + `artisan test`, nessun `migrate`);
   - tutti i `tests/Pest.php` di modulo e il root `tests/Pest.php` (nessun hook
     `beforeEach`/`beforeAll` che migra).
   - `config/testing.php` dichiara `'data' => ['auto_migrate' => true, ...]` ma **questa chiave non è
     letta da nessun codice applicativo** — è documentazione/intento, non un meccanismo attivo
     (dead config).
4. `Modules\Xot\Models\XotBaseModel` (classe base di **tutti** i model, incl. `Product`, `Bom`,
   `BomItem`) ha `protected $connection = 'xot';` hard-coded. Con SQLite `:memory:`, ogni connessione
   Laravel (per nome) apre un PDO **indipendente e vuoto**: dato che nessuna migrazione viene mai
   eseguita esplicitamente su questa connessione durante Pest, la tabella richiesta (`products`, ma in
   realtà qualunque tabella) non esiste mai, a prescindere da quali moduli/tests vengono eseguiti insieme
   o da soli.

In sintesi: **il fallimento non è causato dall'ordine di priorità/migrazione tra moduli**, ma dall'assenza
di un passo di migrazione effettivo per la connessione SQLite `:memory:` usata dai test in questo
ambiente. La regola scritta in `docs/wiki/rules/testing_database_refresh.md` ("mai `RefreshDatabase`,
sempre SQLite `:memory:` + configurazione dedicata") presuppone un meccanismo di migrazione automatica
che, per come risulta ispezionando il codice attuale, **non è cablato da nessuna parte**: `auto_migrate:
true` in `config/testing.php` è un'intenzione documentata ma non un comportamento implementato.

### Perché il prompt originale riportava "i singoli moduli passano da soli"

Non è stato possibile riprodurre questa condizione in questa sessione (Bom fallisce anche da solo). Le
spiegazioni plausibili, non verificabili a ritroso in questa sessione:
- una sessione precedente potrebbe aver eseguito manualmente `php artisan migrate` con
  `DB_DATABASE` puntata a un file SQLite persistente (non `:memory:`) prima di lanciare Pest sul singolo
  modulo, oppure
- una divergenza di ambiente (es. variabile `DB_CONNECTION`/`DB_DATABASE` effettivamente diversa da
  quella vista in questa sessione) tra la sessione che ha originato il ticket e questa.

Questo non cambia la conclusione operativa: la causa profonda è l'assenza di un meccanismo di
migrazione test-time, non l'ordine `priority` dei moduli.

## 4. Esito e raccomandazione

- **Priorità `module.json` corrette e mantenute**: riflettono correttamente la catena di dipendenze reale
  (verificata via grep del codice, non solo della documentazione) e sono corrette/necessarie di per sé
  per l'ordine di boot in produzione (nwidart carica provider e migration path in ordine di `priority`).
  Verificate con `php artisan module:list` e `php artisan about` senza regressioni.
- **Non risolvono e non potevano risolvere** i 7 fallimenti "no such table: products": causa radice
  diversa e indipendente (nessun passo di migrazione attivo per la connessione test SQLite `:memory:`).
- **Limitazione accettata per questa sessione**: implementare un meccanismo di migrazione test-time
  (es. un hook in `XotBaseTestCase::setUp()` che esegue `Artisan::call('migrate', ['--database' =>
  'xot', '--path' => ...])` una sola volta per processo, oppure adottare `Orchestra\Testbench\TestCase`
  come base reale per sfruttare `getPackageProviders()`/`defineDatabaseMigrations()` già scritti nei
  `TestCase` di modulo, oggi inerti) è un cambiamento strutturale all'infrastruttura di test condivisa da
  **tutti** i moduli del monorepo (non solo i 16 portati), fuori dallo scope di questo task (assegnazione
  `priority` ai 16 moduli). Va aperto come issue/discussion separata sul modulo `Xot` (proprietario di
  `XotBaseTestCase`), perché tocca una convenzione trasversale (`docs/wiki/rules/testing_database_refresh.md`)
  e non un singolo modulo applicativo.
