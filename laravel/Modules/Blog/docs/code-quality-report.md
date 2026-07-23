# Code quality — modulo Blog

Report locale (2026-07-17). Metodo: `phpstan analyse` livello max, `phpmd` (ruleset codesize+unusedcode), grep mirati (TODO/FIXME/@deprecated, dd()/dump(), facade in app/Actions, extends Filament diretto), rapporto file test/app.

## Numeri

- File in `app/`: 280
- File di test: 0 — rapporto test/app: 0%
- File con TODO/FIXME/@deprecated: 0
- PHPStan: 0 errori (livello max, sweep repo-wide 2026-07-16/17)
- Violazioni PHPMD (codesize+unusedcode): 6
- File in `app/Actions/` che importano Facade Laravel direttamente (violazione pattern QueueableAction, vedi skill `queueable-action-trait`): 2

### File con Facade in Actions da convertire

- Modules/Blog/app/Actions/ImportFromNewsApi.php
- Modules/Blog/app/Actions/Article/GetArticleMainImageUrlAction.php

### Complessità / dimensione classi da rivedere

- Modules/Blog/app/Models/Article.php:231                                                           TooManyPublicMethods      The class Article has 18 public methods. Consider refactoring Article to keep number of public methods under 10.
- Modules/Blog/app/View/Composers/Support/ThemeArticleQueries.php:12                                TooManyPublicMethods      The class ThemeArticleQueries has 13 public methods. Consider refactoring ThemeArticleQueries to keep number of public methods under 10.
- Modules/Blog/app/View/Composers/Support/ThemeArticleSupport.php:14                                TooManyPublicMethods      The class ThemeArticleSupport has 20 public methods. Consider refactoring ThemeArticleSupport to keep number of public methods under 10.

## Stato architetturale

- Nessuna violazione `extends \Filament\...` diretto rilevata (regola XotBase rispettata).

## Azioni consigliate

- **Priorità alta**: copertura test sotto il 20%, aggiungere test Pest Feature/Unit sui path critici.
- Convertire le 2 Action con Facade dirette al pattern QueueableAction (niente facade nella cartella Actions).
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

### 1. Rimuovere le Facade da `app/Actions/`

Regola del progetto (skill `queueable-action-trait`): nelle Action **niente Facade**, le dipendenze si iniettano nel costruttore — il container le risolve automaticamente quando l'Action viene chiamata con `app(XxxAction::class)->execute(...)`.

Facade usate in questo modulo e relativa dipendenza da iniettare al loro posto:

| Facade | Inietta invece |
|---|---|
| `Http::` | `Illuminate\Http\Client\Factory` |
| `Storage::` | `Illuminate\Contracts\Filesystem\Factory` |

**Esempio concreto** — `Modules/Blog/app/Actions/ImportFromNewsApi.php`:

```php
// PRIMA
use Illuminate\Support\Facades\Http;

class XxxAction
{
    use QueueableAction;

    public function execute(string $arg): mixed
    {
        $response = Http::get($url);
        // ...
    }
}

// DOPO
use Illuminate\Http\Client\Factory as HttpFactory;

class XxxAction
{
    use QueueableAction;

    public function __construct(private readonly HttpFactory $http)
    {
    }

    public function execute(string $arg): mixed
    {
        $response = $this->http->get($url);
        // ...
    }
}
```

Vantaggio pratico: l'Action diventa testabile senza `Http::fake()` globale — nei test Pest si passa un mock/fake del client via `app()->instance(HttpFactory::class, $fakeClient)` o via binding nel service provider di test.

File da convertire in questo modulo (elenco sopra in "Numeri"), uno alla volta, con `php -l` + PHPStan L max sul singolo file dopo ogni modifica.

### 3. Alzare la copertura test (attualmente 0%)

Struttura minima di un test Pest per un'Action di questo modulo (adattare namespace/nome):

```php
<?php

declare(strict_types=1);

use Modules\Blog\Actions\ExampleAction;

it('esegue la logica attesa con input valido', function () {
    $result = app(ExampleAction::class)->execute($validInput);

    expect($result)->not->toBeNull();
});

it('gestisce input non valido senza eccezioni non gestite', function () {
    app(ExampleAction::class)->execute($invalidInput);
})->throws(\InvalidArgumentException::class);
```

Priorità di stesura: prima le Action richiamate da Filament Resource/Livewire (più esposte a input utente), poi i Model con business logic negli accessor/scope, infine helper puri.
