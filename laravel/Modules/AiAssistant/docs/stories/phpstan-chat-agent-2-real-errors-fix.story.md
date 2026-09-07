---
id: phpstan-chat-agent-2-real-errors-fix
slug: phpstan-chat-agent-2-real-errors-fix
scope: [module:AiAssistant, project:base_workorder_fila5]
status: Done
priority: High
created: 2026-09-07
---

## Problema

Utente ha eseguito `phpstan analyse Modules` direttamente e segnalato 2
errori reali:

1. `app/Filament/Pages/AiAssistantChat.php:403-404` —
   `nullCoalesce.offset` + `function.alreadyNarrowedType`.
2. `app/Neuron/OperatorAssistantAgent.php:222` — `method.notFound` su
   `Collection::firstOrNull()`.

## Investigazione

1. Dopo un `preg_match_all(...) >= 1`, PHPStan tipizza `$matches` come
   `array{list<string>, list<string>}`: l'offset 1 e' garantito esistere,
   quindi `?? []` era codice morto, e con `$requests` gia' `list<string>`
   anche `is_array($requests)` era sempre vero.
2. `firstOrNull()` **non esiste** su nessuna Collection Laravel (Eloquent o
   Support) — bug runtime reale, non solo di tipo: il tool AI
   `searchCustomers` sarebbe fallito con `Error: Call to undefined method`
   ogni volta che un cliente aveva almeno una sede.

## Fix

1. `$requests = $matches[1]; $lastRequest = $requests === [] ? '' : (string) end($requests);`
   — comportamento identico a runtime (verificato caso vuoto/non vuoto),
   tipo preciso.
2. `$customer->sites->firstOrNull()` → `$customer->sites->first()` (gia'
   nullable di suo su collection vuota).

## Verifica

- `phpstan analyse Modules/AiAssistant` (cache pulita): 2 -> 0.
- `phpmd`: nessun nuovo finding introdotto (debito preesistente enorme,
  documentato in `docs/coverage.md`, fuori scope).
- `pest Modules/AiAssistant/tests`: 20/20 passati, nessuna regressione.
- PHPInsights: Code 81.4, Complexity 55.7, Architecture 76.5, Style 90.4
  (Complexity/Architecture sotto la soglia informale 70, debito
  preesistente).
- **Test dedicato per il fix #2 non scritto**: `OperatorAssistantAgent
  extends NeuronAI\Agent`, pacchetto `neuron-ai` non installato in questo
  repo (ne' in `vendor/`, ne' in nessun `composer.json`) — qualunque test
  che tocchi questa classe fallirebbe al class-load indipendentemente dal
  fix. Installare un pacchetto nuovo richiede approvazione esplicita,
  fuori scope per un fix di una parola. Segnalato in `docs/coverage.md`
  per decisione owner.

## Git sync

Pending: `laravel/Modules/AiAssistant` (repo proprio) + root repo.
