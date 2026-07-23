# Design Comuni HTML Replication - Action Plan

**Project:** FixCity Fila5
**Date:** 2026-04-01
**Status:** 🔄 **In Progress**
**Priority:** 🔴 **Critical**

---

## 🎯 Goal

Replicare **ESATTAMENTE** l'HTML di Design Comuni (dentro `<body>`, esclusi `<script>`) usando componenti Blade riutilizzabili.

---

## 📋 Action Plan

### Step 1: Scaricare HTML Grezzo da GitHub

**Source:** https://github.com/italia/design-comuni-pagine-statiche

```bash
# Scarica homepage.html grezzo
curl -s https://raw.githubusercontent.com/italia/design-comuni-pagine-statiche/refs/heads/main/sito/homepage.html \
  > /tmp/design-comuni-homepage.html

# Estrai solo il contenuto <body> (esclusi <script>)
# Usare puppeter o similar per estrarre HTML renderizzato
```

**Alternative:** Usare GitHub raw URL direttamente

---

### Step 2: Analizzare Struttura HTML

**Struttura da replicare:**

```
<body>
├── .skiplink (2 link)
├── header.it-header-wrapper
│   ├── .it-top-nav-wrapper
│   ├── .it-header-slim-wrapper
│   └── nav.navbar
├── main#main-container
│   ├── .it-hero-wrapper
│   ├── section (news)
│   ├── section (governance)
│   ├── section (events)
│   ├── section (topics)
│   ├── section (search & feedback)
│   └── section (quick links)
└── footer#footer.it-footer
    ├── .it-footer-main (6 columns)
    └── .it-footer-small-prints
```

---

### Step 3: Mappare Componenti Blade

| HTML Element | Blade Component | File | Status |
|--------------|----------------|-------|--------|
| `.skiplink` | `<x-section slug="skiplink" />` | `components/sections/skiplink.blade.php` | ✅ Done |
| `header.it-header-wrapper` | `<x-section slug="header" />` | `components/sections/header.blade.php` | ✅ Done |
| `.it-top-nav-wrapper` | `<x-blocks.header.top-bar />` | `components/blocks/header/top-bar.blade.php` | ⏳ To Do |
| `.it-header-slim-wrapper` | `<x-blocks.header.slim />` | `components/blocks/header/slim.blade.php` | ✅ Done |
| `nav.navbar` | `<x-blocks.header.navbar />` | `components/blocks/header/navbar.blade.php` | ⏳ To Do |
| `.it-hero-wrapper` | `<x-blocks.hero.default />` | `components/blocks/hero/default.blade.php` | ✅ Done |
| `section.news` | `<x-blocks.news.carousel />` | `components/blocks/news/carousel.blade.php` | ✅ Done |
| `section.governance` | `<x-blocks.governance.cards />` | `components/blocks/governance/cards.blade.php` | ⏳ To Do |
| `section.events` | `<x-blocks.events.calendar />` | `components/blocks/events/calendar.blade.php` | ⏳ To Do |
| `section.topics` | `<x-blocks.topics-grid.default />` | `components/blocks/topics-grid/default.blade.php` | ✅ Done |
| `section.search` | `<x-blocks.search.support-links />` | `components/blocks/search/support-links.blade.php` | ✅ Done |
| `section.feedback` | `<x-blocks.feedback.survey />` | `components/blocks/feedback/survey.blade.php` | ✅ Done |
| `footer.it-footer` | `<x-section slug="footer" tpl="full" />` | `components/sections/footer/full.blade.php` | ✅ Done |

---

### Step 4: Creare Componenti Mancanti

#### 4.1 Header Top Bar

**File:** `components/blocks/header/top-bar.blade.php`

```blade
@props(['region' => 'Nome della Regione'])

<div class="it-top-nav-wrapper">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-6 col-md-3">
                <span class="top-nav-region">{{ $region }}</span>
            </div>
            <div class="col-6 col-md-3 offset-md-6 text-right">
                {{-- Language selector --}}
                <select class="form-control form-control-sm" aria-label="Seleziona lingua">
                    <option value="it" selected>ITA</option>
                    <option value="en">ENG</option>
                </select>
                
                {{-- Login button --}}
                <a href="{{ route('login') }}" class="btn btn-sm btn-primary">
                    <svg class="icon icon-sm">
                        <use href="#it-user"></use>
                    </svg>
                    Accedi all'area personale
                </a>
            </div>
        </div>
    </div>
</div>
```

---

#### 4.2 Header Navbar

**File:** `components/blocks/header/navbar.blade.php`

