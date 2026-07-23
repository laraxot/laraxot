# Piano di Implementazione Login AGID-Compliant

## 🎯 **OBIETTIVO**

Trasformare la pagina di login attuale in una pagina completamente AGID-compliant seguendo le linee guida di design Italia e garantendo accessibilità WCAG 2.1 AA.

## 🔍 **ANALISI DEL PROBLEMA**

### **Problema Principale**
La pagina di login usa il layout `guest.blade.php` che è un wrapper minimale non-AGID che "schiaccia" tutto il contenuto AGID della pagina login in una piccola card centrata.

### **Conflitto Architetturale**
```
login.blade.php (AGID content) 
    ↓ wrapped by
guest.blade.php (Non-AGID layout)
    ↓ risultato
Pagina malformata e non conforme
```

## 🛠️ **SOLUZIONE IMPLEMENTATIVA**

### **Fase 1: Creazione Layout AGID Dedicato**

#### **File**: `resources/views/components/layouts/auth-agid.blade.php`

```blade
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- AGID Required Meta -->
    <meta name="description" content="Accesso sicuro ai servizi digitali - {{ config('app.name') }}">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#0066CC">
    
    <!-- Title -->
    <title>{{ $title ?? 'Accesso' }} - {{ config('app.name') }}</title>
    
    <!-- AGID Fonts - Titillium Web -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Titillium+Web:ital,wght@0,300;0,400;0,600;0,700;1,300;1,400&display=swap" rel="stylesheet">
    
    <!-- Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'], 'themes/Sixteen')
    
    <!-- AGID Custom Styles -->
    <style>
        :root {
            --agid-primary: #0066CC;
            --agid-primary-dark: #004080;
            --agid-secondary: #5C6F82;
            --agid-success: #008758;
            --agid-warning: #A66300;
            --agid-danger: #D73527;
            --agid-gray-50: #F8F9FA;
            --agid-gray-100: #F1F3F4;
            --agid-gray-900: #17324D;
        }
        
        body {
            font-family: 'Titillium Web', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }
        
        .agid-skip-link {
            position: absolute;
            top: -40px;
            left: 6px;
            background: var(--agid-primary);
            color: white;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 0 0 4px 4px;
            z-index: 1000;
            transition: top 0.2s;
        }
        
        .agid-skip-link:focus {
            top: 0;
        }
        
        .agid-header {
            background: var(--agid-primary);
            color: white;
            padding: 1rem 0;
        }
        
        .agid-breadcrumb {
            background: var(--agid-gray-50);
            border-bottom: 1px solid #E5E7EB;
            padding: 0.75rem 0;
        }
        
        .agid-main {
            min-height: calc(100vh - 200px);
            background: var(--agid-gray-50);
            padding: 2rem 0;
        }
        
        .agid-footer {
            background: var(--agid-gray-900);
            color: white;
            padding: 2rem 0;
        }
        
        .agid-form-container {
            max-width: 28rem;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #E5E7EB;
            overflow: hidden;
        }
        
        .agid-form-header {
            background: #F8FAFC;
            padding: 1.5rem;
            border-bottom: 1px solid #E5E7EB;
        }
        
        .agid-form-body {
            padding: 1.5rem;
        }
        
        .agid-input {
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #D1D5DB;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        
        .agid-input:focus {
            outline: none;
            border-color: var(--agid-primary);
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.1);
        }
        
        .agid-button {
            width: 100%;
            background: var(--agid-primary);
            color: white;
            padding: 0.875rem 1.5rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.2s;
        }
        
        .agid-button:hover:not(:disabled) {
            background: var(--agid-primary-dark);
        }
        
        .agid-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
        
        .agid-button:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.3);
        }
    </style>
</head>
<body class="h-full bg-gray-50">
    <!-- Skip Links AGID -->
    <a href="#main-content" class="agid-skip-link">Salta al contenuto principale</a>
    <a href="#login-form" class="agid-skip-link">Vai al modulo di accesso</a>

    <!-- AGID Header Istituzionale -->
    <header role="banner" class="agid-header">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <x-pub_theme::ui.logo class="h-8 w-auto text-white" />
                    <div>
                        <h1 class="text-lg font-semibold">{{ config('app.name', 'Nome Ente') }}</h1>
                        <p class="text-sm opacity-90">{{ config('app.tagline', 'Servizi digitali per i cittadini') }}</p>
                    </div>
                </div>
                @if(config('app.institution_url'))
                    <a href="{{ config('app.institution_url') }}" 
                       class="text-white hover:text-blue-200 transition-colors text-sm"
                       target="_blank" rel="noopener noreferrer">
                        Vai al sito dell'ente
                    </a>
                @endif
            </div>
        </div>
    </header>

    <!-- AGID Breadcrumb -->
    <nav class="agid-breadcrumb" aria-label="Percorso di navigazione">
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
                    Accesso
                </li>
            </ol>
        </div>
    </nav>

    <!-- Main Content -->
    <main id="main-content" role="main" class="agid-main">
        <div class="container mx-auto px-4">
            {{ $slot }}
        </div>
    </main>

    <!-- AGID Footer Istituzionale -->
    <footer role="contentinfo" class="agid-footer">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <!-- Ente Info -->
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <x-pub_theme::ui.logo class="h-10 w-auto text-white" />
                        <div>
                            <h3 class="text-lg font-semibold">{{ config('app.name', 'Nome Ente') }}</h3>
                            <p class="text-sm opacity-80">{{ config('app.tagline', 'Servizi digitali') }}</p>
                        </div>
                    </div>
                    @if(config('app.institution_address'))
                        <p class="text-sm opacity-80">{{ config('app.institution_address') }}</p>
                    @endif
                </div>
                
                <!-- Link Obbligatori AGID -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Informazioni</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('pages.view', ['slug' => 'privacy']) }}" class="opacity-80 hover:opacity-100 transition-opacity">Privacy</a></li>
                        <li><a href="{{ route('pages.view', ['slug' => 'legal-notes']) }}" class="opacity-80 hover:opacity-100 transition-opacity">Note legali</a></li>
                        <li><a href="{{ route('pages.view', ['slug' => 'accessibility']) }}" class="opacity-80 hover:opacity-100 transition-opacity">Dichiarazione di accessibilità</a></li>
                    </ul>
                </div>
                
                <!-- Contatti -->
                <div>
                    <h4 class="text-lg font-semibold mb-4">Assistenza</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('pages.view', ['slug' => 'contacts']) }}" class="opacity-80 hover:opacity-100 transition-opacity">Contatti</a></li>
                        <li><a href="{{ route('pages.view', ['slug' => 'help']) }}" class="opacity-80 hover:opacity-100 transition-opacity">Guida</a></li>
                        @if(config('app.support_email'))
                            <li><a href="mailto:{{ config('app.support_email') }}" class="opacity-80 hover:opacity-100 transition-opacity">{{ config('app.support_email') }}</a></li>
                        @endif
                    </ul>
                </div>
            </div>
            
            <!-- Copyright -->
            <div class="pt-8 border-t border-gray-700 text-center text-sm opacity-80">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Tutti i diritti riservati.</p>
                <p class="mt-2">Conforme alle linee guida AGID per i servizi digitali della Pubblica Amministrazione</p>
            </div>
        </div>
    </footer>

    <!-- AGID JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Focus Management
            const firstInput = document.querySelector('#login-form input:not([type="hidden"]):not([disabled])');
            if (firstInput) {
                // Delay focus to allow page to fully load
                setTimeout(() => firstInput.focus(), 100);
            }
            
            // Form Submission Feedback
            const form = document.querySelector('#login-form form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn && !submitBtn.disabled) {
                        submitBtn.setAttribute('aria-busy', 'true');
                        submitBtn.disabled = true;
                        const originalText = submitBtn.textContent;
                        submitBtn.textContent = 'Accesso in corso...';
                        
                        // Re-enable after 10 seconds as fallback
                        setTimeout(() => {
                            if (submitBtn.disabled) {
                                submitBtn.disabled = false;
                                submitBtn.removeAttribute('aria-busy');
                                submitBtn.textContent = originalText;
                            }
                        }, 10000);
                    }
                });
            }
            
            // Error Messages Accessibility
            const errorMessages = document.querySelectorAll('.error-message, .alert-danger');
            errorMessages.forEach(error => {
                error.setAttribute('role', 'alert');
                error.setAttribute('aria-live', 'polite');
            });
            
            // Success Messages Accessibility
            const successMessages = document.querySelectorAll('.success-message, .alert-success');
            successMessages.forEach(success => {
                success.setAttribute('role', 'status');
                success.setAttribute('aria-live', 'polite');
            });
        });
    </script>
</body>
</html>
```

