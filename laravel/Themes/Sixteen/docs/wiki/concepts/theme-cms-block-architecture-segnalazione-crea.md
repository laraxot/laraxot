---
name: theme-cms-block-architecture-segnalazione-crea
description: "Theme Sixteen organizes CMS pages via JSON blocks → block views → widget mount pattern. segnalazione-crea is CMS-driven not hardcoded."
type: discovery
---

# Theme CMS Block Architecture — Segnalazione-Crea

## Pattern: JSON → Block → Widget

Il tema Sixteen usa un approccio **CMS-driven** per le pagine di test (`/it/tests/*`):

```
URL: /it/tests/{slug}
  → Folio: tests/[slug].blade.php (route: tests.view)
  → $pageSlug = "tests.{slug}"
  → Page::getBlocksBySlug($pageSlug)
  → legge: config/local/fixcity/database/content/pages/tests.{slug}.json
  → per ogni blocco: @include($block->view, ['data' => $block->data])
```

## File coinvolti

### Route page

`resources/views/pages/tests/[slug].blade.php` — gestisce TUTTE le pagine `/it/tests/*`

### Block views

`resources/views/components/blocks/tests/` — un blade per ogni tipo di blocco test

Ogni block view è un **thin wrapper** che monta il componente appropriato:

```blade
{{-- segnalazione-crea.blade.php —}}
@props(['data' => []])
@livewire(\Modules\Fixcity\Filament\Widgets\CreateTicketWizardWidget::class, ['blockData' => $data])
```

### JSON config

`config/local/fixcity/database/content/pages/tests.{slug}.json` — definisce la composizione della pagina

## Regola

- **Il tema NON definisce pagine hardcoded in `pages/segnalazione-crea.blade.php`** (esiste ma non è il canale CMS)
- **IL percorso CMS è la source of truth**: il JSON definisce i blocchi, il tema li renderizza
- I block views sono thin wrappers (`@livewire(...)`), nessuna logica di business

## Design Comuni alignment

Questa architettura separa:
- **Presentazione** → tema Sixteen (blade, CSS)
- **Componenti** → moduli Fixcity/Geo (Livewire widgets)
- **Contenuto** → CMS JSON (configurabile)

## CSS

Il wizard usa CSS globali nel tema (no page-specific). Vedi:
- `concepts/no-page-specific-css.md`
- `concepts/design-comuni-wizard-css-generalization-rule.md`
