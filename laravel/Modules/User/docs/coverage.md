---
title: "Code Coverage: User"
type: concept
tags: [coverage]
created: 2026-07-14
updated: 2026-07-14
qmd: "coverage code coverage: user"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index.md"
  - "./2fa-guide.md"
  - "./2fa.md"
  - "./accessor-delegation-pattern.md"
  - "./actions-path-convention-1.md"
  - "./actions-path-convention-2.md"
  - "./actions-path-convention.md"
---

# Code Coverage: User

**Lines Coverage:** N/A
**Methods Coverage:** N/A
**Classes Coverage:** N/A
**Functions Coverage:** N/A
**Test Status:** ⚠️  OTHER ERROR

## Summary

This module contains User functionality for the application.

## Coverage Reflections

- ⚠️  **Low Coverage**: The module has low test coverage, indicating potential risks in production
- Tests are not fully executed
- 🏗️  **Foundation Module**: User module is critical as it provides base functionality for all other modules
- 📋 **Module Size**: Medium complexity with multiple components

- 🔍 **Recommendations**: Focus on integration tests for complex workflows
- 🔐 **Security Critical**: User management module requires comprehensive testing
- 📋 **Module Size**: Medium complexity with multiple components

- 🔍 **Recommendations**: Focus on integration tests for complex workflows

## PHPStan (level max) — swarm fix 2026-09-02

Contesto: `phpstan analyse Modules` (livello max) segnalava 212 errori nel modulo
User su un totale di 542 nell'intero albero `Modules/` (claim:
`docs/chat/claim-phpstan-542-swarm-2026-09-02.md`). Lista errori sorgente:
`errors2-User.txt` (237 righe, 212 errori distinti), generata da una run
full-tree con `--error-format=json`.

**Esito**: `phpstan analyse -c <isolato> Modules/User` ora torna **0 errori**
(tmpDir dedicato per evitare inquinamento da cache condivisa fra sessioni
concorrenti). Commit `fcb5bca5` — 69 file toccati (su 84 elencati; i restanti 15
erano gia' stati corretti da un commit precedente nella stessa giornata,
`5ec97b13`, prima che questo lavoro iniziasse).

Per identifier:
- `cast.string` / `cast.int` (mixed non narrowed): sostituito il cast cieco con
  `is_scalar()`/`is_numeric()`/`is_int()` reali, o con la proprieta' tipizzata
  del model (`->name` al posto di `->getAttribute('name')`) dove esisteva un
  `@property` nel docblock della classe.
- `typeCoverage.paramTypeCoverage` / `typeCoverage.constantTypeCoverage`: tipi
  nativi espliciti su parametri di closure/metodi, inferiti dal contesto reale
  (firma del metodo Filament/Eloquent chiamante, `@property` del model
  collegato) o `mixed` solo dove il framework stesso lo dichiara cosi'
  (es. `Filament\Actions\*::toMailUsing(Closure(mixed, string): ...)`).
  Costanti tipizzate PHP 8.3 (`private const string|int NOME = ...`).
- Deprecazioni Filament v5: `Placeholder::make()->content()` ->
  `TextEntry::make()->state()`; `bulkActions()` -> `toolbarActions()`;
  `actions()` -> `recordActions()`; `form()` -> `schema()`;
  `modalSubheading()` -> `modalDescription()`; `modalButton()` ->
  `modalSubmitActionLabel()`; `MetatagData::getLogoHeader()` ->
  `getBrandLogo()`.
- `class.notFound` (`BaseUser::canAccessPanel()`): rimosso un riferimento morto
  a `App\Support\AccountFeatures` — classe mai esistita dal commit iniziale del
  modulo (`git log -S` conferma), causava un `Error` fatale a runtime su ogni
  pannello diverso da `admin`. Non un problema di sola analisi statica: un bug
  reale mai eseguito con successo in produzione su quel path.

**Gate**: `tools/phpmd.sh` e `tools/phpinsights.sh` sul modulo intero non
mostrano regressioni riconducibili a questi 69 file (i finding residui sono
debito preesistente su file non toccati). Suite Pest non eseguibile in modo
significativo: DB di test `10.100.200.53` irraggiungibile da questo ambiente
(condizione nota, vedi memoria second-brain "test-db-unreachable-drives-skips").

**Fuori scope**: 15 file della lista originale non richiedevano piu' modifiche
(gia' risolti dal commit `5ec97b13` prima dell'inizio di questo lavoro).

## PHPStan (level max) — swarm fix 2026-09-07 (sessione pomeriggio)

Contesto: recon segnalava ~19 errori residui in `Modules/User`; alla verifica
reale con `phpstan analyse Modules/User --memory-limit=-1 --no-progress` (cache
`/tmp/phpstan` ripulita prima di ogni run, condivisa fra sessioni swarm
concorrenti — vedi nota sotto) il numero reale era **12**, poi sceso a **9**
dopo che la cache stale si e' auto-corretta (2 errori su file di test erano
falsi positivi da cache contaminata da un run precedente).

**Esito: 9 -> 0 errori.** File toccati (5 sorgente + 2 test):