### **Fase 2: Refactoring Pagina Login**

#### **File**: `resources/views/pages/auth/login.blade.php`

```blade
<?php

declare(strict_types=1);

use function Laravel\Folio\{middleware, name};

middleware(['guest']);
name('login');

?>

<x-layouts.auth-agid>
    <x-slot name="title">
        {{ __('auth.login.title') }}
    </x-slot>

    <!-- Page Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">
            {{ __('auth.login.title') }}
        </h1>
        <p class="text-gray-600 max-w-md mx-auto">
            {{ __('auth.login.description', ['service' => config('app.name')]) }}
        </p>
    </div>

    <!-- Login Form Container -->
    <div id="login-form" class="agid-form-container" role="region" aria-labelledby="login-heading">
        <!-- Form Header -->
        <div class="agid-form-header">
            <h2 id="login-heading" class="text-xl font-semibold text-gray-900 mb-1">
                Accedi con le tue credenziali
            </h2>
            <p class="text-sm text-gray-600">
                Utilizza le credenziali fornite dall'amministratore del sistema
            </p>
        </div>
        
        <!-- Form Body -->
        <div class="agid-form-body">
            @livewire(\Modules\User\Http\Livewire\Auth\Login::class)
        </div>
    </div>

    <!-- Additional Information -->
    <div class="mt-8 text-center text-sm text-gray-600 max-w-md mx-auto space-y-4">
        <!-- AGID Compliance -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <p class="mb-2">
                <strong>Servizio conforme AGID:</strong> Questo sistema rispetta le 
                <a href="https://www.agid.gov.it/" 
                   class="text-blue-600 hover:text-blue-800 underline"
                   target="_blank" rel="noopener noreferrer">
                    linee guida AGID
                </a>
                per i servizi digitali della Pubblica Amministrazione.
            </p>
        </div>
        
        <!-- Privacy -->
        <p>
            Per informazioni sulla privacy consulta l'
            <a href="{{ route('pages.view', ['slug' => 'privacy']) }}" 
               class="text-blue-600 hover:text-blue-800 underline">
                informativa sulla privacy
            </a>
        </p>
        
        <!-- Accessibility -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <p class="mb-2">
                <strong>Accessibilità:</strong> Questo servizio è accessibile secondo le linee guida WCAG 2.1 AA.
                <a href="{{ route('pages.view', ['slug' => 'accessibility']) }}" 
                   class="text-blue-600 hover:text-blue-800 underline">
                    Dichiarazione di accessibilità
                </a>
            </p>
            <p class="text-xs text-gray-500">
                <strong>Navigazione da tastiera:</strong> Usa Tab per navigare, Invio per confermare, Esc per annullare.
            </p>
        </div>
        
        <!-- Help -->
        <p>
            <x-filament::icon name="heroicon-o-question-mark-circle" class="w-4 h-4 inline mr-1" />
            Hai bisogno di aiuto? 
            <a href="{{ route('pages.view', ['slug' => 'help']) }}" 
               class="text-blue-600 hover:text-blue-800 underline">
                Consulta la guida
            </a>
        </p>
    </div>

    <!-- Registration Link (if enabled) -->
    @if (Route::has('register'))
        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                Non hai un account? 
                <a href="{{ route('register') }}" 
                   class="text-blue-600 hover:text-blue-800 underline font-medium">
                    {{ __('auth.login.create_account') }}
                </a>
            </p>
        </div>
    @endif
</x-layouts.auth-agid>
```

