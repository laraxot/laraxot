# Import status — MetalWork

Modulo **nuovo** della seconda tranche (sorgente `gestionale_commesse/Modules/MetalWorks`).

## Rinomina e riorganizzazione

`MetalWorks` → **`MetalWork`** (regola del nome modulo al singolare). Con il nome sono
stati allineati il prefisso delle tabelle (`metalwork_*`), la chiave di config, l'id e
il path del pannello (`/metalwork`) e i ruoli (`metalwork_admin`, `metalwork_operator`).

| Sorgente (DDD-lite) | Destinazione (canonica Laraxot) |
|---|---|
| `Application/Actions` | `app/Actions` |
| `Application/DTOs` | `app/Datas` |
| `Application/Support` | `app/Support` |
| `Infrastructure/Models` | `app/Models` (estendono `XotBaseModel`) |
| `Database/Migrations` (2 file, 7 tabelle) | 6 migrazioni `XotBaseMigration`, una per tabella |
| `Filament/*` | `app/Filament/*` (resource su `GatedXotBaseResource`) |
| `Resources/views` | `resources/views` |

I centri di lavoro **non** sono nel modulo: vivono nella tabella condivisa del modulo
`Costing`, filtrati per contesto. La migrazione sorgente che spostava le righe dalle
tabelle per-modulo non serve qui, perché il modulo nasce già sulla tabella condivisa.

## Adattamenti

- Le resource avevano `form(Schema)`: convertite in `getFormSchema(): array` come
  richiede `XotBaseResource`. `MetalDrawingResource` e `MetalPartEstimateResource`
  restituiscono uno schema vuoto ed è corretto: disegni e stime non si compilano a
  mano, entrano dall'import e dal calcolo.
- La dashboard si dichiara non scopribile e va montata a mano nel pannello: un panel
  senza pagina sulla radice ricade sul redirect di Filament e manda l'utente sulla
  prima resource che capita.
- `config/metalwork.php` senza `env()` (Larastan lo vieta fuori dalla config di root);
  il provider registra l'alias `metalwork.*` perché XotBase lo esporrebbe come
  `metalwork.metalwork.*` — stesso schema già usato da Catalog per `metel`.
- Nuova dipendenza: **`smalot/pdfparser`**, necessaria a leggere le fatture fornitore.

## Correzioni fatte strada facendo

- Le funzioni `Safe\*` (regola PHPStan del progetto) hanno reso morti i controlli
  `=== false` sui parser e più stretti i tipi di `$matches`: rimossi i controlli
  morti e resi difensivi gli accessi ai gruppi catturati.
- `StepSolidExtractor` riusava `$reference` sia come array di match sia come stringa
  nel ciclo: con `Safe\preg_match` questo diventa un TypeError. Rinominata la
  variabile del ciclo.
- PHPDoc `@property` generati per tutti i modelli dalle migrazioni.

Test: **42 verdi** (parser STEP, distinta, costo pezzo, import fatture, pagine del
pannello). PHPStan: **zero errori sul codice di produzione** (erano 165).
