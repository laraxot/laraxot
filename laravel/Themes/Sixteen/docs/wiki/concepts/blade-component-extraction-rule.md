# Blade Component Extraction Rule — Tema Sixteen

## Principio

Estrarre componenti riusabili da **tutte** le Blade del tema, non solo da `v1.blade.php`.

Un blocco va estratto quando:
1. Si ripete in più file → componente riusabile
2. Ha responsabilità autonoma e leggibile come unità → partial
3. Rende il file owner troppo lungo → partial locale

Non estrarre per moda. DRY + KISS prima di tutto.

## Classificazione e destinazione

| Tipo | Destinazione |
|------|-------------|
| Riusabile cross-section | `resources/views/components/<area>/<name>.blade.php` |
| Owner di sezione | `resources/views/components/sections/<section>/v*.blade.php` |
| Partial locale a una section | `resources/views/components/sections/<section>/partials/<name>.blade.php` |

## Regola header section

- **Owner/SSoT**: `components/sections/header/v1.blade.php`
- **Partial locali**: `components/sections/header/partials/`

```
✅ sections/header/partials/language-switcher.blade.php
✅ sections/header/partials/user-dropdown.blade.php
✅ sections/header/partials/personal-area-guest-cta.blade.php
✅ sections/header/partials/personal-area-guest-parity.blade.php
❌ sections/header/language-switcher.blade.php   ← sbagliato: stesso livello di v1
```

## Include pattern

```blade
@include('pub_theme::components.sections.header.partials.language-switcher')
@include('pub_theme::components.sections.header.partials.user-dropdown')
```

## Guardrails

- `v1.blade.php` resta sempre orchestratore e SSoT
- Mai spostare ownership fuori da `v1.blade.php`
- Mai mettere partial locali allo stesso livello del file owner
- Mai creare componenti globali per blocchi sezione-specifici

## Story di riferimento

- 8-36: estrazione subcomponenti da header/v1
- 8-37: regola generale estrazione da tutte le Blade + `partials/` governance
