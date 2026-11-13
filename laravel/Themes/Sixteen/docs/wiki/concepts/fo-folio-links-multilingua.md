---
qmd: "fo folio links multilingua"
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
status: draft
---

# Link FO multilingua — path Folio + `FrontofficeUrl`

<!--
Supersede: ownership che imponeva route('profile.edit') nel FO chrome.
Linked from: why-profile-edit, D34, HeaderAreaPersonaleLinksContractTest, fo-folio-named-routes-header.
User: «creare laravel/Themes/Sixteen/resources/views/pages/profile/edit.blade.php e' sbagliato! … bmad + second brain + filament blueprint»
-->

## Ownership rule: impostazioni FO (non `pages/profile/` nel tema)

Il chrome Design Comuni «Impostazioni» usa
`FrontofficeRoutes::personalAreaImpostazioni()` → `container0.view`
area-personale/impostazioni (JSON CMS + blocchi owner). **Mai** creare
`Themes/Sixteen/resources/views/pages/profile/edit.blade.php` (alias ripetuto
≥3 volte). Logica account eventualmente in `Modules/User/.../profile/edit.blade.php`
— il tema non la duplica e il FO civico non punta a `route('profile.edit')`.
Canone: [why-profile-edit-blade-must-not-exist.md](../../bmad/knowledge/why-profile-edit-blade-must-not-exist.md) (D34).

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
| `FrontofficeRoutes::personalAreaImpostazioni()` | `/it/area-personale/impostazioni` | Impostazioni FO (D34) — **non** `profile.edit` / **non** `pages/profile/` |
| `logout` | `/it/auth/logout` | Logout Folio auth |
| `tests.view` | `/it/tests/{slug}` | **Solo** parity Design Comuni — non header produzione |
| `fromStoredUrl($url)` | — | Normalizza URL CMS (`/it/servizi` → locale corrente) |

## Uso CTA blocchi (mappa homepage)

```blade
{{-- components/blocks/cta/ticket.blade.php — rotta Folio canonica --}}
@php
    $buttonUrl = route('container0.view', ['container0' => 'tickets', 'slug0' => 'create']);
@endphp
<a href="{{ $buttonUrl }}">…</a>
```

## Uso in partial header

```blade
<a href="{{ route('container0.view', ['container0' => 'area-personale', 'slug0' => 'notifiche']) }}">
```

Le label usano chiavi strutturate, es. `pub_theme::header.user.dropdown.notifications.label`.
Breadcrumb listing: `pub_theme::navigation.home` / `homepage.news` / `homepage.events`.

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
- [why-profile-edit-blade-must-not-exist.md](../../bmad/knowledge/why-profile-edit-blade-must-not-exist.md)
- [Root rule folio-frontoffice-navigation-links](../../../../../../docs/wiki/rules/folio-frontoffice-navigation-links.md)
