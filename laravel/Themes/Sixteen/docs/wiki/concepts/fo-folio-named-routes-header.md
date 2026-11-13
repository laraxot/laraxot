---
discussions: []
title: "Header FO — named route Folio verificate"
type: concept
tags: [folio, header, routing, named-routes, sixteen]
created: 2026-06-05
updated: 2026-10-22
qmd: "folio named route header container0.view localizeUrl vietato tickets.show"
issues:
  - https://github.com/laraxot/base_fixcity_fila5/issues/289
related:
  - fo-folio-routing-zen.md
  - fo-folio-links-multilingua.md
  - ../../../../../Modules/Fixcity/docs/frontoffice-link-conventions.md
  - ../../../../../Modules/Cms/docs/wiki/concepts/folio-filesystem-routing-no-web-php.md
status: active
---

<!--
Supersedes: mapping 2026-06-10 che vietava area-personale e puntava a dashboard/profile.edit.
Linked from: frontoffice-link-conventions, prompts/04, fo-link-coherence-audit.
User: localizeUrl sbagliato; tickets.show sbagliato; corretta container0.view; non rifarlo.
-->

# Header FO — named route Folio

## Religione (2026-10-22)

SSoT: [frontoffice-link-conventions.md](../../../../../Modules/Fixcity/docs/frontoffice-link-conventions.md).

- Obbligatorio: `route('container0.view'|'container0.index', …)`
- Vietato Blade: `LaravelLocalization::localizeUrl` / `localizeURL`
- Vietato FO: `route('tickets.show'|create|track)`

## Mapping menu autenticato

| Voce UI | `route()` |
|---------|-----------|
| I miei servizi | `services.categories` |
| Le mie pratiche | `container0.view` area-personale/pratiche |
| Notifiche | `container0.view` area-personale/notifiche |
| Impostazioni | `container0.view` area-personale/impostazioni |
| Esci (POST) | `logout` |
| Esci (GET conferma) | `auth.logout` |
| Accedi | `login` / `auth.login` |

```blade
<a href="{{ route('container0.view', ['container0' => 'area-personale', 'slug0' => 'notifiche']) }}" role="menuitem">
    <span>{{ __('pub_theme::header.user.dropdown.notifications.label') }}</span>
</a>
```

Path da JSON CMS: `FrontofficeUrl::fromStoredUrl()` — non la facciata Mcamara.
