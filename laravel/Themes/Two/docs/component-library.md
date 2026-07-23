# TechPlanner Component Library
## Comprehensive Guide to All Blade Components

**Date**: February 6, 2026
**Version**: 1.0
**Scope**: All Blade components, blocks, and layout elements

---

## 1. Layout Components

### 1.1 App Layout
**File**: `resources/views/layouts/app.blade.php`
**Purpose**: Main layout wrapper for all pages
**Usage**:
```blade
<x-layouts.app>
    <x-slot name="title">Page Title</x-slot>
    <x-slot name="description">Page Description</x-slot>

    <!-- Page Content -->
    <main>
        @yield('content')
    </main>
</x-layouts.app>
```

**Features**:
- Filament styles and scripts
- Vite asset loading
- Responsive navigation
- Footer inclusion
- SEO meta tags

### 1.2 Page Component
**File**: `resources/views/pages/index.blade.php`
**Purpose**: Homepage template with content blocks
**Usage**:
```blade
<x-page side="content" slug="home">
    <!-- Page content automatically loaded from home.json -->
</x-page>
```

**Features**:
- Dynamic content loading from JSON
- Sidebar support
- Block rendering system
- SEO optimization
- Caching enabled

---

## 1.5 UI Components

### Logo
**File**: `components/ui/logo.blade.php`
**Usage**: `<x-pub_theme::ui.logo class="h-14 w-auto" color="white" />`
- `color="white"` per sfondi scuri (login, register)
- Vedi [auth-login-page](auth-login-page.md)

### Particles
**File**: `components/ui/particles.blade.php`
**Usage**: `@include('pub_theme::components.ui.particles')`
- Effetto particelle canvas per pannelli auth
- Vedi [particles-component](particles-component.md)

---

## 2. Block Components

### 2.1 Hero Block
**Type**: `hero`
**View**: `components/blocks/hero/main.blade.php`
**Purpose**: Main hero section with background image and CTAs
**Configuration**:
```json
{
  "type": "hero",
  "slug": "hero-section",
  "data": {
    "view": "pub_theme::components.blocks.hero.main",
    "title": "Benvenuto in TechPlanner",
    "subtitle": "La rivoluzione nella gestione tecnica aziendale",
    "description": "Ottimizza ogni processo, automatizza la pianificazione e potenzia la produttività del tuo team con la piattaforma più avanzata sul mercato.",
    "background_image": "https://images.unsplash.com/photo-1497366216548-37526070297c?auto=format&fit=crop&q=80&w=2069",
    "ctaPrimary": {
      "label": "Inizia la Prova Gratuita",
      "url": "/register",
      "style": "primary"
    },
    "ctaSecondary": {
      "label": "Guarda come funziona",
      "url": "#features",
      "style": "secondary"
    }
  }
}
```

**Enhancements Needed**:
- [ ] Add countdown timer option
- [ ] Add video background support
- [ ] Add trust indicators (logos)
- [ ] Add scroll indicator animation
- [ ] Optimize image loading

### 2.2 Features Block
**Type**: `features`
**View**: `components/blocks/features/grid.blade.php`
**Purpose**: Display feature cards in grid layout
**Configuration**:
```json
{
  "type": "features",
  "slug": "features-grid",
  "data": {
    "view": "pub_theme::components.blocks.features.grid",
    "title": "Eccellenza Operativa",
    "description": "Strumenti progettati per scalare la tua azienda verso il successo",
    "features": [
      {
        "icon": "heroicon-o-users",
        "title": "Smart HR Management",
        "description": "Algoritmi intelligenti per la gestione del personale e valutazione delle performance.",
        "url": "/employee/admin",
        "color": "primary"
      },
      {
        "icon": "heroicon-o-chart-bar",
        "title": "Predictive Analytics",
        "description": "Prevedi i trend e prendi decisioni basate sui dati con reportistica real-time.",
        "url": "/chart/admin",
        "color": "secondary"
      },
      {
        "icon": "heroicon-o-sparkles",
        "title": "Automazione Totale",
        "description": "Elimina i task ripetitivi e focalizzati su ciò che conta davvero per il tuo business.",
        "url": "/admin/settings",
        "color": "accent"
      }
    ]
  }
}
```

