# Design Comuni - Amministrazione HTML Analysis

**Project:** FixCity Fila5
**Date:** 2026-04-01
**Status:** 🔄 **Analyzing**
**Priority:** 🔴 **Critical**

**Reference:** https://italia.github.io/design-comuni-pagine-statiche/sito/amministrazione.html
**FixCity URL:** http://127.0.0.1:8000/it/tests/amministrazione

---

## 📐 Struttura HTML Body (Design Comuni)

```html
<body>
    <!-- 1. Skip Links -->
    <div class="skiplink">
        <a class="visually-hidden-focusable" href="#main-container">Vai ai contenuti</a>
        <a class="visually-hidden-focusable" href="#footer">Vai al footer</a>
    </div>

    <!-- 2. Header -->
    <header class="it-header-wrapper" data-bs-target="#header-nav-wrapper">
        <!-- 2.1 Header Slim -->
        <div class="it-header-slim-wrapper">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="it-header-slim-wrapper-content">
                            <!-- Region -->
                            <a class="d-lg-block navbar-brand" href="#">Nome della Regione</a>
                            
                            <!-- Right Zone -->
                            <div class="it-header-slim-right-zone">
                                <!-- Language Dropdown -->
                                <div class="dropdown">
                                    <button class="dropdown-toggle">ITA</button>
                                    <div class="dropdown-menu">ITA, ENG</div>
                                </div>
                                
                                <!-- Login Button -->
                                <a class="btn btn-primary btn-icon btn-full">Accedi all'area personale</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2.2 Header Center (Branding) -->
        <div class="it-nav-wrapper">
            <div class="it-header-center-wrapper">
                <div class="container">
                    <div class="it-header-center-content-wrapper">
                        <!-- Brand -->
                        <div class="it-brand-wrapper">
                            <a href="homepage.html">
                                <svg width="82" height="82">Logo</svg>
                                <div class="it-brand-text">
                                    <div class="it-brand-title">Il mio Comune</div>
                                    <div class="it-brand-tagline">Un comune da vivere</div>
                                </div>
                            </a>
                        </div>
                        
                        <!-- Social -->
                        <div class="it-socials">Twitter, Facebook, etc.</div>
                    </div>
                </div>
            </div>

            <!-- 2.3 Navbar -->
            <div class="it-header-navbar-wrapper">
                <div class="container">
                    <nav class="navbar navbar-expand-lg">
                        <!-- Menu items: Amministrazione, Novità, Servizi, Vivere -->
                    </nav>
                </div>
            </div>
        </div>
    </header>

    <!-- 3. Main Content -->
    <main id="main-container">
        <!-- 3.1 Breadcrumbs -->
        <div class="breadcrumb-container">
            <ol class="breadcrumb">
                <li><a href="#">Home</a></li>
                <li class="active">Amministrazione</li>
            </ol>
        </div>

        <!-- 3.2 Hero Section -->
        <div class="container">
            <div class="it-page-title">
                <h1>Amministrazione</h1>
                <p>Conosci gli organi di governo...</p>
            </div>
        </div>

        <!-- 3.3 Cards Section - Organi di governo -->
        <section class="py-5">
            <div class="container">
                <h2>Organi di governo</h2>
                <div class="row g-4">
                    <div class="col-12 col-md-4">
                        <div class="card card-teaser">
                            <div class="card-body">
                                <h5>Sindaco</h5>
                                <p>Il Sindaco e la sua giunta</p>
                            </div>
                        </div>
                    </div>
                    <!-- More cards... -->
                </div>
            </div>
        </section>

        <!-- 3.4 Links Section - Documenti e dati -->
        <section class="py-5 bg-gray-100">
            <div class="container">
                <h2>Documenti e dati</h2>
                <ul class="link-list">
                    <li><a href="#">Statuto comunale</a></li>
                    <li><a href="#">Regolamenti</a></li>
                </ul>
            </div>
        </section>

        <!-- 3.5 Explore Section - Esplora amministrazione -->
        <section class="py-5">
            <div class="container">
                <h2>Esplora amministrazione</h2>
                <div class="row g-4">
                    <div class="col-12 col-md-4">
                        <div class="card card-simple">
                            <h5>Giunta</h5>
                        </div>
                    </div>
                    <!-- More cards... -->
                </div>
            </div>
        </section>
    </main>

    <!-- 4. Footer -->
    <footer id="footer" class="it-footer">
        <!-- Footer content... -->
    </footer>
</body>
```

---

## 🔍 Confronto con FixCity

### FixCity Current Implementation

