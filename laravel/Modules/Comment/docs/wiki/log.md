## [2026-06-29] boundary | Comment → User (DIP) — round 2

- Index wiki + `depends-on-user-module.md` (manifest User senza Comment)
- Temi FO: user-comment-module-boundary (Sixteen, Zero, Meetup, Barthelemy, Two)
- Xot: [module-user-comment-dependency-governance.md](concepts/module-user-comment-dependency-governance.md)

## [2026-06-29] boundary | Comment → User (DIP)

- [depends-on-user-module.md](concepts/depends-on-user-module.md)
- [can-comment-contract-owner.md](concepts/can-comment-contract-owner.md) — BaseUser non implementa
- GitHub: [#216](https://github.com/laraxot/base_predict_fila5/issues/216) / [D#217](https://github.com/laraxot/base_predict_fila5/discussions/217)

## [2026-06-05] concept | Spatie Comments — integrazione FO ticket

- [concepts/spatie-comments-fo-ticket-integration.md](concepts/spatie-comments-fo-ticket-integration.md)
- Issue: [#2](https://github.com/laraxot/module_comment_fila5/issues/2) · STORY-157

## [2026-06-06] bugfix | CommentPolicy native (no Spatie type hint)

- `CommentPolicy` / `ReactionPolicy` in `app/Policies/` — param `?CanComment` Laraxot
- `CommentEngineServiceProvider`: registrazione Gate in `booted()` dopo Spatie provider
- `config/comments.php`: `policies.comment` / `policies.reaction` → classi native
- Fix TypeError su `/it/tickets/14` (`Gate::check('createComment')`)
- Test: `tests/Unit/Modules/Comment/CommentPolicyTest.php`

## [2026-06-06] architecture | CanComment → `Models\Contracts`

- Spostato da `app/Contracts/` a `app/Models/Contracts/CanComment.php`
- Namespace `Modules\Comment\Models\Contracts\CanComment` — allineato Spatie + Rating/Lang
- Consumer: `BaseUser`, `CommentConfig`, `HasComments`, test
- Canon BMAD: `docs/wiki/bmad/architecture-models-contracts-placement.md` (§9 indice architecture)
- Gate: PHPStan L10 OK · PHPInsights 100% style · Pest 3/3

## [2026-06-06] adr | Models/Contracts vs app/Contracts

- ADR: [adr-models-contracts-vs-app-contracts.md](decisions/adr-models-contracts-vs-app-contracts.md)
- Memory root: `docs/wiki/memories/models-contracts-vs-app-contracts.md`
- `CanComment` canon: `app/Models/Contracts/CanComment.php`

## [2026-06-06] bugfix | CanComment PHP 8.4 — rimosso `getKey(): mixed`

- Fatal su `/it/tickets/14`: incompatibile con `Model::getKey()`
- Fix: firma loose come Spatie; test `CanCommentContractTest`

## [2026-06-06] architecture | Module providers manifest (no register manuale)

- Rimosso anti-pattern `$this->app->register(CommentEngineServiceProvider)` da `CommentServiceProvider`
- SSoT: `module.json` + `composer.json` `extra.laravel.providers`
- Doc: `wiki/concepts/module-providers-manifest.md` · root memory + rule

## [2026-06-06] architecture | STORY-158 internalizzazione Spatie comments

- Workflow BMAD: `/internalize-spatie-comments`
- Doc: `native-comments-architecture.md`, `spatie-package-inventory.md`, ADR
- GitHub: [#4](https://github.com/laraxot/module_comment_fila5/issues/4) · base [#297](https://github.com/laraxot/base_fixcity_fila5/issues/297)

## [2026-06-05] architecture | Provider manifest — no register manuale

- Regola: `CommentEngineServiceProvider` solo in `module.json` + `composer.json`
- `CommentServiceProvider` senza override `register()`
- Audit: `bashscripts/tools/audit-module-provider-manifest.sh Comment`
- Canon: [module-providers-manifest.md](concepts/module-providers-manifest.md)

## [2026-06-06] implementation | Fase 1 core nativo avviata

- Core: `CommentConfig`, `HasComments`, `InteractsWithComments`, `CanComment`, Enums, Exceptions
- Script audit: `bashscripts/tools/comment/audit-spatie-usage.sh`
- Hub: [native-comments-engine.md](../../native-comments-engine.md)
- `BaseUser` → `InteractsWithComments` (alias `InteractsWithSpatieComments` deprecato)

## [2026-06-05] docs | HackerNoon harness — tips 001-022 in wiki locale

- Stub/checklist: second-brain → canon Xot, ai-harness, [hackernoon map](../../../../../docs/wiki/concepts/hackernoon-ai-coding-tips-fixcity-map.md), [llm-wiki.txt](../../../../../bashscripts/tools/prompts/llm-wiki.txt)
- GitHub: [#272](https://github.com/laraxot/base_fixcity_fila5/issues/272) / [D#273](https://github.com/laraxot/base_fixcity_fila5/discussions/273)

# Comment Wiki Log

## [2026-06-05] concept | Spatie Comments — integrazione FO ticket

- [concepts/spatie-comments-fo-ticket-integration.md](concepts/spatie-comments-fo-ticket-integration.md)
- Issue: [#2](https://github.com/laraxot/module_comment_fila5/issues/2) · STORY-157

## [2026-04-15] init | wiki bootstrap
- Struttura wiki/log.md inizializzata.
- Layer raw: tutti i file in `docs/` (eccetto `wiki/`).
- Layer wiki: `docs/wiki/` — LLM-maintained, sintesi ad alto riuso.
- Schema: `docs/.schema/WIKI_SCHEMA.md`
- Adozione moduli: `docs/project/llm-wiki-module-adoption.md`

## [2026-06-10] STORY-291 | Spatie v2 parity gap analysis
- Creato: concepts/spatie-v2-parity-gap-analysis.md
- Implementato: ReactionCollection summary count, Reaction::$collectionClass, shouldBeAutomaticallyApproved granulare, MentionSearchComponent registration
- Rimosso: packages/spatie/ residuo
- Test: ReactionCollectionTest, ShouldBeAutomaticallyApprovedTest, CommentEngineLivewireRegistrationTest
- GitHub: base #317, discussion #318, module #7

## [2026-06-10] GitHub modulo — STORY-291 backlog
- Abilitate discussions su laraxot/module_comment_fila5
- Discussion hub: #11
- Issue backlog: #8 mentions, #9 Filament, #10 audit ripgrep
- Commenti cross-link su #7, base #317
