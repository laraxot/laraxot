---
title: "PHPStan — modulo Notify a zero errori — 2026-07-06"
type: chat
created: 2026-07-06
updated: 2026-07-06
tags: [phpstan, modules, notify, multi-agent, locks]
issue: "https://github.com/laraxot/base_techplanner_fila5/issues/34"
related:
  - ./phpstan-usercontract-membershipteams-and-dead-probes-2026-07-06.md
  - ./phpstan-modules-progress-2026-07-06-pm.md
---

# PHPStan — Notify: 172 -> 0 errori — 2026-07-06

Nessun `.lock` trovato su Notify a inizio sessione (verificato con
`find Modules/Notify -name "*.lock"`). Lavorato senza collisioni note.

## Root cause principale: nessuna estensione PHPStan per Mockery

`mockery/mockery` non ha un'estensione PHPStan/Larastan installata in questo
progetto (verificato: nessun file `*.neon` in `vendor/mockery`, nessun
riferimento in `vendor/larastan`). `Mockery::mock()` e `shouldReceive()`
hanno tipi di ritorno nativamente imprecisi
(`Expectation|ExpectationInterface|HigherOrderMessage`), quindi ogni
`->with()/->andReturn()/->once()/->times()` incatenato genera
`method.notFound`/`method.nonObject` a livello `max`, indipendentemente da
come viene chiamato `Mockery::mock()`.

**Fix**: creato `Modules/Notify/tests/Support/helpers.php` (registrato in
`composer.json` → `autoload-dev.files`) con due helper generici, stesso
pattern gia' in uso in `Modules/User/tests/Support/helpers.php::typedMock()`:

- `typedMock(string $class): T&MockInterface` — risolve gli errori
  `argument.type` quando un mock viene passato a una firma che richiede il
  tipo reale.
- `mockExpectation(MockInterface $mock, string $method): \Mockery\Expectation`
  — risolve gli errori su `->with()/->andReturn()/->once()/->times()`
  incapsulando la certezza runtime (quando si passa un singolo nome di
  metodo, `shouldReceive()` restituisce sempre una `Expectation` concreta,
  ma PHPStan non lo sa staticamente).

Non e' un `@phpstan-ignore` né un `assert()`/`@var` per silenziare: sono
funzioni generiche con `@template`/`@return T&MockInterface`, il pattern
raccomandato da PHPStan stesso per i factory helper. Applicato in
`Modules/Notify/tests/Unit/Services/NotificationManagerTest.php`.

## Altri fix reali (non falsi positivi)

- Asserzioni tautologiche rimosse: `$result === null || $result instanceof
  Notification` e' sempre vero per un tipo `?Notification` — non era un bug
  di PHPStan, era la mia prima bozza di fix, corretta rimuovendo
  l'asserzione (il comportamento reale e' verificato da Mockery in
  `tearDown()` tramite `->once()`).
- `it_has_required_methods()` in `NotificationManagerTest.php` e i due `it()`
  analoghi in `TwilioDataTest.php`: **rimossi**, asserivano
  `method_exists()` su metodi gia' garantiti staticamente dal type system
  (zero valore, sempre `true`).
- `Modules/Notify/tests/Unit/Actions/SendNotificationActionTest.php`: `uses(TestCase::class);`
  era stato inserito (da uno script automatico di un altro agente, non mio)
  **dentro il corpo di una classe anonima**, rompendo la sintassi. Spostato
  nella posizione corretta (dopo gli `use` import, prima della funzione).
- `Modules/Notify/tests/Unit/Actions/NetfunSendActionTest.php`: due blocchi
  con `$content = file_get_contents(...)` corrotti (variabile a sinistra
  dell'`=` mancante, argomenti vuoti) — probabile residuo di un find/replace
  automatico precedente. Ripristinato `$filename`/`$content` corretti.
- 8 file `Send*SMSActionTest.php`: pattern rotto
  `expect($params[0]->getType()?->getName())->toBe(...)` — `getType()`
  ritorna `?ReflectionType`, ma solo `ReflectionNamedType` ha `getName()`.
  Sostituito con l'helper `assertReflectionTypeName()` gia' esistente in
  `tests/Pest.php` (dove disponibile) o una copia locale guardata da
  `function_exists()` (vedi sotto).
- Import `Safe\{file_get_contents,class_uses,class_implements}` aggiunti
  dove mancanti + cast `(string) $filename` dove `ReflectionClass::getFileName()`
  puo' restituire `false`.

## Bug di infrastruttura scoperto (documentato, non risolto in questa sessione)

`Modules/Notify/tests/Pest.php` (helper globali senza namespace) **non viene
caricato in modo affidabile quando si esegue un singolo file test in
isolamento** (`./vendor/bin/pest path/to/UnFile.php`), anche per file mai
toccati in questa sessione (es. `SmsDataTest.php`). Riproducibile con
chiamata fully-qualified (`\assertListContains(...)`), quindi non e' un
problema di namespace fallback — il bootstrap del modulo semplicemente non
viene richiesto in quel path di esecuzione. Dettagli e workaround (funzione
locale guardata da `function_exists`) in
`Modules/Notify/docs/wiki/concepts/pest-bootstrap-helpers-not-loaded-single-file.md`.
Non ho investigato la causa esatta (richiede spelonca in
`vendor/pestphp/pest/src/Kernel.php`) — fuori scope per questa sessione.

## Stato finale

```bash
./vendor/bin/phpstan analyse Modules/Notify --no-progress
# 1 errore residuo, e' l'artefatto noto "Ignored error pattern ... was not
# matched" che compare SOLO scansionando un sottoinsieme di Modules/ invece
# di tutto l'albero — non e' un errore di codice (vedi
# Modules/Xot/docs/wiki/concepts/phpstan-partial-scope-false-positives.md)
```

Pest verde su tutti i file toccati (verificato isolati e in batch).

## Prossimo modulo

TechPlanner (87 errori nella baseline precedente), User (234, `.lock` attivi
su alcuni file Employee/User visti a inizio sessione — verificare stato
lock aggiornato prima di procedere), Cms (72), Employee (il piu' grande,
1700+, gia' in lavorazione da altri agenti secondo le note precedenti).

— Claude (`claude-sonnet-5`)
