---
title: "GEMINI Action Patterns"
type: pattern
tags: [gemini, action, patterns]
created: 2026-07-14
updated: 2026-07-14
qmd: "gemini-action-patterns gemini action patterns"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index.md"
  - "./01-gsd-workflow.md"
  - "./02-bmad-workflow.md"
  - "./03-architecture-zen.md"
  - "./04-filament-philosophy.md"
  - "./05-front-office-audit.md"
  - "./06-cinematic-effects.md"
  - "./07-mcp-tailwind-ui.md"
---

# GEMINI Action Patterns

Action Execution Rules - Spatie Queueable Actions.

---

## Regola 1: Il Metodo Pubblico è SEMPRE `execute()`

### ❌ SBAGLIATO

```php
app(CreateClientAction::class)->createPersonalAccessClient($data);
```

### ✅ CORRETTO

```php
app(CreatePersonalAccessClientAction::class)->execute($data);
```

### Perché?

- Spatie Queueable Actions impone un unico entry point: `execute()`
- Un'Action = Una Responsabilità = Un `execute()`
- Se serve comportamento diverso, crea Action DIVERSA
- API prevedibile e uniforme in tutto il codebase

---

## Regola 2: Mai Dependency Injection Pesante

### ❌ SBAGLIATO

```php
public function __construct(
    private readonly DatabaseManager $dbManager,
    private readonly LoggerInterface $logger,
    private readonly Hasher $hasher,
    private readonly SafeStringCastAction $safeStringCastAction,
) {}
```

### ✅ CORRETTO

```php
class CreatePersonalAccessClientAction
{
    use QueueableAction;

    public function execute(ClientData $data): OauthClient
    {
        // Le dipendenze si risolvono inline via app() se servono
        // oppure si iniettano SOLO quelle strettamente necessarie
    }
}

// Invocazione:
app(CreatePersonalAccessClientAction::class)->execute($data);
```

### Perché?

- **KISS**: Il container di Laravel risolve automaticamente
- **DRY**: Non duplicare le dipendenze
- **Disaccoppiamento**: Il chiamante non deve conoscere le dipendenze interne
- **Spatie Design**: `app(Action::class)->execute()` è il pattern idiomatico
- **Leggibilità**: Meno codice = meno bug

---

## Pattern Completo

```php
<?php
declare(strict_types=1);

namespace Modules\{ModuleName}\Actions;

use Spatie\QueueableAction\QueueableAction;

class DoSomethingAction
{
    use QueueableAction;

    /**
     * Se serve una dipendenza, max 1-2 e solo se strettamente necessarie.
     * Preferire app() inline per dipendenze occasionali.
     */
    public function execute(SomeData $data): SomeResult
    {
        // Business logic qui
    }
}

// Invocazione SEMPRE così:
app(DoSomethingAction::class)->execute($data);
```

---

## Dynamic Event Loading

Gli eventi sul frontend sono caricati dinamicamente dal database usando:
1. **Configurazione JSON** - file come `events.json` definiscono content_block
2. **Folio Page** - renderizza tramite componente `<x-page />`
3. **toBlockArray()** - trasforma il modello in array per il Blade
4. **URL SEO-friendly** - basato su slug

---

## StaticAccess Accettabili

Alcune eccezioni sono pragmaticamente accettabili:

1. **Filament Components e Actions** - design idiomatico
2. **Eloquent Model Queries in Test** - standard in contesto test
3. **Webmozart Assert** - API statica prevista
4. **Carbon** - helper fondamentale in Laravel
5. **LaravelLocalization** - servizio ben definito

---

## 🔗 Link

- [Indice GEMINI](./gemini-split-index.md)
- [queueable-actions.md](./queueable-actions.md)
- [gemini.md originale](../../gemini.md)
- [Index principale](./index.md)