## 🎨 **PERSONALIZZAZIONI CSS AGID**

### **File**: `resources/css/agid-components.css`

```css
/* AGID Design System Implementation */

/* Typography Scale AGID */
.agid-h1 { font-size: 2.25rem; font-weight: 700; line-height: 1.2; }
.agid-h2 { font-size: 1.875rem; font-weight: 600; line-height: 1.3; }
.agid-h3 { font-size: 1.5rem; font-weight: 600; line-height: 1.4; }
.agid-body { font-size: 1rem; line-height: 1.6; }
.agid-small { font-size: 0.875rem; line-height: 1.5; }

/* Color System AGID */
.agid-text-primary { color: var(--agid-primary); }
.agid-text-secondary { color: var(--agid-secondary); }
.agid-bg-primary { background-color: var(--agid-primary); }
.agid-bg-secondary { background-color: var(--agid-secondary); }

/* Spacing System AGID */
.agid-spacing-xs { margin: 0.5rem; }
.agid-spacing-sm { margin: 1rem; }
.agid-spacing-md { margin: 1.5rem; }
.agid-spacing-lg { margin: 2rem; }
.agid-spacing-xl { margin: 3rem; }

/* Form Components AGID */
.agid-form-group {
    margin-bottom: 1.5rem;
}

.agid-label {
    display: block;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.agid-input-error {
    border-color: var(--agid-danger);
    box-shadow: 0 0 0 3px rgba(215, 53, 39, 0.1);
}

.agid-error-message {
    color: var(--agid-danger);
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: flex;
    align-items: center;
}

.agid-success-message {
    color: var(--agid-success);
    font-size: 0.875rem;
    margin-top: 0.25rem;
    display: flex;
    align-items: center;
}

/* Responsive Design AGID */
@media (max-width: 640px) {
    .agid-form-container {
        margin: 0 1rem;
        border-radius: 0;
    }
    
    .agid-main {
        padding: 1rem 0;
    }
    
    .agid-h1 { font-size: 1.875rem; }
    .agid-h2 { font-size: 1.5rem; }
}

/* Focus States AGID */
.agid-focus:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(0, 102, 204, 0.3);
    border-color: var(--agid-primary);
}

/* Loading States */
.agid-loading {
    position: relative;
    pointer-events: none;
}

.agid-loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin: -10px 0 0 -10px;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: agid-spin 1s linear infinite;
}

@keyframes agid-spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
```

