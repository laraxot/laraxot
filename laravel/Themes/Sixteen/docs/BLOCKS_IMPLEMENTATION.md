# Blocks Implementation - Complete

> **Documentazione implementazione blocchi universali per Design Comuni**

## 📋 Panoramica

**Data:** 2026-04-01  
**Stato:** ✅ Blocchi Homepage implementati  
**Totale Blocchi:** 7 blocchi creati

---

## 🧩 Blocchi Implementati

### 1. Navigation Main

**File:** `components/blocks/navigation/main.blade.php`  
**Tipo:** `navigation`  
**Utilizzo:** Homepage e tutte le pagine con menu principale

**JSON Example:**
```json
{
    "type": "navigation",
    "data": {
        "view": "pub_theme::components.blocks.navigation.main",
        "items": [
            {
                "label": "Amministrazione",
                "url": "/it/amministrazione",
                "children": [
                    {"label": "Giunta", "url": "/it/giunta"},
                    {"label": "Consiglio", "url": "/it/consiglio"}
                ]
            },
            {
                "label": "Servizi",
                "url": "/it/servizi",
                "children": []
            }
        ]
    }
}
```

**Props:**
- `items`: Array di voci di menu con label, url, children (opzionale)

**Documentazione:** [Blocks Index](../design-comuni/blocks/00-index.md)

---

### 2. Hero Homepage

**File:** `components/blocks/hero/homepage.blade.php`  
**Tipo:** `hero`  
**Utilizzo:** Homepage - Hero section

**JSON Example:**
```json
{
    "type": "hero",
    "data": {
        "view": "pub_theme::components.blocks.hero.homepage",
        "title": "Benvenuto nel Comune",
        "subtitle": "Portale dei servizi digitali",
        "image": "/themes/Sixteen/images/hero-homepage.jpg",
        "cta": {
            "label": "Scopri i servizi",
            "url": "/it/servizi"
        }
    }
}
```

**Props:**
- `title`: Titolo hero (H1)
- `subtitle`: Sottotitolo
- `image`: URL immagine
- `cta`: Call-to-action con label e url

**Documentazione:** [Blocks Index](../design-comuni/blocks/00-index.md)

---

### 3. Quick Links Default

**File:** `components/blocks/quick-links/default.blade.php`  
**Tipo:** `quick-links`  
**Utilizzo:** Homepage - Accesso rapido

**JSON Example:**
```json
{
    "type": "quick-links",
    "data": {
        "view": "pub_theme::components.blocks.quick-links.default",
        "title": "Accesso rapido",
        "links": [
            {
                "icon": "it-calendar",
                "label": "Appuntamenti",
                "url": "/it/appuntamenti"
            },
            {
                "icon": "it-file",
                "label": "Documenti",
                "url": "/it/documenti"
            }
        ]
    }
}
```

**Props:**
- `title`: Titolo sezione
- `links`: Array di link con icon, label, url

**Documentazione:** [Blocks Index](../design-comuni/blocks/00-index.md)

---

### 4. News Carousel

**File:** `components/blocks/news/carousel.blade.php`  
**Tipo:** `news`  
**Utilizzo:** Homepage - Ultime notizie

**JSON Example:**
```json
{
    "type": "news-carousel",
    "data": {
        "view": "pub_theme::components.blocks.news.carousel",
        "title": "Ultime notizie",
        "news": [
            {
                "title": "Nuovo servizio digitale",
                "date": "2026-04-01",
                "excerpt": "Attivato nuovo servizio online per...",
                "url": "/it/novita/nuovo-servizio"
            }
        ]
    }
}
```

**Props:**
- `title`: Titolo sezione
- `news`: Array di news con title, date, excerpt, url

**Documentazione:** [Blocks Index](../design-comuni/blocks/00-index.md)

---

### 5. Header Slim

**File:** `components/blocks/header/slim.blade.php` (già esistente)  
**Tipo:** `header-slim`  
**Utilizzo:** Header superiore (regione, lingua, login)

**JSON Example:**
```json
{
    "type": "header-slim",
    "data": {
        "view": "pub_theme::components.blocks.header.slim",
        "region": "Nome della Regione",
        "language": "ITA",
        "login_url": "/login",
        "social": [...]
    }
}
```

**Props:**
- `region`: Nome regione
- `language`: Lingua corrente
- `login_url`: URL area personale
- `social`: Social links

**Documentazione:** [Blocks Index](../design-comuni/blocks/00-index.md)

---

### 6. Header Main

**File:** `components/blocks/header/main.blade.php` (già esistente)  
**Tipo:** `header-main`  
**Utilizzo:** Header principale (logo, nome comune, search)

**JSON Example:**
```json
{
    "type": "header-main",
    "data": {
        "view": "pub_theme::components.blocks.header.main",
        "logo": "/themes/Sixteen/images/logo.svg",
        "title": "Nome del Comune",
        "tagline": "Slogan del Comune",
        "social": [...],
        "search": {"enabled": true, "action": "/it/search"}
    }
}
```

**Props:**
- `municipality`: Nome comune
- `subtitle`: Sottotitolo/tagline
- `logo_url`: URL logo
- `search`: Configurazione search

