# Code quality — tema Sixteen

Report locale (2026-07-17). Metodo: `phpstan analyse` (sweep repo-wide, incluso nei Themes), `phpmd` (codesize+unusedcode), grep mirati (TODO/FIXME, dd()/dump() nei .blade.php, facade dirette in app/Actions).

## Numeri

- File PHP applicativi (`app/`): 50
- File Blade: 632
- File con TODO/FIXME/@deprecated: 0
- `dd()`/`dump()`/`var_dump()` residui in Blade: 0
- Violazioni PHPMD (codesize+unusedcode): 72
- Facade Laravel dirette in `app/Actions/` (violazione pattern QueueableAction): 2
- PHPStan: incluso nello sweep repo-wide, 0 errori residui noti

### File da convertire

- Themes/Sixteen/app/Actions/SpidAuthAction.php
- Themes/Sixteen/app/Actions/CieAuthAction.php

## Azioni consigliate

- Convertire le Action con Facade dirette al pattern QueueableAction.
- PHPMD segnala 72 violazioni codesize/unusedcode: rivedere i metodi/classi più complessi (vedi output completo phpmd).
- La qualità delle view Blade/Volt (duplicazione, componenti riusabili) non è stata misurata quantitativamente in questo giro — possibile follow-up con un audit dedicato ai componenti.


## Come migliorare — modifiche effettive da fare

### 1. Rimuovere le Facade da `app/Actions/`

Regola del progetto (skill `queueable-action-trait`): nelle Action **niente Facade**, le dipendenze si iniettano nel costruttore — il container le risolve automaticamente quando l'Action viene chiamata con `app(XxxAction::class)->execute(...)`.

Facade usate in questo modulo e relativa dipendenza da iniettare al loro posto:

| Facade | Inietta invece |
|---|---|
| `Http::` | `Illuminate\Http\Client\Factory` |
| `Log::` | `Psr\Log\LoggerInterface` |
| `Session::` | `Illuminate\Contracts\Session\Session` |

**Esempio concreto** — `Themes/Sixteen/app/Actions/SpidAuthAction.php`:

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

