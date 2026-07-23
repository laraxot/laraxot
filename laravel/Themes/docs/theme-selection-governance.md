# Theme Selection Governance

## Rule

Il tema pubblico non deve essere registrato hardcoded in `app/Providers/AppServiceProvider.php`.

Pattern vietato:

```php
$this->app->register(\Themes\Sixteen\Providers\ThemeServiceProvider::class);
```

## Why

Il tema e un vestito, non il motore applicativo.

Questo significa:
- il tema deve restare sostituibile
- il namespace pubblico resta `pub_theme`
- la selezione del tema passa da configurazione e bootstrap del sistema temi, non dal provider generico dell'app
- `AppServiceProvider` non deve conoscere `Themes\Sixteen`

## Correct Direction

Il root `laravel/composer.json` resta minimale e il sistema usa:
- `nwidart/laravel-modules`
- `wikimedia/composer-merge-plugin`
- configurazione del tema pubblico via config

Il codice applicativo deve dipendere da `pub_theme`, non da un tema concreto.
