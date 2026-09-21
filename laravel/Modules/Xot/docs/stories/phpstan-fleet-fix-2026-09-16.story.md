---
id: Xot/phpstan-fleet-fix-2026-09-16
title: "PHPStan fleet fix 2026-09-16 (Xot)"
status: done
module: Xot
priority: P1
updated: 2026-09-16
---

# Claim

Sessione base-ptvx-fila5-0d — swarm PHPStan fleet, coordinato con codex-2026-09-16 (bashscripts/docs/bmad/stories/phpstan-fleet-random-swarm-2026-09-16.story.md) e peer base-ptvx-fila5-c5.

# Root cause analysis

Assessment iniziale (fork dedicato, stessa sessione): 96 errori riportati da PHPStan
su `Modules/Xot`, riconducibili a 4 root cause distinte (evidence in
`laravel/build/phpstan-Xot.json`, poi risultato vuoto/stantio — non riaffidabile).
Un fork successivo della stessa sessione ha gia' applicato i 4 fix nel working tree
prima che questo sub-agente di chiusura partisse; qui sotto la conferma riga per
riga di ciascun punto.

1. **`HasXotTable::hasColumn()`** (`app/Filament/Traits/HasXotTable.php:694-704`) —
   confermato: unico bug reale dietro ~90/96 errori (riportati una volta per
   ciascuna delle classi consumer "in context of"). Causa: `$model =
   app($modelClass)` senza narrowing tipizzato faceva restare `$model` `mixed`,
   quindi `method.nonObject` su `getConnection()/getSchemaBuilder()/getTable()` e
   `return.type` sul contratto `bool`. Fix (gia' applicato): aggiunta
   `Assert::isInstanceOf($model, Model::class);` (Webmozart Assert, gia'
   importato) subito dopo `app($modelClass)` — narrowing esplicito, nessun cast/
   `@var`. Rimosso anche un blocco `try/catch` morto e commentato attorno al
   metodo.
2. **`XotBaseResourceTable::getModelClass()` (righe 73/77)** — gia' `public
   static function getModelClass(): string` con `@return class-string<Model>`
   corretto; mancava solo l'import di `Illuminate\Database\Eloquent\Model` (gia'
   aggiunto). Nessun `class.notFound`/`return.type` residuo.
3. **Contratto `getModelClass()` statico** — 3 classi test double in
   `Tests/Feature/Filament/Traits/HasXotTableReorderingTest.php` (righe 70/86/102)
   estendono `XotBaseResourceTable` (metodo `static`) ma dichiaravano
   `getModelClass()` come istanza: PHP vieta di rendere non-static un metodo
   ereditato static. Stesso pattern trovato anche in una classe anonima in
   `tests/Unit/HasXotTableSortHooksTest.php` (non elencata nell'assessment
   iniziale, stessa causa). Tutte e 4 corrette a `public static function
   getModelClass(): string`.
4. **`SaveArrayAction` deprecato** — sia `app/Actions/Array/SaveArrayAction.php`
   sia `app/Actions/Arrays/SaveArrayAction.php` chiamavano il deprecato
   `Modules\Xot\Actions\Array\SavePhpArrayAction` (`{@see}` puntava a
   `Modules\Xot\Actions\Arr\SavePhpArrayAction`, namespace `Arr` canonico). Gia'
   corrette a chiamare `Arr\SavePhpArrayAction` direttamente. Verificato che
   `Arrays\SavePhpArrayAction.php` e `Array\SavePhpArrayAction.php` sono entrambi
   wrapper `@deprecated` coerenti verso lo stesso target — non toccati, fuori
   scope del fix (nessun errore PHPStan li segnala).

**Trappola cache condivisa (da segnalare)**: il primo `phpstan analyse Modules/Xot`
di verifica di questo sub-agente riportava ancora 4 errori `method.nonStatic`/
"Non-static method ... overrides static method" esattamente sulle righe gia'
corrette nel working tree (punto 3) — cache result stantia in `tmpDir:
/tmp/phpstan/` (condivisa dal neon fra le ~30 sessioni concorrenti, non
modificato). `./vendor/bin/phpstan clear-result-cache` (comando ufficiale, non
tocca `phpstan.neon` ne' usa `-c`/`--level`) + rerun → pulito.

# Esiti gate (questo sub-agente, dopo clear-result-cache)

- `./vendor/bin/phpstan analyse Modules/Xot --no-progress --memory-limit=-1` →
  **[OK] No errors** (`{"errors": 0, "file_errors": 0}`).
- Non-regressione `./vendor/bin/phpstan analyse Modules/Rating --no-progress
  --memory-limit=-1` → **[OK] No errors**.
- `tools/phpmd.sh Modules/Xot/app` → 1 finding informativo pre-esistente
  (collisione trait `getKeyTransFunc` su `XotBaseManageRelatedRecords`), fuori
  scope, non toccato.
- `tools/phpinsights.sh analyse Modules/Xot/app` → eseguibile, Code 78.8/100,
  Complexity 100/100, Architecture 57.1/100; alcuni finding di stile su file non
  toccati da questa campagna — fuori scope.
- Pest (`Modules/Xot/tests`): skip ambientale documentato —
  `nc -z -w3 10.100.200.53 3306` fallisce (DB irraggiungibile).

Dettagli e log completi: `Modules/Xot/docs/coverage.md` (sezione "PHPStan fleet
fix — 4 root cause, 1 vera (2026-09-16)").

## Verifica worker Xot — 2026-09-16

- Assessment canonico ripetuto dopo `clear-result-cache`: `phpstan analyse Modules/Xot`
  exit 0, **0 errori**; `phpstan.neon` non modificato e nessuna soppressione aggiunta.
- Corretto il test `tests/Unit/HasXotTableSortHooksTest.php`: il contratto corrente
  non dichiara hook sort di default su `XotBaseResourceTable`; il test ora verifica
  questo fatto via Reflection e conserva la verifica degli override concreti.
- Gate pertinenti: `php -l` exit 0; Pest su HasXotTable/Filament hooks/configure
  **7 passed, 16 assertions**.
- I tre test SaveArrayAction isolati sono rimasti in timeout a 25s durante il
  bootstrap applicativo (exit 124), senza modifiche ai file già corretti dagli
  agenti precedenti; da rieseguire con DB di test disponibile.
