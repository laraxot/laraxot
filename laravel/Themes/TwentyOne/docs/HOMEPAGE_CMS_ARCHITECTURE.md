# HOMEPAGE CMS ARCHITECTURE

## Principio Fondamentale

**La homepage deve essere gestita tramite CMS blocks, non contenuto hardcoded nella blade.**

Questo permette:
- Contenuto editabile da backoffice Filament
- Tema agnostico e versatile
- Separazione contenuto/codice

## Pattern Implementato

### ✅ Blade Corretta

```php
<?php
declare(strict_types=1);

use function Laravel\Folio\{name};

name('home');
?>
<x-layouts.app>
    @volt('home')
    <div>
        <x-page side="content" slug="home" />
    </div>
    @endvolt
</x-layouts.app>
```

### ❌ Blade Errata (NON USARE)

```php
<!-- MAI contenuto hardcoded nella blade -->
<section class="hero">
    <h1>Titolo Hardcoded</h1>
    ...
</section>
```

## Architettura CMS Blocks

### Flusso di Rendering

```
Request: /
    ↓
pages/index.blade.php
    ↓
<x-page side="content" slug="home" />
    ↓
Page::render()
    ↓
PageModel::getBlocksBySlug('home', 'content')
    ↓
Blocchi da home.json
    ↓
Componenti blade (predict::components.blocks.home.*)
```

### Struttura File

| Componente | Path | Responsabilita |
|------------|------|----------------|
| Blade Homepage | `Themes/TwentyOne/resources/views/pages/index.blade.php` | Routing, layout |
| JSON Content | `config/local/predict/database/content/pages/home.json` | Configurazione blocchi |
| CMS Blocks | `Modules/Predict/resources/views/components/blocks/home/*.blade.php` | UI componenti |

### JSON Content Structure

```json
{
    "id": "home",
    "slug": "home",
    "title": { "it": "...", "en": "..." },
    "meta_description": { "it": "...", "en": "..." },
    "content_blocks": {
        "it": [
            {
                "type": "hero",
                "data": {
                    "title": { "it": "...", "en": "..." },
                    "view": "predict::components.blocks.home.hero"
                }
            }
        ]
    }
}
```

## Backoffice Filament

I blocchi CMS possono essere editati tramite Filament:

1. **ContentBuilder** - Per modificare la struttura JSON
2. **Form Builder** - Per editare i contenuti in modo visuale

### Vantaggi

- Contenuto gestibile da non tecnici
- Preview in tempo reale
- Versioning dei contenuti
- Multi-lingua supportato

## Blocchi Disponibili

### Home Page Blocks

| Tipo | View | Descrizione |
|------|------|-------------|
| hero | `hero.blade.php` | Hero section principale |
| trust_bar | `trust/bar.blade.php` | Badge trust/sicurezza |
| social_proof | `social-proof.blade.php` | Testimonianze |
| breaking_news | `breaking-news.blade.php` | Ultime notizie |
| hot_topics | `hot-topics.blade.php` | Topic caldi |
| featured_markets | `featured-markets.blade.php` | Mercati in evidenza |
| trending_markets | `trending-markets.blade.php` | Mercati trending |
| how_it_works | `how-it-works.blade.php` | Come funziona |
| categories_grid | `categories-grid.blade.php` | Griglia categorie |
| leaderboard_preview | `leaderboard-preview.blade.php` | Classifica preview |
| footer | `footer.blade.php` | Footer section |

## Come Aggiungere Nuovi Blocchi

### 1. Creare la View

```blade
{{-- Modules/Predict/resources/views/components/blocks/home/new-block.blade.php --}}
@props(['data' => []])

<section class="new-block">
    <h2>{{ $data['title']['it'] ?? '' }}</h2>
    <p>{{ $data['description']['it'] ?? '' }}</p>
</section>
```

### 2. Aggiungere al JSON

```json
{
    "type": "new_block",
    "data": {
        "title": { "it": "Titolo", "en": "Title" },
        "view": "predict::components.blocks.home.new-block"
    }
}
```

### 3. (Futuro) Editare da Filament

Usa `Filament\Forms\Components\Repeater` o `Builder` per editare i blocchi.

## Tema Agnostic

Il tema TwentyOne deve funzionare con qualsiasi contenuto CMS:

- **NON hardcodare** contenuti specifici nella blade
- **Delegare** tutto al CMS blocks system
- **Supportare** qualsiasi container/slug

## Convenzioni

- **Slug JSON**: deve coincidere con il nome file
- **View path**: `namespace::components.blocks.category.name`
- **Tipo blocco**: snake_case
- **Traduzioni**: oggetti `{ "it": "...", "en": "..." }`

## Riferimenti

- `Modules/Cms/docs/content-blocks-system-1.md`
- `Modules/Predict/docs/GENERIC_PAGE_ARCHITECTURE.md`
- `Themes/TwentyOne/docs/blade-generic-architecture.md`

---

**Stato**: Implementato (2026-03-18)
**Autore**: AI Agent