**File:** `http://127.0.0.1:8000/it/tests/amministrazione`

**Issues:**
1. ❌ Layout Laravel default invece del tema Sixteen
2. ❌ Header non Bootstrap Italia
3. ❌ Mancano breadcrumbs
4. ❌ Mancano sezioni contenuto

---

## 📋 Componenti Necessari

| Sezione | Componente Blade | File | Status |
|---------|-----------------|------|--------|
| Breadcrumbs | `<x-blocks.breadcrumb.default>` | `components/blocks/breadcrumb/default.blade.php` | ⏳ To Do |
| Hero Page | `<x-blocks.hero.default>` | `components/blocks/hero/default.blade.php` | ✅ Done |
| Cards Grid | `<x-blocks.cards.grid>` | `components/blocks/cards/grid.blade.php` | ⏳ To Do |
| Links List | `<x-blocks.links.list>` | `components/blocks/links/list.blade.php` | ⏳ To Do |

---

## 🎯 Action Plan

### 1. Creare Breadcrumb Component

**File:** `components/blocks/breadcrumb/default.blade.php`

```blade
@props(['data' => []])

@php
    $items = $data['items'] ?? [];
@endphp

<div class="breadcrumb-container" aria-label="breadcrumb">
    <div class="container">
        <ol class="breadcrumb">
            @foreach($items as $item)
            <li class="breadcrumb-item @if($loop->last) active @endif">
                @if($loop->last)
                    {{ $item['label'] }}
                @else
                    <a href="{{ $item['url'] }}">{{ $item['label'] }}</a>
                @endif
            </li>
            @endforeach
        </ol>
    </div>
</div>
```

---

### 2. Creare Cards Grid Component

**File:** `components/blocks/cards/grid.blade.php`

```blade
@props(['data' => []])

@php
    $title = $data['title'] ?? '';
    $cards = $data['cards'] ?? [];
@endphp

<section class="py-5">
    <div class="container">
        @if($title)
        <h2 class="mb-4">{{ $title }}</h2>
        @endif
        
        <div class="row g-4">
            @foreach($cards as $card)
            <div class="col-12 col-md-4">
                <div class="card card-teaser shadow p-4 rounded border border-light">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ $card['url'] ?? '#' }}">{{ $card['title'] ?? '' }}</a>
                        </h5>
                        @if($card['description'] ?? false)
                        <p class="card-text">{{ $card['description'] }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
```

---

### 3. Creare Links List Component

**File:** `components/blocks/links/list.blade.php`

```blade
@props(['data' => []])

@php
    $title = $data['title'] ?? '';
    $links = $data['links'] ?? [];
@endphp

<section class="py-5 bg-it-gray-50">
    <div class="container">
        @if($title)
        <h2 class="mb-4">{{ $title }}</h2>
        @endif
        
        <div class="link-list-wrapper">
            <ul class="link-list">
                @foreach($links as $link)
                <li>
                    <a href="{{ $link['url'] ?? '#' }}">
                        <span>{{ $link['label'] ?? '' }}</span>
                        @if($link['description'] ?? false)
                        <p class="small text-muted">{{ $link['description'] }}</p>
                        @endif
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
```

---

## 📊 JSON Structure

**File:** `config/local/fixcity/database/content/pages/tests.amministrazione.json`

Il JSON esiste già con questa struttura:

```json
{
  "content_blocks": {
    "it": [
      {
        "type": "breadcrumb",
        "data": { "view": "pub_theme::components.blocks.breadcrumb.default", ... }
      },
      {
        "type": "hero",
        "data": { "view": "pub_theme::components.blocks.hero.default", ... }
      },
      {
        "type": "cards-grid",
        "data": { "view": "pub_theme::components.blocks.cards.grid", ... }
      },
      {
        "type": "links-list",
        "data": { "view": "pub_theme::components.blocks.links.list", ... }
      }
    ]
  }
}
```

---

## ✅ Checklist Implementazione

- [ ] Creare `components/blocks/breadcrumb/default.blade.php`
- [ ] Creare `components/blocks/cards/grid.blade.php`
- [ ] Creare `components/blocks/links/list.blade.php`
- [ ] Verificare che il tema Sixteen sia attivo
- [ ] Testare pagina `/it/tests/amministrazione`
- [ ] Confrontare HTML con Design Comuni

---

**📝 Documento preparato da:** Multi-Agent Team (BMad + GSD)
**📅 Data:** 2026-04-01
**🔄 Status:** 🔄 **In Progress**

🐮 **Amministrazione Analysis In Progress!**
