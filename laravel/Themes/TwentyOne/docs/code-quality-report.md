# Code quality — tema TwentyOne

Report locale (2026-07-17). Metodo: `phpstan analyse` (sweep repo-wide, incluso nei Themes), `phpmd` (codesize+unusedcode), grep mirati (TODO/FIXME, dd()/dump() nei .blade.php, facade dirette in app/Actions).

## Numeri

- File PHP applicativi (`app/`): 2
- File Blade: 258
- File con TODO/FIXME/@deprecated: 0
- `dd()`/`dump()`/`var_dump()` residui in Blade: 2
- Violazioni PHPMD (codesize+unusedcode): 1
- Facade Laravel dirette in `app/Actions/` (violazione pattern QueueableAction): 1
- PHPStan: incluso nello sweep repo-wide, 0 errori residui noti

### File da convertire

- Themes/TwentyOne/app/Actions/Hero/ResolveCinematicHeroDataAction.php

### Blade con dd()/dump() da rimuovere

- Themes/TwentyOne/resources/views/foliopages/[lang]/contact_us.blade.php
- Themes/TwentyOne/resources/views/article/show/content.blade.php

## Azioni consigliate

- Convertire le Action con Facade dirette al pattern QueueableAction.
- Rimuovere i `dd()`/`dump()` residui dalle view elencate.
- La qualità delle view Blade/Volt (duplicazione, componenti riusabili) non è stata misurata quantitativamente in questo giro — possibile follow-up con un audit dedicato ai componenti.


## Come migliorare — modifiche effettive da fare

### 1. Rimuovere le Facade da `app/Actions/`

Regola del progetto (skill `queueable-action-trait`): nelle Action **niente Facade**, le dipendenze si iniettano nel costruttore — il container le risolve automaticamente quando l'Action viene chiamata con `app(XxxAction::class)->execute(...)`.

Facade usate in questo modulo e relativa dipendenza da iniettare al loro posto:

| Facade | Inietta invece |
|---|---|
| `Schema::` | `Illuminate\Database\ConnectionInterface (poi ->getSchemaBuilder())` |

**Esempio concreto** — `Themes/TwentyOne/app/Actions/Hero/ResolveCinematicHeroDataAction.php`:

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

