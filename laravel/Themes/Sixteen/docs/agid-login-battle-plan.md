# Piano di Battaglia - Login AGID Compliant

## 🚨 PROBLEMA IDENTIFICATO

**STATO ATTUALE**: La pagina di login è un DISASTRO
- ❌ Non sembra AGID compliant
- ❌ Design scadente e non professionale
- ❌ Testi non tradotti (`auth.login.title`)
- ❌ Struttura confusa e disordinata
- ❌ Mancano elementi AGID obbligatori
- ❌ Layout non conforme alle linee guida

## 🎯 OBIETTIVI STRATEGICI

### 1. Conformità AGID Completa
- ✅ Header istituzionale con logo e branding
- ✅ Breadcrumb navigation
- ✅ Skip links per accessibilità
- ✅ Footer con link obbligatori
- ✅ WCAG 2.1 AA compliance
- ✅ Responsive design

### 2. Design Professionale
- ✅ Layout pulito e moderno
- ✅ Colori istituzionali
- ✅ Typography professionale
- ✅ Spacing e padding corretti
- ✅ Visual hierarchy chiara

### 3. Funzionalità Complete
- ✅ Form di login funzionante
- ✅ Gestione errori
- ✅ Validazione
- ✅ Redirect appropriati
- ✅ Supporto "Remember me"

## 📋 PIANO DI BATTAGLIA DETTAGLIATO

### FASE 1: Analisi e Documentazione (COMPLETATA)
- ✅ Analisi documentazione esistente
- ✅ Identificazione problemi
- ✅ Creazione piano di battaglia

### FASE 2: Struttura AGID Base
**Obiettivo**: Implementare struttura AGID completa

#### 2.1 Header Istituzionale
```blade
<!-- Header PA con Logo e Branding -->
<div class="bg-blue-600 text-white py-3">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center space-x-4">
                <x-pub_theme::ui.logo class="h-8 w-auto text-white" />
                <span class="font-semibold">{{ config('app.institution_name', 'Ente di appartenenza') }}</span>
            </div>
            @if(config('app.institution_url'))
                <a href="{{ config('app.institution_url') }}" 
                   class="text-white hover:text-blue-200 transition-colors"
                   target="_blank" rel="noopener noreferrer">
                    Vai al sito dell'ente
                </a>
            @endif
        </div>
    </div>
</div>
```

#### 2.2 Breadcrumb Navigation
```blade
<!-- Breadcrumb Navigation (AGID Compliant) -->
<nav class="bg-gray-50 py-3 border-b border-gray-200" aria-label="Percorso di navigazione">
    <div class="container mx-auto px-4">
        <ol class="flex items-center space-x-2 text-sm">
            <li>
                <a href="{{ route('home') }}" class="text-blue-600 hover:text-blue-800 transition-colors">
                    <x-filament::icon name="heroicon-o-home" class="w-4 h-4 inline mr-1" />
                    Home
                </a>
            </li>
            <li class="text-gray-400" aria-hidden="true">/</li>
            <li class="text-gray-700 font-medium" aria-current="page">
                {{ __('auth.login.title') }}
            </li>
        </ol>
    </div>
</nav>
```

#### 2.3 Skip Links
```blade
<!-- Skip Links for Accessibility (AGID Compliant) -->
<div class="sr-only focus:not-sr-only">
    <a href="#main-content" 
       class="absolute top-0 left-0 bg-blue-600 text-white px-4 py-2 z-50 focus:relative">
        Salta al contenuto principale
    </a>
    <a href="#login-form" 
       class="absolute top-0 left-0 bg-blue-600 text-white px-4 py-2 z-50 focus:relative">
        Vai al modulo di accesso
    </a>
</div>
```

### FASE 3: Layout e Design
**Obiettivo**: Design professionale e pulito

#### 3.1 Layout Container
```blade
<!-- Main Content with AGID Structure -->
<main id="main-content" role="main" class="min-h-screen bg-gray-50">
    <div class="container mx-auto px-4 py-12">
        <!-- Content here -->
    </div>
</main>
```

