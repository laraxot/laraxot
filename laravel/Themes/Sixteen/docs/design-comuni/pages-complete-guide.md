# 📄 DESIGN COMUNI PAGES - COMPLETE GUIDE

**Data**: 2026-03-30  
**Status**: ✅ IMPLEMENTED  
**Priority**: HIGH

---

## 🎯 PAGES CREATED

### 1. Homepage

**URL**: `/it/tests/homepage`  
**JSON**: `config/local/fixcity/database/content/pages/tests.homepage.json`

**Blocks**:
- `hero.homepage` - Hero section
- `news.featured` - Featured news
- `services.grid` - Services grid
- `topics.grid` - Topics grid

### 2. Argomenti

**URL**: `/it/tests/argomenti`  
**JSON**: `config/local/fixcity/database/content/pages/tests.argomenti.json`

**Blocks**:
- `hero.argomenti` - Page header
- `topics.featured` - Featured topics (IN EVIDENZA)
- `topics.grid` - All topics (ESPLORA PER ARGOMENTO)
- `feedback.rating` - Page feedback

### 3. Appuntamento Conferma

**URL**: `/it/tests/appuntamento-06-conferma`  
**JSON**: `config/local/fixcity/database/content/pages/tests.appuntamento-06-conferma.json`

**Blocks**:
- `confirmation.with-details` - Confirmation message
- `steps.horizontal` - Next steps
- `contact.info` - Contact information

---

## 📁 JSON STRUCTURE

### Standard Structure

```json
{
    "id": "tests.<page-name>",
    "slug": "tests.<page-name>",
    "title": {
        "it": "Titolo in italiano",
        "en": "Title in English"
    },
    "content_blocks": {
        "it": [
            {
                "type": "<block-type>",
                "data": {
                    "view": "pub_theme::components.blocks.<type>.<view>",
                    "title": "...",
                    "items": [...]
                }
            }
        ],
        "en": []
    }
}
```

### Key Fields

| Field | Description | Example |
|-------|-------------|---------|
| `id` | Unique identifier | `tests.homepage` |
| `slug` | CMS slug | `tests.homepage` |
| `title` | Page title (multi-lang) | `{"it": "Homepage"}` |
| `content_blocks` | Blocks array | `[...]` |
| `type` | Block type | `hero`, `topics`, etc. |
| `view` | Blade view path | `pub_theme::components.blocks.hero.homepage` |

---

## 🎨 BLOCK TYPES CATALOG

### Hero Blocks

| Block | View | Purpose |
|-------|------|---------|
| `hero.homepage` | `components.blocks.hero.homepage` | Homepage hero |
| `hero.argomenti` | `components.blocks.hero.argomenti` | Topics page hero |

### Content Blocks

| Block | View | Purpose |
|-------|------|---------|
| `news.featured` | `components.blocks.news.featured` | Featured news |
| `services.grid` | `components.blocks.services.grid` | Services grid |
| `topics.grid` | `components.blocks.topics.grid` | Topics grid |
| `topics.featured` | `components.blocks.topics.featured` | Featured topics |

### Utility Blocks

| Block | View | Purpose |
|-------|------|---------|
| `confirmation.with-details` | `components.blocks.confirmation.with-details` | Confirmation |
| `steps.horizontal` | `components.blocks.steps.horizontal` | Steps |
| `contact.info` | `components.blocks.contact.info` | Contact info |
| `feedback.rating` | `components.blocks.feedback.rating` | Feedback |

---

## 🧩 HOW IT WORKS

### 1. Request Flow

```
URL: /it/tests/argomenti
↓
Folio Route: pages/tests/[slug].blade.php
↓
Volt Component: mount(slug='argomenti')
↓
CMS Reads: config/local/fixcity/database/content/pages/tests.argomenti.json
↓
Renders Blocks: hero, topics.featured, topics.grid, feedback
```

### 2. Block Rendering

```blade
@foreach($content_blocks['it'] as $block)
    @includeIf($block['data']['view'], $block['data'])
@endforeach
```

### 3. View Resolution

```
pub_theme::components.blocks.hero.homepage
↓
Themes/Sixteen/resources/views/components/blocks/hero/homepage.blade.php
```

---

## 📋 IMPLEMENTATION CHECKLIST

### For Each New Page

- [ ] Create JSON in `config/local/fixcity/database/content/pages/`
- [ ] Use universal block types (NOT page-specific)
- [ ] Multiple blocks per page (NOT single block)
- [ ] Document in `docs/design-comuni/`

### For Each New Block

- [ ] Create in `Themes/Sixteen/resources/views/components/blocks/<type>/`
- [ ] Use props for configuration
- [ ] Use `<x-filament::icon>` for icons
- [ ] Follow Bootstrap Italia design
- [ ] Document in `docs/blocks/`

---

## 🧘 DEVELOPER MANTRAS

> *"JSON per le pagine. Blocchi per i contenuti."*

> *"Blocchi universali, non specifici per pagina."*

> *"Multiple blocchi per pagina. Mai uno solo."*

> *"CMS-driven, non hardcoded."*

---

## 📖 REFERENCES

### Internal
- `config/local/fixcity/database/content/pages/` - JSON files location
- `Themes/Sixteen/resources/views/components/blocks/` - Blocks location
- `docs/design-comuni/PAGES_ANALYSIS.md` - Analysis document

### External
- [Design Comuni](https://italia.github.io/design-comuni-pagine-statiche/)
- [Filament Icons](https://filamentphp.com/docs/5.x/forms/icon-picker)

---

**Status**: ✅ 3 PAGES CREATED  
**Next**: Create remaining 4 pages (servizio, notizia, evento, amministrazione)