**Documentazione:** [Blocks Index](../design-comuni/blocks/00-index.md)

---

### 7. Footer Main

**File:** `components/blocks/footer/main.blade.php` (NUOVO)  
**Tipo:** `footer-main`  
**Utilizzo:** Footer principale

**JSON Example:**
```json
{
    "type": "footer-main",
    "data": {
        "view": "pub_theme::components.blocks.footer.main",
        "contact": {
            "address": "Via Roma 123, 00100 Comune (XX)",
            "phone": "+39 0123 456789",
            "email": "info@comune.it",
            "pec": "pec@comune.it"
        },
        "columns": [
            {
                "title": "Amministrazione",
                "links": [
                    {"label": "Giunta", "url": "/it/giunta"},
                    {"label": "Consiglio", "url": "/it/consiglio"}
                ]
            }
        ],
        "social": [
            {"platform": "facebook", "url": "https://facebook.com"}
        ],
        "legal": {
            "privacy": "/it/privacy",
            "cookies": "/it/cookies",
            "notes": "/it/note-legali"
        }
    }
}
```

**Props:**
- `contact`: Dati di contatto (address, phone, email, pec)
- `columns`: Colonne link (title, links[])
- `social`: Social media links
- `legal`: Link legali (privacy, cookies, notes)

**Documentazione:** [Blocks Index](../design-comuni/blocks/00-index.md)

---

## 📊 Homepage Blocks Status

| Blocco | File | Stato | Priorità |
|--------|------|-------|----------|
| Header Slim | `header/slim.blade.php` | ✅ Esistente | 🔴 Alta |
| Header Main | `header/main.blade.php` | ✅ Esistente | 🔴 Alta |
| Navigation Main | `navigation/main.blade.php` | ✅ Creato | 🔴 Alta |
| Hero Homepage | `hero/homepage.blade.php` | ✅ Creato | 🔴 Alta |
| Quick Links | `quick-links/default.blade.php` | ✅ Creato | 🟡 Media |
| News Carousel | `news/carousel.blade.php` | ✅ Creato | 🟡 Media |
| Footer Main | `footer/main.blade.php` | ✅ Creato | 🔴 Alta |

**Stato Homepage:** ✅ **7/7 blocchi implementati (100%)**

---

## 🎯 Prossimi Blocchi da Implementare

### Per Altre Pagine

| Pagina | Blocchi Richiesti | Priorità |
|--------|-------------------|----------|
| Argomenti | `topics-grid.all`, `topics-grid.featured` | 🔴 Alta |
| FAQ | `list.faq`, `form.search` | 🟡 Media |
| Ricerca | `list.search-results`, `filters` | 🟡 Media |
| Amministrazione | `card-grid.organi`, `list.administration` | 🟡 Media |
| Appuntamento | `stepper.horizontal`, `form.*` | 🟢 Bassa |

---

## 📐 Standard Implementazione Blocchi

### Struttura File

```blade
@props(['prop1' => 'default', 'prop2' => []])

{{--
    Block Name - Description
    
    Usage:
    "view": "pub_theme::components.blocks.<type>.<blade>",
    "prop1": "value",
    "prop2": [...]
    
    Docs: docs/design-comuni/blocks/00-index.md
--}}

<div class="block-class">
    {{-- Block HTML --}}
</div>
```

### Requisiti

- ✅ Props ben definiti con default
- ✅ Commento con usage example
- ✅ Link a documentazione
- ✅ Classi **TailwindCSS** (NO Bootstrap Italia)
- ✅ Alpine.js per interattività (x-data, @click, x-show, x-transition)
- ✅ Accessibilità (ARIA labels, semantic HTML)
- ✅ Responsive ready
- ✅ Stringhe multilingua tramite `__('namespace::context.collection.key.type')` - **NO testo hardcoded**

---

## 🔗 Documenti Correlati

- [Blocks System Index](../design-comuni/blocks/00-index.md)
- [Pages Census](../design-comuni/pages-census.md)
- [Product Brief](../design-comuni/product-brief.md)
- [Layout Architecture](../architecture/layout-architecture.md)
- [Replikate.txt](./prompts/replikate.txt)

---

## ✅ Checklist Implementazione Blocco

```markdown
## Creazione
- [ ] File Blade creato: components/blocks/<tipo>/<blade>.blade.php
- [ ] Props definiti con @props
- [ ] Documentazione nel commento
- [ ] Link a docs/design-comuni/blocks/00-index.md

## Testing
- [ ] Testato con dati JSON
- [ ] Verificato rendering
- [ ] Controllata accessibilità
- [ ] Testato responsive

## Documentazione
- [ ] Aggiunto a blocks/00-index.md
- [ ] Esempio JSON fornito
- [ ] Props documentati
- [ ] Link bidirezionali
```

---

**Data:** 2026-04-01  
**Versione:** 1.0  
**Stato:** ✅ Homepage blocks completi (7/7)  
**Prossimo:** Implementare blocchi per pagina Argomenti

---

*"Blocchi universali, NON pagina-specifici"*  
*"DRY + KISS"*  
*"7 blocchi implementati per Homepage"*
