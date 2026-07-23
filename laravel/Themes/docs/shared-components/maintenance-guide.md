# Guida alla Manutenzione

## 🎯 Scopo del Documento
Questa guida fornisce istruzioni dettagliate per la manutenzione, modifiche e estensioni del sistema Bootstrap Italia convertito a CSS nativo.

## 📁 Struttura del Progetto

```
/var/www/_bases/five/
├── index.html                 # Pagina principale
├── src/
│   └── style.css             # CSS principale (1800+ linee)
├── assets/
│   ├── bootstrap-italia/
│   │   └── dist/svg/sprites.svg
│   └── images/
│       ├── logo-comune.svg
│       ├── logo-eu-inverted.svg
│       └── [immagini varie]
└── docs/
    ├── README.md
    ├── conversion-log.md
    ├── css-architecture.md
    ├── ui-libraries-analysis.md
    ├── improvements-todo.md
    └── maintenance-guide.md
```

## 🔧 Modifiche Comuni

### 1. Cambio Colori del Tema

**File**: `/src/style.css` (linee 18-30)

```css
:root {
  --bs-primary: #007a52;        /* Verde principale */
  --bs-primary-dark: #00614a;   /* Verde scuro header */
  --bs-secondary: #5d7083;      /* Grigio secondario */
  --bs-success: #008055;        /* Verde successo */
  --bs-blue: #006cc6;          /* Blu originale */
  --bs-dark: #17334f;          /* Scuro testi */
}
```

**Per cambiare tema**:
1. Modificare le variabili CSS in `:root`
2. Testare su tutti i componenti
3. Verificare contrasto accessibilità

### 2. Modifica Layout Header

**Sezioni coinvolte**:
- Header Slim: linee 74-115 (CSS)
- Header Center: linee 326-337 (CSS)
- Header Navbar: linee 320-400+ (CSS)

**Esempio - Cambiare altezza header**:
```css
.it-header-center-wrapper {
  padding: 1.5rem 0; /* era 1rem 0 */
}
```

### 3. Aggiunta Nuovi Componenti

**Processo raccomandato**:
1. Studia componente originale Bootstrap Italia
2. Crea sezione dedicata nel CSS (usa commenti per separare)
3. Implementa CSS nativo (no @apply)
4. Testa responsive breakpoints
5. Aggiungi documentazione

**Template sezione CSS**:
```css
/* [Nome Componente] - Bootstrap Italia style */
.nuovo-componente {
  /* Proprietà base */
}

.nuovo-componente .elemento {
  /* Elementi interni */
}

/* Responsive */
@media (min-width: 992px) {
  .nuovo-componente {
    /* Desktop styling */
  }
}
```

### 4. Modifica Navigazione

**File interessati**:
- HTML: linee 145-180 (navbar structure)
- CSS: linee 390-480 (desktop/mobile logic)

**Per aggiungere link menu**:
1. Trova `<ul class="navbar-nav" data-element="main-navigation">`
2. Aggiungi nuovo `<li class="nav-item">`
3. Mantieni structure esistente
4. Testa su desktop e mobile

### 5. Personalizzazione Footer

**Sezioni CSS**:
- Footer main: linee 1231-1300
- Logo layout: linee 1247-1252
- Social links: linee 1290-1300

**Cambio loghi**:
1. Sostituisci file in `/assets/images/`
2. Mantieni stessi nomi file O aggiorna src in HTML
3. Verifica dimensioni responsive

## ⚙️ Workflow di Sviluppo

### Setup Sviluppo Locale
```bash
# Naviga alla directory
cd /var/www/_bases/five/

# Apri file in editor
# Modifica /src/style.css per CSS
# Modifica /index.html per struttura

# Test locale
# Apri index.html in browser o usa live server
```

### Testing Checklist
Prima di deployare modifiche:

**Visual Testing**:
- [ ] Desktop (1920x1080, 1366x768)
- [ ] Tablet (768x1024)
- [ ] Mobile (375x667, 414x896)

**Functional Testing**:
- [ ] Menu navigation (desktop/mobile)
- [ ] Link functionality
- [ ] Rating stars interaction
- [ ] Modal windows (se presenti)
- [ ] Form behavior
- [ ] Search functionality

