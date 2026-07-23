# Analisi Conformità AGID - Pagina di Login

## 🚨 **PROBLEMA IDENTIFICATO**

La pagina di login attuale **NON è AGID-compliant** per i seguenti motivi critici:

### 1. **Layout Wrapper Sbagliato**
- **PROBLEMA**: Usa `<x-layouts.guest>` che è un layout centrato minimale
- **CONSEGUENZA**: Sovrascrive tutto il contenuto AGID della pagina login
- **LAYOUT ATTUALE**: Semplice div centrato con logo e form in una card
- **LAYOUT RICHIESTO**: Struttura AGID completa con header, breadcrumb, main, footer

### 2. **Conflitto Strutturale**
- **PROBLEMA**: La pagina `login.blade.php` ha contenuto AGID completo (header, nav, main, footer)
- **CONFLITTO**: Il layout `guest.blade.php` lo "avvolge" in una struttura non-AGID
- **RISULTATO**: Doppia struttura HTML malformata e non conforme

### 3. **Violazioni AGID Specifiche**

#### A. **Struttura HTML Non Conforme**
```html
<!-- ATTUALE (SBAGLIATO) -->
<html>
<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div>
            <a href="/"><x-application-logo /></a>
        </div>
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md sm:rounded-lg">
            {{ $slot }} <!-- Qui va tutto il contenuto AGID -->
        </div>
    </div>
</body>
</html>
```

#### B. **Mancanza di Elementi AGID Obbligatori**
- ❌ **Header istituzionale** nascosto/sovrapposto
- ❌ **Breadcrumb navigation** nascosto/sovrapposto  
- ❌ **Footer istituzionale** nascosto/sovrapposto
- ❌ **Skip links** inefficaci per la struttura centrata
- ❌ **Struttura semantica** compromessa

#### C. **Problemi di Accessibilità**
- ❌ **Focus management** compromesso dalla struttura centrata
- ❌ **Screen reader navigation** confusa per doppia struttura
- ❌ **Keyboard navigation** non ottimale
- ❌ **ARIA landmarks** non correttamente posizionati

#### D. **Problemi di Responsive Design**
- ❌ **Mobile layout** non conforme alle linee guida AGID
- ❌ **Breakpoints** non standard per PA
- ❌ **Typography scale** non conforme

## 🎯 **SOLUZIONE PROPOSTA**

### **Opzione 1: Layout AGID Dedicato (RACCOMANDATO)**

Creare un layout specifico `auth-agid.blade.php` che rispetti completamente le linee guida AGID:

```html
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name') }}</title>
    
    <!-- AGID Required Meta Tags -->
    <meta name="description" content="Accesso ai servizi digitali - {{ config('app.name') }}">
    <meta name="robots" content="noindex, nofollow">
    
    <!-- AGID Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Sixteen')
</head>
<body class="font-titillium bg-gray-50">
    <!-- Skip Links AGID -->
    <div class="sr-only focus:not-sr-only">
        <a href="#main-content" class="skip-link">Salta al contenuto principale</a>
        <a href="#login-form" class="skip-link">Vai al modulo di accesso</a>
    </div>

    <!-- AGID Header -->
    <header role="banner" class="agid-header">
        <!-- Header istituzionale completo -->
    </header>

    <!-- AGID Breadcrumb -->
    <nav aria-label="Percorso di navigazione" class="agid-breadcrumb">
        <!-- Breadcrumb AGID compliant -->
    </nav>

    <!-- Main Content -->
    <main id="main-content" role="main" class="agid-main">
        {{ $slot }}
    </main>

    <!-- AGID Footer -->
    <footer role="contentinfo" class="agid-footer">
        <!-- Footer istituzionale completo -->
    </footer>

    <!-- AGID Scripts -->
    <script>
        // Focus management e accessibility scripts
    </script>
</body>
</html>
```

### **Opzione 2: Modifica Layout Guest (SCONSIGLIATO)**

Modificare il layout `guest.blade.php` per essere AGID-compliant, ma questo romperebbe altre pagine che lo usano.

## 🔧 **IMPLEMENTAZIONE DETTAGLIATA**

### **Fase 1: Creazione Layout AGID**

1. **Creare** `auth-agid.blade.php` con struttura AGID completa
2. **Implementare** CSS AGID-compliant con Titillium Web
3. **Aggiungere** JavaScript per accessibility e focus management
4. **Testare** con screen reader e keyboard navigation

### **Fase 2: Refactoring Pagina Login**

1. **Cambiare** da `<x-layouts.guest>` a `<x-layouts.auth-agid>`
2. **Semplificare** il contenuto della pagina (rimuovere header/footer duplicati)
3. **Mantenere** solo il form e contenuto principale
4. **Ottimizzare** per la nuova struttura

### **Fase 3: CSS AGID-Compliant**

