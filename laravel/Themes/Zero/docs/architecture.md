---
title: "Zero Theme Architecture"
type: architecture
tags: [theme, architecture, zero]
created: 2026-08-04
updated: 2026-08-04
---
# Zero Theme — Architecture

## Purpose
Zero theme architecture and design patterns for Laraxot PTVX.

## Core Components

**Views:**
- Blade templates for base layouts
- Component-based structure

**Assets:**
- Vite build pipeline
- Tailwind CSS compilation

**Providers:**
- ThemeServiceProvider registration

## Quality Gates
- Build passes without errors
- Components render correctly
- Performance optimized
---
title: "Architettura del Tema Zero"
type: guide
tags: ['filament', 'laravel', 'charts', 'testing']
created: 2026-07-14
updated: 2026-07-14
qmd: "architettura del tema zero"
related:
  - "./00-INDEX.md"
  - "./00-index.md"
---

# Architettura del Tema Zero

## Filosofia: "Il Tema come Vestito"

Il tema Zero segue la filosofia architetturale dove **il tema serve come "vestito"** per l'applicazione, fornendo solo la presentazione visiva mentre la logica di business rimane centralizzata nei moduli.

## Principi Architetturali

### 1. Separazione delle Responsabilità

```
┌─────────────────────────────────────────────────────────────┐
│                    TEMA ZERO (Vestito)                      │
├─────────────────────────────────────────────────────────────┤
│  • Layout e Styling                                         │
│  • Componenti UI                                            │
│  • Personalizzazione visiva                                 │
│  • Responsive design                                        │
│  • Accessibilità                                            │
└─────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────┐
│                   MODULI (Logica)                           │
├─────────────────────────────────────────────────────────────┤
│  • User: Autenticazione, gestione utenti                    │
│  • Cms: Gestione contenuti                                  │
│  • Xot: Funzionalità core                                   │
│  • Altri moduli specifici                                   │
└─────────────────────────────────────────────────────────────┘
```

### 2. Widget Filament per la Logica

I form e le funzionalità interattive utilizzano **widget Filament** che:
- Contengono la logica di business
- Gestiscono la validazione
- Implementano la sicurezza
- Forniscono feedback utente

### 3. Viste Minimaliste

Le viste del tema sono **minimaliste** e focalizzate solo su:
- Layout e styling
- Personalizzazione visiva
- Responsive design
- Accessibilità

## Struttura delle Directory

```
laravel/Themes/Zero/
├── resources/
│   ├── views/
│   │   ├── layouts/              # Layout principali
│   │   │   ├── main.blade.php    # Layout principale
│   │   │   └── app.blade.php     # Layout semplificato
│   │   ├── pages/                # Pagine Folio
│   │   │   ├── auth/             # Pagine di autenticazione
│   │   │   │   └── login.blade.php
│   │   │   ├── home.blade.php    # Homepage
│   │   │   └── index.blade.php   # Pagina principale
│   │   ├── components/           # Componenti riutilizzabili
│   │   │   ├── ui/               # Componenti UI base
│   │   │   └── blocks/           # Blocchi di contenuto
│   │   └── filament/             # Viste widget Filament
│   │       └── widgets/
│   │           └── auth/
│   │               └── login.blade.php
│   ├── css/                      # Stili personalizzati
│   └── js/                       # JavaScript personalizzato
├── docs/                         # Documentazione
├── tailwind.config.js            # Configurazione Tailwind
└── package.json                  # Dipendenze frontend
```

## Flusso di Rendering

### 1. Pagina di Login

```mermaid
graph TD
    A[Richiesta /auth/login] --> B[Folio Routing]
    B --> C[login.blade.php]
    C --> D[Layout main.blade.php]
    D --> E[@livewire LoginWidget]
    E --> F[LoginWidget.php]
    F --> G[login.blade.php widget]
    G --> H[Form Filament]
    H --> I[Output HTML]
```

### 2. Componenti Widget

```php
// 1. Pagina chiama il widget
@livewire(\Modules\User\Filament\Widgets\Auth\LoginWidget::class)

// 2. Widget contiene la logica
class LoginWidget extends XotBaseWidget
{
    protected static string $view = 'pub_theme::filament.widgets.auth.login';
    
    public function login(): void
    {
        // Logica di autenticazione
    }
}

// 3. Vista del tema personalizza l'aspetto
<x-filament-widgets::widget>
    {{ $this->form }}  {{-- Form renderizzato da Filament --}}
</x-filament-widgets::widget>
```

## Vantaggi dell'Architettura

### 🎨 **Personalizzazione**
- Ogni tema può avere il suo stile unico
- Colori, layout e tipografia personalizzabili
- Mantenimento coerenza con il design del tema

