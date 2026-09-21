---
title: "XotBaseResourceTable: $model esplicito + audit colonne — batch 6 file core Xot"
type: story
module: Xot
epic: null
story_id: null
slug: xotbaseresourcetable-model-audit-batch-xot-modeltable-marco
status: done
created: '2026-09-11'
updated: '2026-09-11'
repository: "https://github.com/laraxot/module_xot_fila5.git"
github_issue: "https://github.com/laraxot/module_xot_fila5/issues/118"
github_discussion: null
estimated_effort: "1 turno, 6 file"
blocked_by: []
blocks: []
supersedes: []
owned_scope:
  - "laravel/Modules/Xot/app/Filament/Resources/CacheLockResource/Tables/CacheLocksTable.php"
  - "laravel/Modules/Xot/app/Filament/Resources/CacheResource/Tables/CachesTable.php"
  - "laravel/Modules/Xot/app/Filament/Resources/ExtraResource/Tables/ExtrasTable.php"
  - "laravel/Modules/Xot/app/Filament/Resources/LogResource/Tables/LogsTable.php"
  - "laravel/Modules/Xot/app/Filament/Resources/ModuleResource/Tables/ModulesTable.php"
  - "laravel/Modules/Xot/app/Filament/Resources/SessionResource/Tables/SessionsTable.php"
related:
  - "laravel/Modules/Xot/docs/stories/xotbaseresourcetable-model-property-and-column-audit.story.md"
  - "laravel/Modules/Xot/docs/stories/xotbaseresourcetable-model-property-audit.story.md"
---

# XotBaseResourceTable — batch 6 file core Xot: `$model` + audit colonne

## Story

Batch isolato (lock/unlock per file) della story cross-modulo
`xotbaseresourcetable-model-property-and-column-audit.story.md` (fase 1b +
fase 2), limitato ai 6 file `*Table` del modulo Xot elencati in
`owned_scope`: `CacheLocksTable`, `CachesTable`, `ExtrasTable`, `LogsTable`,
`ModulesTable`, `SessionsTable`.

## Second brain interrogato prima

[[xot-baseresourcetable-no-table-override]] (Template Method rispettato,
solo `getTableColumns()` toccato), [[xotbaseresourcetable-orphaned-columns-git-archaeology]]
(mai cancellare una colonna senza verifica contro lo schema reale — applicato
qui: nessuna colonna rimossa, il caso `cache` sospetto è solo segnalato),
[[psr4-skipping-referenziata-o-no]] (prova di vivo/morto = uso reale, non il
grep — qui non applicabile, tutte e 6 le classi sono referenziate dalla
Resource sorella `{Model}Resource::table()`), [[array-una-chiave-per-riga]]
(rispettato in ogni modifica).

## Task 1 — `protected static string $model`

All'apertura del batch, le modifiche di Task 1 erano già presenti nel
working tree (non ancora committate) su tutti e 6 i file — probabilmente
lavoro di una sessione precedente sullo stesso topic. Verificate una per una
contro la Resource sorella (fonte autorevole, mai dedotto dal nome file):

| File | `$model` | Confermato da |
|---|---|---|
| `CacheLocksTable.php` | `CacheLock::class` | `CacheLockResource::$model = CacheLock::class` |
| `CachesTable.php` | `Cache::class` | `CacheResource::$model = Cache::class` |
| `ExtrasTable.php` | `Extra::class` | `ExtraResource::$model = Extra::class` |
| `LogsTable.php` | `Log::class` | `LogResource::$model = Log::class` |
| `ModulesTable.php` | `Module::class` | `ModuleResource::$model = Module::class` |
| `SessionsTable.php` | `Session::class` | `SessionResource::$model = Session::class` |

Tutte corrette, nessuna correzione necessaria.

## Task 2 — Audit colonne (`Schema::getColumnListing`, sola lettura)