```css
/* AGID Typography */
.font-titillium {
    font-family: 'Titillium Web', sans-serif;
}

/* AGID Colors */
.agid-primary { color: #0066CC; }
.agid-secondary { color: #5C6F82; }
.agid-success { color: #008758; }
.agid-warning { color: #A66300; }
.agid-danger { color: #D73527; }

/* AGID Components */
.skip-link {
    @apply absolute top-0 left-0 bg-blue-600 text-white px-4 py-2 z-50;
    @apply focus:relative focus:z-50;
}

.agid-header {
    @apply bg-blue-600 text-white;
}

.agid-breadcrumb {
    @apply bg-gray-50 border-b border-gray-200;
}

.agid-main {
    @apply min-h-screen bg-gray-50;
}

.agid-footer {
    @apply bg-gray-900 text-white;
}

/* AGID Form Styles */
.agid-form {
    @apply max-w-md mx-auto bg-white rounded-lg shadow-lg border;
}

.agid-input {
    @apply w-full px-3 py-2 border border-gray-300 rounded-md;
    @apply focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500;
}

.agid-button {
    @apply w-full bg-blue-600 text-white py-2 px-4 rounded-md;
    @apply hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500;
    @apply disabled:opacity-50 disabled:cursor-not-allowed;
}
```

### **Fase 4: JavaScript AGID-Compliant**

```javascript
// Focus Management AGID
document.addEventListener('DOMContentLoaded', function() {
    // Auto-focus primo campo
    const firstInput = document.querySelector('#login-form input:not([type="hidden"])');
    if (firstInput) {
        firstInput.focus();
    }
    
    // Form submission feedback
    const form = document.querySelector('#login-form form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.setAttribute('aria-busy', 'true');
                submitBtn.disabled = true;
                submitBtn.textContent = 'Accesso in corso...';
            }
        });
    }
    
    // Error announcement for screen readers
    const errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(error => {
        error.setAttribute('role', 'alert');
        error.setAttribute('aria-live', 'polite');
    });
});
```

## 📋 **CHECKLIST CONFORMITÀ AGID**

### **Struttura HTML**
- [ ] DOCTYPE HTML5
- [ ] Lang attribute corretto
- [ ] Meta viewport per responsive
- [ ] Meta description appropriato
- [ ] Titolo pagina descrittivo

### **Accessibilità**
- [ ] Skip links funzionanti
- [ ] ARIA landmarks (banner, main, contentinfo)
- [ ] ARIA labels per form elements
- [ ] Focus management corretto
- [ ] Keyboard navigation completa
- [ ] Screen reader compatibility

### **Design AGID**
- [ ] Font Titillium Web
- [ ] Colori conformi palette AGID
- [ ] Spaziature standard AGID
- [ ] Responsive design AGID-compliant
- [ ] Componenti UI standard PA

### **Contenuti Obbligatori**
- [ ] Header istituzionale
- [ ] Logo ente
- [ ] Breadcrumb navigation
- [ ] Footer con link obbligatori
- [ ] Privacy policy link
- [ ] Dichiarazione accessibilità link
- [ ] Note legali link

### **Performance**
- [ ] CSS minificato
- [ ] JavaScript ottimizzato
- [ ] Immagini ottimizzate
- [ ] Font loading ottimizzato

## 🚀 **PIANO DI IMPLEMENTAZIONE**

### **Sprint 1: Layout Base (2-3 giorni)**
1. Creare `auth-agid.blade.php`
2. Implementare CSS AGID base
3. Testare struttura HTML

### **Sprint 2: Componenti AGID (3-4 giorni)**
1. Header istituzionale
2. Breadcrumb navigation
3. Footer istituzionale
4. Form styling AGID

### **Sprint 3: Accessibilità (2-3 giorni)**
1. Skip links
2. ARIA attributes
3. Focus management
4. Keyboard navigation
5. Screen reader testing

### **Sprint 4: Testing e Validazione (2-3 giorni)**
1. Validator HTML5
2. WAVE accessibility testing
3. Lighthouse audit
4. Manual testing
5. Cross-browser testing

## 📚 **RIFERIMENTI**

- [Linee Guida AGID Design](https://docs.italia.it/italia/designers-italia/design-linee-guida-docs/)
- [Kit UI Italia](https://designers.italia.it/kit/ui-kit/)
- [WCAG 2.1 AA Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [Documentazione Tema Sixteen](./components.md)

## 🔗 **Collegamenti**

- [Componenti Tema Sixteen](./components.md)
- [Implementazione AGID](./agid-login-implementation.md)
- [Regole Layout](./layout-usage-correction.md)
- [Documentazione Root AGID](../../../docs/agid-compliance.md)

---

**Creato**: 2025-07-31  
**Autore**: Sistema di Analisi AGID  
**Versione**: 1.0  
**Status**: Analisi Completata - Implementazione Richiesta
