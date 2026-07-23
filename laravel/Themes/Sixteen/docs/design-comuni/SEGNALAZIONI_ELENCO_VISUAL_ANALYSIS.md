# Analisi Visiva Segnalazioni Elenco - CSS/JS Fix Plan

## Panoramica

- **Reference**: https://italia.github.io/design-comuni-pagine-statiche/sito/ticket-list.html
- **Local**: http://127.0.0.1:8000/it/tests/ticket-list
- **Data**: 2026-04-03
- **HTML Match**: 95.7% ✅
- **Focus**: Differenze visive (CSS/JS)

## 📊 Screenshots Disponibili

| Screenshot | Path |
|-----------|------|
| Reference Full | [screenshots/ticket-list/reference-full.png](./screenshots/ticket-list/reference-full.png) |
| Local Full | [screenshots/ticket-list/local-full.png](./screenshots/ticket-list/local-full.png) |
| Reference Header | [screenshots/ticket-list/reference-header.png](./screenshots/ticket-list/reference-header.png) |
| Local Header | [screenshots/ticket-list/local-header.png](./screenshots/ticket-list/local-header.png) |
| Reference Breadcrumb | [screenshots/ticket-list/reference-breadcrumb.png](./screenshots/ticket-list/reference-breadcrumb.png) |
| Local Breadcrumb | [screenshots/ticket-list/local-breadcrumb.png](./screenshots/ticket-list/local-breadcrumb.png) |
| Reference Content | [screenshots/ticket-list/reference-content.png](./screenshots/ticket-list/reference-content.png) |
| Local Content | [screenshots/ticket-list/local-content.png](./screenshots/ticket-list/local-content.png) |

## 🔍 Differenze Visive Identificate

### 1. Sidebar Filters
**Reference**: Sidebar fissa a sinistra con checkbox categorie
**Local**: Da verificare rendering corretto

**CSS Necessario**:
```css
/* Sidebar filters styling */
.col-lg-3 {
    /* Positioning e spacing */
}

.category-list__title {
    /* Typography */
}

.checkbox-body {
    /* Checkbox styling */
}
```

### 2. Tabs Navigation (Mappa/Elenco)
**Reference**: Tabs orizzontali con stile Bootstrap Italia
**Local**: Da verificare rendering

**CSS Necessario**:
```css
/* Tabs styling */
.nav-tabs {
    /* Border, spacing */
}

.nav-tabs .nav-link {
    /* Active state, typography */
}

.nav-tabs .nav-link.active {
    /* Active styling */
}
```

### 3. Cards Segnalazione
**Reference**: Cards con sfondo grigio, ombra, struttura cmp-info-button-card
**Local**: Da verificare rendering

**CSS Necessario**:
```css
/* Card styling */
.cmp-card {
    /* Margin, spacing */
}

.card.has-bkg-grey {
    /* Background color */
}

.cmp-info-button-card {
    /* Card internal structure */
}
```

### 4. Accordion Dettagli
**Reference**: Accordion con icona che ruota, struttura single-line-info
**Local**: Da verificare interattività

**CSS Necessario**:
```css
/* Accordion styling */
.accordion-button {
    /* Button styling */
}

.accordion-button .icon-wrapper svg {
    /* Icon rotation transition */
    transition: transform 0.2s ease;
}

.accordion-button[aria-expanded="true"] .icon-wrapper svg {
    transform: rotate(180deg);
}

/* Single line info */
.single-line-info {
    /* Border, spacing */
}

.text-paragraph-small {
    /* Label typography */
}

.data-text {
    /* Value typography */
}
```

### 5. Rating Section
**Reference**: Sezione con sfondo primario (blu), stelle rating, steps
**Local**: Da verificare rendering

**CSS Necessario**:
```css
/* Rating section */
.bg-primary {
    background-color: #0066CC;
}

.cmp-rating {
    /* Padding, centering */
}

.rating input[type="radio"] {
    /* Hide default radio */
}

.rating .full {
    /* Star styling */
}

.rating .full:hover,
.rating .full.active {
    /* Active/hover state */
}
```

### 6. Modal Disservizio
**Reference**: Modal con struttura completa
**Local**: Da verificare rendering

**CSS Necessario**:
```css
/* Modal styling */
.modal-content {
    /* Border, shadow */
}

.modal-header {
    /* Header styling */
}

.modal-body {
    /* Body spacing */
}

.modal-footer {
    /* Footer styling */
}
```

## 🎯 Piano di Lavoro

### Fase 1: CSS Base (Priority Alta)
- [ ] Sidebar filters styling
- [ ] Tabs navigation styling
- [ ] Cards styling completo

### Fase 2: CSS Dettagli (Priority Media)
- [ ] Accordion interattività (icon rotation)
- [ ] Single-line-info styling
- [ ] Rating section styling

### Fase 3: CSS Modals (Priority Media)
- [ ] Modal disservizio styling
- [ ] Modal categories styling

### Fase 4: JS Alpine.js (Priority Alta)
- [ ] Tabs toggle functionality
- [ ] Accordion toggle
- [ ] Rating stars interaction
- [ ] Modal open/close

## 📝 File da Modificare

1. `laravel/Themes/Sixteen/resources/css/components/design-comuni.css`
2. `laravel/Themes/Sixteen/resources/js/app.js` (Alpine.js components)

## 📚 Link Correlati

- **Screenshot**: [screenshots/ticket-list/](./screenshots/ticket-list/)
- **Script**: [bashscripts/design-comuni/analyze-ticket-list.js](../../../bashscripts/design-comuni/analyze-ticket-list.js)
- **Master Index**: [docs/design-comuni/MASTER_INDEX.md](../../../docs/design-comuni/MASTER_INDEX.md)
- **Progress Report**: [PROGRESS_REPORT.md](./PROGRESS_REPORT.md)

---

**Data**: 2026-04-03  
**Stato HTML**: ✅ 95.7%  
**Focus**: CSS/JS per allineamento visivo  
**Target**: 90%+ match visivo