#### 3.2 Login Form Card
```blade
<!-- Login Form Container (AGID Compliant) -->
<div id="login-form" 
     class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden max-w-md mx-auto"
     role="region" 
     aria-labelledby="login-heading">
    
    <!-- Form Header -->
    <div class="bg-blue-50 px-6 py-4 border-b border-gray-200">
        <h2 id="login-heading" class="text-lg font-semibold text-gray-900">
            Accedi con le tue credenziali
        </h2>
        <p class="text-sm text-gray-600 mt-1">
            Utilizza le credenziali fornite dall'amministratore
        </p>
    </div>
    
    <!-- Form Body with Mandatory Livewire Component -->
    <div class="px-6 py-6">
        @livewire(\Modules\User\Http\Livewire\Auth\Login::class)
    </div>
</div>
```

### FASE 4: Footer AGID
**Obiettivo**: Footer istituzionale completo

#### 4.1 Footer Structure
```blade
<!-- AGID Institutional Footer -->
<footer role="contentinfo" class="mt-12 bg-gray-900 text-white py-8">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Logo and Institution Info -->
            <div class="flex items-center space-x-4">
                <x-pub_theme::ui.logo class="h-10 w-auto text-white" />
                <div>
                    <h3 class="text-lg font-semibold">{{ config('app.name', 'Nome Ente') }}</h3>
                    <p class="text-sm opacity-80 mt-1">{{ config('app.tagline', 'Servizi digitali per i cittadini') }}</p>
                </div>
            </div>
            
            <!-- Institutional Links -->
            <div>
                <h4 class="text-lg font-semibold mb-4">Link utili</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('pages.view', ['slug' => 'privacy']) }}" class="opacity-80 hover:opacity-100 transition-opacity">Privacy</a></li>
                    <li><a href="{{ route('pages.view', ['slug' => 'accessibility']) }}" class="opacity-80 hover:opacity-100 transition-opacity">Dichiarazione di accessibilità</a></li>
                    <li><a href="{{ route('pages.view', ['slug' => 'contacts']) }}" class="opacity-80 hover:opacity-100 transition-opacity">Contatti</a></li>
                    <li><a href="{{ route('pages.view', ['slug' => 'legal-notice']) }}" class="opacity-80 hover:opacity-100 transition-opacity">Note legali</a></li>
                </ul>
            </div>
        </div>
        
        <div class="mt-8 pt-8 border-t border-gray-700 text-center text-sm opacity-80">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tutti i diritti riservati.</p>
        </div>
    </div>
</footer>
```

### FASE 5: Traduzioni e Testi
**Obiettivo**: Testi corretti e localizzati

#### 5.1 Chiavi di Traduzione
```php
// auth.login.*
'title' => 'Accesso ai servizi',
'description' => 'Inserisci le tue credenziali per accedere a :service',
'no_account' => 'Non hai un account?',
'create_account' => 'Registrati',
'security_title' => 'Connessione sicura',
'security_message' => 'I tuoi dati sono protetti con crittografia SSL',
'accessibility_info' => 'Questo servizio è accessibile secondo le linee guida WCAG 2.1 AA.',
'accessibility_declaration' => 'Dichiarazione di accessibilità',
'keyboard_navigation' => 'Usa Tab per navigare tra i campi e Invio per inviare il modulo.',
```

#### 5.2 Testi Hardcoded AGID
```blade
<!-- Testi standard AGID (NON tradurre) -->
<p>Questo servizio è conforme alle <a href="https://www.agid.gov.it/">linee guida AGID</a></p>
<p><strong>Accessibilità:</strong> Questo servizio è accessibile secondo le linee guida WCAG 2.1 AA.</p>
<p><strong>Navigazione da tastiera:</strong> Usa Tab per navigare tra i campi e Invio per inviare il modulo.</p>
```

### FASE 6: Accessibilità e Compliance
**Obiettivo**: WCAG 2.1 AA compliance

#### 6.1 ARIA Labels
```blade
<!-- ARIA Labels for Accessibility -->
<nav aria-label="Percorso di navigazione">
<main role="main" id="main-content">
<footer role="contentinfo">
<div role="region" aria-labelledby="login-heading">
```

