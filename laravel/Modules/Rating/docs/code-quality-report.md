# Code quality — modulo Rating

Report locale (2026-07-17). Metodo: `phpstan analyse` livello max, `phpmd` (ruleset codesize+unusedcode), grep mirati (TODO/FIXME/@deprecated, dd()/dump(), facade in app/Actions, extends Filament diretto), rapporto file test/app.

## Numeri

- File in `app/`: 57
- File di test: 4 — rapporto test/app: 7%
- File con TODO/FIXME/@deprecated: 0
- PHPStan: 0 errori (livello max, sweep repo-wide 2026-07-16/17)
- Violazioni PHPMD (codesize+unusedcode): 16
- File in `app/Actions/` che importano Facade Laravel direttamente (violazione pattern QueueableAction, vedi skill `queueable-action-trait`): 0

### Complessità / dimensione classi da rivedere

- Modules/Rating/app/Datas/RatingData.php:34                                                        CyclomaticComplexity      The method fromArray() has a Cyclomatic Complexity of 11. The configured cyclomatic complexity threshold is 10.

## Stato architetturale

- Nessuna violazione `extends \Filament\...` diretto rilevata (regola XotBase rispettata).

## Azioni consigliate

- **Priorità alta**: copertura test sotto il 20%, aggiungere test Pest Feature/Unit sui path critici.
- Rifattorizzare i metodi/classi elencati sopra (complessità ciclomatica/NPath oltre soglia).

## Confronto con gli altri moduli (rapporto test/app)

| Modulo | app | test | % | facade-in-Actions |
|---|---|---|---|---|
| Activity | - | - | 127% | 5 |
| AI | - | - | 42% | 2 |
| Blog | - | - | 0% | 2 |
| Cms | - | - | 102% | 1 |
| Comment | - | - | 26% | 2 |
| Employee | - | - | 26% | 1 |
| Gdpr | - | - | 52% | 4 |
| Geo | - | - | 41% | 34 |
| Job | - | - | 21% | 3 |
| Lang | - | - | 30% | 3 |
| Media | - | - | 11% | 10 |
| Notify | - | - | 61% | 21 |
| Rating | - | - | 7% | 0 |
| Seo | - | - | 100% | 0 |
| TechPlanner | - | - | 2% | 0 |
| Tenant | - | - | 75% | 6 |
| UI | - | - | 34% | 4 |
| User | - | - | 23% | 4 |
| Xot | - | - | 28% | 57 |



## Come migliorare — modifiche effettive da fare

### 2. Ridurre la complessità ciclomatica

Metodi/classi oltre soglia (10 per metodo, 50 per classe) in questo modulo:

- Modules/Rating/app/Datas/RatingData.php:34                                                        CyclomaticComplexity      The method fromArray() has a Cyclomatic Complexity of 11. The configured cyclomatic complexity threshold is 10.

Tecnica di refactoring consigliata: **estrarre ogni ramo condizionale in un metodo privato dedicato**, o sostituire lunghe catene if/elseif con una `match()` che delega a metodi/Action più piccoli. Esempio:

```php
// PRIMA — un metodo con 15+ rami
public function resolveType(string $type): string
{
    if ($type === "a") { /* ... */ }
    elseif ($type === "b") { /* ... */ }
    // ... altri 10+ rami
}

// DOPO — dispatch table, ogni ramo è un metodo testabile singolarmente
public function resolveType(string $type): string
{
    return match ($type) {
        "a" => $this->resolveA(),
        "b" => $this->resolveB(),
        default => throw new \InvalidArgumentException("Unknown type: {$type}"),
    };
}
```

Ogni `resolveX()` estratto scende sotto soglia 10 e diventa testabile in isolamento con un test Pest dedicato.

### 3. Alzare la copertura test (attualmente 7%)

Struttura minima di un test Pest per un'Action di questo modulo (adattare namespace/nome):

```php
<?php

declare(strict_types=1);

use Modules\Rating\Actions\ExampleAction;

it('esegue la logica attesa con input valido', function () {
    $result = app(ExampleAction::class)->execute($validInput);

    expect($result)->not->toBeNull();
});

it('gestisce input non valido senza eccezioni non gestite', function () {
    app(ExampleAction::class)->execute($invalidInput);
})->throws(\InvalidArgumentException::class);
```

Priorità di stesura: prima le Action richiamate da Filament Resource/Livewire (più esposte a input utente), poi i Model con business logic negli accessor/scope, infine helper puri.