**Cross-browser Testing**:
- [ ] Chrome (desktop + mobile)
- [ ] Firefox
- [ ] Safari (desktop + mobile)
- [ ] Edge

### Git Workflow (se applicabile)
```bash
# Crea branch per feature
git checkout -b feature/nome-modifica

# Commit incrementali
git add src/style.css
git commit -m "Update: descrizione modifica specifica"

# Test completo prima di merge
# Merge al main branch solo dopo testing
```

## 🚨 Problemi Comuni e Soluzioni

### 1. CSS non si applica
**Cause possibili**:
- Specificity CSS insufficiente
- Cache browser
- Errore sintattico CSS

**Debug**:
```css
/* Aumenta specificity se necessario */
.component.specific-class {
  property: value !important; /* uso temporaneo */
}
```

**Soluzione permanente**: Rivedi selettori per specificity naturale

### 2. Layout si rompe su mobile
**Debug process**:
1. Usa DevTools mobile view
2. Controlla media queries
3. Verifica flexbox/grid properties
4. Test su device reale

### 3. Icone SVG non visibili
**Cause**:
- Path sprites.svg errato
- Symbol ID errato nel `<use>`
- File sprites.svg mancante

**Fix**:
```html
<!-- Verifica path corretto -->
<use href="/assets/bootstrap-italia/dist/svg/sprites.svg#it-icon-name"></use>
```

### 4. JavaScript non funziona
**Con Alpine.js**:
- Verifica `x-data` inizializzazione
- Controlla console per errori
- Assicurati Alpine.js sia caricato

```html
<!-- Debug Alpine.js -->
<div x-data="{ test: true }" x-show="test">
  Alpine.js is working!
</div>
```

## 📊 Performance Monitoring

### Metriche da Monitorare
- **First Contentful Paint**: <1.5s
- **Largest Contentful Paint**: <2.5s
- **Cumulative Layout Shift**: <0.1
- **Total Blocking Time**: <200ms

### Tools Raccomandati
- Chrome Lighthouse
- WebPageTest
- GTmetrix
- Chrome DevTools Performance tab

### Ottimizzazioni Comuni
```css
/* Preload critical resources */
<link rel="preload" href="/src/style.css" as="style">
<link rel="preload" href="/assets/bootstrap-italia/dist/svg/sprites.svg" as="image">

/* Optimize images */
/* Usa formato WebP quando possibile */
/* Comprimi SVG sprites */

/* CSS performance */
/* Evita selettori complessi */
/* Usa transform invece di position per animazioni */
```

## 🔐 Security Considerations

### CSP Headers Raccomandati
```html
<meta http-equiv="Content-Security-Policy"
      content="default-src 'self';
               style-src 'self' 'unsafe-inline' fonts.googleapis.com;
               font-src fonts.gstatic.com;
               script-src 'self' cdn.jsdelivr.net;">
```

### Input Sanitization
- Sempre sanitizzare input utente
- Validare form data lato server
- Usare HTTPS in produzione

## 📚 Risorse Utili

### Documentazione di Riferimento
- [Bootstrap Italia Docs](https://italia.github.io/bootstrap-italia/)
- [Tailwind CSS Docs](https://tailwindcss.com/docs)
- [Alpine.js Docs](https://alpinejs.dev/)
- [Filament CSS Guidelines](https://filamentphp.com/docs/3.x/support/style-customization)

### Tool Raccomandati
- **CSS**: VS Code con estensioni CSS
- **Browser Testing**: BrowserStack/CrossBrowserTesting
- **Performance**: Chrome DevTools
- **Accessibility**: axe-core, WAVE

### Community e Supporto
- Bootstrap Italia GitHub Issues
- Tailwind CSS Discord
- Stack Overflow (tag specific)

## 📞 Escalation Process

**Per problemi complessi**:
1. Consulta questa documentazione
2. Verifica nei log di conversione (`conversion-log.md`)
3. Controlla issues noti (`improvements-todo.md`)
4. Se problema persiste, documenta:
   - Browser e versione
   - Steps per riprodurre
   - Screenshot/video
   - Console errors
5. Cerca soluzioni in community Bootstrap Italia/Tailwind