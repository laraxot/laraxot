> **CORRECTION (2026-02-08)**: This doc references `two::` and `@include` patterns which are WRONG.
> Use `<x-section slug="header" />` in layouts. View namespace is `pub_theme::` not `two::`.
> See `folio-page-file-rules.md` for the authoritative reference.

# Header Navigation Component v1


## Descrizione

Componente header multilingua per il tema Two, replicato dal sito target https://lightseagreen-dogfish-560272.hostingersite.com/

## File Location

```
Themes/Two/resources/views/components/sections/header/v1.blade.php
```

## Features

### 1. Multilingua (Modulo Lang)
- Utilizza `LaravelLocalization` per la gestione delle lingue
- Supporta IT/EN con flag icons
- Language switcher dropdown in header e mobile menu
- URL localizzati automaticamente

### 2. Login & Avatar Dropdown
- Rilevamento utente autenticato via `Auth::check()`
- Avatar con iniziali o immagine profilo
- Dropdown menu con:
  - Nome utente e email
  - Link a Dashboard
  - Link a Profilo
  - Link a Impostazioni
  - Logout
- Visualizzazione "Login" per utenti non autenticati

### 3. Navigation Dinamica
- Items caricati da `header.json` (config/local/techplanner/database/content/sections/)
- Supporto multilingua per label
- Active state highlighting
- Mobile responsive menu

### 4. CTA Button
- Label e URL configurabili da header.json
- Stile "Richiedi Consulenza" in arancione (#E67E22)
- Responsive: nascosto su mobile, visibile su desktop

### 5. Design Responsive
- Mobile-first approach
- Hamburger menu su mobile
- **Header background**: Glassmorphism effect con `bg-[#0f2b46]/90 backdrop-blur-md` per garantire leggibilità del testo bianco su qualsiasi sfondo
- Breakpoint: lg (1024px) per desktop nav

## Problema UI/UX Risolto

**Problema**: Header con `bg-transparent` e testo bianco (`text-white`) risulta illeggibile su sfondi chiari della pagina.

**Soluzione**: Implementato sfondo scuro semi-trasparente con effetto blur:
- `bg-[#0f2b46]/90` - Sfondo blu scuro con 90% opacità
- `backdrop-blur-md` - Effetto glassmorphism per modernità
- Bordo inferiore sottile per separazione visiva

## Struttura JSON (header.json)

```json
{
  "blocks": {
    "it": [{
      "type": "navigation",
      "data": {
        "brand": "Marco Sottana",
        "brand_subtitle": "Consulenza Sicurezza",
        "cta_label": "Richiedi Consulenza",
        "cta_url": "/it/contatti",
        "items": [
          {"label": "Home", "url": "/it/pages/home"},
          {"label": "Chi Siamo", "url": "/it/pages/chi-siamo"},
          {"label": "Servizi", "url": "/it/pages/services"},
          {"label": "Blog", "url": "/it/pages/blog"},
          {"label": "FAQ", "url": "/it/pages/faq"},
          {"label": "Contatti", "url": "/it/pages/contatti"}
        ]
      }
    }],
    "en": [{...}]
  }
}
```

## Dipendenze

- `LaravelLocalization` (Modulo Lang)
- `Auth` (Modulo User)
- Alpine.js per interactivity
- Heroicons per icone
- Tailwind CSS per styling

## Utilizzo

```blade
<x-two::sections.header.v1 :blocks="$section->blocks" />
```

Oppure incluso automaticamente dal layout:

```blade
@include('two::components.sections.header.v1')
```

## Collegamenti

- [Sito Target](https://lightseagreen-dogfish-560272.hostingersite.com/)
- [header.json](../../../config/local/techplanner/database/content/sections/header.json)
- [Modulo Lang](../../../../Modules/Lang/docs/multi-language-conventions.md)
- [Modulo User](../../../../Modules/User/docs/README.md)

---

*Componente aggiornato con glassmorphism per migliore leggibilità*
