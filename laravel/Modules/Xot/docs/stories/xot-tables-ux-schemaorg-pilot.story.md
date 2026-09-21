---
title: "Pilota UI/UX getTableColumns() su 6 Table native di Xot (schema.org dove pertinente)"
type: story
module: Xot
epic: null
story_id: null
slug: xot-tables-ux-schemaorg-pilot
status: review
cold_gate: null
created: '2026-09-11'
updated: '2026-09-11'
repository: "https://github.com/laraxot/module_xot_fila5.git"
github_issue: null
github_discussion: null
estimated_effort: "2-4h (ambito volutamente piccolo, pilota)"
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
  - "laravel/Modules/Xot/docs/stories/xotbaseresourcetable-model-property-and-column-audit.story.md (contesto/motivazione: questa story e' la fase 3, applicata a un ambito piccolo e sicuro invece che alle 96 classi intere)"
  - "laravel/Modules/Xot/docs/stories/xotbaseresourcetable-model-audit-batch-xot-modeltable-marco.story.md (status: done, github_issue #118 — stesso owned_scope esatto, gia' fatta fase $model+audit+2 piccole migliorie searchable/sortable su Modules/Sessions; questa story estende con UI/UX piu' ricca — since()/dateTimeTooltip nativi, badge/color, wrap mancanti, Number::fileSize — non duplica, commentato su issue #118)"
  - "laravel/Modules/UI/app/Filament/Tables/Columns/PersonColumn.php (pattern schema.org/Person gia' in repo, riferimento — non applicabile a questi 6 model)"
  - "laravel/Modules/Xot/app/Filament/Support/ColumnBuilder.php (helper di colonne riusabili gia' in repo — riferimento, non forzato in questa story perche' non copre colonne timestamp-int custom)"
---

# Pilota UI/UX getTableColumns() su 6 Table native di Xot (schema.org dove pertinente)

## Story

Come manutentore, voglio migliorare la UI/UX di `getTableColumns()` sulle 6
classi Table native del modulo Xot stesso (Cache, CacheLock, Extra, Log,
Module, Session), verificando ogni colonna contro lo schema DB reale prima
di toccarla e applicando schema.org solo dove i model rappresentano
concetti di dominio a cui schema.org si applica davvero, cosi' da avere un
esempio concreto, piccolo e verificato che un'altra sessione possa
replicare sulle restanti ~90 classi `{Model}Table` del monorepo (vedi
`xotbaseresourcetable-model-property-and-column-audit.story.md`, che ha
gia' fatto fase 1 — `$model` esplicito su tutte — e fase 2 — nessun
mismatch di colonne rilevato su queste 6).

## Contesto / Baseline

Le 6 classi in scope hanno gia' `protected static string $model` esplicito
(fatto nella story collegata, fase 1, sessione precedente) — verificato
leggendo i 6 file prima di iniziare:
`CacheLocksTable::$model = CacheLock::class`,
`CachesTable::$model = Cache::class`,
`ExtrasTable::$model = Extra::class`,
`LogsTable::$model = Log::class`,
`ModulesTable::$model = Module::class`,
`SessionsTable::$model = Session::class`.

Schema DB reale verificato con `php artisan tinker` (mai assunto dal
docblock del model, che puo' mentire — vedi second brain
`xotbaseresourcetable-orphaned-columns-git-archaeology`):

- `CacheLock` → tabella `cache_locks` (connessione `xot`, mysql reale):
  colonne `key, owner, expiration`. `getTableColumns()` referenzia
  esattamente queste 3, zero orfane.
- `Cache` → tabella `cache` (connessione `xot`): la migration
  (`2023_09_04_125039_create_cache_table.php`) definisce
  `key, value, expiration`, ma **la tabella fisica non esiste nel DB reale
  di questo ambiente** (`SQLSTATE[42S02]: Base table ... 'quaeris_data.cache'
  doesn't exist`) nonostante `migrate:status` la segni `Ran`. Non e' un
  difetto di questa story (non tocco migration/infra), lo segnalo come
  scoperta per chi seguira': le colonne usate in `getTableColumns()`
  (`key`, `expiration`) sono comunque quelle vere per definizione di
  migration, `value` e' correttamente omesso (payload potenzialmente
  grande, non da mostrare in lista).
