
## Panoramica

Il tema Sixteen è progettato per garantire **accessibilità completa** seguendo gli standard **WCAG 2.1 AA** e le **Linee Guida di Design per i Servizi Digitali della Pubblica Amministrazione**.

## Standard di Conformità

### WCAG 2.1 AA
- **Livello A**: Requisiti base di accessibilità
- **Livello AA**: Requisiti avanzati (obiettivo del tema Sixteen)
- **Livello AAA**: Requisiti massimi (opzionale)

### Principi Fondamentali
1. **Percepibile**: Le informazioni devono essere presentate in modo che gli utenti possano percepirle
2. **Operabile**: Gli utenti devono poter navigare e interagire con l'interfaccia
3. **Comprensibile**: Le informazioni e l'operazione dell'interfaccia devono essere comprensibili
4. **Robusto**: Il contenuto deve essere interpretabile da una vasta gamma di tecnologie assistive

## Contrasto e Colori

### Requisiti di Contrasto
```css
/* Testo normale (≤ 18px) */
--text-normal: #1f2937; /* Contrasto ≥ 4.5:1 */
--text-normal-bg: #ffffff;

/* Testo grande (> 18px) */
--text-large: #374151; /* Contrasto ≥ 3:1 */
--text-large-bg: #f9fafb;
```

### Palette Colori Accessibile
```css
:root {
    /* Colori primari con contrasto garantito */
    --sixteen-primary: #0066cc;
    --sixteen-primary-dark: #004499;
    --sixteen-primary-light: #3388dd;
    
    /* Colori semantici */
    --sixteen-success: #28a745;
    --sixteen-warning: #ffc107;
    --sixteen-danger: #dc3545;
    --sixteen-info: #17a2b8;
}
```

## Navigazione da Tastiera

### Focus Management
```css
/* Stili per focus visibile */
.focus-visible {
    outline: 2px solid var(--sixteen-primary);
    outline-offset: 2px;
    border-radius: 4px;
}
```

### Skip Links
```html
<!-- Link per saltare la navigazione -->
<a href="#main-content" class="sr-only focus:not-sr-only">
    Vai al contenuto principale
</a>
```

## Screen Reader Support

### ARIA Labels
```html
<!-- Etichette ARIA per elementi interattivi -->
<button aria-label="Chiudi finestra" aria-describedby="close-help">
    <x-pub_theme::icon.x />
</button>
```

### Landmark Roles
```html
<!-- Ruoli di landmark per struttura semantica -->
<header role="banner">
    <nav role="navigation" aria-label="Navigazione principale">
        <!-- Menu -->
    </nav>
</header>

<main role="main" id="main-content">
    <!-- Contenuto principale -->
</main>
```

## Form e Input

### Label Associate
```html
<!-- Label esplicite -->
<label for="email">Indirizzo Email</label>
<input type="email" id="email" name="email" aria-describedby="email-help">

<!-- Help text -->
<div id="email-help" class="help-text">
    L'email verrà utilizzata per accedere al sistema
</div>
```

### Required Fields
```html
<!-- Campi obbligatori -->
<label for="name">
    Nome Completo
    <span aria-label="obbligatorio">*</span>
</label>
<input 
    type="text" 
    id="name" 
    name="name" 
    required 
    aria-required="true"
>
```

## Immagini e Media

### Alt Text
```html
<!-- Testo alternativo descrittivo -->
<img src="logo.png" alt="Logo della Pubblica Amministrazione">

<!-- Immagini decorative -->
<img src="decoration.png" alt="" role="presentation">
```

## Componenti Interattivi

### Modal e Dialog
```html
<!-- Modal accessibile -->
<div 
    role="dialog" 
    aria-modal="true" 
    aria-labelledby="modal-title"
    aria-describedby="modal-description"
>
    <h2 id="modal-title">Conferma Eliminazione</h2>
    <p id="modal-description">
        Sei sicuro di voler eliminare questo elemento?
    </p>
</div>
```

## Testing di Accessibilità

### Test Automatici
```javascript
// Test con axe-core
import axe from 'axe-core';

axe.run((err, results) => {
    if (results.violations.length > 0) {
        console.warn('Violazioni di accessibilità trovate:', results.violations);
    }
});
```

### Strumenti di Testing
- **axe DevTools**: Estensione browser per test automatici
- **WAVE**: Web Accessibility Evaluation Tool
- **Lighthouse**: Audit di accessibilità integrato
- **Color Contrast Analyzer**: Test contrasto colori

## Best Practices

### 1. Struttura Semantica
```html
<!-- Utilizzare sempre elementi semantici -->
<header>
    <nav>
        <ul>
            <li><a href="/">Home</a></li>
            <li><a href="/about">Chi Siamo</a></li>
        </ul>
    </nav>
</header>
```

### 2. Testo Alternativo
```html
<!-- Sempre fornire alt text appropriato -->
<img src="icon.png" alt="Icona informazione">

<!-- Per immagini decorative -->
<img src="decoration.png" alt="" role="presentation">
```

## Compliance Legale

### Normativa Italiana
- **Legge Stanca**: Legge 4/2004 per l'accessibilità
- **Linee Guida AGID**: Requisiti tecnici per siti PA
- **WCAG 2.1 AA**: Standard internazionale

### Requisiti PA
- **Contrasto minimo**: 4.5:1 per testo normale
- **Navigazione da tastiera**: Completa
- **Screen reader**: Supporto completo
- **Testo alternativo**: Obbligatorio per immagini
- **Struttura semantica**: Corretta

---

**Versione**: 1.0.0  
**Ultimo aggiornamento**: Gennaio 2025  
**Standard**: WCAG 2.1 AA, Legge Stanca, Linee Guida AGID 
# Accessibilità del Tema Sixteen
 
