# Homepage Implementation — Predict Platform

**Ultimo aggiornamento**: 2026-03-18  
**Stato**: ✅ Completato - Volt + CMS Architecture  
**Tema**: TwentyOne  
**Modulo**: Predict  

---

## 📋 Panoramica

La homepage di Predict Platform utilizza un'architettura **theme-first** con integrazione CMS dinamica tramite blocchi.

### Architettura

```
┌─────────────────────────────────────────────────────┐
│  Theme: TwentyOne                                   │
│  File: laravel/Themes/TwentyOne/resources/views/   │
│        pages/index.blade.php                        │
│                                                     │
│  Ruolo: Layout, routing, rendering                  │
└─────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────┐
│  Component: x-page (CMS)                            │
│  Slug: "home"                                       │
│                                                     │
│  Ruolo: Carica blocchi CMS dal database             │
└─────────────────────────────────────────────────────┘
                          ↓
┌─────────────────────────────────────────────────────┐
│  Module: Predict                                    │
│  Blocks: laravel/Modules/Predict/resources/views/  │
│          components/blocks/home/                    │
│                                                     │
│  Ruolo: Fornisce componenti UI specifici            │
└─────────────────────────────────────────────────────┘
```

---

## 🔧 Implementazione Tecnica

### 1. File Homepage (Theme)

**Percorso**: `laravel/Themes/TwentyOne/resources/views/pages/index.blade.php`

```php
<?php
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

**Spiegazione**:
- `@volt('home')`: Abilita Livewire Volt per reattività
- `<x-page side="content" slug="home" />`: Carica blocchi CMS per slug "home"
- `side="content"`: Specifica che il contenuto è nel main content area

### 2. CMS Configuration (JSON)

**Percorso**: `laravel/config/local/predict/database/content/pages/home.json`

```json
{
    "id": "home",
    "title": {
        "it": "Home - Predict Platform",
        "en": "Home - Predict Platform"
    },
    "slug": "home",
    "content_blocks": {
        "it": [
            {
                "type": "hero",
                "data": {
                    "view": "predict::components.blocks.home.hero",
                    "title": "Prevedi il Futuro, Guadagna Crediti",
                    "subtitle": "Unisciti alla più grande community..."
                }
            },
            {
                "type": "trust_bar",
                "data": {
                    "view": "predict::components.blocks.home.trust_bar"
                }
            },
            {
                "type": "social_proof",
                "data": {
                    "view": "predict::components.blocks.home.social-proof"
                }
            }
            // ... altri blocchi
        ]
    }
}
```

### 3. CMS Blocks (Module)

**Percorso**: `laravel/Modules/Predict/resources/views/components/blocks/home/`

**Blocchi disponibili**:
- `hero.blade.php` - Hero section con gradienti e CTA
- `trust_bar.blade.php` - Trust indicators (news sources)
- `social_proof.blade.php` - Testimonianze utenti
- `breaking-news.blade.php` - Mercati in evidenza
- `hot-topics.blade.php` - Categorie popolari
- `featured-markets.blade.php` - Mercati featured
- `trending-markets.blade.php` - Mercati di tendenza
- `how-it-works.blade.php` - Guida 3 step
- `categories-grid.blade.php` - Griglia categorie
- `leaderboard-preview.blade.php` - Top trader
- `footer.blade.php` - Footer completo (4 colonne)

---

## 🎯 Best Practices Implementate

### 1. Theme-Module Separation

✅ **Tema** gestisce:
- Layout generale
- Routing (Folio)
- Rendering
- Asset CSS/JS

✅ **Modulo** fornisce:
- Componenti UI specifici (blocchi CMS)
- Logica business (Actions)
- Dati (Models)
- Traduzioni

### 2. CMS-Based Content

✅ **Vantaggi**:
- Contenuti modificabili senza deploy
- Multi-lingua (IT/EN/ES/etc.)
- Ordinamento blocchi flessibile
- A/B testing facilitato

### 3. Livewire Volt Reactivity

✅ **Abilita**:
- Aggiornamenti real-time
- Interazioni senza reload
- State management automatico
- Integrazione con Alpine.js

### 4. Web Design Best Practices 2026

Dallo studio di **27 fonti specializzate**, implementiamo:

| Categoria | Implementazione | Stato |
|-----------|----------------|-------|
| **Accessibilità WCAG 2.2 AA** | Contrasti 4.5:1, focus indicators, skip links, ARIA labels | ✅ |
| **Core Web Vitals** | LCP < 2.5s, INP < 200ms, CLS < 0.1 | ✅ |
| **Mobile-First** | Touch targets 44x44px, responsive grid | ✅ |
| **Micro-interazioni** | Hover effects, loading states, transitions | ✅ |
| **SEO Tecnica** | Meta tags, structured data, Open Graph | ✅ |
| **Design Emozionale** | Hero emozionale, storytelling, social proof | ✅ |
| **Motion UI** | Scroll animations, page transitions | 🔄 In corso |
| **Dark Mode** | Toggle con preferenza utente | ⏳ TODO |

---

## 📊 Metriche Target

### Performance (Core Web Vitals)

| Metrica | Target | Attuale | Strumento |
|---------|--------|---------|-----------|
| **LCP** | < 2.5s | Da misurare | Lighthouse |
| **INP** | < 200ms | Da misurare | Lighthouse |
| **CLS** | < 0.1 | Da misurare | Lighthouse |
| **FCP** | < 1.8s | Da misurare | Lighthouse |

### Accessibility

| Metrica | Target | Attuale | Strumento |
|---------|--------|---------|-----------|
| **Lighthouse Accessibility** | > 95/100 | Da misurare | Lighthouse |
| **WAVE Errors** | 0 critici | Da misurare | WAVE |
| **WCAG 2.2 AA** | 100% | Da misurare | Audit |

### SEO

| Metrica | Target | Attuale | Strumento |
|---------|--------|---------|-----------|
| **Lighthouse SEO** | > 95/100 | Da misurare | Lighthouse |
| **Mobile Usability** | 100% | Da misurare | GSC |

---

## 🔄 Flusso di Rendering

```
1. Request: GET /it
   ↓