- `Extra` → tabella `extras` (connessione `xot`): colonne
  `id, model_type, model_id, extra_attributes, created_at, updated_at,
  updated_by, created_by, deleted_at, deleted_by`. `getTableColumns()`
  referenzia `model_type, model_id, extra_attributes, id, updated_at,
  created_at` — tutte reali, zero orfane. `updated_by/created_by/
  deleted_at/deleted_by` esistono ma non sono mostrate: scelta consapevole,
  non aggiunte in questa story (vedi "Esplicitamente fuori scope").
- `Log` → model Sushi (tabella virtuale sqlite generata da `getRows()`, non
  MySQL). Verificato forzando una query e leggendo lo schema
  dall'istanza (`(new Log())->getConnection()->getSchemaBuilder()
  ->getColumnListing('logs')` dopo un `Log::all()`): colonne reali
  `id, name, size`. `getTableColumns()` referenzia `name, size`, zero
  orfane.
- `Module` → model Sushi (tabella virtuale, connessione dinamica per
  classe): colonne reali verificate allo stesso modo:
  `id, name, description, status, priority, path, icon, colors`.
  `getTableColumns()` referenzia `name, description, status, priority,
  path` — tutte reali, zero orfane. `icon`/`colors` esistono ma non sono
  mostrate (vedi "Esplicitamente fuori scope").
- `Session` → tabella `sessions` (connessione `xot`, mysql reale, migration
  custom che estende la tabella standard Laravel con
  `updateTimestamps($table, true)`): colonne reali
  `id, user_id, ip_address, user_agent, payload, last_activity,
  created_at, updated_at, updated_by, created_by, deleted_at, deleted_by`.
  `getTableColumns()` referenzia `user_id, ip_address, last_activity,
  user_agent, id` — tutte reali, zero orfane. `payload` correttamente
  omesso (blob di sessione, non da mostrare in lista).

**Esito verifica colonne orfane: zero su tutte e 6 le classi.** Nessuna
rimozione necessaria in questa story.

**Schema.org — valutazione onesta**: i 6 model sono infrastruttura tecnica
(cache, lock, sessione HTTP, log file, extra-attributes polimorfici,
modulo applicativo), non entita' di dominio. Schema.org non definisce tipi
per nessuno di questi concetti in modo pertinente (non esiste un tipo
sensato per "voce di cache" o "lock distribuito"; `Session` non ha un tipo
schema.org standard riferito a sessioni HTTP autenticate; `SoftwareApplication`
esisterebbe per `Module` ma e' pensato per descrivere applicazioni/pacchetti
pubblicati, non moduli Laravel interni gestiti da `Nwidart\Modules` — forzarlo
avrebbe significato solo aggiungere `@type` decorativo senza beneficio UX
reale). Confermato con
`grep -rln "schema.org" Modules/Xot Modules/*/app/Filament/Tables/Columns`:
l'unico pattern reale in repo e' `PersonColumn`
(`Modules/UI/app/Filament/Tables/Columns/PersonColumn.php`), un aggregato
per entita' anagrafiche (persona) — non applicabile a nessuno di questi 6
model. **Decisione: schema.org non applicato in questa story, onestamente
dichiarato invece di forzarlo.**

## Acceptance Criteria

<!-- LOCKED. -->

1. Per ognuna delle 6 classi, le colonne di `getTableColumns()` sono
   verificate contro lo schema DB reale (comando eseguito + colonne
   riportate in Dev Notes) — nessuna rimozione senza certezza.
2. Miglioramenti UI/UX applicati solo dove giustificabili con un criterio
   esplicito (data/ora, booleano, testo lungo, colonna tecnica/id,
   searchable/sortable) — nessuna modifica arbitraria.
3. `php -l` + `phpstan analyse` (senza path, da `laravel/`) a 0 errori dopo
   ogni file toccato.
4. Suite Pest pertinente eseguita, nessuna regressione.
5. Sezione "pattern riusabile per domani" compilata con checklist
   applicabile alle restanti ~90 classi.
6. Commit + push solo dei file di questo scope (6 Table + questa story),
   sia nel repo modulo Xot sia nel repo root (mirror), su tutti i remoti
   configurati.

## Esplicitamente fuori scope

- Aggiungere nuove colonne non gia' presenti in `getTableColumns()` (es.
  `icon`/`colors` su `Module`, `created_by`/`updated_by`/`deleted_at`/
  `deleted_by` su `Extra`) — sarebbe una decisione di information
  architecture (cosa mostrare, non come migliorare cio' che c'e' gia'),
  fuori dal criterio "migliora dove sensato" di questa story pilota.
  Annotato come possibile follow-up, non implementato.
- Estrarre le colonne timestamp-int (`expiration`, `last_activity`) in un
  helper riusabile stile `ColumnBuilder` — `ColumnBuilder` esistente
  copre solo colonne Carbon-cast native (`created_at`/`updated_at`/
  `status` enum), non colonne intere con timestamp Unix grezzo; forzarlo
  avrebbe richiesto modificare un file fuori scope (`ColumnBuilder.php`,
  condiviso da altri moduli) per un pilota di 6 classi. Segnalato nel
  pattern riusabile sotto come idea per una story dedicata futura.
- Toccare `XotBaseResourceTable.php` o `XotBaseManageRelatedRecords.php`
  (esplicitamente vietato dal mandato).
- Fase 2/audit su altre classi (`{Model}Table` nelle restanti ~90) — resta
  backlog della story collegata.

## Tasks / Subtasks

<!-- LOCKED. Ogni riga mappata a un AC. -->

- [x] Verifica schema DB reale per i 6 model (AC: 1)
- [x] Confronto colonne vs `getTableColumns()` esistente, zero orfane (AC: 1)
- [x] Miglioramento colonne data/ora (`expiration`, `last_activity`) con
      `->since()`/`->dateTimeTooltip()` nativi Filament invece di
      `formatStateUsing` manuale (AC: 2)
- [x] Badge/colore su `expiration` (CacheLock, Cache) basato su
      `isPast()` — stato derivato oggettivo, non arbitrario (AC: 2)
- [x] `->wrap()` su colonne testo lunghe che avevano `limit()`/
      `toggleable()` ma non `wrap()` (`extra_attributes`, `user_agent`,
      `path`) (AC: 2)
- [x] Formattazione leggibile per `size` (byte → `Number::fileSize()`)
      su `LogsTable` (AC: 2)
- [x] Badge + basename leggibile per `model_type` (FQCN polimorfico) su
      `ExtrasTable`, con tooltip che preserva il valore completo (AC: 2)
- [x] `php -l` + `phpstan analyse` dopo ogni file (AC: 3)
- [x] Pest `Modules/Xot/tests` (AC: 4)
- [x] Sezione pattern riusabile (AC: 5)
- [x] Commit + push per file, modulo Xot + root, tutti i remoti (AC: 6)

## Dev Notes

<!-- LOCKED. Ogni affermazione con [Source: ...]. -->

- [Source: comando `php artisan tinker` interattivo, sessione 2026-09-11] —
  schema reale delle 6 tabelle, vedi "Contesto / Baseline" sopra per il
  dettaglio colonna-per-colonna.
- [Source: `laravel/vendor/filament/tables/src/Columns/Concerns/CanFormatState.php#L121-L184`]
  — `since()`, `dateTimeTooltip()`, `sinceTooltip()` usano tutti
  internamente `Carbon::parse($state)`; verificato con tinker che
  `Carbon::parse(<int unix timestamp>)` produce lo stesso risultato di
  `Carbon::createFromTimestamp()` — quindi i metodi nativi Filament sono
  applicabili direttamente alle colonne `expiration`/`last_activity` senza
  bisogno di `formatStateUsing` manuale come nel codice precedente.
- [Source: `laravel/Modules/Xot/app/Filament/Support/ColumnBuilder.php#L121-L134`]
  — pattern esistente `badge()->color(closure)` per colonne status, stile
  riusato qui per `expiration` (verde/rosso in base a `isPast()`), stessa
  convenzione di colori stringa (`'success'`/`'danger'`) gia' in uso nel
  modulo.
- [Source: `laravel/Modules/UI/app/Filament/Tables/Columns/PersonColumn.php`]
  — unico pattern schema.org reale nel repo (grep confermato), aggregato
  per `schema.org/Person`; non applicabile ai 6 model di questa story
  (infrastruttura tecnica, non entita' anagrafiche/di dominio).
- [Source: `laravel/Modules/Xot/database/migrations/2024_01_01_000001_create_sessions_table.php#L28-L38`]
  — la tabella `sessions` di questo progetto non e' lo schema Laravel
  standard: la migration chiama `$this->updateTimestamps($table, true)`,
  che aggiunge `created_at/updated_at/created_by/updated_by/deleted_at/
  deleted_by` oltre alle colonne standard — confermato anche via
  `Schema::getColumnListing` a runtime.
- [Source: `XDEBUG_MODE=coverage vendor/bin/pest Modules/Xot/tests --no-coverage`,
  sessione 2026-09-11, 262.22s] — risultato repo-wide (non causato da
  questa story): `119 failed, 32 risky, 5 todos, 23 skipped, 519 passed
  (2765 assertions)`. Nessun fallimento cita le 6 classi toccate o i
  model `Cache/CacheLock/Extra/Log/Module/Session` per nome (`grep` sul
  log completo, zero hit). Verificato nello specifico il test piu'
  vicino allo scope (`Modules/Xot/tests/Unit/XotExecuteCoverage50Test.php`,
  che itera le 6 Resource): 2 failure preesistenti, entrambe
  **strutturalmente indipendenti** da `getTableColumns()` —
  `assertNotEmpty($enumClass::getColumnDefinitions())` (un enum, non le
  nostre classi) e `assertNotEmpty($resource::getRelations())` a riga 536
  (itera le 6 Resource, ma testa `getRelations()`, mai toccato). Confermato
  con una query diretta via tinker che **tutte e 6** le Resource
  (`CacheResource`, `CacheLockResource`, `LogResource`, `ModuleResource`,
  `SessionResource`, `ExtraResource`) restituiscono `getRelations()`
  vuoto — comportamento strutturale legittimo (sono risorse tecniche
  senza relation manager), non causato da nessuna modifica di questa
  story. **Nessuna regressione introdotta.**

## Testing

<!-- LOCKED. -->

- `php -l` su ognuno dei 6 file dopo la modifica: atteso "No syntax errors
  detected".
- `vendor/bin/phpstan analyse` (da `laravel/`, **senza path** — l'unico
  comando che certifica repo-wide, vedi second brain
  `phpstan-path-cli-spegne-type-coverage`): atteso 0 errori, uguale alla
  baseline misurata prima di iniziare.
- `XDEBUG_MODE=coverage vendor/bin/pest Modules/Xot/tests --no-coverage`
  (da `laravel/`): atteso nessuna nuova regressione rispetto alla baseline
  della suite (la suite ha gia' fallimenti preesistenti non legati a
  questa story, si confronta il set di failure, non lo zero assoluto —
  vedi second brain `env-sqlite-manca-suite-non-eseguibile`).

## Dependency Maps

Dipende (informativamente, non `blocked_by` meccanico) dal completamento
della fase 1 di `xotbaseresourcetable-model-property-and-column-audit.story.md`
(le 6 classi dovevano gia' avere `$model` esplicito — verificato presente
prima di iniziare, non rifatto qui). Non blocca nessuna story esistente;
sblocca concettualmente la fase 3 per gli altri moduli fornendo un esempio
verificato.

## Owned File/Module Scope

Solo le 6 classi Table elencate in `owned_scope` + questo file story. Non
tocca `XotBaseResourceTable.php`, `XotBaseManageRelatedRecords.php`,
`ColumnBuilder.php`, ne' alcun model (`CacheLock`, `Cache`, `Extra`, `Log`,
`Module`, `Session` letti ma non modificati), ne' migration, ne' `.env`.

## Learnings from Previous Stories

- [[xotbaseresourcetable-orphaned-columns-git-archaeology]] — verificare
  sempre contro lo schema reale prima di toccare/rimuovere una colonna;
  applicato qui verificando via tinker invece di fidarsi dei docblock.
- [[personcolumn-schema-org-aggregate]] — pattern schema.org reale gia' in
  repo, usato come riferimento per concludere (onestamente) che qui non si
  applica.
- [[xot-baseresourcetable-no-table-override]] — rispettato: nessuna
  modifica a `table()`, solo a `getTableColumns()`.
- Dalla story collegata (`xotbaseresourcetable-model-property-and-column-audit`):
  fase 1 (`$model` esplicito) gia' fatta su queste 6 classi da una sessione
  precedente — verificato presente, non riaperto.

## Dev Agent Record

### Agent Model Used

Claude Sonnet 5

### Completion Notes List

- 2026-09-11: story creata con scoperta iniziale (schema reale delle 6
  tabelle via tinker, zero colonne orfane, schema.org non pertinente),
  prima di scrivere codice.
- 2026-09-11: implementate le modifiche alle 6 classi, verificate con
  `php -l` + `phpstan analyse` (0 errori) dopo ogni file, eseguita suite
  Pest `Modules/Xot/tests`, commit + push modulo Xot e mirror root.

### File List

- `laravel/Modules/Xot/app/Filament/Resources/CacheLockResource/Tables/CacheLocksTable.php` (modificato)
- `laravel/Modules/Xot/app/Filament/Resources/CacheResource/Tables/CachesTable.php` (modificato)
- `laravel/Modules/Xot/app/Filament/Resources/ExtraResource/Tables/ExtrasTable.php` (modificato)
- `laravel/Modules/Xot/app/Filament/Resources/LogResource/Tables/LogsTable.php` (modificato)
- `laravel/Modules/Xot/app/Filament/Resources/ModuleResource/Tables/ModulesTable.php` (modificato)
- `laravel/Modules/Xot/app/Filament/Resources/SessionResource/Tables/SessionsTable.php` (modificato)
- `laravel/Modules/Xot/docs/stories/xot-tables-ux-schemaorg-pilot.story.md` (nuovo)

---

## Pattern riusabile per domani (checklist per le altre ~90 classi)

Per ogni classe `{Model}Table extends XotBaseResourceTable`:

1. **Verifica `$model`**: deve gia' esistere `protected static string $model`
   (fase 1 della story collegata). Se manca, e' fuori scope per una story
   di solo UX — segnalarlo, non aggiungerlo "di passaggio".
2. **Verifica schema reale, mai il docblock**: `php artisan tinker` →
   istanzia il model, `$m->getConnection()->getSchemaBuilder()
   ->getColumnListing($m->getTable())`. Per model **Sushi**: forzare prima
   una query (`Model::all()`) e usare **una nuova istanza** del model per
   leggere la connessione (la connessione Sushi e' dinamica per istanza,
   una `Schema::connection($nomeVecchio)` presa da un'istanza precedente
   puo' risultare vuota).
3. **Confronta con `getTableColumns()` esistente**: colonna per colonna.
   Rimuovi SOLO se hai certezza (non e' un accessor, non e' una relazione
   dot-notation, non e' in una migration recente non ancora deployata). In
   caso di dubbio: lascia e annota il dubbio nella story, non cancellare.
4. **Applica i criteri concreti, uno per uno, solo se il tipo di colonna
   lo giustifica**:
   - Colonna con timestamp Unix intero grezzo (non Carbon-cast) → verifica
     prima con tinker che `Carbon::parse($valoreIntero)` produca la data
     corretta (di solito si', Carbon interpreta int come epoch), poi usa
     `->since()` + `->dateTimeTooltip()` nativi invece di
     `formatStateUsing` manuale. Se rappresenta una scadenza/validita'
     oggettivamente verificabile (`isPast()`), valuta `->badge()->color()`
     verde/rosso — solo se la soglia e' oggettiva (es. "e' gia' passata"),
     mai una soglia arbitraria inventata.
   - Colonna booleana → `IconColumn::make()->boolean()`.
   - Colonna enum/stato con un set di valori noto e finito → `->badge()
     ->colors([...])` o `->color(closure)`.
   - Colonna testo potenzialmente lunga (FQCN, path, JSON, user agent,
     descrizioni) → verifica che abbia **tutti e tre**: `->limit(N)`,
     `->wrap()`, `->toggleable()` (spesso manca solo `wrap()`, facile da
     dimenticare). Per FQCN/classi polimorfiche, valuta
     `formatStateUsing(fn ($s) => class_basename($s))` + `->tooltip()`
     col valore pieno, cosi' non perdi informazione.
   - Colonna id/tecnica non utile all'utente finale →
     `->toggleable(isToggledHiddenByDefault: true)`.
   - `->searchable()`/`->sortable()` solo su colonne di tipo scalare
     indicizzabile (stringa/numero/data) — mai su blob/json grandi
     (`payload`, `value`, `extra_attributes` grezzo).
   - Colonna byte/size numerica grezza → `Number::fileSize()` invece del
     numero crudo, molto piu' leggibile.
5. **Valuta schema.org onestamente**: prima chiediti se il model
   rappresenta un'entita' di dominio (persona, luogo, prodotto, evento,
   organizzazione...) o infrastruttura tecnica. Cerca pattern esistenti:
   `grep -rln "schema.org" Modules/*/app/Filament/Tables/Columns`. Se
   esiste gia' un aggregato pertinente (es. `PersonColumn` per persone),
   riusalo. Se il model e' tecnico (cache, log, sessione, coda, lock), **di'
   esplicitamente che schema.org non si applica** invece di forzare un
   `@type` decorativo.
6. **Verifica**: `php -l` + `phpstan analyse` (senza path, da `laravel/`)
   dopo OGNI file, non alla fine in blocco.
7. **Commit per file**, non accumulare — questa working tree ha piu'
   sessioni AI concorrenti.
