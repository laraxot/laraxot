# Block 00: Header e Navigazione

**ID:** `header-navigation`  
**Reference:** Homepage - Header completo con slim bar, brand, search, navigation  
**Bootstrap Italia Classi:** `it-header-wrapper`, `it-header-slim-wrapper`, `navbar`, `has-megamenu`  
**Tailwind Equivalente:** Da implementare con `@apply` + Alpine.js per interazioni

## 📍 Posizione nella Pagina

- **Primo elemento** dopo `<body>` e skip links
- **Contiene:** Regione, Lingua, Area personale, Social, Brand, Search, Navigazione principale
- **Tipo:** Sticky/fixed header (opzionale)

## 🏗️ Struttura HTML

```html
<header class="it-header-wrapper" data-bs-target="#header-nav-wrapper">
  
  <!-- === PARTE 1: Slim Header (barra superiore) === -->
  <div class="it-header-slim-wrapper">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="it-header-slim-wrapper-content">
            
            <!-- Link Regione -->
            <a class="d-lg-block navbar-brand" 
               target="_blank" 
               href="#" 
               aria-label="Vai al portale {Nome della Regione} - link esterno - apertura nuova scheda" 
               title="Vai al portale {Nome della Regione}">
              Nome della Regione
            </a>
            
            <!-- Right zone: Lingua + Area personale -->
            <div class="it-header-slim-right-zone" role="navigation">
              
              <!-- Language Switcher -->
              <div class="nav-item dropdown">
                <button type="button" 
                        class="nav-link dropdown-toggle" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false" 
                        aria-controls="languages" 
                        aria-haspopup="true">
                  <span class="visually-hidden">Lingua attiva:</span>
                  <span>ITA</span>
                  <svg class="icon">
                    <use href="../assets/bootstrap-italia/dist/svg/sprites.svg#it-expand"></use>
                  </svg>
                </button>
                
                <div class="dropdown-menu">
                  <div class="row">
                    <div class="col-12">
                      <div class="link-list-wrapper">
                        <ul class="link-list">
                          <li>
                            <a class="dropdown-item list-item" href="#">
                              <span>ITA <span class="visually-hidden">selezionata</span></span>
                            </a>
                          </li>
                          <li>
                            <a class="dropdown-item list-item" href="#">
                              <span>ENG</span>
                            </a>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <!-- Accedi Area Personale -->
              <a class="btn btn-primary btn-icon btn-full" 
                 href="../servizi/accesso-servizio.html" 
                 data-element="personal-area-login">
                <span class="rounded-icon" aria-hidden="true">
                  <svg class="icon icon-primary">
                    <use xlink:href="../assets/bootstrap-italia/dist/svg/sprites.svg#it-user"></use>
                  </svg>
                </span>
                <span class="d-none d-lg-block">Accedi all'area personale</span>
              </a>
              
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- === PARTE 2: Center Wrapper (Brand + Social + Search) === -->
  <div class="it-nav-wrapper">
    <div class="it-header-center-wrapper">
      <div class="container">
        <div class="row">
          <div class="col-12">
            <div class="it-header-center-content-wrapper">
              
              <!-- Brand -->
              <div class="it-brand-wrapper">
                <a href="homepage.html">
                  <svg width="82" height="82" class="icon" aria-hidden="true">
                    <image xlink:href="../assets/images/logo-comune.svg"/>
                  </svg>
                  <div class="it-brand-text">
                    <div class="it-brand-title">Il mio Comune</div>
                    <div class="it-brand-tagline d-none d-md-block">Un comune da vivere</div>
                  </div>
                </a>
              </div>
              
              <!-- Right zone: Social + Search -->
              <div class="it-right-zone">
                
                <!-- Social Icons -->
                <div class="it-socials d-none d-lg-flex">
                  <span>Seguici su</span>
                  <ul>
                    <li>
                      <a href="#" target="_blank">
                        <svg class="icon icon-sm icon-white align-top">
                          <use xlink:href="../assets/bootstrap-italia/dist/svg/sprites.svg#it-twitter"></use>
                        </svg>
                        <span class="visually-hidden">Twitter</span>
                      </a>
                    </li>
                    <!-- ... Facebook, YouTube, Telegram, WhatsApp, RSS ... -->
                  </ul>
                </div>
                
                <!-- Search -->
                <div class="it-search-wrapper">
                  <span class="d-none d-md-block">Cerca</span>
                  <button class="search-link rounded-icon" 
                          type="button" 
                          data-bs-toggle="modal" 
                          data-bs-target="#search-modal" 
                          aria-label="Cerca nel sito">
                    <svg class="icon">
                      <use href="../assets/bootstrap-italia/dist/svg/sprites.svg#it-search"></use>
                    </svg>
                  </button>
                </div>
                
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <!-- === PARTE 3: Navbar Navigation === -->
    <div class="it-header-navbar-wrapper" id="header-nav-wrapper">
      <div class="container">
        <div class="row">
          <div class="col-12">
            
            <div class="navbar navbar-expand-lg has-megamenu">
              
              <!-- Hamburger Toggle -->
              <button class="custom-navbar-toggler" 
                      type="button" 
                      aria-controls="nav4" 
                      aria-expanded="false" 
                      aria-label="Mostra/Nascondi la navigazione" 
                      data-bs-target="#nav4" 
                      data-bs-toggle="navbarcollapsible">
                <svg class="icon">
                  <use href="../assets/bootstrap-italia/dist/svg/sprites.svg#it-burger"></use>
                </svg>
              </button>
              
              <!-- Collapsible Navigation -->
              <div class="navbar-collapsable" id="nav4">
                <div class="overlay" style="display: none;"></div>
                
                <!-- Close Button (mobile) -->
                <div class="close-div">
                  <button class="btn close-menu" type="button">
                    <span class="visually-hidden">Nascondi la navigazione</span>
                    <svg class="icon">
                      <use href="../assets/bootstrap-italia/dist/svg/sprites.svg#it-close-big"></use>
                    </svg>
                  </button>
                </div>
                
                <div class="menu-wrapper">
                  
                  <!-- Logo Hamburger (mobile) -->
                  <a href="homepage.html" class="logo-hamburger">
                    <svg class="icon" aria-hidden="true">
                      <use href="../assets/bootstrap-italia/dist/svg/sprites.svg#it-pa"></use>
                    </svg>
                    <div class="it-brand-text">
                      <div class="it-brand-title">Nome del Comune</div>
                    </div>
                  </a>
                  
                  <!-- Main Navigation -->
                  <nav aria-label="Principale">
                    <ul class="navbar-nav" data-element="main-navigation">
                      <li class="nav-item">
                        <a class="nav-link" 
                           href="../sito/amministrazione.html" 
                           data-element="management">
                          <span>Amministrazione</span>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" 
                           href="../sito/novita.html" 
                           data-element="news">
                          <span>Novità</span>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" 
                           href="../sito/servizi.html" 
                           data-element="all-services">
                          <span>Servizi</span>
                        </a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" 
                           href="../sito/eventi.html" 
                           data-element="live">
                          <span>Vivere il Comune</span>
                        </a>
                      </li>
                    </ul>
                  </nav>
                  
                  <!-- Secondary Navigation (sub-items) -->
                  <nav aria-label="Secondaria">
                    <ul class="navbar-nav navbar-secondary">
                      <li class="nav-item">
                        <a class="nav-link" href="#">Iscrizioni</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#">Estate in città</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#">Polizia locale</a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link" href="#">Tutti gli argomenti</a>
                      </li>
                    </ul>
                  </nav>
                  
                </div>
              </div>
              
            </div>
            
          </div>
        </div>
      </div>
    </div>
    
  </div>
</header>
```