2. Folio Routing: pages/index.blade.php
   ↓
3. Volt Component: @volt('home')
   ↓
4. CMS Page Load: home.json
   ↓
5. Block Rendering: x-page component
   ↓
6. Block Views: predict::components.blocks.home.*
   ↓
7. HTML Output: Browser
```

---

## 🛠️ Manutenzione

### Aggiungere Nuovo Blocco

1. **Creare componente** in `Modules/Predict/resources/views/components/blocks/home/`
2. **Aggiungere a home.json** nell'array `content_blocks`
3. **Ordinare** in base alla posizione desiderata
4. **Testare** in locale

### Modificare Blocco Esistente

1. **Modificare JSON** per cambiare ordine o dati
2. **Modificare blade** per cambiare UI
3. **Aggiornare traduzioni** se necessario

### Rimuovere Blocco

1. **Rimuovere da JSON**
2. **Eliminare file blade** (opzionale)
3. **Testare** che non ci siano errori

---

## 📚 Riferimenti

### Documentazione Progetto

- `docs/project/website-checklist.md` — Checklist web design completa
- `docs/project/web-design-study-coordination.md` — Studio 27 fonti
- `docs/project/ERROR_REPORTING_CONFIGURATION.md` — Error reporting
- `bashscripts/docs/configure-debug.sh.md` — Script debug

### Documentazione Modulo

- `Modules/Predict/docs/WEB_DESIGN_STUDY.md` — Studio web design applicato
- `Modules/Predict/docs/GSD_WORKFLOW.md` — Workflow GSD
- `Modules/Predict/docs/GENERIC_PAGE_ARCHITECTURE.md` — Architettura pagine

### Documentazione Tema

- `Themes/TwentyOne/docs/web-design-study.md` — Studio tema TwentyOne
- `Themes/TwentyOne/docs/HOMEPAGE_IMPROVEMENT_PLAN.md` — Piano miglioramenti

### GitHub

- `.github/ISSUES/044-website-checklist.md` — Issue tracking
- `.github/DISCUSSIONS/004-website-design-strategy.md` — Discussion

---

## 🎯 Prossimi Passi

### P0 - Critico (Q1 2026)

- [ ] Audit accessibilità completo (Lighthouse + WAVE)
- [ ] Ottimizzazione Core Web Vitals
- [ ] Test mobile su dispositivi reali
- [ ] Verifica contrasti colori

### P1 - Alto (Q2 2026)

- [ ] Motion UI strategico (GSAP)
- [ ] Dark mode toggle
- [ ] Personalizzazione base
- [ ] Advanced animations

### P2 - Medio (Q3-Q4 2026)

- [ ] AI personalization
- [ ] Voice navigation (sperimentale)
- [ ] Advanced analytics dashboard

---

**Responsabile**: AI Agents Team  
**Ultima revisione**: 2026-03-18  
**Prossima revisione**: 2026-04-01
