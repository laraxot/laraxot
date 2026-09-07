---
id: hasxotfactory-regression-prevention
slug: hasxotfactory-regression-prevention
scope:
  - module:Xot
  - project:base_workorder_fila5
status: ready-for-dev
priority: High
created: 2026-09-07
epic: 18.2.phpstan-level-10-monorepo-cleanup
---

## Problema

`Modules/Xot/app/Models/Traits/HasXotFactory.php` (piu' `XotBaseModel.php`,
`XotBasePivot.php`, `XotBaseMorphPivot.php`) e' stato regredito **5 volte nella
stessa sessione** (2026-09-07), sempre con lo stesso pattern: un agente fa una
pulizia/generalizzazione "innocua" (rimuovere il metodo, appiattire il generic,
togliere `newFactory()`, perdere `$count`/`$state`, restringere il tipo del
callable) e rompe silenziosamente la firma di `factory()`.

L'impatto reale non e' rumore PHPStan: PHP non segnala un errore quando un
metodo utente viene chiamato con piu' argomenti posizionali di quanti ne
dichiari — li scarta. `Modules/Employee/database/seeders/WorkHourSeeder.php:25`
chiama `User::factory(5)`; con la versione a zero argomenti, il `5` viene
scartato e `->create()` crea 1 record invece di 5, senza errore, warning o
hit PHPStan. Dato reale sbagliato in silenzio, non un crash.

Vedi `docs/chat/hasxotfactory-count-param-dropped-5th-regression.md` (5a
occorrenza, fix in `5c965975`) e le 4 occorrenze precedenti citate li'
(`docs/chat/2026-09-07-URGENT-xotbasemodel-itself-lost-hasxotfactory.md` e
story sorelle `2.1.fix-hasxotfactory-merge-conflict-regression.story.md`,
`18.2.1.hasxotfactory-factory-method-regression-fix.story.md`).

Il rimedio applicato finora e' solo procedurale ("prima di editare questi 4
file, esegui il test Pest guardia prima e dopo") — scritto in un file
`docs/chat/` che non tutti gli agenti leggono prima di editare. Non esiste
un meccanismo che lo renda strutturale.

## Acceptance criteria

- [ ] `HasXotFactory.php`, `XotBaseModel.php`, `XotBasePivot.php`,
      `XotBaseMorphPivot.php` portano un commento breve in testa al metodo
      `factory()`/al trait che rimanda esplicitamente a
      `tests/Unit/Traits/HasXotFactoryTest.php` come guardia di regressione
      e spiega perche' la firma a 2 argomenti (`$count`, `$state`) e'
      obbligatoria (vedi `WorkHourSeeder` sopra).
- [ ] `tests/Unit/Traits/HasXotFactoryTest.php` copre esplicitamente una
      chiamata `Model::factory($count)` con `$count > 1` e assevera il
      numero di record creati (non solo che il metodo esista/non lanci) —
      verificare se questo caso e' gia' coperto, altrimenti aggiungerlo.
- [ ] Verificare se esiste un modo per far fallire PHPStan/Pest in CI se uno
      di questi 4 file cambia senza che il test guardia venga eseguito nello
      stesso commit (es. grep pre-commit su `git diff --name-only` +
      trigger automatico del test filtrato) — se non e' fattibile senza
      strumenti nuovi, documentarlo come limite noto invece di lasciarlo
      silenzioso.
- [ ] Nessuna soppressione PHPStan aggiunta; `phpstan analyse Modules/Xot`
      resta a 0 errori dopo la modifica.

## Note

Non e' un bugfix (il bug e' gia' stato corretto 5 volte); e' hardening
contro la sesta regressione. Rischio basso, tocca solo commenti/test, non
comportamento a runtime.
