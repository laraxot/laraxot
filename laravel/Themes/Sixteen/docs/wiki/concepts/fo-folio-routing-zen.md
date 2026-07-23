---
title: "FO Folio — zen routing (file = rotta)"
type: concept
tags: [folio, sixteen, routing, frontoffice, theme]
created: 2026-06-10
updated: 2026-06-10
qmd: "sixteen folio pages web.php route name filesystem theme vestito area-personale notifiche"
related:
  - fo-folio-named-routes-header.md
  - ../troubleshooting/route-not-found-view-cache.md
  - ../../../../Modules/Cms/docs/wiki/concepts/folio-filesystem-routing-no-web-php.md
  - ../../../../../../docs/wiki/memories/folio-no-web-routes-religion.md
---

# FO Folio — zen routing (file = rotta)

## Politica tema

**Sixteen è vestito**: non registra rotte. Espone pagine in `resources/views/pages/`; il dominio vive nei moduli (`User`, `Fixcity`, …).

`FolioVoltServiceProvider` (Cms) monta tema + moduli con `Folio::path()->uri($locale)`.

## `route()` non definisce nulla

`route('notifications')` **risolve** l'URL di un file Folio con `name('notifications')`. Se assente da `folio:list` → `RouteNotFoundException`. Nessuna registrazione parallela in `web.php`.

Canon: [folio-filesystem-routing-no-web-php.md](../../../../Modules/Cms/docs/wiki/concepts/folio-filesystem-routing-no-web-php.md).


## Domanda giusta

| Sbagliata | Giusta |
|-----------|--------|
| «Dove metto `Route::get` in web.php?» | «In quale modulo/tema creo il file `pages/`?» |
| «Come registro `area-personale.notifiche`?» | «Quale `name()` inglese metto nel file Folio?» |

## Caso reale: notifiche header

| Errore | Corretto |
|--------|----------|
| `route('area-personale.notifiche')` | `route('notifications')` |
| path inventato `/it/area-personale/notifiche` | Folio `pages/notifications/index.blade.php` |
| label IT nel `name()` | label IT solo in `pub_theme::header.user.dropdown.*` |

Se il sorgente è corretto ma l'errore resta → [route-not-found-view-cache.md](../troubleshooting/route-not-found-view-cache.md).

## Tre errori ricorrenti

1. **`Route::` in `web.php`** per FO — doppia fonte di verità.
2. **`name()` in italiano** — assente da `folio:list`, `RouteNotFoundException`.
3. **Confondere `route()` con definizione** — genera URL verso Folio esistente; la rotta è il file.

## Due canali link nel header

- **Dropdown utente** → `route('<folio-name>')` ([fo-folio-named-routes-header.md](fo-folio-named-routes-header.md))
- **Nav CMS `header.json`** → `FrontofficeUrl::fromStoredUrl($url)` ([fo-folio-links-multilingua.md](fo-folio-links-multilingua.md))


## Sintesi imparata (2026-06-10)

1. **File = rotta** — `pages/notifications/index.blade.php` è la definizione; `web.php` non entra in gioco.
2. **`name()` registra, `route()` linka** — confusione principale su Folio.
3. **`folio:list` > `route:list`** per audit FO ([folio-list-vs-route-list.md](../../../../Modules/Cms/docs/wiki/concepts/folio-list-vs-route-list.md)).
4. **Label IT / name EN** — `pub_theme::header...` vs `name('notifications')`.
5. **Cache stale** — fix sorgente + `view:clear` + riavvio serve.


## Audit

```bash
cd laravel && php artisan folio:list
```

Pest: `tests/Unit/HeaderAreaPersonaleLinksContractTest.php`