## 🎯 Funzionalità

### Parte 1: Slim Header
- **Link Regione:** Apre nuova scheda (esterno)
- **Language Switcher:** Dropdown ITA/ENG
- **Area Personale:** Bottone con icona user + testo (desktop)
- **Social Slim:** NO (solo in center wrapper)

### Parte 2: Center Wrapper
- **Brand:** Logo SVG + Nome comune + Tagline
- **Social:** 6 piattaforme (desktop only)
- **Search:** Bottone con icona → apre modal ricerca

### Parte 3: Navbar
- **Hamburger:** Toggle navigazione (mobile)
- **Main Nav:** 4 link (Amministrazione, Novità, Servizi, Vivere)
- **Secondary Nav:** 4 link rapidi (sotto-items)
- **Mobile:** Overlay full-screen con close button

## 📊 Dati JSON (Struttura Attesa)

```json
{
  "type": "header-navigation",
  "region": {
    "name": "Nome della Regione",
    "url": "https://regione.it",
    "external": true
  },
  "languages": [
    { "code": "ITA", "label": "Italiano", "active": true, "url": "/it" },
    { "code": "ENG", "label": "English", "active": false, "url": "/en" }
  ],
  "personalArea": {
    "label": "Accedi all'area personale",
    "url": "/servizi/accesso-servizio",
    "icon": "it-user"
  },
  "brand": {
    "logo": "/assets/images/logo-comune.svg",
    "name": "Il mio Comune",
    "tagline": "Un comune da vivere",
    "url": "/"
  },
  "social": [
    { "platform": "Twitter", "url": "https://twitter.com/comune", "icon": "it-twitter" },
    { "platform": "Facebook", "url": "https://facebook.com/comune", "icon": "it-facebook" },
    { "platform": "YouTube", "url": "https://youtube.com/comune", "icon": "it-youtube" },
    { "platform": "Telegram", "url": "https://t.me/comune", "icon": "it-telegram" },
    { "platform": "WhatsApp", "url": "https://wa.me/39000000000", "icon": "it-whatsapp" },
    { "platform": "RSS", "url": "/feed.xml", "icon": "it-rss" }
  ],
  "search": {
    "label": "Cerca nel sito",
    "modalId": "search-modal"
  },
  "mainNavigation": [
    { "label": "Amministrazione", "url": "/amministrazione", "dataElement": "management" },
    { "label": "Novità", "url": "/novita", "dataElement": "news" },
    { "label": "Servizi", "url": "/servizi", "dataElement": "all-services" },
    { "label": "Vivere il Comune", "url": "/eventi", "dataElement": "live" }
  ],
  "secondaryNavigation": [
    { "label": "Iscrizioni", "url": "/servizi/iscrizioni" },
    { "label": "Estate in città", "url": "/evento/estate-citta" },
    { "label": "Polizia locale", "url": "/servizi/polizia-locale" },
    { "label": "Tutti gli argomenti", "url": "/argomenti" }
  ]
}
```