#### 6.2 Focus Management
```blade
<!-- Focus Management Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Focus management for accessibility
    const firstInput = document.querySelector('input[type="email"], input[type="text"]');
    if (firstInput) {
        firstInput.focus();
    }
    
    // Handle form submission feedback
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() {
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.setAttribute('aria-busy', 'true');
                submitButton.disabled = true;
            }
        });
    }
});
</script>
```

### FASE 7: Responsive Design
**Obiettivo**: Design responsive per tutti i dispositivi

#### 7.1 Breakpoints
```css
/* Mobile First Approach */
.container {
    @apply px-4 mx-auto;
}

/* Tablet */
@media (min-width: 768px) {
    .container {
        @apply px-6;
    }
}

/* Desktop */
@media (min-width: 1024px) {
    .container {
        @apply px-8;
    }
}
```

#### 7.2 Grid System
```blade
<!-- Responsive Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- Content -->
</div>
```

### FASE 8: Testing e Validazione
**Obiettivo**: Verifica completa

#### 8.1 Test Funzionali
- [ ] Form di login funziona
- [ ] Validazione errori
- [ ] Redirect post-login
- [ ] "Remember me" funziona

#### 8.2 Test Accessibilità
- [ ] Screen reader compatibility
- [ ] Keyboard navigation
- [ ] Color contrast
- [ ] ARIA labels

#### 8.3 Test Responsive
- [ ] Mobile (320px+)
- [ ] Tablet (768px+)
- [ ] Desktop (1024px+)
- [ ] Large screens (1440px+)

## 🎯 RISULTATI ATTESI

### Design
- ✅ Layout pulito e professionale
- ✅ Colori istituzionali
- ✅ Typography moderna
- ✅ Spacing corretto
- ✅ Visual hierarchy chiara

### Funzionalità
- ✅ Form di login funzionante
- ✅ Gestione errori
- ✅ Validazione
- ✅ Redirect appropriati
- ✅ Supporto "Remember me"

### AGID Compliance
- ✅ Header istituzionale
- ✅ Breadcrumb navigation
- ✅ Skip links
- ✅ Footer con link obbligatori
- ✅ WCAG 2.1 AA compliance
- ✅ Responsive design

### Accessibilità
- ✅ Screen reader support
- ✅ Keyboard navigation
- ✅ Color contrast
- ✅ ARIA labels
- ✅ Focus management

## 📋 CHECKLIST IMPLEMENTAZIONE

### FASE 1: Struttura Base
- [ ] Header istituzionale con logo
- [ ] Breadcrumb navigation
- [ ] Skip links per accessibilità
- [ ] Main content area
- [ ] Footer istituzionale

### FASE 2: Design e Layout
- [ ] Container responsive
- [ ] Login form card
- [ ] Typography professionale
- [ ] Colori istituzionali
- [ ] Spacing corretto

### FASE 3: Funzionalità
- [ ] Livewire login component
- [ ] Form validation
- [ ] Error handling
- [ ] Redirect logic
- [ ] Remember me functionality

### FASE 4: Traduzioni
- [ ] Chiavi di traduzione
- [ ] Testi hardcoded AGID
- [ ] Localizzazione
- [ ] Fallback texts

### FASE 5: Accessibilità
- [ ] ARIA labels
- [ ] Focus management
- [ ] Keyboard navigation
- [ ] Color contrast
- [ ] Screen reader support

### FASE 6: Testing
- [ ] Test funzionali
- [ ] Test accessibilità
- [ ] Test responsive
- [ ] Test cross-browser

## 🚀 IMPLEMENTAZIONE IMMEDIATA

### File da Modificare
1. `laravel/Themes/Sixteen/resources/views/pages/auth/login.blade.php`
2. `laravel/Themes/Sixteen/lang/it/auth.php`
3. `laravel/Themes/Sixteen/lang/en/auth.php`

### Comandi da Eseguire
```bash
# Clear cache
php artisan view:clear
php artisan cache:clear
php artisan config:clear

# Test
php artisan serve
# Visit: http://localhost:8000/it/auth/login
```

---

**Data Creazione**: Dicembre 2024  
**Priorità**: ASSOLUTA  
**Stato**: Piano di Battaglia Completato  
**Prossimo Passo**: Implementazione FASE 1 