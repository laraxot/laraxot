---
title: Folio Frontend Architecture - No Controller/Routes
description: Regola architettonica per il frontend: routing basato su file, named route Folio verificate
tags: [architecture, folio, routing, frontend, controllers]
updated: 2026-06-05
sources:
  - https://laravel.com/docs/11.x/folio
related:
  - ../concepts/fo-folio-named-routes-header.md
  - ../concepts/fo-folio-links-multilingua.md
  - ../../../../../../docs/wiki/rules/folio-frontoffice-navigation-links.md
---

# Folio Frontend Architecture Rule

## Principio Fondamentale

**IL FRONT OFFICE UTILIZZA Folio — NON DEVE MAI USARE `route('user.*')` / `route('tests.view')` PER NAVIGAZIONE**

Menu utente header: **named route Folio** verificate (`route('services.categories')` ecc.).

## Architettura

| Livello | Tecnologia | Caratteristiche |
|---------|------------|-----------------|
| **Frontend (FO)** | **Folio** | File-based routing, Blade pages, nessun Controller |
| **Backend (BO)** | **Filament** | Admin panel, cluster/pages, rotte gestite |

## SSoT link header Sixteen

- Dropdown utente: [fo-folio-named-routes-header.md](../concepts/fo-folio-named-routes-header.md)
- Nav CMS/JSON: `FrontofficeUrl::fromStoredUrl()` — [fo-folio-links-multilingua.md](../concepts/fo-folio-links-multilingua.md)

### ✅ CORRETTO
```blade
<a href="{{ route('services.categories') }}">
    <span>{{ __('pub_theme::header.user.dropdown.my_services.label') }}</span>
</a>
```

### ❌ ERRATO
```blade
<a href="{{ route('user.services') }}">Servizi</a>
<a href="{{ \Themes\Sixteen\Support\FrontofficeUrl::personalAreaServices() }}">Servizi</a>
<a href="/it/profilo/servizi"><span>I miei servizi</span></a>
```

## Perché?

1. **Folio** genera rotte da `resources/views/pages/`
2. **`route('<folio-name>')`** è il contratto Laravel verso quel file
3. **Multilingua** via mcamara — prefisso `/{locale}/`
4. **Filament** gestisce il backend — non confondere con menu cittadino

## Verifica

- [ ] Header partial usa named route Folio (`folio:list`)
- [ ] Nessun `route('user.*')` / `route('tests.view')` in `sections/header/`
- [ ] Pest `HeaderAreaPersonaleLinksContractTest` verde

## Riferimenti

- [fo-folio-named-routes-header.md](../concepts/fo-folio-named-routes-header.md)
- [folio-frontoffice-navigation-links.md](../../../../../../docs/wiki/rules/folio-frontoffice-navigation-links.md)
- [Laravel Folio Docs](https://laravel.com/docs/11.x/folio)
