# Architettura Routing Dinamico - Theme Two

## Filosofia (Zen)

> **Un solo file Blade per infinite pagine. Il contenuto è Reale, la struttura è Virtuale.**

Invece di creare un file `.blade.php` per ogni pagina (about, services, contact, ecc.), usiamo un sistema dinamico basato su:

1. **Singolo file Blade**: `[slug].blade.php` cattura TUTTE le pagine
2. **JSON come sorgente di verità**: Ogni pagina ha un file `.json` in `config/local/techplanner/database/content/pages/`
3. **Componenti riutilizzabili**: I blocchi JSON definiscono quali componenti renderizzare

## Come funziona

### 1. Laravel Folio - File-based Routing

```php
// Themes/Two/resources/views/pages/pages/[slug].blade.php
<?php
use function Laravel\Folio\{middleware, name};
use Modules\Cms\Http\Middleware\PageSlugMiddleware;

name('pages.view');
middleware(PageSlugMiddleware::class);
?>

<x-layouts.app>
    @volt('pages.view')
    <div>
        <x-page side="content" :slug="$slug" />
    </div>
    @endvolt
</x-layouts.app>
```

**URLs gestite:**
- `/it/pages/about` → slug = "about"
- `/it/pages/services` → slug = "services"
- `/en/pages/contact` → slug = "contact"

Tutte vengono gestite dal **SINGOLO** file `[slug].blade.php`.

### 2. JSON - Content Management

```json
// config/local/techplanner/database/content/pages/about.json
{
    "id": "5",
    "slug": "about",
    "title": { "it": "Chi Siamo", "en": "About Us" },
    "content_blocks": {
        "it": [
            {
                "type": "hero",
                "slug": "about-hero",
                "data": {
                    "view": "pub_theme::components.blocks.hero.about",
                    "title": "Chi Siamo",
                    "subtitle": "Marco Sottana - Consulenza Sicurezza..."
                }
            },
            {
                "type": "content-split",
                "slug": "professional-profile",
                "data": {
                    "view": "pub_theme::components.blocks.content.split",
                    "title": "La Nostra Storia",
                    "content": "<p>Testo...</p>",
                    "image": "/themes/Two/Main_files/images/medical-equipment.jpg"
                }
            }
        ]
    }
}
```

### 3. Componente <x-page> - Rendering dinamico

Il componente `Page` (dal modulo CMS):
1. Legge lo slug dall'URL
2. Carica il JSON corrispondente
3. Itera i `content_blocks`
4. Include dinamicamente le view specificate in ogni blocco

## Vantaggi

| Approccio Tradizionale | Approccio Zen (JSON-Driven) |
|------------------------|------------------------------|
| 1 file blade per pagina | 1 file blade per TUTTE le pagine |
| Duplicazione codice | Componenti riutilizzabili |
| Dev per ogni modifica | Content editor via JSON |
| Hardcoded content | Multilingua nativo |
| Difficile manutenzione | Centralizzato e manutenibile |

## Anti-Pattern (NON FARE)

```bash
# ❌ SBAGLIATO - Non creare file per ogni pagina
Themes/Two/resources/views/pages/pages/about/index.blade.php
Themes/Two/resources/views/pages/pages/services.blade.php
Themes/Two/resources/views/pages/pages/contact/index.blade.php
```

```bash
# ✅ CORRETTO - Usare il sistema dinamico
# Il file esistente è sufficiente:
Themes/Two/resources/views/pages/pages/[slug].blade.php

# Aggiungere/modificare solo i JSON:
config/local/techplanner/database/content/pages/about.json
config/local/techplanner/database/content/pages/services.json
config/local/techplanner/database/content/pages/contact.json
```

## Workflow per nuove pagine

1. **Non creare file blade** - usa `[slug].blade.php` esistente
2. **Creare JSON** in `config/local/techplanner/database/content/pages/{slug}.json`
3. **Definire blocchi** nel `content_blocks.it` (e `content_blocks.en` per multilingua)
4. **Specificare view** per ogni blocco in `data.view`
5. **Testare** andando su `/it/pages/{slug}`

## Struttura JSON completa

```json
{
    "id": "string",
    "slug": "page-slug",
    "title": { "it": "Titolo IT", "en": "Title EN" },
    "middleware": null,
    "content": null,
    "content_blocks": {
        "it": [
            {
                "type": "hero|content-split|values|team|...",
                "slug": "unique-block-id",
                "data": {
                    "view": "pub_theme::components.blocks.{type}.{variant}",
                    "title": "...",
                    "subtitle": "...",
                    "...": "altri dati specifici del componente"
                }
            }
        ],
        "en": [...]
    },
    "sidebar_blocks": { "it": [], "en": [] },
    "footer_blocks": { "it": [], "en": [] }
}
```

## Componenti disponibili (Blocks)

I componenti risiedono in:
```
Themes/Two/resources/views/components/blocks/
```

Esempi:
- `hero/` - Hero sections (about, home, services)
- `content/` - Content split, text blocks
- `services/` - Services grid
- `why-critical/` - Feature grids
- `cta/` - Call-to-action sections
- `about/` - Team, company data
- `benefits/` - Benefits grids
- `faq/` - FAQ accordion

## Collegamenti

- [Folio Documentation](https://laravel.com/docs/11.x/folio)
- [Modulo CMS - Page Model](../../../../Modules/Cms/app/Models/Page.php)
- [Componente Page](../../../../Modules/Cms/app/View/Components/Page.php)
- [header.json esempio](../../../config/local/techplanner/database/content/sections/header.json)

---

**Ricorda**: Il contenuto è Reale (JSON), la struttura è Virtuale (un singolo file Blade).