```blade
@props(['comuneName' => 'Nome del Comune', 'menuItems' => []])

<nav class="navbar navbar-expand-lg" role="navigation" aria-label="Navigazione principale">
    <div class="container">
        <button class="custom-navbar-toggler" type="button" aria-controls="nav10" aria-expanded="false" aria-label="Toggle navigation">
            <svg class="icon">
                <use href="#it-burger"></use>
            </svg>
        </button>
        
        <div class="navbar-collapsable" id="nav10">
            <div class="overlay"></div>
            <div class="close-div sr-only">
                <button class="btn close-menu" type="button">
                    <span class="it-close"></span>close
                </button>
            </div>
            
            <div class="menu-wrapper">
                <ul class="navbar-nav">
                    @foreach($menuItems as $item)
                    <li class="nav-item @if($item['children']) dropdown @endif">
                        <a class="nav-link @if($item['active']) active @endif" 
                           href="{{ $item['url'] }}"
                           @if($item['children']) aria-haspopup="true" aria-expanded="false" @endif>
                            <span>{{ $item['label'] }}</span>
                            @if($item['children'])
                            <svg class="icon icon-xs">
                                <use href="#it-expand"></use>
                            </svg>
                            @endif
                        </a>
                        
                        @if($item['children'])
                        <div class="dropdown-menu">
                            <div class="link-list-wrapper">
                                <ul class="link-list">
                                    @foreach($item['children'] as $child)
                                    <li>
                                        <a href="{{ $child['url'] }}">
                                            <span>{{ $child['label'] }}</span>
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</nav>
```

---

#### 4.3 Governance Cards

**File:** `components/blocks/governance/cards.blade.php`

```blade
@props(['data' => []])

@php
    $title = $data['title'] ?? 'Organi di governo';
    $cards = $data['cards'] ?? [];
@endphp

<section class="py-5">
    <div class="container">
        <h2 class="mb-4">{{ $title }}</h2>
        
        <div class="row g-4">
            @foreach($cards as $card)
            <div class="col-12 col-md-4">
                <div class="card card-teaser shadow p-4 rounded border border-light h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $card['title'] }}</h5>
                        <p class="card-text text-muted">{{ $card['role'] }}</p>
                        
                        @if($card['description'] ?? false)
                        <p class="card-text">{{ $card['description'] }}</p>
                        @endif
                        
                        <a href="{{ $card['url'] }}" class="btn btn-outline-primary mt-3">
                            Vai alla pagina
                            <svg class="icon icon-sm">
                                <use href="#it-arrow-right"></use>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
```

---

### Step 5: Aggiornare JSON Homepage

**File:** `config/local/fixcity/database/content/pages/tests.homepage.json`

Aggiornare i blocchi per usare i nuovi componenti:

```json
{
  "content_blocks": {
    "it": [
      {
        "type": "hero",
        "weight": 10,
        "data": {
          "view": "pub_theme::components.blocks.hero.default",
          "title": "NOME DEL COMUNE",
          "subtitle": "CONTENUTI IN EVIDENZA",
          "backgroundImage": "/themes/Sixteen/images/hero-bg.jpg"
        }
      },
      {
        "type": "governance",
        "weight": 20,
        "data": {
          "view": "pub_theme::components.blocks.governance.cards",
          "title": "Organi di governo",
          "cards": [
            {
              "title": "MARIO ROSSI",
              "role": "Il Sindaco della città",
              "url": "/it/amministrazione/sindaco"
            }
          ]
        }
      }
    ]
  }
}
```

---

## 📊 Progress Tracking

| Component | File | HTML Parity | Status |
|-----------|------|-------------|--------|
| Skip Links | ✅ | 100% | ✅ Complete |
| Header Top Bar | ⏳ | 0% | ⏳ To Do |
| Header Slim | ✅ | 90% | ⚠️ Verify |
| Header Navbar | ⏳ | 0% | ⏳ To Do |
| Hero | ✅ | 95% | ⚠️ Verify |
| News Carousel | ✅ | 90% | ⚠️ Verify |
| Governance Cards | ⏳ | 0% | ⏳ To Do |
| Events Calendar | ⏳ | 0% | ⏳ To Do |
| Topics Grid | ✅ | 95% | ⚠️ Verify |
| Search | ✅ | 90% | ✅ Complete |
| Feedback | ✅ | 90% | ✅ Complete |
| Footer | ✅ | 95% | ⚠️ Verify |

**Overall:** 50% Complete (5/10 components)

---

## 🔗 Cross-References

### Internal Documents

- → [HTML Replication Analysis](DESIGN_COMUNI_HTML_REPLICATION.md) - Detailed analysis
- → [Layout Architecture](LAYOUT_ARCHITECTURE_AND_NAMESPACE.md) - Layout hierarchy
- → [Block Analysis](_bmad-output/design-comuni-block-analysis.md) - 47 componenti
- → [BMad UI Spec](_bmad-output/design-comuni-ui-spec.md) - Component specs

### External Resources

- → [Design Comuni GitHub](https://github.com/italia/design-comuni-pagine-statiche)
- → [Design Comuni Live](https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html)

---

**📝 Documento preparato da:** Multi-Agent Team (BMad + GSD)
**📅 Data:** 2026-04-01
**🔄 Next Review:** After component implementation
**🎯 Status:** 🔄 **In Progress**

🐮 **Action Plan Created - Implement Components!**