Eseguito da `laravel/` con `php artisan tinker --execute="..."`
(`Schema::connection(...)->getColumnListing($table)`, per i due model Sushi
via `$model->getConnection()->getSchemaBuilder()->getColumnListing($table)`
dopo aver forzato la build in-memory con una query).

| Model | Tabella | Colonne reali | Colonne in getTableColumns() | Esito |
|---|---|---|---|---|
| `CacheLock` | `cache_locks` | key, owner, expiration | key, owner, expiration | OK |
| `Cache` | `cache` | **tabella assente** | key, expiration | vedi nota sotto |
| `Extra` | `extras` | id, model_type, model_id, extra_attributes, created_at, updated_at, updated_by, created_by, deleted_at, deleted_by | model_type, model_id, extra_attributes, id, updated_at, created_at | OK |
| `Log` (Sushi) | `logs` (sqlite in-memory) | id, name, size | name, size | OK |
| `Module` (Sushi) | `modules` (sqlite in-memory) | id, name, description, status, priority, path, icon, colors | name, description, status, priority, path | OK |
| `Session` | `sessions` | id, user_id, ip_address, user_agent, payload, last_activity, created_at, updated_at, updated_by, created_by, deleted_at, deleted_by | user_id, ip_address, last_activity, user_agent, id | OK |

### Nota — `Cache` / tabella `cache` assente

`Schema::connection('xot')->hasTable('cache')` → `no`, stessa
`host`/`database` (`quaeris_data`) della connessione `mysql` di default.
`php artisan migrate:status` segnala **due** migration "Ran" per la stessa
tabella (`2023_09_04_000000_create_cache_table` e
`2023_09_04_125039_create_cache_table`, schema identico: `key` PK,
`value` mediumText, `expiration` integer) — probabile quasi-duplicato
storico, non causa dell'assenza. `cache_locks` invece esiste regolarmente.
Ipotesi più probabile: la tabella è stata droppata manualmente in questo
ambiente perché l'app usa `CACHE_STORE=file` (cache DB mai realmente
usata) — non verificabile con certezza senza scrivere sul DB, quindi non
investigato oltre (vietate operazioni di scrittura).

Le colonne `key`/`expiration` usate in `CachesTable::getTableColumns()`
**sono corrette** rispetto alla definizione di migrazione (key, value,
expiration) — nessuna modifica al codice, nessuna colonna rimossa. Segnalo
solo l'anomalia ambientale (tabella assente) per chi ha accesso a fare
manutenzione DB — **non è un task per questo batch** (nessuna scrittura
DB consentita).

Nessun'altra colonna sospetta: 0 mismatch reali su 5/6 model verificabili.

## Task 3 — Migliorie UX (additive, basso rischio)

- `ModulesTable.php`: `description` → aggiunto `->searchable()` (era solo
  `->wrap()->limit(100)`; campo testo libero, ricerca coerente con `name`
  già searchable nello stesso file).
- `SessionsTable.php`: `ip_address` → aggiunto `->sortable()` (era solo
  `->searchable()`); `user_agent` → aggiunto `->searchable()` (era solo
  `->limit(60)->toggleable()`).

Nessun'altra modifica: gli altri file avevano già `searchable()`/`sortable()`
dove sensato e formattazione data (`->dateTime()`, `formatStateUsing` con
`Carbon`) già presente — nessun intervento a basso rischio aggiuntivo
identificato con certezza.

## Verifica

- `php -l` pulito su tutti e 6 i file.
- `cd laravel && vendor/bin/phpstan analyse <6 file> --no-progress` →
  `[OK] No errors`.

## Dev Agent Record

### Agent Model Used

Claude Sonnet 5

### Completion Notes List

- 2026-09-11: batch completato, 6/6 file verificati (Task 1 già presente e
  confermato corretto, Task 2 zero mismatch reali + 1 anomalia ambientale
  segnalata su `cache`, Task 3 due migliorie additive su 2 file), phpstan
  pulito, commit + push su `laraxot/dev`, issue GitHub #118 aggiornata con
  commento di dettaglio.
