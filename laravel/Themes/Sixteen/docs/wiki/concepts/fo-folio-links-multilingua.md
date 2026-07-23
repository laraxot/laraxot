---
title: "Link FO multilingua — path Folio + FrontofficeUrl"
type: concept
tags: [folio, localization, frontoffice, header, navigation, dry]
created: 2026-06-05
updated: 2026-06-05
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/285"
  - "https://github.com/laraxot/theme_sixteen_fila5/issues/64"
discussions:
  - "https://github.com/laraxot/base_fixcity_fila5/discussions/287"
  - "https://github.com/laraxot/theme_sixteen_fila5/discussions/67"
related:
  - header-authenticated-state.md
  - ../../../../../../docs/wiki/rules/folio-frontoffice-navigation-links.md
  - ../../../../../../docs/wiki/memories/folio-frontoffice-links-localize-url.md
  - ../../../../../../laravel/Modules/Cms/docs/folio-routing-locale.md
---

# Link FO multilingua — path Folio + `FrontofficeUrl`

## Religione

| Layer | Routing | Link in Blade |
|-------|---------|---------------|
| **Frontoffice** | Folio `resources/views/pages/` | `route('<folio-name>')` verificata con `folio:list`; `FrontofficeUrl` solo CMS/JSON |
| **Backoffice** | Filament | `Resource::getUrl()` / panel routes |
| **API** | Folio `pages/api/` + Action | Path o Action, mai Controller app |

**Vietato** nel menu/header FO e nei blocchi CTA (`cta/ticket`):

- `Themes\Sixteen\Support\FrontofficeUrl` — scope solo CMS/JSON nav (`fromStoredUrl`)
- `route('user.services')`, `route('tests.view', …)` e rotte named modulo per navigazione
- `url('/servizi')` senza prefisso lingua
- Controller + `Route::get` per pagine HTML cittadino

**Consentito** (eccezioni strette):

- `route('logout')` solo se verificata da `php artisan folio:list` come route Folio auth
- Rotte Filament solo in BO

## SSoT codice

`Themes\Sixteen\Support\FrontofficeUrl` resta utility per path CMS/JSON (`path()`, `fromStoredUrl()`); il dropdown utente deve usare named route Folio reali.

| Metodo | Path Folio | Destinazione |
|--------|------------|--------------|
| `services.categories` | `/it/lista-categorie` | Catalogo servizi, route name inglese su pagina Folio esistente |
| `dashboard` | `/it/dashboard` | Pratiche / area utente, Folio User |
| `notifications` | `/it/notifications` | Notifiche, pagina Folio tema Sixteen |
| `profile.edit` | `/it/profile/edit` | Impostazioni profilo, Folio User |
| `logout` | `/it/auth/logout` | Logout Folio auth |
| `tests.view` | `/it/tests/{slug}` | **Solo** parity Design Comuni — non header produzione |
| `fromStoredUrl($url)` | — | Normalizza URL CMS (`/it/servizi` → locale corrente) |

## Uso CTA blocchi (mappa homepage)

```blade
{{-- components/blocks/cta/ticket.blade.php — NO FrontofficeUrl --}}
@php
    $buttonUrl = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::localizeURL('/tests/ticket-crea');
@endphp
<a href="{{ $buttonUrl }}">…</a>
```

## Uso in partial header

```blade
<a href="{{ route('services.categories') }}">
```

Le label usano chiavi strutturate, es. `pub_theme::header.user.dropdown.notifications.label`.

## Perché (zen)

- Il tema **trasporta** URL, non conosce rotte Laravel.
- Folio è il contratto URL; mcamara aggiunge `/{locale}/`.
- Named route Folio verificate evitano drift tra path fisico, locale e header.

## Anti-pattern

```blade
{{-- ❌ rotte named / sandbox tests in produzione --}}
href="{{ \Themes\Sixteen\Support\FrontofficeUrl::personalAreaServices() }}"  {{-- wrapper vietato --}}
href="{{ route('tests.view', ['slug' => 'servizi']) }}"
href="/{{ app()->getLocale() }}/profilo/notifiche"

{{-- ❌ locale hardcoded + path inventato + italiano nel Blade --}}
<a href="/it/profilo/servizi"><span>I miei servizi</span></a>
```

Label corretta (5 livelli: `namespace::context.collection.element.type`):

```blade
<span>{{ __('pub_theme::header.user.dropdown.notifications.label') }}</span>
```

Contratto completo: [fo-folio-named-routes-header.md](fo-folio-named-routes-header.md) · [fo-header-url-and-translation-contract.md](fo-header-url-and-translation-contract.md)

## Verifica

- Pest: `Themes/Sixteen/tests/Unit/HeaderAreaPersonaleLinksContractTest.php`
- `php artisan folio:list` contiene le named route usate
- Browser: link menu generano URL localizzati senza path inventati `/profilo/*`

## Backlink

- [header-authenticated-state.md](header-authenticated-state.md)
- [Root rule folio-frontoffice-navigation-links](../../../../../../docs/wiki/rules/folio-frontoffice-navigation-links.md)
