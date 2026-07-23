# Design Comuni HTML Replication - Body Structure Analysis

**Project:** FixCity Fila5
**Date:** 2026-04-01
**Status:** 🔄 **In Progress**
**Priority:** 🔴 **Critical**

---

## 🎯 Obiettivo

Replicare l'HTML esatto di Design Comuni all'interno del tag `<body>` (esclusi gli `<script>`).

**URL di riferimento:** https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html

---

## 📐 Struttura HTML Body (Design Comuni)

### Struttura Generale

```html
<body>
    <!-- 1. Skip Links -->
    <div class="skiplink">
        <a class="visually-hidden-focusable" href="#main-container">Vai ai contenuti</a>
        <a class="visually-hidden-focusable" href="#footer">Vai al footer</a>
    </div>
    
    <!-- 2. Header -->
    <header class="it-header-wrapper" role="banner">
        <!-- 2.1 Top Bar -->
        <div class="it-top-nav-wrapper">...</div>
        
        <!-- 2.2 Header Slim -->
        <div class="it-header-slim-wrapper">...</div>
        
        <!-- 2.3 Navbar -->
        <nav class="navbar navbar-expand-lg">...</nav>
    </header>
    
    <!-- 3. Main Content -->
    <main id="main-container">
        <!-- 3.1 Hero Section -->
        <div class="it-hero-wrapper">...</div>
        
        <!-- 3.2 News Section -->
        <section class="py-5">...</section>
        
        <!-- 3.3 Governance Section -->
        <section class="py-5">...</section>
        
        <!-- 3.4 Events Section -->
        <section class="py-5">...</section>
        
        <!-- 3.5 Topics Section -->
        <section class="py-5">...</section>
        
        <!-- 3.6 Search & Feedback -->
        <section class="py-5">...</section>
    </main>
    
    <!-- 4. Footer -->
    <footer id="footer" class="it-footer" role="contentinfo">
        <!-- 4.1 Footer Main -->
        <div class="it-footer-main">...</div>
        
        <!-- 4.2 Legal Bar -->
        <div class="it-footer-small-prints">...</div>
    </footer>
</body>
```

---

## 🔍 Analisi Dettagliata per Sezione

### 1. Skip Links

**Design Comuni HTML:**
```html
<div class="skiplink">
    <a class="visually-hidden-focusable" href="#main-container">Vai ai contenuti</a>
    <a class="visually-hidden-focusable" href="#footer">Vai al footer</a>
</div>
```

**FixCity Implementation:**
```blade
{{-- layouts/app.blade.php --}}
<div class="skiplink">
    <a class="visually-hidden-focusable" href="#main-container">Vai ai contenuti</a>
    <a class="visually-hidden-focusable" href="#footer">Vai al footer</a>
</div>
```

**Status:** ✅ **Implementato**

---

### 2. Header

#### 2.1 Top Bar

**Design Comuni HTML:**
```html
<div class="it-top-nav-wrapper">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-6 col-md-3">
                <span class="top-nav-region">Nome della Regione</span>
            </div>
            <div class="col-6 col-md-3 offset-md-6 text-right">
                <select class="form-control form-control-sm" aria-label="Seleziona lingua">
                    <option value="it" selected>ITA</option>
                    <option value="en">ENG</option>
                </select>
                <a href="/login" class="btn btn-sm btn-primary">
                    <svg class="icon icon-sm"><use href="/svg/sprites.svg#it-user"></use></svg>
                    Accedi all'area personale
                </a>
            </div>
        </div>
    </div>
</div>
```

**FixCity Implementation:**
```blade
{{-- components/blocks/header/slim.blade.php --}}
<div class="it-header-slim-wrapper">
    <div class="container">
        <div class="it-header-slim-wrapper-content">
            <a href="#" class="it-header-slim-link">
                {{ $region ?: 'Nome della Regione' }}
            </a>
            
            <div class="it-header-slim-right-zone">
                <div class="dropdown">
                    <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="visually-hidden">Lingua attiva:</span>
                        IT
                        <svg class="icon icon-xs">
                            <use href="#it-chevron-down"></use>
                        </svg>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item" href="#" lang="it">
                                <span>ITA</span>
                                @if($language === 'ITA')
                                    <svg class="icon icon-xs ms-2">
                                        <use href="#it-check"></use>
                                    </svg>
                                @endif
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#" lang="en">
                                <span>ENG</span>
                            </a>
                        </li>
                    </ul>
                </div>
                
                <a href="{{ $login_url }}" class="btn btn-primary btn-sm">
                    <svg class="icon icon-sm">
                        <use href="#it-user"></use>
                    </svg>
                    <span>Accedi all'area personale</span>
                </a>
            </div>
        </div>
    </div>
</div>
```

**Status:** ⚠️ **Parzialmente Implementato**
- ❌ Classe: `it-top-nav-wrapper` vs `it-header-slim-wrapper`
- ❌ Struttura: `row align-items-center` vs `it-header-slim-wrapper-content`
- ❌ Language selector: `<select>` vs dropdown Bootstrap

**Action:** Creare componente `components/blocks/header/top-bar.blade.php`

---

#### 2.2 Header Branding