- `app/Filament/Resources/OauthAuthCodeResource.php` — rimossa una docblock
  orfana (`@return array<string, Select|TextInput>`) incastrata fra
  `#[\Override]` e il vero docblock di `extendTableCallback()`: PHPStan
  associava il tipo di ritorno sbagliato al metodo. Il metodo
  `getFormSchema()` che quel docblock descriveva in origine era gia' morto
  (override illegale di un hook risolto staticamente da
  `XotBaseResource::form()`, `final`, verso `Schemas/OauthAuthCodeForm.php`
  gia' esistente) ed e' stato rimosso.
- `app/Filament/Resources/OauthRefreshTokenResource.php` — stesso pattern
  (docblock orfana + `getFormSchema()` morto, `Schemas/OauthRefreshTokenForm.php`
  gia' esistente).
- `app/Filament/Resources/TeamUserResource.php`,
  `app/Filament/Resources/TenantUserResource.php` — stesso pattern per
  `getFormSchema()` (Schemas dedicate gia' esistenti) + `getEloquentQuery()`
  senza generics sul tipo di ritorno. Fix: `@return Builder<Model>` (non
  `Builder<TeamUser>`/`Builder<TenantUser>`) perche' `XotBaseResource` non
  lega il template `TModel` di Filament `Resource` al model concreto (nessun
  `@extends Resource<TModel>` nella catena) — dichiarare un generic piu'
  stretto sarebbe stata un'affermazione non verificabile dal type system.
  Commentato inline il motivo per chi legge dopo.
- `app/Filament/Resources/TenantResource.php` — stesso pattern
  `getFormSchema()` morto (`Schemas/TenantForm.php` gia' esistente) +
  `getRelations()` con un docblock copiato per errore da un altro metodo
  (`@return array<string, Component>` invece del tipo reale
  `array<int, class-string<RelationManager>|RelationGroup|RelationManagerConfiguration>`
  usato dal parent `XotBaseResource`/Filament `Resource`); aggiunti gli
  `use` mancanti per le 3 classi Filament.
- `tests/Unit/UserExecuteCoverage50Test.php`,
  `tests/Unit/UserModulePhpstanFixesTest.php` — due test chiamavano
  staticamente metodi `getFormSchema()` che sono **istanza**, non statici
  (`UserForm::getFormSchema()`, `PasswordData::getFormSchema()`, entrambi
  `public function`, non `public static function`): `(new UserForm())->getFormSchema()`
  e `PasswordData::make()->getFormSchema()`.

**mixed residui**: nessuno introdotto da questi file.

**Nota cache condivisa**: `phpstan.neon` usa `tmpDir: /tmp/phpstan`, condiviso
da tutte le sessioni swarm attive in parallelo sullo stesso host — un run "0
errori" subito dopo un edit di un altro agente puo' essere un falso negativo
da cache contaminata. Verificato con run ripetuti a cache pulita
(`rm -f /tmp/phpstan/resultCache.php`) prima di dichiarare 0 errori definitivo.

**Concorrenza sul modulo**: durante questa sessione il working tree di
`Modules/User` aveva ~650 file gia' modificati/non tracciati da altre sessioni
swarm attive in parallelo (probabile `migration-foreignidfor-swarm`, che
elenca esplicitamente `User` fra i moduli in lavorazione — vedi
`docs/chat/INDEX.md` root repo). Il commit di questa sessione include **solo**
i 7 file elencati sopra (`git add` esplicito per path, mai `-A`); il resto
del working tree non e' stato toccato ne' committato.

**Gate**:
- `pint --test` (scope esplicito sui 7 file, mai `--dirty` che spazzola l'intero
  repo condiviso) -> 2 file con debito di stile preesistente
  (`new_with_parentheses` ecc.), corretti con `pint` scope-limitato.
- `tools/phpmd.sh` sull'intero modulo va in crash (`PHP Fatal error` interno al
  phar, `AbstractLocalVariable::isPassedByReference()` su `getParent()` di un
  nodo null) — crash del tool su un file del modulo non identificato, non
  riconducibile ai 7 file di questa sessione: scope-limitato ai 5 file sorgente
  toccati, **0 violazioni**.
- `phpinsights` risulta rimosso dal repo (incompatibile con Pest 5, vedi second
  brain) — non eseguito.
- `pest` scope-limitato ai 2 file di test toccati (`--filter` sui test
  modificati): entrambi **passano** a runtime (non solo staticamente).
  Il run completo `Modules/User/tests` (suite intera, ~650+ test) e' stato
  lanciato ma non atteso fino in fondo per via del carico dell'host condiviso
  da altre sessioni swarm (piu' processi Pest concorrenti su altri moduli);
  i fallimenti osservati nella porzione completata sono preesistenti e non
  riconducibili a questi 7 file (`mockeryExpect()` funzione non importata in
  test non toccati, `HasPassportConfiguration::tokenLifetime()` metodo non
  esistente in test non toccati, tabella `users` mancante sul DB di test —
  stesso pattern gia' documentato in second brain
  "Test DB missing migrations blocks Feature tests").