## 📱 **RESPONSIVE DESIGN AGID**

### **Breakpoints Standard AGID**
- Mobile: 320px - 767px
- Tablet: 768px - 1023px  
- Desktop: 1024px+

### **Layout Mobile-First**
```css
/* Mobile First Approach */
.agid-container {
    width: 100%;
    padding: 0 1rem;
}

@media (min-width: 768px) {
    .agid-container {
        max-width: 768px;
        margin: 0 auto;
        padding: 0 2rem;
    }
}

@media (min-width: 1024px) {
    .agid-container {
        max-width: 1024px;
        padding: 0 3rem;
    }
}
```

## 🧪 **TESTING E VALIDAZIONE**

### **Checklist Testing AGID**

#### **Accessibilità**
- [ ] Screen reader compatibility (NVDA, JAWS, VoiceOver)
- [ ] Keyboard navigation completa
- [ ] Focus management corretto
- [ ] ARIA attributes appropriati
- [ ] Color contrast ratio >= 4.5:1

#### **Performance**
- [ ] Lighthouse Score >= 90
- [ ] First Contentful Paint < 2s
- [ ] Largest Contentful Paint < 2.5s
- [ ] Cumulative Layout Shift < 0.1

#### **Compatibilità Browser**
- [ ] Chrome 90+
- [ ] Firefox 88+
- [ ] Safari 14+
- [ ] Edge 90+

#### **Responsive**
- [ ] Mobile 320px-767px
- [ ] Tablet 768px-1023px
- [ ] Desktop 1024px+

## 🚀 **DEPLOYMENT**

### **Comandi di Implementazione**

```bash
# 1. Backup layout attuale
cp resources/views/components/layouts/guest.blade.php resources/views/components/layouts/guest.blade.php.backup

# 2. Creare nuovo layout AGID
# (Copiare il contenuto del layout auth-agid.blade.php)

# 3. Aggiornare pagina login
# (Sostituire <x-layouts.guest> con <x-layouts.auth-agid>)

# 4. Compilare assets
npm run build

# 5. Clear cache
php artisan view:clear
php artisan config:clear

# 6. Test
php artisan serve
```

## 📋 **TIMELINE IMPLEMENTAZIONE**

### **Settimana 1**
- [ ] Creazione layout `auth-agid.blade.php`
- [ ] CSS AGID base
- [ ] Test struttura HTML

### **Settimana 2**  
- [ ] Refactoring pagina login
- [ ] JavaScript accessibility
- [ ] Test responsive

### **Settimana 3**
- [ ] Testing completo
- [ ] Validazione AGID
- [ ] Documentazione finale

## 🔗 **COLLEGAMENTI**

- [Analisi Problemi AGID](./agid-login-compliance-analysis.md)
- [Componenti Tema Sixteen](./components.md)
- [Linee Guida AGID](https://docs.italia.it/italia/designers-italia/design-linee-guida-docs/)
- [Kit UI Italia](https://designers.italia.it/kit/ui-kit/)

---

**Creato**: 2025-07-31  
**Autore**: Piano Implementazione AGID  
**Versione**: 1.0  
**Status**: Pronto per Implementazione
