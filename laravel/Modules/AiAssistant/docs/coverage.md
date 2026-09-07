---
name: coverage
description: PHPStan/PHPMD/Pest/PHPInsights gate status for Modules/AiAssistant
metadata:
  type: reference
---

# Coverage e qualita' — AiAssistant

## 2026-09-07 — 2 errori PHPStan reali

Segnalati dall'utente via run diretto `phpstan analyse Modules`:

- `app/Filament/Pages/AiAssistantChat.php:403-404` — `nullCoalesce.offset` +
  `function.alreadyNarrowedType`. Dopo un `preg_match_all(...) >= 1`, PHPStan
  restringe `$matches` a `array{list<string>, list<string>}` (tupla fissa,
  entrambi i gruppi garantiti non-nullable): `$matches[1] ?? []` e
  `is_array($requests)` erano quindi codice morto (l'offset esiste sempre, il
  valore e' gia' `list<string>`). Semplificato a
  `$requests = $matches[1]; $lastRequest = $requests === [] ? '' : (string) end($requests);`
  — stesso comportamento a runtime (verificato caso per caso: array vuoto e
  non vuoto), solo tipo piu' preciso.
- `app/Neuron/OperatorAssistantAgent.php:222` — `method.notFound` su
  `Collection::firstOrNull()`. **Bug reale, non solo di tipo**:
  `firstOrNull()` non e' mai esistito ne' su `Illuminate\Database\Eloquent\Collection`
  ne' su `Illuminate\Support\Collection` — questo ramo avrebbe lanciato un
  `Error: Call to undefined method` a runtime ogni volta che veniva eseguito
  (il tool `searchCustomers` dell'agente AI). Fix: `->first()`, che su una
  collection vuota restituisce gia' `null` nativamente (nessuna sostituzione
  di comportamento necessaria).

PHPStan: 2 -> 0.

## Nota: pacchetto `neuron-ai` non installato

`OperatorAssistantAgent extends NeuronAI\Agent` (namespace `NeuronAI\Tools\*`
per `Tool`/`ToolInterface`/`ToolProperty`): il pacchetto `neuron-ai` (o
equivalente) **non risulta installato** ne' in `vendor/`, ne' referenziato in
`composer.json` di questo modulo o della root. PHPStan analizza comunque il
file (risolve i tipi Eloquent reali come `Collection<int, CustomerSite>`,
lascia `NeuronAI\*` come classi sconosciute senza bloccare l'intero file).
Conseguenza pratica: **non e' possibile scrivere un test Pest per questo fix**
in questo momento — qualunque test che tocchi `OperatorAssistantAgent` fallirebbe
al class-load (`Agent` non esiste), indipendentemente dal fix. Non installato
qui (pacchetto nuovo, richiede approvazione esplicita utente, fuori scope per
un fix di 2 righe). Segnalato per decisione owner.

## PHPMD

Debito preesistente enorme e non toccato da questo fix (non introdotto oggi,
verificato non correlato alle righe modificate):

- `AiAssistantChat.php`: `ExcessiveClassLength` (1029 righe, soglia 1000),
  `TooManyFields` (34, soglia 15), `TooManyMethods` (46, soglia 30),
  `ExcessiveClassComplexity` (230, soglia 50), `CouplingBetweenObjects` (20,
  soglia 13), numerosi `LongVariable`.
- `OperatorAssistantAgent.php`: decine di `CamelCaseVariableName` su variabili
  snake_case (`travel_km`, `product_id`, `work_order_id`, ecc.) — questi nomi
  rispecchiano i campi dei tool AI (contratto JSON esterno verso il modello
  LLM), non sono rinominabili senza rompere lo schema dei tool.

Nessun ruleset proprio (`phpmd.ruleset.xml`) presente nel modulo prima di
questa sessione; copiato da `Modules/Xot/phpmd.ruleset.xml` (SSoT).

## Pest

`./vendor/bin/pest Modules/AiAssistant/tests --no-coverage`: 20/20 passati,
nessuna regressione (nessun test esistente tocca i due file modificati —
coerente con la nota sopra su `neuron-ai` mancante).

## PHPInsights

Config ephemeral in `/tmp` (vedi `project_phpinsights_composer_lock_scoped_path.md`):
Code 81.4, Complexity 55.7, Architecture 76.5, Style 90.4. Complexity e
Architecture sotto la soglia informale 70 — debito preesistente coerente col
PHPMD sopra (classi molto grandi/complesse), non introdotto da questo fix.
