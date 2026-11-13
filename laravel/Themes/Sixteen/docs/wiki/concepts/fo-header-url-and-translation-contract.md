---
title: "Header FO — contratto URL Folio + chiavi traduzione 5 livelli"
type: concept
tags: [folio, header, i18n, translation, frontoffice, six]
created: 2026-06-05
updated: 2026-10-22
qmd: "header frontoffice route services.categories translation five levels pub_theme header.user.dropdown no profilo getLocale"
issues:
  - "https://github.com/laraxot/base_fixcity_fila5/issues/289"
  - "https://github.com/laraxot/base_fixcity_fila5/issues/290"
discussions: []
related:
  - fo-folio-named-routes-header.md
  - fo-folio-links-multilingua.md
  - ../../../../../../docs/wiki/memories/no-hardcoded-locale-italian-in-fo-blade.md
  - ../../../../../../docs/wiki/agents/rules/translations.md
status: draft
---

> **Correzione 2026-10-30.** Gli esempi `route('notifications')` e `pages/notifications/` di questo documento sono superati: le pagine CMS (area personale, ticket, FAQ) si linkano con `route('container0.view', ['container0' => 'area-personale', 'slug0' => 'notifiche'])` e `route('container0.index', ['container0' => '...'])` (nome generico, valori nei parametri). Vedi `docs/wiki/memories/translation-protocol-memory-aid.md` §2 e `docs/wiki/rules/folio-named-routes-route-helper.md` nel repo root.

# Header FO — contratto URL + traduzioni

## Religione (tre pilastri)

| # | Pilastro | Vietato | Corretto |
|---|----------|---------|----------|
| 1 | **URL** | `href="/{{ app()->getLocale() }}/…"` / `localizeUrl` | `route('container0.view|index', …)` (o `login` / POST `logout`) |
| 2 | **Path** | segmenti inventati (`profilo`, `tickets.show`) | Folio shell `[container0]/[slug0]` |
| 3 | **Copy** | stringhe hardcoded / `__('ui::…')` sbagliate | `__('pub_theme::header…label')` / `navigation.*` |

## Perché `app()->getLocale()` nell'href è sbagliato

- Duplica logica già in Folio + mcamara
- Path `profilo/*` **non esiste** in Folio owner
- `route()` su nome Folio è il contratto verso il file pages

## Mapping voci menu (2026-06-05)

| Voce | `route()` | Chiave |
|------|-----------|--------|
| Notifiche | `container0.view` area-personale/notifiche | `pub_theme::header.user.dropdown.notifications.label` |
| Impostazioni | `container0.view` area-personale/impostazioni | `pub_theme::header.user.dropdown.settings.label` |
| Esci (conferma GET) | Folio `auth.logout` → POST `logout` | `pub_theme::auth.logout.*` |
| Accedi (guest) | `login` | `pub_theme::header.guest.cta.label` |
| Breadcrumb Home / Novità / Eventi | `home` / `container0.index` blog\|events | `pub_theme::navigation.home` / `homepage.news` / `homepage.events` |

## Anti-pattern completo

```blade
<a href="/{{ app()->getLocale() }}/profilo/notifiche">
    <span>{{ __('ui::ui.profile.notifications') }}</span>
</a>
```

## Esempio canonico

```blade
<a href="{{ route('notifications') }}">
    <span>{{ __('pub_theme::header.user.dropdown.notifications.label') }}</span>
</a>
```

## Verifica

- Pest: `tests/Unit/HeaderAreaPersonaleLinksContractTest.php`
- CLI: `php artisan folio:list`
- Grep: `rg "getLocale\(\)|/profilo/|ui::ui\.profile|personalArea" Themes/Sixteen/resources/views/components/sections/header`

## Backlink

- [fo-folio-named-routes-header.md](fo-folio-named-routes-header.md)
- [fo-folio-links-multilingua.md](fo-folio-links-multilingua.md)
