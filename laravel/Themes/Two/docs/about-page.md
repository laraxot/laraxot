# About Page (Chi Siamo) Implementation

## Status: ✅ Implemented (IT), ⚠️ Partial (EN)

## Overview

La pagina Chi Siamo replica la struttura del target site con sezioni per Hero, Storia, Valori e Team.

## Blade Components Created

### `hero/about.blade.php`
- Hero section con stats e dual CTAs
- Background image con gradient overlay
- Grid di statistiche con bordo brand-orange
- CTAs primario e secondario con icone Filament

### `about/team.blade.php`
- Grid di member cards responsive
- Immagini con overlay gradient
- Badge certificazioni con icone
- Hover effects e transitions

## JSON Content Structure

```json
{
  "content_blocks": {
    "it": [
      { "type": "hero", "view": "hero.about" },
      { "type": "content-split", "view": "content.split" },
      { "type": "values", "view": "services.grid" },
      { "type": "team", "view": "about.team" }
    ]
  }
}
```

## Image Mappings

| JSON Path | Actual File |
|-----------|-------------|
| `dr-roberto-magni.jpg` | ✅ Exists |
| `dr-elena-visentin.jpg` | ✅ Exists |
| `dr-paolo-verdi.jpg` | ✅ Exists |
| `medical-equipment.jpg` | ✅ Exists (used for story) |

## TODO

- [ ] Complete English translations for all sections
- [ ] Add stats to English hero section
- [ ] Add content-split, values, and team sections to EN array
