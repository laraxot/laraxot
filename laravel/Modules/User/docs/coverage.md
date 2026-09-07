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