### 🔧 **Manutenzione**
- Logica centralizzata nei moduli
- Un solo punto di verità per la logica
- Facile aggiornamento delle funzionalità

### 🔄 **Flessibilità**
- Facile switch tra temi diversi
- Riutilizzo della logica tra temi
- Personalizzazione per brand specifici

### 🚀 **Performance**
- Separazione chiara tra logica e presentazione
- Caching ottimizzato per componente
- Lazy loading dei widget

## Convenzioni di Naming

### Namespace delle Viste

```php
// Widget nel modulo User
protected static string $view = 'pub_theme::filament.widgets.auth.login';

// Vista nel tema Zero
laravel/Themes/Zero/resources/views/filament/widgets/auth/login.blade.php
```

### Struttura dei File

```
// Pagina Folio
pages/auth/login.blade.php

// Vista Widget
filament/widgets/auth/login.blade.php

// Componenti UI
components/ui/button.blade.php
components/blocks/header.blade.php
```

## Best Practices

### 1. Viste Minimaliste
```blade
{{-- ✅ CORRETTO: Vista focalizzata solo su styling --}}
<x-filament-widgets::widget>
    <div class="custom-styling">
        {{ $this->form }}
    </div>
</x-filament-widgets::widget>

{{-- ❌ ERRATO: Logica nella vista --}}
@volt('auth.login')
    // Logica di autenticazione
@endvolt
```

### 2. Utilizzo Widget Filament
```blade
{{-- ✅ CORRETTO: Utilizzo widget --}}
@livewire(\Modules\User\Filament\Widgets\Auth\LoginWidget::class)

{{-- ❌ ERRATO: Form manuale --}}
<form wire:submit="login">
    // Campi form manuali
</form>
```

### 3. Separazione Responsabilità
```php
// ✅ CORRETTO: Logica nel widget
class LoginWidget extends XotBaseWidget
{
    public function login(): void
    {
        // Logica di autenticazione
    }
}

// ❌ ERRATO: Logica nella vista
@volt('auth.login')
    // Logica nella vista
@endvolt
```

## Integrazione con Moduli

### Modulo User
- **LoginWidget**: Gestione autenticazione
- **RegisterWidget**: Registrazione utenti
- **PasswordResetWidget**: Reset password

### Modulo Cms
- **ContentWidget**: Gestione contenuti
- **NavigationWidget**: Navigazione
- **SearchWidget**: Ricerca

### Modulo Xot
- **XotBaseWidget**: Classe base per tutti i widget
- **Funzionalità core**: Utility e helper

## Testing

### Test di Integrazione
```php
// Test che la pagina utilizza il widget corretto
public function test_login_page_uses_login_widget()
{
    $response = $this->get('/auth/login');
    
    $response->assertSee('@livewire');
    $response->assertSee('LoginWidget');
}
```

### Test di Widget
```php
// Test del widget isolato
public function test_login_widget_validation()
{
    $component = Livewire::test(LoginWidget::class);
    
    $component->set('data.email', 'invalid-email')
              ->call('login')
              ->assertHasErrors(['data.email']);
}
```

## Riferimenti

- [Documentazione Autenticazione](./authentication.md)
- [Documentazione Componenti](./components.md)
- [Documentazione Layout](./layouts.md)
- [Best Practices Filament](../Cms/docs/frontoffice/filament-auth.md)
- [Architettura Widget](../User/docs/widgets_structure.md) 

---

<!-- Merged from ARCHITECTURE.md, which collided with this file on case-insensitive filesystems. -->

---
title: "Zero Theme Architecture"
type: architecture
tags: [theme, architecture, zero, frontend]
created: 2026-08-04
updated: 2026-08-04
---
# Zero Theme — Architecture

## Purpose
Base Laravel theme (skeleton/boostrap)

## Core Components

**Views:**
- Blade templates for Zero-specific layouts
- Component-based structure
- Filament integration patterns

**Assets:**
- Vite-based build pipeline
- CSS/JS compilation with Tailwind

**Providers:**
- `ZeroThemeServiceProvider` — Theme registration
- View namespace configuration

## Design Decisions

| Decision | Rationale |
|----------|-----------|
| Blade templates | Laravel-native, component-friendly |
| Tailwind utility-first | Rapid, consistent styling |
| Vite bundler | Fast HMR, optimized builds |

## Integration Points
**Depends On:** Laravel, Blade, Tailwind CSS, Vite
**Active Theme:** Applied system-wide or tenant-specific

## Quality Gates
- **Build**: npm run build passes
- **Lint**: Tailwind/Pug linter checks
- **Preview**: Theme renders correctly in browser
