# Blog Page Implementation

## Status: ✅ Implemented (IT + EN)

## Overview

La pagina Blog replica il design del target site con sezioni hero, grid articoli e newsletter.

## Components Updated/Created

### `blog.json`
- 4 articoli con immagini Unsplash
- Branding Marco Sottana
- Traduzione inglese completa
- Newsletter section integrata

### `blog/grid.blade.php`
- Grid 2 colonne (md:grid-cols-2)
- Cards con border-t-4 colorato per categoria
- Category badges con colori brand
- Reading time display
- Lucide-style SVG icons
- Hover effects con scala immagine

### `hero/internal.blade.php`
- Fixed data binding (`$data` instead of `@props`)
- Breadcrumb con link home
- Gradiente brand colors

### `newsletter/simple.blade.php` [NEW]
- Gradiente blue→green (#1E5A96 → #2D8659)
- Lucide icons (Mail, Send)
- Form email responsive
- Privacy text

## Design System (da Target Site)

| Colore | Hex | Uso |
|--------|-----|-----|
| Primary Blue | #1E5A96 | Headers, buttons |
| Green | #2D8659 | CTA, links |
| Orange | #E67E22 | Accents, badges |

## Articoli Dummy

1. Nuove Linee Guida 2026 (Radioprotezione)
2. D.Lgs 101/2020 (Normativa)
3. Verifica IEC 62353 (Elettromedicali)
4. Protocolli Veterinari (Veterinaria)

## File Path References

- JSON: `config/local/techplanner/database/content/pages/blog.json`
- Grid: `Themes/Two/resources/views/components/blocks/blog/grid.blade.php`
- Hero: `Themes/Two/resources/views/components/blocks/hero/internal.blade.php`
- Newsletter: `Themes/Two/resources/views/components/blocks/newsletter/simple.blade.php`