## 🎨 Design System Mapping

| Bootstrap | Tailwind (@apply) | Note |
|-----------|------------------|------|
| `it-header-wrapper` | `.cmp-header` | Container principale |
| `it-header-slim-wrapper` | `.cmp-header__slim` | Barra superiore |
| `it-header-center-wrapper` | `.cmp-header__center` | Brand + social + search |
| `it-header-navbar-wrapper` | `.cmp-header__nav` | Navigation bar |
| `navbar-collapsable` | Alpine.js toggle | Sostituire Bootstrap JS |
| `custom-navbar-toggler` | `.cmp-header__toggle` | Hamburger button |
| `navbar-nav` | `.cmp-header__list` | Lista link |
| `nav-link` | `.cmp-header__link` | Singolo link |
| `navbar-secondary` | `.cmp-header__secondary` | Sub-navigation |

## 🔄 Alpine.js Implementation

```javascript
document.addEventListener('alpine:init', () => {
  Alpine.data('headerNavigation', () => ({
    mobileMenuOpen: false,
    languageDropdownOpen: false,
    searchModalOpen: false,
    
    toggleMobileMenu() {
      this.mobileMenuOpen = !this.mobileMenuOpen;
      document.body.style.overflow = this.mobileMenuOpen ? 'hidden' : '';
    },
    
    toggleLanguageDropdown() {
      this.languageDropdownOpen = !this.languageDropdownOpen;
    },
    
    openSearchModal() {
      this.searchModalOpen = true;
    }
  }));
});
```

## ♿ Accessibilità

- ✅ Skip links a `#content` e `#footer`
- ✅ Link regione con `aria-label` descrittivo
- ✅ Language switcher con `visually-hidden` label
- ✅ Social icons con `visually-hidden` platform names
- ✅ Search button con `aria-label`
- ✅ Nav con `aria-label` (Principale, Secondaria)
- ✅ Hamburger con `aria-label`, `aria-controls`, `aria-expanded`
- ⚠️ Dropdown lingua: aggiungere `role="menu"`
- ⚠️ Mobile overlay: `role="dialog"`, `aria-modal="true"`

## 📐 Responsive Behavior

| Breakpoint | Layout |
|------------|--------|
| Mobile (< lg) | Slim stack, Brand center, Hamburger menu |
| Tablet (lg) | Slim flex, Brand + social + search flex, Nav visible |
| Desktop (xl) | Full layout, Secondary nav visible |

## 🔗 Blocchi Correlati

- [Block 01: Hero/Contenuti in Evidenza](./block-01-hero-evidence.md)
- [Block 06: Footer](./block-06-footer.md)
- [Search Modal (da documentare)](./block-07-search-modal.md)

## 📝 Note Implementazione

- **Alpine.js:** NO Bootstrap JS, implementare toggle custom
- **Social Icons:** SVG sprites da copiare in tema
- **Language Switcher:** Configurable da backend (IT/EN default)
- **Search Modal:** Componente separato (da documentare)
- **Performance:** Header cacheable (statico, tranne auth state)
