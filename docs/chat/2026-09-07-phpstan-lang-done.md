---
title: "PHPStan Modules/Lang — regression fix (static call su Schemas canonical)"
type: coordination
tags: [phpstan, lang, multi-agent, bmad, done]
created: 2026-09-07
agent: claude (sonnet 5) — this session
status: done
blocking: false
---

# PHPStan Modules/Lang — chiuso di nuovo, 2026-09-07

## Contesto trovato alla ricognizione

Il modulo era già stato chiuso a 0 errori il 2026-09-06
(`Modules/Lang/docs/stories/phpstan-Lang-fix.md`, status Done; vedi anche
`docs/chat/2026-09-06-sonnet5-pest-fixes-plus-unclaimed-modules-wave.md`, sezione
Lang(46)). Alla ricognizione di questa sessione il modulo aveva pero' ~27 file dirty
di un'altra sessione attiva in parallelo (swarm Epic 4 "filament-canonical-structure",
vedi `docs/chat/INDEX.md`), che stava rifattorizzando
`app/Filament/Resources/TranslationFileResource.php` + le sue classi `Schemas/` per
il pattern canonico (schema spostato da inline sulla Resource a metodo di istanza
sulla classe `Schemas/{Model}Form`/`{Model}Infolist`). Nessun claim esplicito su Lang
in `docs/chat/INDEX.md` per quello swarm (lista 14 moduli, Lang non incluso), quindi
non e' stato uno standdown per contesa diretta.

## Cosa e' stato fatto

Quel refactor concorrente era corretto architetturalmente ma ha reso 3 errori
PHPStan reali in `tests/Unit/LangCoverageBoostTest.php` (chiuso in precedenza da
`7.2-LANG` contro la shape non-canonica): chiamate statiche a
`TranslationFileResource::getFormSchema()` / `TranslationFileForm::getFormSchema()` /
`TranslationFileInfolist::getInfolistSchema()`, tutti in realta' metodi di istanza
(`XotBaseResourceForm`/`XotBaseResourceInfolist` li dichiarano `abstract public
function`, mai `static`).

Fix: 3 call site convertiti da chiamata statica a istanziazione + chiamata di
istanza, root cause non sintomo, nessun `@phpstan-ignore`/baseline/widening,
`phpstan.neon` invariato. Story BMAD `7.4-LANG` creata e chiusa
(`Modules/Lang/docs/stories/7.4.phpstan-canonical-schemas-static-call-fix.story.md`).

Limitato l'ambito SOLO ai file necessari per l'errore PHPStan reale, mai
`git add -A`: i ~27 file dirty dell'altra sessione (Resource/Schemas in
migrazione, doc consolidation, altri test fixture) sono stati lasciati intatti e
non committati da questa sessione.

Effetto collaterale scoperto e ripulito: la suite Pest scrive per davvero su
`lang/en/*.php`/`lang/it/*.php` (side effect gia' documentato in
`Modules/Lang/docs/coverage.md`, entry 2026-09-04) — ripristinato con
`git checkout -- lang/ && git clean -fd lang/` prima del commit.

## Numeri

| Modulo | Errori iniziali | Errori finali | File toccati | Coverage | `mixed` residui | Story ID | Commit |
|---|---|---|---|---|---|---|---|
| Lang | 3 (regressione post-refactor, non i 0 di baseline storica) | 0 | 3 (1 test + coverage.md + story) | LangCoverageBoostTest 14/14 verde prima e dopo (nessuna variazione di scope test) | nessuno introdotto | 7.4-LANG | `efdee37d` (laraxot/module_lang_fila5, dev) |

## Verifiche eseguite

- PHPStan `Modules/Lang` cache pulita: 3 -> 0 errori.
- Pest mirato (`LangCoverageBoostTest.php`): 14 passed, 58 assertions.
- PHPMD scoped (`Modules/Lang/phpmd.ruleset.xml`): nessun nuovo finding sulle 3 righe
  toccate, solo debito preesistente su tutto il resto del modulo (non in scope).
- Pint scoped sul file: passed.
- Pest full-suite: confermato bloccato da un `dddx()` (dump-and-die) pre-esistente in
  `app/Actions/SaveTransAction.php`, gia' documentato in `docs/coverage.md`
  (2026-09-04) — non introdotto, non risolto qui (fuori scope di un fix mirato a una
  regressione PHPStan di 3 righe). Raggiunta e superata `LangCoverageBoostTest`
  pulita nel contesto full-suite prima del crash successivo.
- Una failure pre-esistente e scorrelata confermata isolata in
  `tests/Unit/Actions/ReadTranslationFileActionTest.php` (assert su cast booleano a
  stringa) — file mai toccato da questa sessione.

## Git

Commit `efdee37d` su `laraxot/module_lang_fila5` branch `dev`, fetch prima (nessuna
divergenza, remoto non avanzato durante la sessione), push diretto senza merge
necessario.

## Second brain

Aggiunta una terza variante ("test che consuma le proprie classi Schemas") alla
memoria gia' esistente
`bashscripts/ai/wiki/memories/geo-addressform-schema-reuse-static-call-bug.md`
(pattern gia' mappato per override inline morto e riuso da componente esterno; qui
il consumer e' un test unitario del modulo stesso rimasto indietro rispetto a un
refactor concorrente).

## Stato finale

Modulo chiuso di nuovo a 0 errori PHPStan reali. Il refactor Epic 4 in corso su
`TranslationFileResource`/`Schemas/` resta di proprieta' dell'altra sessione — non
toccato, non commentato nel merito qui.
