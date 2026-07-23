# 🏛️ DESIGN COMUNI ITALIANI - INTEGRATION GUIDE

**Data**: 2025-10-02  
**Source**: https://github.com/italia/design-comuni-pagine-statiche  
**Live Demo**: https://italia.github.io/design-comuni-pagine-statiche  
**Purpose**: Integrare il design system ufficiale dei comuni italiani in FixCity  

---

## 📊 ANALISI DESIGN COMUNI

### Scopo
Template HTML ufficiali per i siti web e servizi digitali dei Comuni Italiani, conformi alle Linee guida di design per i servizi web della PA.

### Stack Tecnologico
- **Bootstrap Italia** - Framework UI ufficiale
- **Handlebars** - Template engine
- **SCSS** - Styling
- **Webpack** - Build system
- **Node >= 18** - Runtime

### Caratteristiche Principali
✅ **AGID Compliant** - 100% conforme linee guida  
✅ **Accessibilità** - WCAG 2.1 AA validato  
✅ **Bootstrap Italia** - UI Kit ufficiale  
✅ **Responsive** - Mobile-first design  
✅ **Templates Pronti** - HTML/CSS/JS validati  

---

## 🎯 TEMPLATES ANALIZZATI

### Segnalazione Disservizio (PERFETTO PER FIXCITY!)

#### 1. Scheda Servizio
**File**: `segnalazione-dettaglio.html`
- Presentazione servizio segnalazione
- Informazioni necessarie
- Accesso alla funzionalità
- Lista tutte le segnalazioni

#### 2. Step 1 - Privacy
**File**: `segnalazione-01-privacy.html`
- Informativa privacy
- Consenso trattamento dati
- GDPR compliant

#### 3. Step 2 - Dati Segnalazione
**File**: `segnalazione-02-dati.html`
- Inserimento luogo
- Dettagli disservizio
- Upload allegati
- Dati autore segnalazione

#### 4. Step 3 - Riepilogo
**File**: `segnalazione-03-riepilogo.html`
- Riepilogo informazioni
- Conferma o modifica
- Validazione finale

#### 5. Step 4 - Conferma
**File**: `segnalazione-04-conferma.html`
- Conferma invio
- Numero protocollo
- Prossimi passi

#### 6. Area Personale
**File**: `segnalazione-area-personale.html`
- Dashboard utente
- Ultime attività
- Segnalazioni effettuate

#### 7. Elenco Segnalazioni
**File**: `segnalazioni-elenco.html`
- Lista completa segnalazioni
- Mappa interattiva
- Filtri per tipologia
- Ricerca avanzata

---

## 🚀 APPLICABILITÀ A FIXCITY

### Similitudini Perfette
| Design Comuni | FixCity | Match |
|---------------|---------|-------|
| Segnalazione Disservizio | Ticket System | ✅ 100% |
| Step Privacy | GDPR Consent | ✅ 100% |
| Form Dati | Ticket Creation | ✅ 100% |
| Riepilogo | Ticket Preview | ✅ 100% |
| Conferma | Ticket Submitted | ✅ 100% |
| Area Personale | User Dashboard | ✅ 100% |
| Elenco + Mappa | Tickets List + Map | ✅ 100% |

### Differenze
- Design Comuni: Template statici
- FixCity: Applicazione dinamica Laravel
- Design Comuni: HTML puro
- FixCity: Livewire + Blade

---

## 📦 COMPONENTI DA INTEGRARE

### 1. Bootstrap Italia (CRITICAL)

**Cosa**: Framework UI ufficiale AGID

**Installazione**:
```bash
npm install bootstrap-italia
```

**Benefits**:
- ✅ AGID 100% compliant
- ✅ Accessibilità garantita
- ✅ Componenti validati
- ✅ Design system ufficiale
- ✅ Manutenzione attiva

### 2. Layout Templates

**Header Ufficiale**:
```html
<header class="it-header-wrapper">
  <div class="it-header-slim-wrapper">
    <div class="container">
      <div class="row">
        <div class="col-12">
          <div class="it-header-slim-wrapper-content">
            <!-- Slim header content -->
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="it-nav-wrapper">
    <div class="it-header-center-wrapper">
      <!-- Main header -->
    </div>
  </div>
</header>
```

**Footer Ufficiale**:
```html
<footer class="it-footer">
  <div class="it-footer-main">
    <div class="container">
      <!-- Footer content -->
    </div>
  </div>
  <div class="it-footer-small-prints">
    <div class="container">
      <!-- Legal links -->
    </div>
  </div>
</footer>
```

### 3. Form Wizard Pattern

**Step Indicator**:
```html
<div class="steppers">
  <div class="steppers-header">
    <ul>
      <li class="confirmed">Step 1 <span class="sr-only">Confermato</span></li>
      <li class="active">Step 2 <span class="sr-only">Attivo</span></li>
      <li>Step 3</li>
      <li>Step 4</li>
    </ul>
  </div>
</div>
```

### 4. Card Components

