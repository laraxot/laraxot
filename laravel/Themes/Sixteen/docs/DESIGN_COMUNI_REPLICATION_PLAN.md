# Design Comuni Replication Plan

## Overview

This document outlines the plan to replicate all static pages from [design-comuni-pagine-statiche](https://github.com/italia/design-comuni-pagine-statiche) in the FixCity project.

## Source Reference

- **Repository**: https://github.com/italia/design-comuni-pagine-statiche
- **Live Demo**: https://italia.github.io/design-comuni-pagine-statiche/
- **Version**: v2.4.0
- **Technologies**: Handlebars, SCSS, JavaScript (Webpack)

## Target URLs

All pages will be accessible at `http://fixcity.local/it/tests/<page-slug>`

### Pages to Replicate

#### Generali (9 pages)
| Page | Source URL | Target URL |
|------|------------|------------|
| Homepage | `/sit/homepage.html` | `/it/tests/homepage` |
| Domande frequenti | `/sit/domande-frequenti.html` | `/it/tests/domande-frequenti` |
| Risultati ricerca | `/sit/risultati-ricerca.html` | `/it/tests/risultati-ricerca` |
| Argomenti | `/sit/argomenti.html` | `/it/tests/argomenti` |
| Argomento | `/sit/argomento.html` | `/it/tests/argomento` |
| Lista risorse | `/sit/lista-risorse.html` | `/it/tests/lista-risorse` |
| Lista categorie | `/sit/lista-categorie.html` | `/it/tests/lista-categorie` |
| Lista risorse e categorie | `/sit/lista-risorse-categorie.html` | `/it/tests/lista-risorse-categorie` |
| Mappa sito | `/sit/mappa-sito.html` | `/it/tests/mappa-sito` |

#### Amministrazione (2 pages)
| Page | Source URL | Target URL |
|------|------------|------------|
| Amministrazione | `/sit/amministrazione.html` | `/it/tests/amministrazione` |
| Documenti e dati | `/sit/documenti-dati.html` | `/it/tests/documenti-dati` |

#### Novità (2 pages)
| Page | Source URL | Target URL |
|------|------------|------------|
| Novità | `/sit/novita.html` | `/it/tests/novita` |
| Notizia dettaglio | `/sit/novita-dettaglio.html` | `/it/tests/novita-dettaglio` |

#### Servizi (3 pages)
| Page | Source URL | Target URL |
|------|------------|------------|
| Servizi | `/sit/servizi.html` | `/it/tests/servizi` |
| Servizi categoria | `/sit/servizi-categoria.html` | `/it/tests/servizi-categoria` |
| Servizio dettaglio | `/sit/servizio-dettaglio.html` | `/it/tests/servizio-dettaglio` |

#### Vivere il Comune (2 pages)
| Page | Source URL | Target URL |
|------|------------|------------|
| Eventi | `/sit/eventi.html` | `/it/tests/eventi` |
| Evento dettaglio | `/sit/evento-dettaglio.html` | `/it/tests/evento-dettaglio` |

#### Prenotazione Appuntamento (8 pages)
| Page | Source URL | Target URL |
|------|------------|------------|
| Step 1 - Ufficio | `/sit/appuntamento-01-ufficio.html` | `/it/tests/appuntamento-01-ufficio` |
| Step 1 - Ufficio luogo | `/sit/appuntamento-01-ufficio-luogo.html` | `/it/tests/appuntamento-01-ufficio-luogo` |
| Step 2 - Data orario | `/sit/appuntamento-02-data-orario.html` | `/it/tests/appuntamento-02-data-orario` |
| Step 3 - Dettagli | `/sit/appuntamento-03-dettagli.html` | `/it/tests/appuntamento-03-dettagli` |
| Step 4 - Richiedente | `/sit/appuntamento-04-richiedente.html` | `/it/tests/appuntamento-04-richiedente` |
| Step 4 - Richiedente autenticato | `/sit/appuntamento-04-richiedente-autenticato.html` | `/it/tests/appuntamento-04-richiedente-autenticato` |
| Step 5 - Riepilogo | `/sit/appuntamento-05-riepilogo.html` | `/it/tests/appuntamento-05-riepilogo` |
| Step 6 - Conferma | `/sit/appuntamento-06-conferma.html` | `/it/tests/appuntamento-06-conferma` |

#### Richiesta Assistenza (2 pages)
| Page | Source URL | Target URL |
|------|------------|------------|
| Step 1 - Dati | `/sit/assistenza-01-dati.html` | `/it/tests/assistenza-01-dati` |
| Step 2 - Conferma | `/sit/assistenza-02-conferma.html` | `/it/tests/assistenza-02-conferma` |

#### Segnalazione Disservizio (7 pages)
| Page | Source URL | Target URL |
|------|------------|------------|
| Scheda servizio | `/sit/segnalazione-dettaglio.html` | `/it/tests/segnalazione-dettaglio` |
| Step 1 - Privacy | `/sit/segnalazione-01-privacy.html` | `/it/tests/segnalazione-01-privacy` |
| Step 2 - Dati | `/sit/segnalazione-02-dati.html` | `/it/tests/segnalazione-02-dati` |
| Step 3 - Riepilogo | `/sit/segnalazione-03-riepilogo.html` | `/it/tests/segnalazione-03-riepilogo` |
| Step 4 - Conferma | `/sit/segnalazione-04-conferma.html` | `/it/tests/segnalazione-04-conferma` |
| Area personale | `/sit/segnalazione-area-personale.html` | `/it/tests/segnalazione-area-personale` |
| Elenco segnalazioni | `/sit/ticket-list.html` | `/it/tests/ticket-list` |

**Total: 38 pages**

## Architecture

### Dynamic Page Route

```php
// laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php
<?php

declare(strict_types=1);

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
use Livewire\Volt\Component;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;

name('tests.view');
middleware(PageSlugMiddleware::class);

new class extends Component {
    public string $slug = '';
    public string $pageSlug = '';

    /** @var array<string, mixed> */
    public array $data = [];

    public function mount(string $slug): void
    {
        $this->slug = $slug;
        $this->pageSlug = 'tests.'.$slug;
        $this->data = [
            'slug' => $slug
        ];
    }
};
?>
<x-layouts.app>
 @volt('tests.view')
    <div>
        <x-page side="content" :slug="$pageSlug" :data="$data" />
    </div>
 @endvolt
</x-layouts.app>
```

### Current Status

| Component | Status | Location |
|-----------|--------|----------|
| Dynamic route `[slug].blade.php` | ✅ Created | `resources/views/pages/tests/[slug].blade.php` |
| JSON content files | ✅ 38+ files exist | `config/local/fixcity/database/content/pages/tests.*.json` |
| Header component (Bootstrap Italia) | ✅ Exists | `resources/views/components/bootstrap-italia/header.blade.php` |
| Footer component (Bootstrap Italia) | ✅ Exists | `resources/views/components/bootstrap-italia/footer.blade.php` |
| Vite build | ✅ Fixed | `npm run build && npm run copy` in `Themes/Sixteen/` |
| Folio routing | ⚠️ Disabled | `CmsServiceProvider.php` line 64 - `$this->registerFolio()` commented out |

### Enable Folio Routing

To enable Folio routes, uncomment line 64 in `Modules/Cms/app/Providers/CmsServiceProvider.php`:

```php
// $this->registerFolio();
```

Should become:

```php
$this->registerFolio();
```

After enabling, routes should be available at `/it/tests/<slug>`.

### Content JSON Structure

Each page corresponds to a JSON file in `laravel/config/local/fixcity/database/content/pages/`:

```json
{
    "slug": "tests.homepage",
    "title": "Homepage",
    "blocks": [
        {
            "type": "hero",
            "data": {
                "view": "pub_theme::components.blocks.hero.homepage"
            }
        }
    ]
}
```

### Block Type Naming Convention

Following prototypes from:
- https://flowbite.com/blocks/
- https://tailwindcss.com/plus/ui-blocks
- https://daisyui.com/components/
- https://italia.github.io/bootstrap-italia/docs/componenti/introduzione/

**Never use page-specific types like `tests.argomenti`** - always use generic, reusable block types.

## Implementation Steps

### Phase 1: Infrastructure (Completed)
- [x] Fix Vite manifest error
- [x] Build theme assets
- [x] Verify theme configuration

### Phase 2: Base Components
- [ ] Header section (Design Comuni style)
- [ ] Footer section (Design Comuni style)
- [ ] Skiplink component
- [ ] Base CSS with Tailwind @apply

### Phase 3: Page Structure
- [ ] Create `[slug].blade.php` route
- [ ] Create `index.blade.php` for tests index
- [ ] Create PageSlugMiddleware

### Phase 4: Content Pages
- [ ] Create JSON files for all 38 pages
- [ ] Create reusable block components
- [ ] Verify HTML matches source

### Phase 5: Verification
- [ ] Visual comparison tests
- [ ] HTML structure validation
- [ ] Accessibility compliance

## Key Principles

1. **DRY**: Reusable blocks, not page-specific code
2. **KISS**: Simple dynamic route, not 38 separate files
3. **Generic block types**: `hero`, `card`, `grid`, not `homepage-hero`
4. **Tailwind + @apply**: Replicate Bootstrap Italia classes, don't import it directly
5. **Section pattern**: Header/footer as `<x-section slug="header" />`

## Related Documentation

- `docs/VITE_MANIFEST_ROOT_CAUSE.md` - Vite error fix
- `docs/layout-architecture.md` - Layout system
- `docs/components/navigation-components.md` - Navigation components
- `docs/agid/complete-agid-compliance-analysis.md` - AGID compliance

## GitHub Tracking

All tasks tracked via GitHub Issues in: https://github.com/laraxot/base_fixcity_fila5/issues