> **CORRECTION (2026-02-08)**: This doc references `two::` and `@include` patterns which are WRONG.
> Use `<x-section slug="footer" />` in layouts. View namespace is `pub_theme::` not `two::`.
> See `folio-page-file-rules.md` for the authoritative reference.

# Footer Component v1 - Theme Two

## Descrizione

Componente footer multilingua per il tema Two, replicato dal sito target `https://lightseagreen-dogfish-560272.hostingersite.com/`.

## Path

```
laravel/Themes/Two/resources/views/components/sections/footer/v1.blade.php
```

## Dati (JSON)

I contenuti del footer sono gestiti da:

```bash
laravel/config/local/techplanner/database/content/sections/footer.json
```

### Struttura JSON

```json
{
  "blocks": {
    "it": [
      {
        "type": "footer",
        "slug": "main-footer",
        "data": {
          "brand": {
            "name": "Marco Sottana",
            "subtitle": "Consulenza Sicurezza",
            "description": "..."
          },
          "social": {
            "linkedin": "https://...",
            "facebook": "https://...",
            "instagram": "https://..."
          },
          "normative": {
            "title": "Normative & Certificazioni",
            "items": [...]
          },
          "services": {
            "title": "Servizi",
            "items": [...]
          },
          "contact": {
            "title": "Contatti",
            "address": "...",
            "city": "...",
            "email": "...",
            "phone": "...",
            "piva": "...",
            "rea": "..."
          },
          "legal": {
            "copyright": " 2026...",
            "links": [...]
          }
        }
      }
    ]
  }
}
```

## Features

- **Multilingua**: Supporta it/en tramite il modulo Lang
- **4 colonne responsive**:
  1. Brand + social links (LinkedIn, Facebook, Instagram)
  2. Normative & Certificazioni
  3. Servizi
  4. Contatti (indirizzo, email, telefono, P.IVA, REA)
- **Bottom bar**: Copyright + legal links (Privacy, Cookie, Terms)
- **Design**: Gradient background #0f2b46 → #1a3a5c → #0d1f35
- **Icons**: SVG inline per social e contatti

## Usage

Nel layout:

```php
@php
    $footerSection = \Modules\Cms\Models\Section::getBlocksBySlug('footer');
@endphp
@include('two::components.sections.footer.v1', ['blocks' => $footerSection])
```

## Troubleshooting

### Problema: Footer non si renderizza

**Causa**: `Section::getBlocksBySlug()` ritorna `DataCollection` che deve essere convertita in array.

**Soluzione**: Il componente gestisce automaticamente la conversione:

```php
if ($blocks instanceof \Spatie\LaravelData\DataCollection) {
    $blocks = $blocks->toArray();
}
```

### Problema: Dati non trovati

**Causa**: File JSON vuoti o mancanti in `database/content/sections/`

**Soluzione**: Verificare che solo `footer.json` esista (eliminare eventuali `1.json`, `2.json` vuoti).

## Backlinks

- [header-v1-component.md](./header-v1-component.md)
- [Theme Two Docs](../README.md)
