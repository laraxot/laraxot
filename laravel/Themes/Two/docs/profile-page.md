# Pagina Profilo (Theme Two)

## Scopo

Pagina profilo utente autenticato accessibile da dropdown header. URL localizzato: `/{lang}/profile` (es. `/it/profile`, `/en/profile`).

## URL e routing

- **URL**: `/{locale}/profile` (es. `/it/profile`)
- **Folio**: `[container0]/index` con container0=profile
- **Slug CMS**: `profile` (da profile.json)

## Link nel dropdown utente

**Regola**: il link Profilo deve usare sempre l'URL localizzato, mai `/profile` hardcoded.

```blade
{{-- Corretto --}}
<a href="{{ LaravelLocalization::getLocalizedURL($currentLocale, '/profile') }}">
    {{ __('Profilo') }}
</a>

{{-- Oppure --}}
<a href="{{ url(app()->getLocale().'/profile') }}">
    {{ __('Profilo') }}
</a>
```

**Vietato**: `href="/profile"` (perde il prefisso lingua).

## Contenuto CMS

- **File**: `config/local/techplanner/database/content/pages/profile.json`
- **Slug**: `"profile"`
- **Struttura**: come about.json, contacts.json (content_blocks per locale it/en)

## Dove si modifica il link

1. **Header v1** (Theme Two): `components/sections/header/v1.blade.php` - dropdown desktop e mobile
2. **Auth button** (Module User): `Modules/User/resources/views/components/ui/app/auth-button.blade.php` - se usato

## Collegamenti

- [header-auth-button](header-auth-button.md)
- [content-json-mapping](../../Modules/Cms/docs/content-json-mapping.md)
- [url-mapping-target-vs-local](url-mapping-target-vs-local.md)
