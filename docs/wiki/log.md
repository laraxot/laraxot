---
title: "TechPlanner LLM Wiki Log"
type: "log"
tags:
  - second-brain
  - qmd
  - techplanner
created: "2026-06-06"
updated: 2026-09-02
qmd: "second brain project log PHPStan modules repository inventory documentation changes"
issues:
  - "https://github.com/laraxot/base_workorder_fila5/issues/14"
discussions:
  - "https://github.com/laraxot/base_workorder_fila5/discussions/15"
---

# TechPlanner LLM Wiki Log

## 2026-09-21 (gitmodules.ini — repo figli, non submodule)

- Mancanza grave: status git letto sulla root invece che sul `.git` del modulo. Isolamento: `git -C <path>` + `rev-parse --show-toplevel`. Iterare tutti i path di `gitmodules.ini`. Canon aggiornato: [no-git-submodules-module-repos.md](memories/no-git-submodules-module-repos.md), trigger map, `AGENTS.md`.
- Inventario: `laravel/Themes/Sixteen` ha `.git/` e remote `laraxot/theme_sixteen_fila5` ma mancava da `gitmodules.ini` (working tree). Temi senza `.git` (TwentyOne, Meetup, Barthelemy) non vanno aggiunti.
- Rebase in corso su User/Notify/Rating **abortiti** (rebase vietato; 486 pick residui su Rating). Merge `ort` + `-X theirs` dove la storia locale era un rewrite dello stesso lavoro remoto. Marker `<<<<<<<` committati puliti in User (PHP lint) e Rating (32 file PHP + gitignore/gitattributes). Notify: merge + igiene GSD/archive + PHP lint sul tree toccato. Push su `laraxot/dev`: User, Notify, Rating, Cms, Employee, Gdpr, root (`gitmodules.ini` + Sixteen). `git merge --abort` usato solo per uscire da un merge esploso, non come rollback di contenuto.

## 2026-09-21 (PHPStan Activity/Log/ListLogFilesActionTest)

- Fixed redundant `toBeInt()` assertion causing `pest.expectation.redundant` error in Activity/tests/Unit/Actions/Log/ListLogFilesActionTest.php:71. Changed `expect($files[0]->modifiedAt)->toBeInt()->toBeGreaterThan(0)` to `expect($files[0]->modifiedAt)->toBeGreaterThan(0)`. All 7 Activity tests pass, PHPStan Modules zero errors (10958 file). Second brain + BMAD workflow: identified error via PHPStan, fixed underlying cause without ignore/baseline/cast, verified with Pest tests.

## 2026-09-02 (PHPStan Modules — Rating, UI, Xot)

- Story [2.3](../stories/2.3.deep-quality-enabled-modules.story.md): `./vendor/bin/phpstan analyse Modules` verde su **10573 file**. Corretti null safety e generics Eloquent in Rating, helper Mockery UI con `Expectation` concreta e resolver `XotBaseResource::getTableClass()`. Nessuna modifica a `phpstan.neon`, nessun ignore/baseline/cast artificiale. Creato [PHPSTAN-INDEX](PHPSTAN-INDEX.md) e compattata la skill [phpstan-pest-remediation](skills/phpstan-pest-remediation.md).

## 2026-09-02 (inventario repository moduli)

- Allineato `gitmodules.ini` ai 48 moduli Laravel validi (`module.json` presente): aggiunte 32 configurazioni mancanti. Verificati `Rating` e `Seo` già esistenti e create con `gh` le altre 30 repository `laraxot/module_*_fila5`, poi rese **private**. Esclusi `Modules/docs` e lo scaffold incompleto `TestModule`, privi di `module.json`.

## 2026-08-31 (notte — Theme Sixteen dati sacri)

- Rimosso `RefreshDatabase` da `BootstrapItaliaComponentsTest` (solo view). `ComunePagesTest` / `ComuneControllerTest`: `DatabaseTransactions`, tolto `migrate` in `beforeEach`, skip se Fixcity assente. Themes PHP: **0** `RefreshDatabase`.

## 2026-08-31 (sera — poetry: no migrate --force)

- Story [1.15](../stories/1.15.data-sacred-no-migrate-force.story.md): tolto `--force` da `ArtisanAction` / `ArtisanService` / `MigrationCommandHandler`. PHPStan Modules 0; Pest Artisan 8/8. DB `*_test` solo `CREATE IF NOT EXISTS`.

## 2026-08-31 (sera — dati sacri write-back)