**Ticket Card**:
```html
<div class="card-wrapper card-space">
  <div class="card card-bg card-big">
    <div class="card-body">
      <div class="category-top">
        <span class="category">Categoria</span>
        <span class="data">01/01/2025</span>
      </div>
      <h5 class="card-title big-heading">Titolo Segnalazione</h5>
      <p class="card-text">Descrizione breve...</p>
      <span class="card-signature">Autore</span>
      <a class="read-more" href="#">
        <span class="text">Leggi di più</span>
        <svg class="icon"><use href="/bootstrap-italia/svg/sprites.svg#it-arrow-right"></use></svg>
      </a>
    </div>
  </div>
</div>
```

### 5. Status Badges

**Badge Ufficiali**:
```html
<!-- Status badges -->
<span class="badge badge-danger">Aperto</span>
<span class="badge badge-warning">In Corso</span>
<span class="badge badge-success">Risolto</span>
<span class="badge badge-secondary">Chiuso</span>

<!-- Priority badges -->
<span class="badge badge-danger">Urgente</span>
<span class="badge badge-warning">Alta</span>
<span class="badge badge-info">Media</span>
<span class="badge badge-secondary">Bassa</span>
```

### 6. Timeline Component

**Activity Timeline**:
```html
<div class="timeline-wrapper">
  <div class="timeline">
    <div class="timeline-element">
      <span class="it-now-label d-none d-lg-flex">Ora</span>
      <div class="it-pin-wrapper it-evidence">
        <div class="pin-icon">
          <svg class="icon"><use href="/bootstrap-italia/svg/sprites.svg#it-check"></use></svg>
        </div>
      </div>
      <div class="card-wrapper">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title">Evento</h5>
            <p class="card-text">Descrizione evento</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
```

### 7. Map Integration

**Mappa Segnalazioni**:
```html
<div class="map-wrapper">
  <div id="map" class="map-container"></div>
  <div class="map-legend">
    <ul class="legend-list">
      <li><span class="legend-marker marker-red"></span> Aperto</li>
      <li><span class="legend-marker marker-orange"></span> In Corso</li>
      <li><span class="legend-marker marker-green"></span> Risolto</li>
    </ul>
  </div>
</div>
```

---

## 🎨 BLADE COMPONENTS DA CREARE

### 1. Header Component
```php
// Themes/Sixteen/Resources/views/components/header.blade.php
<header class="it-header-wrapper" {{ $attributes }}>
    <x-sixteen::header.slim />
    <x-sixteen::header.center />
    <x-sixteen::header.nav />
</header>
```

### 2. Footer Component
```php
// Themes/Sixteen/Resources/views/components/footer.blade.php
<footer class="it-footer">
    <x-sixteen::footer.main />
    <x-sixteen::footer.small-prints />
</footer>
```

### 3. Ticket Card Component
```php
// Fixcity/Resources/views/components/ticket-card.blade.php
<div class="card-wrapper card-space">
    <div class="card card-bg">
        <div class="card-body">
            <div class="category-top">
                <span class="category">{{ $ticket->category->name }}</span>
                <span class="data">{{ $ticket->created_at->format('d/m/Y') }}</span>
            </div>
            <h5 class="card-title">{{ $ticket->name }}</h5>
            <p class="card-text">{{ Str::limit($ticket->content, 150) }}</p>
            <x-sixteen::badge :status="$ticket->status" />
            <a class="read-more" href="{{ route('tickets.show', $ticket) }}">
                <span class="text">Leggi di più</span>
                <svg class="icon"><use href="/bootstrap-italia/svg/sprites.svg#it-arrow-right"></use></svg>
            </a>
        </div>
    </div>
</div>
```

### 4. Form Wizard Component
```php
// Fixcity/Resources/views/components/wizard.blade.php
<div class="steppers">
    <div class="steppers-header">
        <ul>
            @foreach($steps as $index => $step)
                <li class="{{ $currentStep > $index ? 'confirmed' : ($currentStep === $index ? 'active' : '') }}">
                    {{ $step }}
                    @if($currentStep > $index)
                        <span class="sr-only">Confermato</span>
                    @elseif($currentStep === $index)
                        <span class="sr-only">Attivo</span>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
    <div class="steppers-content">
        {{ $slot }}
    </div>
</div>
```

---

## 🔧 IMPLEMENTATION PLAN

### Phase 1: Bootstrap Italia Setup (Week 1)
1. [ ] Install Bootstrap Italia npm package
2. [ ] Configure Webpack/Vite
3. [ ] Import SCSS variables
4. [ ] Setup icon sprites
5. [ ] Test basic components

### Phase 2: Layout Components (Week 2)
6. [ ] Create Header component
7. [ ] Create Footer component
8. [ ] Create Navigation component
9. [ ] Create Breadcrumbs component
10. [ ] Test responsive layout

### Phase 3: Ticket Components (Week 3)
11. [ ] Create Ticket Card component
12. [ ] Create Ticket List component
13. [ ] Create Ticket Detail component
14. [ ] Create Status Badges component
15. [ ] Create Priority Badges component

