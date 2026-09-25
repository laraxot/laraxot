---
title: "Folio app pages — Volt + PageSlugMiddleware (non procedurale)"
type: concept
tags: [folio, volt, sixteen, area-personale, pageslugmiddleware, auth]
created: 2026-07-13
updated: 2026-07-13
qmd: "folio volt app page mount PageSlugMiddleware no procedural php auth area-personale pratiche"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/362"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/363"
related:
  - ../../folio-page-pattern.md
  - ../../../../Modules/Cms/docs/wiki/concepts/cms-page-middleware-json-ssot.md
  - ../../../../Modules/Cms/docs/wiki/concepts/folio-volt-static-mount-contract.md
  - fo-folio-routing-zen.md
---

# Folio app pages — Volt + PageSlugMiddleware

## Perché (religione)

Le pagine Folio del front office non sono script PHP: sono **componenti Livewire Volt** con stato e lifecycle (`mount()`). La logica di dominio (query, paginazione, redirect) vive **dentro** la classe anonima, non in righe procedurali prima del `?>`.

| Anti-pattern | Problema |
|--------------|----------|
| `$tickets = app(...)->execute()` prima del markup | Nessun lifecycle Livewire, difficile testare, bypassa Volt |
| `middleware(['web', 'auth'])` hardcoded | Duplica il JSON CMS; auth non è SSoT |
| Blade senza `@volt('…')` uguale a `name()` | `VoltDirectiveMissingException` o stato non reattivo |

## Pattern corretto (pagina applicativa)

Esempio: `Modules/User/resources/views/pages/area-personale/pratiche.blade.php`

```php
name('area-personale.pratiche');
middleware(PageSlugMiddleware::class);

new class extends Component {
    public LengthAwarePaginator $tickets;

    public function mount(): void
    {
        $this->tickets = app(BuildAuthenticatedUserTicketsQueryAction::class)
            ->execute()
            ->paginate(15);
    }
};
```

```blade
@volt('area-personale.pratiche')
    {{-- markup che usa $tickets --}}
@endvolt
```

## Auth — JSON CMS, non Folio

1. Folio registra **solo** `middleware(PageSlugMiddleware::class)`.
2. `PageSlugMiddleware` risolve lo slug da `route()->getName()` (es. `area-personale.pratiche`).
3. `config/local/.../content/pages/area-personale.pratiche.json` definisce `"middleware": ["auth"]`.

Vietato `middleware(['web', 'auth'])` sul Folio quando esiste il record CMS con lo stesso slug.

Nel JSON CMS il campo `middleware` contiene middleware singoli risolvibili dal kernel, non gruppi: usare `["auth"]`, non `["web", "auth"]`. Il gruppo `web` e gia nella pipeline HTTP/Folio e dentro `PageSlugMiddleware` causerebbe `Target class [web] does not exist`.

**Fallback in `mount()`:** se il record CMS non è ancora in Sushi/JSON runtime, `PageSlugMiddleware` non applica `auth` — le pagine app possono reindirizzare a login in `mount()` prima di query DB (es. `pratiche`).

Canon: [cms-page-middleware-json-ssot.md](../../../../Modules/Cms/docs/wiki/concepts/cms-page-middleware-json-ssot.md).

## Due famiglie di pagine Sixteen

| Famiglia | Path Folio | Shell | Logica in `mount()` |
|----------|------------|-------|---------------------|
| CMS data bag | `Sixteen/pages/[container0]/…` | `<x-page :slug :data>` | Solo `$pageSlug` + `$data` |
| App page | `Modules/User/pages/area-personale/*`, `dashboard` | Markup tema | Query, redirect in `mount()` |

**Cartelle tema:** solo `auth/`, `[container0]/`, `tests/` (+ `index.blade.php`). Regola: [sixteen-pages-folder-religion.md](../rules/sixteen-pages-folder-religion.md).

Entrambe condividono: `new class extends Component`, `@volt` statico = `name()`, `PageSlugMiddleware`.

## Link FO

Nei blade tema: `LaravelLocalization::localizeURL('/path')` — non `route()` generico (regola FO).

Header dropdown: `route('notifications')`, `route('dashboard')` — nomi Folio verificati in `folio:list`.

## Notifiche — alias deprecato

- Canon header: `route('notifications')` → `Modules/User/.../notifications/index.blade.php`
- Path legacy `/area-personale/notifiche`: Folio Volt con `mount()` → redirect a `/notifications`

## Conversione pratica: area-personale/pratiche

Prima (procedurale — vietato):

```php
name('area-personale.pratiche');
middleware(['web', 'auth']);
$tickets = app(BuildAuthenticatedUserTicketsQueryAction::class)->execute()->paginate(15);
```

Dopo (Volt + PageSlugMiddleware):

```php
use Modules\Cms\Http\Middleware\PageSlugMiddleware;
use Modules\Fixcity\Actions\BuildAuthenticatedUserTicketsQueryAction;

name('area-personale.pratiche');
middleware(PageSlugMiddleware::class);

new class extends Component {
    public LengthAwarePaginator $tickets;

    public function mount(): void
    {
        $this->tickets = app(BuildAuthenticatedUserTicketsQueryAction::class)
            ->execute()
            ->paginate(15);
    }
};
```

Il markup è racchiuso in `@volt('area-personale.pratiche') ... @endvolt`.

## Verifica

```bash
cd laravel && php artisan folio:list | rg area-personale
cd laravel && php artisan view:cache
```

## Collegamenti

- [folio-page-pattern.md](../../folio-page-pattern.md) — mount + x-page
- [personal-area-routes.md](personal-area-routes.md) — mapping rotte area personale
- [fo-folio-routing-zen.md](fo-folio-routing-zen.md) — file = rotta