**Design Comuni HTML:**
```html
<div class="it-header-slim-wrapper">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-3 col-md-2">
                <div class="it-header-slim-logo">
                    <img src="/images/stem.svg" alt="Stemma del Comune">
                </div>
            </div>
            <div class="col-9 col-md-8">
                <div class="it-header-slim-content">
                    <h1 class="it-header-slim-title">NOME DEL COMUNE</h1>
                    <p class="it-header-slim-slogan">Un comune da vivere</p>
                </div>
            </div>
            <div class="col-md-2">
                <div class="it-header-slim-social">
                    <!-- Social icons -->
                </div>
            </div>
        </div>
    </div>
</div>
```

**Status:** ⚠️ **Da Verificare**

---

### 3. Main Content

#### 3.1 Hero Section

**Design Comuni HTML:**
```html
<div class="it-hero-wrapper it-dark it-overlay">
    <div class="img-responsive-wrapper">
        <div class="img-responsive">
            <div class="img-wrapper">
                <img src="/images/hero-bg.jpg" alt="NOME DEL COMUNE">
            </div>
        </div>
    </div>
    
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="it-hero-text-wrapper bg-dark">
                    <h1 class="no_toc">NOME DEL COMUNE</h1>
                    <p class="d-none d-lg-block">CONTENUTI IN EVIDENZA</p>
                </div>
            </div>
        </div>
    </div>
</div>
```

**FixCity Implementation:**
```blade
{{-- components/blocks/hero/default.blade.php --}}
<div class="it-hero-wrapper it-dark it-overlay">
    @if(isset($data['backgroundImage']))
    <div class="img-responsive-wrapper">
        <div class="img-responsive">
            <div class="img-wrapper">
                <img src="{{ $data['backgroundImage'] }}" alt="{{ $data['title'] }}">
            </div>
        </div>
    </div>
    @endif
    
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="it-hero-text-wrapper bg-dark">
                    <h1 class="no_toc">{{ $data['title'] ?? '' }}</h1>
                    @if(isset($data['subtitle']))
                    <p class="d-none d-lg-block">{{ $data['subtitle'] }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
```

**Status:** ✅ **Implementato** (verificare classi esatte)

---

### 4. Footer

**Design Comuni HTML:**
```html
<footer id="footer" class="it-footer" role="contentinfo">
    <div class="it-footer-main">
        <div class="container">
            <div class="row">
                <!-- Column 1: Branding -->
                <div class="col-12 col-md-4">
                    <div class="it-brand-wrapper">
                        <h2 class="no_toc">NOME DEL COMUNE</h2>
                    </div>
                </div>
                
                <!-- Column 2-6: Links -->
                <div class="col-12 col-md-4 col-lg-2">
                    <h4 class="no_toc">Amministrazione</h4>
                    <ul class="link-list">...</ul>
                </div>
                
                <!-- Legal Bar -->
                <div class="it-footer-small-prints">
                    <div class="container">
                        <ul class="list-inline">
                            <li><a href="#">Privacy</a></li>
                            <li><a href="#">Note legali</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
```

**Status:** ⚠️ **Parzialmente Implementato**

---

## 📋 Checklist Implementazione

### Header

- [ ] Top bar con classe `it-top-nav-wrapper`
- [ ] Header branding con stemma
- [ ] Navbar con menu espandibile
- [ ] Social icons nell'header

### Main Content

- [x] Hero section
- [ ] News section (carousel)
- [ ] Governance section (organi di governo)
- [ ] Events section (calendario)
- [ ] Topics section (griglia argomenti)
- [ ] Search section
- [ ] Feedback section

### Footer

- [ ] Footer main a 6 colonne
- [ ] Social links
- [ ] Legal bar
- [ ] Contact info

---

## 🔧 Componenti da Creare/Aggiornare

| Componente | File | Priority | Status |
|------------|------|----------|--------|
| `header/top-bar` | `components/blocks/header/top-bar.blade.php` | 🔴 Critical | ⏳ To Do |
| `header/branding` | `components/blocks/header/branding.blade.php` | 🔴 Critical | ⏳ To Do |
| `header/navbar` | `components/blocks/header/navbar.blade.php` | 🔴 Critical | ⏳ To Do |
| `news/carousel` | `components/blocks/news/carousel.blade.php` | 🟠 High | ✅ Done |
| `governance/cards` | `components/blocks/governance/cards.blade.php` | 🟠 High | ⏳ To Do |
| `events/calendar` | `components/blocks/events/calendar.blade.php` | 🟠 High | ⏳ To Do |
| `topics/grid` | `components/blocks/topics-grid/default.blade.php` | 🟠 High | ✅ Done |
| `footer/main` | `components/blocks/footer/main.blade.php` | 🔴 Critical | ⏳ To Do |

---

## 🔗 Cross-References

### Internal Documents

- → [Layout Architecture](Themes/Sixteen/docs/LAYOUT_ARCHITECTURE_AND_NAMESPACE.md) - Layout hierarchy
- → [Page Component Architecture](Modules/Cms/docs/PAGE_COMPONENT_ARCHITECTURE.md) - Page component
- → [Block Analysis](_bmad-output/design-comuni-block-analysis.md) - 47 componenti
- → [BMad UI Spec](_bmad-output/design-comuni-ui-spec.md) - Component specs

### External Resources

- → [Design Comuni Pagine Statiche](https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html)
- → [Bootstrap Italia Documentation](https://italia.github.io/bootstrap-italia/)

---

**📝 Documento preparato da:** Multi-Agent Team (BMad + GSD)
**📅 Data:** 2026-04-01
**🔄 Next Review:** After component implementation
**🎯 Status:** 🔄 **In Progress**

🐮 **HTML Analysis In Progress - Components To Do!**