### Phase 4: Form Wizard (Week 4)
16. [ ] Create Wizard component
17. [ ] Implement Step 1 - Privacy
18. [ ] Implement Step 2 - Data
19. [ ] Implement Step 3 - Review
20. [ ] Implement Step 4 - Confirmation

### Phase 5: Advanced Components (Week 5)
21. [ ] Create Timeline component
22. [ ] Create Map Legend component
23. [ ] Create Filter Sidebar
24. [ ] Create Search component
25. [ ] Create Pagination component

### Phase 6: Polish & Testing (Week 6)
26. [ ] AGID compliance audit
27. [ ] Accessibility testing
28. [ ] Responsive testing
29. [ ] Browser compatibility
30. [ ] Performance optimization

---

## 📋 PACKAGE.JSON ADDITIONS

```json
{
  "dependencies": {
    "bootstrap-italia": "^2.8.0",
    "owl.carousel": "^2.3.4",
    "accessible-autocomplete": "^2.0.4"
  },
  "devDependencies": {
    "@babel/core": "^7.23.0",
    "@babel/preset-env": "^7.23.0",
    "sass": "^1.69.0",
    "webpack": "^5.89.0"
  }
}
```

---

## 🎯 BENEFITS FOR FIXCITY

### Compliance
✅ **AGID 100%** - Conformità garantita  
✅ **WCAG 2.1 AA** - Accessibilità validata  
✅ **Design System** - Ufficiale PA  
✅ **Best Practices** - Linee guida seguite  

### User Experience
✅ **Familiare** - Design riconoscibile  
✅ **Accessibile** - Per tutti gli utenti  
✅ **Responsive** - Mobile-first  
✅ **Intuitivo** - Pattern consolidati  

### Technical
✅ **Manutenuto** - Aggiornamenti regolari  
✅ **Documentato** - Guide complete  
✅ **Testato** - Validato su tutti i browser  
✅ **Community** - Supporto attivo  

### Business
✅ **Credibilità** - Design ufficiale PA  
✅ **Conformità** - Requisiti legali  
✅ **Adozione** - Facilita deployment comuni  
✅ **Certificazione** - AGID ready  

---

## 📚 RESOURCES

### Official Documentation
- [Bootstrap Italia Docs](https://italia.github.io/bootstrap-italia/)
- [Design System Docs](https://docs.italia.it/italia/designers-italia/design-linee-guida-docs/)
- [UI Kit](https://designers.italia.it/kit/ui-kit/)
- [Design Comuni GitHub](https://github.com/italia/design-comuni-pagine-statiche)

### Examples
- [Live Templates](https://italia.github.io/design-comuni-pagine-statiche)
- [Comuni Reference](https://designers.italia.it/modello/comuni/)

---

## 🎨 DESIGN TOKENS

### Colors
```scss
// Primary colors
$primary: #0066CC;
$secondary: #5C6F82;
$success: #008758;
$danger: #D9364F;
$warning: #A66300;
$info: #0073E6;

// Neutral colors
$white: #FFFFFF;
$black: #17324D;
$gray-100: #F5F6F7;
$gray-200: #E3E7EB;
$gray-300: #C5CBD4;
```

### Typography
```scss
// Font family
$font-family-sans-serif: 'Titillium Web', 'HelveticaNeue', 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
$font-family-monospace: 'Roboto Mono', monospace;

// Font sizes
$font-size-base: 1rem; // 16px
$h1-font-size: 2.5rem; // 40px
$h2-font-size: 2rem; // 32px
$h3-font-size: 1.5rem; // 24px
```

### Spacing
```scss
// Spacing scale
$spacer: 1rem;
$spacers: (
  0: 0,
  1: ($spacer * 0.25), // 4px
  2: ($spacer * 0.5),  // 8px
  3: $spacer,          // 16px
  4: ($spacer * 1.5),  // 24px
  5: ($spacer * 3),    // 48px
);
```

---

## 🔍 CHECKLIST AGID

### Layout
- [ ] Header slim con ente appartenenza
- [ ] Logo comune posizionato correttamente
- [ ] Navigation accessibile
- [ ] Footer con link obbligatori
- [ ] Breadcrumbs su tutte le pagine

### Components
- [ ] Form con label visibili
- [ ] Errori di validazione chiari
- [ ] Focus indicators visibili
- [ ] Skip links implementati
- [ ] ARIA labels corretti

### Content
- [ ] Linguaggio semplice
- [ ] Informazioni chiare
- [ ] Call to action evidenti
- [ ] Date in formato italiano
- [ ] Numeri formattati

### Accessibility
- [ ] Contrasto colori 4.5:1
- [ ] Navigazione da tastiera
- [ ] Screen reader friendly
- [ ] Alt text su immagini
- [ ] Heading hierarchy corretta

---

**Status**: 📋 READY TO IMPLEMENT  
**Priority**: CRITICAL  
**Estimated Time**: 6 weeks  
**Impact**: AGID 100% COMPLIANCE  

*"Integrando il design system ufficiale dei comuni italiani, FixCity sarà 100% AGID compliant e pronto per l'adozione da parte di qualsiasi comune italiano!"*