**Enhancements Needed**:
- [ ] Add hover lift animations
- [ ] Add icon animations
- [ ] Add more layout options (list, carousel)
- [ ] Add feature categories
- [ ] Add search/filter functionality

### 2.3 CTA Block
**Type**: `cta`
**View**: `components/blocks/cta/banner.blade.php`
**Purpose**: Call-to-action banner with gradient background
**Configuration**:
```json
{
  "type": "cta",
  "slug": "final-cta",
  "data": {
    "view": "pub_theme::components.blocks.cta.banner",
    "title": "Trasforma la tua Azienda Oggi",
    "description": "Oltre 500 aziende leader utilizzano già TechPlanner per dominare il loro mercato.",
    "background_gradient": "from-indigo-600 via-blue-700 to-primary",
    "text_color": "text-white",
    "cta_primary": {
      "label": "Accedi Ora",
      "url": "/admin"
    },
    "cta_secondary": {
      "label": "Parla con un esperto",
      "url": "/contact"
    }
  }
}
```

**Enhancements Needed**:
- [ ] Update to brand gradient (#667eea → #764ba2)
- [ ] Add hover lift animation
- [x] Add particle background effect (vedi [particles-component](particles-component.md))
- [ ] Add trust indicators
- [ ] A/B test different CTAs

---

## 3. New Components (To Be Created)

### 3.1 Countdown Timer Component
**File**: `components/blocks/countdown.blade.php`
**Purpose**: Display countdown to specific date/event
**Usage**:
```blade
<x-countdown
    :target-date="$targetDate"
    :title="'Lancio in:'"
    :show-labels="true"
/>
```

**Alpine.js Implementation**:
```blade
<div x-data="countdownTimer()" x-init="startCountdown()" class="flex justify-center gap-5 my-10">
    <template x-for="unit in ['days', 'hours', 'minutes', 'seconds']">
        <div class="bg-gradient-to-br from-brand-light to-brand-dark text-white p-5 rounded-xl min-w-20 text-center shadow-lg">
            <span class="countdown-number block text-4xl font-bold" x-text="timer[unit].toString().padStart(2, '0')"></span>
            <span class="countdown-label text-xs uppercase opacity-90" x-text="unit"></span>
        </div>
    </template>
</div>

<script>
function countdownTimer() {
    return {
        timer: { days: 0, hours: 0, minutes: 0, seconds: 0 },
        interval: null,

        startCountdown() {
            this.interval = setInterval(() => {
                const now = new Date().getTime();
                const distance = this.targetDate - now;

                if (distance < 0) {
                    clearInterval(this.interval);
                    this.timer = { days: 0, hours: 0, minutes: 0, seconds: 0 };
                    return;
                }

                this.timer.days = Math.floor(distance / (1000 * 60 * 60 * 24));
                this.timer.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                this.timer.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                this.timer.seconds = Math.floor((distance % (1000 * 60)) / 1000);
            }, 1000);
        }
    }
}
</script>
```

### 3.2 Lead Capture Component
**File**: `components/blocks/lead-capture/form.blade.php`
**Purpose**: Email subscription form for lead generation
**Usage**:
```blade
<x-lead-capture
    title="Rimani Aggiornato"
    description="Iscriviti per ricevere aggiornamenti esclusivi"
    :success-message="Grazie per l'iscrizione!"
/>
```

**Implementation**:
```blade
<div class="bg-white rounded-2xl shadow-xl p-8 max-w-md mx-auto">
    <h3 class="text-2xl font-bold mb-2">{{ $title }}</h3>
    <p class="text-gray-600 mb-6">{{ $description }}</p>

    <form wire:submit.prevent="subscribe" class="space-y-4">
        <div>
            <input type="email"
                   wire:model="email"
                   placeholder="Il tuo indirizzo email"
                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand focus:border-transparent"
                   required />
            @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit"
                class="w-full bg-gradient-to-r from-brand-light to-brand-dark text-white font-semibold py-3 px-6 rounded-lg hover:shadow-lg transition-all duration-300">
            {{ __('Iscriviti Ora') }}
        </button>
    </form>

    <p class="text-xs text-gray-500 mt-4 text-center">
        <x-heroicon-o-shield-check class="inline w-4 h-4 mr-1" />
        {{ __('I tuoi dati sono al sicuro. Nello spam non ci crediamo.') }}
    </p>
</div>
```

### 3.3 Testimonials Component
**File**: `components/blocks/testimonials/carousel.blade.php`
**Purpose**: Display customer testimonials in carousel
**Usage**:
```blade
<x-testimonials
    title="Cosa Dicono i Nostri Clienti"
    description="Scopri perché oltre 500 aziende scelgono TechPlanner"
    :testimonials="$testimonials"
/>
```

**Implementation**:
```blade
<div class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold mb-4">{{ $title }}</h2>
            <p class="text-gray-600">{{ $description }}</p>
        </div>

        <div x-data="{ activeSlide: 0 }" class="relative">
            <div class="overflow-hidden">
                <div class="flex transition-transform duration-500"
                     :style="`transform: translateX(-${activeSlide * 100}%)`">
                    @foreach ($testimonials as $testimonial)
                    <div class="w-full flex-shrink-0 px-4">
                        <div class="bg-white rounded-2xl shadow-lg p-8 max-w-3xl mx-auto">
                            <div class="flex items-center mb-4">
                                @for ($i = 1; $i <= 5; $i++)
                                    <x-heroicon-o-star :class="$i <= $testimonial['rating'] ? 'text-yellow-400' : 'text-gray-300'"
                                                    class="w-5 h-5" />
                                @endfor
                            </div>
                            <p class="text-gray-700 text-lg mb-6 italic">"{{ $testimonial['quote'] }}"</p>
                            <div class="flex items-center">
                                <img src="{{ $testimonial['avatar'] }}"
                                     alt="{{ $testimonial['name'] }}"
                                     class="w-12 h-12 rounded-full mr-4">
                                <div>
                                    <p class="font-semibold">{{ $testimonial['name'] }}</p>
                                    <p class="text-sm text-gray-600">{{ $testimonial['role'] }}, {{ $testimonial['company'] }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Navigation -->
            <button @click="activeSlide = Math.max(0, activeSlide - 1)"
                    class="absolute left-0 top-1/2 transform -translate-y-1/2 -translate-x-4 bg-white rounded-full p-2 shadow-lg hover:shadow-xl transition-shadow">
                <x-heroicon-o-chevron-left class="w-6 h-6" />
            </button>
            <button @click="activeSlide = Math.min({{ count($testimonials) - 1 }}, activeSlide + 1)"
                    class="absolute right-0 top-1/2 transform -translate-y-1/2 translate-x-4 bg-white rounded-full p-2 shadow-lg hover:shadow-xl transition-shadow">
                <x-heroicon-o-chevron-right class="w-6 h-6" />
            </button>

            <!-- Dots -->
            <div class="flex justify-center mt-6">
                @foreach ($testimonials as $index => $_)
                    <button @click="activeSlide = $index"
                            class="w-3 h-3 rounded-full mx-1 transition-colors"
                            :class="activeSlide === $index ? 'bg-brand' : 'bg-gray-300'">
                    </button>
                @endforeach
            </div>
        </div>
    </div>
</div>
```

### 3.4 Stats Counter Component
**File**: `components/blocks/stats/counter.blade.php`
**Purpose**: Display animated statistics
**Usage**:
```blade
<x-stats-counter
    :stats="[
        ['value' => 500, 'suffix' => '+', 'label' => 'Aziende Attive'],
        ['value' => 98, 'suffix' => '%', 'label' => 'Clienti Soddisfatti'],
        ['value' => 40, 'suffix' => '%', 'label' => 'Aumento Produttività'],
        ['value' => 24, 'suffix' => '/7', 'label' => 'Supporto Dedicato'],
    ]"
/>
```

**Implementation**:
```blade
<div class="grid grid-cols-2 md:grid-cols-4 gap-8 py-16">
    @foreach ($stats as $stat)
    <div x-data="counter({{ $stat['value'] }})"
         x-init="startCounting()"
         class="text-center">
        <div class="text-5xl font-bold text-brand mb-2">
            <span x-text="count"></span>{{ $stat['suffix'] }}
        </div>
        <p class="text-gray-600">{{ $stat['label'] }}</p>
    </div>
    @endforeach
</div>

<script>
function counter(target) {
    return {
        count: 0,
        interval: null,

        startCounting() {
            const duration = 2000; // 2 seconds
            const steps = 60;
            const increment = target / steps;
            const stepDuration = duration / steps;

            this.interval = setInterval(() => {
                this.count += increment;
                if (this.count >= target) {
                    this.count = target;
                    clearInterval(this.interval);
                }
            }, stepDuration);
        }
    }
}
</script>
```

### 3.5 Progress Indicator Component
**File**: `components/blocks/progress/indicator.blade.php`
**Purpose**: Visual progress indicator
**Usage**:
```blade
<x-progress-indicator
    :progress="70"
    :show-percentage="true"
    color="brand"
/>
```

**Implementation**:
```blade
<div class="w-full">
    <div class="flex justify-between mb-2">
        <span class="text-sm font-medium">{{ $label ?? 'Progress' }}</span>
        @if ($showPercentage)
            <span class="text-sm text-gray-600">{{ $progress }}%</span>
        @endif
    </div>
    <div class="w-full bg-gray-200 rounded-full h-2">
        <div class="bg-gradient-to-r from-brand-light to-brand-dark h-2 rounded-full transition-all duration-1000 ease-out"
             :style="`width: ${progress}%`"></div>
    </div>
</div>
```

---

## 4. Sidebar Components

### 4.1 Quick Links Block
**Type**: `quick-links`
**View**: `components/blocks/sidebar/quick-links.blade.php`
**Purpose**: Display quick navigation links
**Configuration**:
```json
{
  "type": "quick-links",
  "slug": "quick-links",
  "data": {
    "view": "pub_theme::components.blocks.sidebar.quick-links",
    "title": "Accesso Rapido",
    "links": [
      {
        "label": "Dashboard Admin",
        "url": "/admin",
        "icon": "heroicon-o-squares-2x2"
      },
      {
        "label": "Gestione Dipendenti",
        "url": "/employee/admin",
        "icon": "heroicon-o-users"
      },
      {
        "label": "Report e Analytics",
        "url": "/chart/admin",
        "icon": "heroicon-o-chart-bar"
      },
      {
        "label": "Documentazione",
        "url": "/docs",
        "icon": "heroicon-o-document-text"
      }
    ]
  }
}
```

**Enhancements Needed**:
- [ ] Add hover animations
- [ ] Add active state highlighting
- [ ] Add icon animations
- [ ] Support nested links
- [ ] Add search/filter

### 4.2 System Info Block
**Type**: `system-info`
**View**: `components/blocks/sidebar/system-info.blade.php`
**Purpose**: Display system information
**Configuration**:
```json
{
  "type": "system-info",
  "slug": "system-info",
  "data": {
    "view": "pub_theme::components.blocks.sidebar.system-info",
    "title": "Informazioni Sistema",
    "info": [
      {
        "label": "Versione",
        "value": "{{ app()->version() }}"
      },
      {
        "label": "Ambiente",
        "value": "{{ app()->environment() }}"
      },
      {
        "label": "Lingua",
        "value": "{{ app()->getLocale() }}"
      },
      {
        "label": "Tema",
        "value": "Two"
      }
    ]
  }
}
```

---

## 5. Utility Components

### 5.1 Language Switcher
**File**: `components/language-switcher.blade.php`
**Purpose**: Switch between supported languages
**Usage**:
```blade
<x-language-switcher />
```

**Implementation**:
```blade
<div class="flex items-center gap-2">
    @foreach(config('techplanner.supported_locales') as $locale => $config)
        <a href="{{ route('localized-home', $locale) }}"
           class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors {{ app()->getLocale() === $locale ? 'bg-brand text-white' : 'hover:bg-gray-100' }}">
            <span class="text-xl">{{ $config['flag'] }}</span>
            <span class="font-medium">{{ $config['name'] }}</span>
        </a>
    @endforeach
</div>
```

### 5.2 Cookie Consent
**File**: `components/cookie-consent.blade.php`
**Purpose**: GDPR-compliant cookie consent banner
**Usage**:
```blade
<x-cookie-consent />
```

### 5.3 AdSense Components
**File**: `components/adsense/*.blade.php`
**Purpose**: Display AdSense ads in various positions
**Variants**:
- `adsense-header.blade.php` - Header banner
- `adsense-sidebar.blade.php` - Sidebar ad
- `adsense-content.blade.php` - In-content ad
- `adsense-footer.blade.php` - Footer banner

---

## 6. Component Styling Standards

### 6.1 Color System
```css
/* Brand Colors */
--brand-light: #667eea;
--brand: #667eea;
--brand-dark: #764ba2;

/* Gradients */
--brand-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);

/* Text Colors */
--text-primary: #333333;
--text-secondary: #666666;
--text-tertiary: #999999;

/* Background Colors */
--bg-primary: #FFFFFF;
--bg-secondary: #F9FAFB;
--bg-tertiary: #F3F4F6;
```

### 6.2 Typography Scale
```css
/* Font Sizes */
.text-hero-title: 48px (mobile) / 60px (desktop)
.text-hero-subtitle: 24px (mobile) / 30px (desktop)
.text-hero-description: 18px
.text-section-title: 32px
.text-card-title: 20px
.text-body: 16px
.text-small: 14px
.text-tiny: 12px

/* Font Weights */
.font-light: 300
.font-normal: 400
.font-medium: 500
.font-semibold: 600
.font-bold: 700
```

### 6.3 Spacing System
```css
/* Spacing Scale (Tailwind) */
- px-1: 4px
- px-2: 8px
- px-3: 12px
- px-4: 16px
- px-6: 24px
- px-8: 32px
- px-12: 48px
- px-16: 64px

/* Component Spacing */
-component-padding: 16px
-section-padding: 32px
-section-padding-lg: 64px
-section-padding-xl: 96px
```

---

## 7. Component Best Practices

### 7.1 Naming Conventions
- Use kebab-case for component files
- Use PascalCase for component classes
- Use descriptive names for props
- Use consistent naming for similar components

### 7.2 Performance Optimization
- Lazy load below-fold components
- Use Alpine.js for simple interactivity
- Implement skeleton loaders
- Optimize images and assets
- Minimize component depth

### 7.3 Accessibility Standards
- Use semantic HTML elements
- Include ARIA labels where needed
- Ensure keyboard navigation works
- Maintain sufficient color contrast
- Test with screen readers

### 7.4 Responsive Design
- Mobile-first approach
- Test on all breakpoints
- Use relative units when possible
- Optimize for touch devices
- Consider landscape orientation

---

## 8. Component Documentation

### 8.1 Component Template
```markdown
## Component Name

**File**: `path/to/component.blade.php`
**Purpose**: Brief description
**Usage**:
```blade
<x-component-name :prop="$value" />
```

**Props**:
- `prop` (type, required): Description

**Slots**:
- `slot-name`: Description

**Features**:
- Feature 1
- Feature 2

**Example**:
```blade
<x-component-name>
    <x-slot name="slot-name">
        Content
    </x-slot>
</x-component-name>
```
```

---

## 9. Component Development Workflow

### 9.1 Creating New Components
1. Create component file in `resources/views/components/`
2. Implement component logic
3. Add Alpine.js if needed
4. Test component in isolation
5. Add component to library documentation
6. Create usage examples
7. Test responsive behavior
8. Test accessibility

### 9.2 Component Maintenance
1. Regular performance audits
2. Update dependencies
3. Fix bugs and issues
4. Add new features as needed
5. Keep documentation updated
6. Test on all browsers
7. Monitor usage analytics
8. Gather user feedback

---

## 10. Future Components

### Planned Components
- [ ] Video player component
- [ ] Image gallery component
- [ ] Pricing table component
- [ ] FAQ accordion component
- [ ] Team members component
- [ ] Blog post card component
- [ ] Newsletter signup component
- [ ] Social share component
- [ ] Rating/review component
- [ ] Search results component

---

## 11. Conclusion

This component library provides a comprehensive foundation for building beautiful, performant, and accessible UI components for TechPlanner. By following these standards and best practices, we ensure consistency across the application and maintain high-quality code.

### Key Principles
- **DRY**: Don't Repeat Yourself
- **KISS**: Keep It Simple, Stupid
- **Consistent**: Use consistent patterns
- **Accessible**: Ensure accessibility for all users
- **Performant**: Optimize for speed
- **Documented**: Keep documentation updated

### Success Metrics
- Component reusability > 80%
- Average component load time < 100ms
- Lighthouse accessibility score > 95
- User satisfaction > 4.5/5

---

**Document Version**: 1.0
**Last Updated**: February 6, 2026
**Next Review**: March 6, 2026
**Status**: Active Development