---
title: "Header FO — contratto URL Folio + chiavi traduzione 5 livelli"
type: concept
tags: [folio, header, i18n, translation, frontoffice, six]
created: 2026-06-05
updated: 2026-06-05
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
---

# Header FO — contratto URL + traduzioni

## Religione (tre pilastri)

| # | Pilastro | Vietato | Corretto |
|---|----------|---------|----------|
| 1 | **URL** | `href="/{{ app()->getLocale() }}/profilo/notifiche"` | `route('notifications')` |
| 2 | **Path** | segmenti italiani inventati (`profilo`, wrapper `personalArea*`) | named route Folio verificata (`folio:list`) |
| 3 | **Copy** | `__('ui::ui.profile.notifications')` | `__('pub_theme::header.user.dropdown.notifications.label')` |

## Perché `app()->getLocale()` nell'href è sbagliato

- Duplica logica già in Folio + mcamara
- Path `profilo/*` **non esiste** in Folio owner
- `route()` su nome Folio è il contratto verso il file pages

## Mapping voci menu (2026-06-05)

| Voce | `route()` | Chiave 5 livelli |
|------|-----------|------------------|
| I miei servizi | `services.categories` | `pub_theme::header.user.dropdown.my_services.label` |
| Le mie pratiche | `dashboard` | `pub_theme::header.user.dropdown.my_practices.label` |
| Notifiche | `notifications` | `pub_theme::header.user.dropdown.notifications.label` |
| Impostazioni | `profile.edit` | `pub_theme::header.user.dropdown.settings.label` |
| Esci | `logout` | `pub_theme::header.user.dropdown.logout.label` |
| Accedi (guest) | `login` | `pub_theme::header.guest.cta.label` |

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
