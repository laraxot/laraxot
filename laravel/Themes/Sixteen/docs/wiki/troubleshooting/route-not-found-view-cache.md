---
title: "RouteNotFoundException — vista compilata stale"
type: troubleshooting
tags: [folio, route, view-cache, header, six]
created: 2026-06-10
updated: 2026-06-10
qmd: "RouteNotFoundException area-personale notifiche view clear blade cache artisan serve restart"
issues:
  - https://github.com/laraxot/base_fixcity_fila5/issues/289
related:
  - ../concepts/fo-folio-named-routes-header.md
  - ../concepts/fo-folio-routing-zen.md
  - ../../../../Modules/Cms/docs/wiki/troubleshooting/folio-route-not-found.md
---

# RouteNotFoundException — vista compilata stale

## Sintomo

`Route [area-personale.notifiche] not defined` su `user-dropdown.blade.php:57` dopo login, su `GET /it`.

Stack trace punta a `route()` nel partial header — ma il sorgente su disco può già avere `route('notifications')`.

## Cause

1. **Nome rotta sbagliato** (storico): `area-personale.notifiche` non esiste — usare `notifications`.
2. **Cache stale**: Blade compilato in `storage/framework/views/` con vecchio `route('area-personale.notifiche')`.
3. **Processo PHP vecchio**: `php artisan serve` avviato prima del fix, non rilegge view.

## Verifica sorgente

```bash
grep -n "route(" laravel/Themes/Sixteen/resources/views/components/sections/header/partials/user-dropdown.blade.php
# riga ~56: route('notifications')
```

```bash
cd laravel && php artisan folio:list | grep notifications
php artisan tinker --execute="echo route('notifications');"
```

## Fix operativo

```bash
cd laravel
php artisan view:clear
php artisan optimize:clear
```

Riavviare il server dev (`Ctrl+C` → `php artisan serve`). Hard refresh browser.

## Contratto corretto

| Campo | Valore |
|-------|--------|
| Pagina | `Modules/User/resources/views/pages/notifications/index.blade.php` |
| `name()` | `notifications` |
| Header link | `route('notifications')` |
| Label | `pub_theme::header.user.dropdown.notifications.label` |


## Diagnostica riga stack trace

| Stack `user-dropdown.blade.php:57` | Significato |
|-------------------------------------|-------------|
| Errore su `area-personale.notifiche` | **Vecchio** Blade compilato (prima del fix riga 57 era `route()`) |
| Sorgente attuale riga 56 | `route('notifications')` — riga 57 è solo label tradotta |

```bash
grep -n "route(" laravel/Themes/Sixteen/resources/views/components/sections/header/partials/user-dropdown.blade.php
grep -r "area-personale.notifiche" laravel/storage/framework/views/ || echo "OK"
```

## Imparato

- Tema **vestito**: link `route()`, pagine owner nei moduli (User per notifiche).
- FO **mai** `web.php` — vedi [folio-filesystem-routing-no-web-php.md](../../../../Modules/Cms/docs/wiki/concepts/folio-filesystem-routing-no-web-php.md).
- Audit FO: `folio:list`, non `route:list` — [folio-list-vs-route-list.md](../../../../Modules/Cms/docs/wiki/concepts/folio-list-vs-route-list.md).


## Collegamenti

- [Header named routes](../concepts/fo-folio-named-routes-header.md)
- [Folio filesystem routing (Cms)](../../../../Modules/Cms/docs/wiki/troubleshooting/folio-route-not-found.md)
