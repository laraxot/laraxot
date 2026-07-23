---
title: "Header FO — named route Folio verificate"
type: concept
tags: [folio, header, routing, named-routes, six]
created: 2026-06-05
updated: 2026-06-10
qmd: "folio named route header services.categories notifications dashboard profile.edit logout area-personale forbidden"
issues:
  - https://github.com/laraxot/base_fixcity_fila5/issues/289
related:
  - fo-folio-routing-zen.md
  - fo-folio-links-multilingua.md
  - ../troubleshooting/route-not-found-view-cache.md
  - ../../../../Modules/Cms/docs/wiki/concepts/folio-filesystem-routing-no-web-php.md
  - ../../../../../../docs/wiki/rules/folio-frontoffice-navigation-links.md
---

# Header FO — named route Folio verificate

## Regola

Il dropdown utente usa **`route('<name>')`** solo per pagine Folio con `name()` registrato. La rotta **non** si definisce in `web.php` — nasce dal file in `pages/` ([fo-folio-routing-zen.md](fo-folio-routing-zen.md)).

**Non** usare wrapper path inventati (`FrontofficeUrl::personalArea*`).

```bash
cd laravel && php artisan folio:list
```

## Mapping menu (2026-06-10)

| Voce UI (traduzione) | `route()` | File Folio |
|----------------------|-----------|------------|
| I miei servizi | `services.categories` | `Themes/Sixteen/.../lista-categorie.blade.php` |
| Le mie pratiche | `dashboard` | `Modules/User/.../dashboard/index.blade.php` |
| Notifiche | `notifications` | `Modules/User/.../notifications/index.blade.php` |
| Impostazioni | `profile.edit` | `Modules/User/.../profile/edit.blade.php` |
| Esci | `logout` | `Themes/Sixteen/.../auth/logout.blade.php` |
| Accedi (guest) | `login` | `Themes/Sixteen/.../auth/login*.blade.php` |

## Esempio canonico

```blade
<a href="{{ route('notifications') }}" role="menuitem">
    <span>{{ __('pub_theme::header.user.dropdown.notifications.label') }}</span>
</a>
```

Copy menu: sempre `pub_theme::header.user.dropdown.*.label` — **mai** stringhe italiane hardcoded nel Blade.

## Vietato

```blade
route('area-personale.notifiche')   {{-- nome inventato, italiano --}}
route('area-personale.*')
route('user.services')
route('tests.view', ['slug' => 'servizi'])
FrontofficeUrl::personalAreaNotifications()
href="/{{ app()->getLocale() }}/profilo/notifiche"
<span>I miei servizi</span>           {{-- usare __() --}}
```

## `FrontofficeUrl` — solo CMS JSON

`path()`, `fromStoredUrl()`, `testsParity()` — **non** per voci menu dropdown.

## Perché (zen)

- **Label** menu in italiano (traduzioni tema).
- **`name()` Folio** sempre in inglese, allineato al filesystem `pages/`.
- `route()` = URL verso file Folio; `web.php` = anti‑pattern FO.



## Imparato (sintesi sessione)

1. **`route()` nel Blade non definisce rotte** — legge named route Folio già registrate con `name()` nel file `pages/`.
2. **Audit FO = `folio:list`** — `route:list` può mostrare solo Filament; `route('notifications')` funziona comunque se Folio ha la pagina.
3. **Errore con sorgente corretto** → `view:clear` + riavvio `artisan serve` ([route-not-found-view-cache.md](../troubleshooting/route-not-found-view-cache.md)).
4. **Partial canonico**: `sections/header/partials/user-dropdown.blade.php` (non legacy `components/header/user-dropdown`).


## Verifica

- Pest: `tests/Unit/HeaderAreaPersonaleLinksContractTest.php`
- CLI: `php artisan folio:list | grep notifications`
- Troubleshooting cache: [route-not-found-view-cache.md](../troubleshooting/route-not-found-view-cache.md)
