---
title: "Area personale — rotte FO (deprecato, vedi canon)"
type: concept
tags: [folio, header, routing, deprecated, sixteen]
created: 2026-06-05
updated: 2026-06-10
qmd: "area personale personal area routes deprecated notifications folio named route"
issues:
  - https://github.com/laraxot/base_fixcity_fila5/issues/289
related:
  - fo-folio-named-routes-header.md
  - fo-folio-routing-zen.md
  - ../../../../Modules/Cms/docs/wiki/concepts/folio-filesystem-routing-no-web-php.md
---

# Area personale — rotte FO

> **Deprecato come guida autonoma.** Usare i canon sotto — questo file resta solo per qmd/backlink storici.

## Canon attuale (2026-06-10)

| Argomento | Documento |
|-----------|-----------|
| Dropdown header + `route()` | [fo-folio-named-routes-header.md](fo-folio-named-routes-header.md) |
| File = rotta, no web.php | [fo-folio-routing-zen.md](fo-folio-routing-zen.md) |
| Mount + modello mentale | [folio-filesystem-routing-no-web-php.md](../../../../Modules/Cms/docs/wiki/concepts/folio-filesystem-routing-no-web-php.md) |
| Cache stale | [route-not-found-view-cache.md](../troubleshooting/route-not-found-view-cache.md) |

## Cosa NON fare (imparato)

- **Vietato** nomi route italiani (`area-personale.notifiche`, `area-personale.services`)
- **Vietato** registrare FO in `web.php`
- **Vietato** `FrontofficeUrl::personalArea*` per voci menu dropdown
- **Corretto** notifiche: `name('notifications')` (User) + `route('notifications')` (Sixteen)

## Notifiche — mapping definitivo

| Layer | Valore |
|-------|--------|
| Pagina Folio | `Modules/User/resources/views/pages/notifications/index.blade.php` |
| `name()` | `notifications` |
| URL | `/it/notifications` |
| Label header | `pub_theme::header.user.dropdown.notifications.label` |