- Rafforzata religione **dati sacri** in second brain + docs moduli/temi: rule/memory [data-sacred-no-destructive-db](rules/data-sacred-no-destructive-db.md); Xot troubleshooting (tolti esempi `migrate:fresh`/`RefreshDatabase`); [ide-helper-models-governance](../../laravel/Modules/Xot/docs/ide-helper-models-governance.md) → solo `--nowrite`; Media `migrations.md`; Themes Sixteen/Zero `ai-handoff` + `testing-refresh-database-rule` + volt-integration. **Mai** fresh/force/RefreshDatabase per ide-helper o PHPStan.

## 2026-08-31 (sera — ide-helper refresh)

- Suite `ide-helper:{generate,meta,models --nowrite,eloquent}` OK. Fix `config/media.php` (dir→file) per meta EISDIR; `force_fqn`; vietato `--write --reset` sui model. PHPStan Modules **0** dopo clear cache. Handoff [ide-helper-refresh.md](../chat/ide-helper-refresh.md). Story [1.14](../stories/1.14.composer-update-phpstan-ide-helper.story.md) aggiornata.

## 2026-08-31 (sera — re-verify PHPStan Modules)

- Ri-run `phpstan analyse Modules` → **[OK] No errors** (10497 file). Nessun fix codice necessario. Story [1.12](../stories/1.12.phpstan-zero-per-module.story.md) → **done**. Memory aggiornata [phpstan-modules-zero.md](memories/phpstan-modules-zero.md). Handoff [phpstan-modules-zero-swarm.md](../chat/phpstan-modules-zero-swarm.md).

## 2026-08-31 (composer + ide-helper)

- **Story [1.14](../stories/1.14.composer-update-phpstan-ide-helper.story.md):** `composer update -W` (Filament 5.7.7) + PHPStan Modules 0 + ide-helper generate/meta/models senza Exception. Handoff [composer-update-phpstan-ide-helper.md](../chat/composer-update-phpstan-ide-helper.md). Fix: ResponseCache `JsonSerializer`, DB `workorder_*`, alias `blog`/`comment`, `config/comments.php`, 9 factory generics.

## 2026-08-31

- **PHPStan Modules = 0:** story [1.12](../stories/1.12.phpstan-zero-per-module.story.md) / issue [#7](https://github.com/laraxot/base_workorder_fila5/issues/7). Handoff [phpstan-modules-zero-swarm.md](../chat/phpstan-modules-zero-swarm.md). Contratto `HasXotFactory` (`@template TFactory of Factory`) in [hasxotfactory.md](../../laravel/Modules/Xot/docs/wiki/traits/hasxotfactory.md); Pest bridge vietato + `pest-plugin-phpstan`.

## 2026-07-27

- **BMAD document-project deep scan:** `bmad-output/project-documentation.md` + sync `project-context.md` (dominio già presente). Religione: [bmad-deep-code-documentation-religion.md](./concepts/bmad-deep-code-documentation-religion.md). Decision 005.
- **BMAD Method v6** reinstall da [`aj-geddes/claude-code-bmad-skills`](https://github.com/aj-geddes/claude-code-bmad-skills): commit `27dca0e`, plugin `bmad-planning-orchestrator` 0.5.0, 20 skill symlink; `--force` gestisce upstream non-git.
- **Second brain max power alwaysApply:** rule `.cursor/rules/second-brain-max-power.mdc`; bootstrap + skill + memory aggiornati.
- **`.agents/` compact on-demand:** 1117 skill/rule stubbed → `archive/`; HOT manifest + audit `audit-agents-on-demand-stubs.sh`.
- Hygiene: `tests/AuditCoverage/` vietata — `ensure-audit-coverage-gitignore.sh`.

## 2026-07-23

- **Story 1.10 + libro errori** — [agent-errors-lessons.md](../../gestionale-commesse-comparison/agent-errors-lessons.md), rule [replica-singular-no-merge-delete.md](./rules/replica-singular-no-merge-delete.md), issue [#9](https://github.com/laraxot/base_workorder_fila5/issues/9). Fase = replica + singolare.
- **Religione fase = replica + singolare** — niente merge Auth→User / AiAssistant→AI in questa fase. SSoT: [replica-singular-phase.md](../../gestionale-commesse-comparison/replica-singular-phase.md).
- **AiAssistant «non si vede»** — cartella presente; chiave `modules_statuses` reinserita `false`. [visibility.md](../../laravel/Modules/AiAssistant/docs/visibility.md).

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
