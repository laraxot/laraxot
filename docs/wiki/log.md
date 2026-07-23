---
title: "TechPlanner LLM Wiki Log"
type: "log"
tags:
  - second-brain
  - qmd
  - techplanner
created: "2026-06-06"
updated: "2026-06-07"
qmd: "tp-wiki-root"
issues:
  - "https://github.com/laraxot/base_techplanner_fila5/issues/11"
discussions:
  - "https://github.com/laraxot/base_techplanner_fila5/discussions/12"
---

# TechPlanner LLM Wiki Log

## 2026-07-16

- **Contenuto JSON FO dichiarato sacro** — dopo la cancellazione massiva nel commit `d6e43cc`, ricostruiti forward-only i JSON TechPlanner da history, aggiunte rule, memory, skill, documentazione owner Cms/Theme Two e gate di presenza/sintassi. Tracciamento: issue #40, discussion #41.

## 2026-07-06

- **QMD installato e second brain reindicizzato** — `@tobilu/qmd@2.5.3` installato globalmente via npm (sudo, `--allow-scripts` per `better-sqlite3`/`node-llama-cpp`). Collection `wiki` creata su percorso reale `bashscripts/ai/wiki` (i mirror `docs/wiki/*` sono symlink non seguiti da `find`/qmd glob di default; indicizzare `docs/wiki` da solo produceva solo 2 file). 5058 file BM25-indicizzati e ricercabili (`bash bashscripts/docs/llm-wiki-qmd.sh search "<query>"`). Embedding vettoriali (`embeddinggemma-300M`) in corso in background su CPU (nessuna GPU disponibile nel container) — lento (~4-5 doc/min), BM25 lessicale è già pienamente funzionante nel frattempo. Aggiunto server MCP `qmd` in `.mcp.json` (`qmd mcp`, stessi env `XDG_CACHE_HOME`/`XDG_CONFIG_HOME` del wrapper). Riferimento mancante `docs/project/qmd-local-docs-search.md` citato da `llm-wiki-qmd-workflow.md` non esiste — da creare o correggere il link.
- **PHPStan Modules zero errors (4th iteration)** — `./vendor/bin/phpstan analyse Modules` → **0 errori**. Ultimi 3 fixati in `User/app/Models/Traits/HasTeams.php`: `@phpstan-ignore return.type` per invarianza `BelongsToMany<TDeclaringModel>` quando `belongsToManyX()` restituisce `$this` ma il contratto dichiara `Model`. Pattern già esistente in `UserContract.php:151`.
- **PHIVE installato** — `~/.local/bin/phive` v0.16.0; php-cs-fixer già globale (`/usr/local/bin/php-cs-fixer` v3.95.11)
- **Cleanup completato** — nessun `PhpstanTraitProbe`, `app/Phpstan`, `pest.php` (minuscolo) presente
- **QMD non disponibile** — binario `qmd` non trovato; `llm-wiki-qmd.sh` richiede installazione
- **Docs wiki completati** — `Modules/Quality/docs/wiki/` creato da zero, `Modules/Employee/docs/wiki/` creato come overlay su flat docs legacy, `Themes/Barthelemy/docs/wiki/` e `Themes/Two/docs/wiki/` integrati con index/log mancanti e cartelle standard (decisions, entities, glossary, how-to, queries, reference, summaries, troubleshooting)
- **CMS test fix** — `Modules/Cms/tests/Feature/HomepageFilamentBlocksArchitectureTest.php`: 2 errori PHPStan (assertArrayHasKey secondo arg. mancante, assertIsString arg. mancante, expect() senza arg.) corretti con parametri reali
- **PHPStan Modules zero errori — round di bug reali post-convergenza multi-agente**: dopo che il conteggio era sceso a 0 in un giro precedente, un secondo `analyse Modules` ha rivelato fatal error e bug introdotti da modifiche concorrenti di altri agenti sullo stesso repo, non semplici falsi positivi statici: (1) namespace corrotto in `Modules/Gdpr/app/Filament/Resources/TreatmentResource/Tables/TreatmentsTable.php` (un path assoluto al posto del namespace, "Cannot redeclare class"); (2) funzione globale `typedMock()` duplicata tra `Modules/Notify/tests/Support/helpers.php` e `Modules/User/tests/Support/helpers.php` (fatal "cannot redeclare function" quando l'intero `Modules/` viene analizzato in un solo processo) — risolto con guardia `function_exists()` su entrambe; (3) marcatori di merge conflict Git (`<<<<<<< HEAD`) mai risolti in `Modules/Gdpr/tests/Feature/ConflictResolutionTest.php`; (4) narrowing di tipo affidato a un commento `/* @var */` a singolo asterisco (quindi ignorato da PHPStan) in `Modules/Cms/tests/Feature/HomepageFilamentBlocksArchitectureTest.php`, sostituito con `Assert::assertIsArray`/`assertIsString` reali. Verificato con `phpstan analyse Modules --memory-limit=-1` a cache pulita: **0 errori, 6595 file**. Lezione: con più agenti che editano lo stesso albero in parallelo, un run "verde" non è stabile finché non lo si riconferma dopo che tutti gli agenti attivi hanno finito — i fatal error da collisione (namespace/funzioni globali/merge marker) non li introduce PHPStan, li introduce la concorrenza.
- **Second brain — wrapper ufficiale rotto, ora fisso**: `bashscripts/docs/llm-wiki-qmd.sh` esportava `XDG_CACHE_HOME=~/.cache/qmd-cache` (nome diverso dal default `~/.cache/qmd` usato dal binario `qmd` invocato direttamente), quindi ogni chiamata al canale ufficiale documentato nei CLAUDE.md di bootstrap restituiva sempre "No results found" nonostante l'indice reale fosse pieno e funzionante. Bug diagnosticato da un altro agente (`docs/chat/second-brain-qmd-cache-bug-2026-07-06.md`) e corretto (in parallelo, da un altro agente) rimuovendo l'override. Verificato: `bash bashscripts/docs/llm-wiki-qmd.sh search "phpstan" -n 3` ora restituisce risultati reali. Junction `docs/wiki/{concepts,memories,skills,commands,rules}` → `bashscripts/ai/wiki/*` verificate conformi con `bashscripts/tools/audit-wiki-junctions.sh`. Embedding vettoriali in corso in background (CPU, lento): passato da ~1051/0 embedded a 6325 documenti indicizzati / 1329+ embedded nel corso della sessione, BM25 pienamente funzionante nel frattempo.

## 2026-06-07

- **PHPStan Modules zero errori** — da `laravel/`: `./vendor/bin/phpstan analyse Modules --memory-limit=-1` → **4993 file, [OK] No errors**. Fix principali: Comment generic/view-string/class-string, Blog `ProfileContract` no Fixcity, Xot form fill/view/string palette, Geo raw SQL con binding, Notify enum/attachment shape, Employee case-only page.
- **PHPStan Modules no-flag recheck** — da `laravel/`: `./vendor/bin/phpstan analyse Modules` → **4993 file, [OK] No errors**. Follow-up DTO concreti: factory `make()`/`from()` tipizzate `self` e istanziate con `new self()` per evitare `new.static`/`return.type`; ripuliti residui parse nei DTO Xot.
- **Second brain root riparato** — `docs/wiki/{concepts,rules,skills,commands}` ripristinati come directory mirror da `bashscripts/ai/*`; rimossi marker merge in `docs/wiki/log.md` e `docs/wiki/memories/phpstan-modules-zero-2026-06-06.md`.
- **QMD collection reale** — `techplanner-wiki` non esiste nel checkout; collection valida usata: `tp-wiki-root`.
- **Healthcheck residuo** — `second-brain-healthcheck.sh phpstan` ora trova i file SSoT root, ma `verify-llm-wiki.sh` resta rosso per temi infrastrutturali separati dal fix PHPStan: `.agents/node_modules`, MCP server mancanti, owner composer `laravel/folio`, `Modules/User/vendor`, directory Folio semantiche.

## 2026-06-06

- **PHPStan Modules re-verifica** — `./vendor/bin/phpstan analyse Modules` → **4822 file, zero errori**; fix post-merge: `XotBaseServiceProvider` (marker Git), migrazioni `team_user`/`profiles`/`client_table`, `XotBaseWizardWidget` view-string; memoria: [phpstan-modules-zero-2026-06-06](./memories/phpstan-modules-zero-2026-06-06.md)
- **architettura fix-conflicts.php** — SSoT `bashscripts/tools/git/fix-conflicts.php` (repo `bashscripts_fila5`); shim `bashscripts/fix/fix-conflicts.php`; doc `bashscripts/docs/fix-conflicts-guide.md` + `architecture-git-conflict-tools.md`; monorepo `.gitignore` ignora clone annidato; vietato in root monorepo
- **git merge sweep (sessione 4, dev)** — zero marker su codice PHP; 457 file docs/script ripuliti da marker orfani; ripristino `ce96248f` per XotBaseServiceProvider e servizi Xot; `composer.lock` rigenerato; 111 test Pest corretti (`uses(TestCase::class)`); FLVP eseguito su file toccati
- **git merge sweep (sessione 2)** — ~2054 file con marker; script `bashscripts/conflict/resolve-git-conflicts.py`; recovery `Modules/*/app/` da `6b9f55ad`; how-to: [git-merge-marker-sweep](./how-to/git-merge-marker-sweep.md); story: [STORY-GIT-001](../stories/story-git-collision-resolution.md)
- **git merge sweep (sessione 3, master)** — script + repair PHP; zero marker; quality gate `post-edit-php.sh`
- **git collision + FLVP (sessione 4)** — `XotBaseServiceProvider` ripulito da marker residui; `post-edit-php.sh` fixato; memoria [post-edit-php-quality-gate](./memories/post-edit-php-quality-gate.md); Pest `UserMigrationSyntaxTest` 116 pass
- **git merge sweep** — risolti ~3700 conflitti `dev` ↔ `origin/dev`; codice ours, stub AGENTS/CLAUDE theirs; how-to: [git-merge-marker-sweep](./how-to/git-merge-marker-sweep.md); story: [STORY-GIT-001](../stories/story-git-collision-resolution.md)
- **corruzione post-merge** — 59 PHP syntax error ripristinati da `origin/master`; bootstrap `php artisan about` OK; recovery docblock/import/`$name` comandi; quality gate: `laravel/tools/post-edit-php.sh`
- **phpstan Modules** — parse blocker risolti (alias `Builder` duplicati); full run 4800 file → **413 errori** tipizzati; memoria: [phpstan-modules-zero-2026-06-06](./memories/phpstan-modules-zero-2026-06-06.md), Fix #2 in [phpstan-fixes-log](../laravel/Modules/Xot/docs/wiki/concepts/phpstan-fixes-log.md)
- Added `public-theme-resolution-and-vite-assets`: prevent wrong `pub_theme` diagnosis and missing Vite deploy assets for Theme Two.
