# ERRORE CRITICO: index.blade.php - Violazione Homepage Governance

**Data**: 2026-03-19  
**Gravità**: CRITICAL  
**File**: `Themes/TwentyOne/resources/views/pages/index.blade.php`

---

## Errore Commesso

Il file `index.blade.php` è stato polluto con ~1000 righe di CSS inline e ~100 righe di JS inline, violando pesantemente le regole del progetto.

### Violazioni Specifiche

| Regola | Violazione |
|--------|------------|
| Homepage Governance #2 | File con 1000+ righe invece di 10-15 righe minimali |
| Theme Agnosticism | Contenuto Predict-specifico hardcoded (statistiche, claim) |
| Translation Prototype | Testo hardcoded invece di `__('<namespace>::<contesto>.<collezione>.<key>.<tipo>')` |
| CSS Inline vietato | `@push('styles')` con 800+ righe di CSS |
| JS Inline vietato | `@push('scripts')` con 90+ righe di JS |

### Pattern Errato (DA NON USARE)

```blade
<x-layouts.app>
    @volt('home')
    <div class="cinematic-page">
        {{-- 1000 righe di CSS inline --}}
        @push('styles')
        <style>
        /* VIOLAZIONE: Centinaia di righe di CSS inline */
        .cinematic-loader-ring { ... }
        /* ... 800+ righe ... */
        </style>
        @endpush
        
        {{-- Contenuto hardcoded Predict-specifico --}}
        <h1>Prevedi il Futuro</h1>
        <span class="counter" data-target="500">0</span>+
        
        {{-- 100 righe di JS inline --}}
        @push('scripts')
        <script>
        // VIOLAZIONE: JS inline
        const counters = document.querySelectorAll('.counter');
        </script>
        @endpush
    </div>
    @endvolt
</x-layouts.app>
```

### Pattern Corretto (DA USARE)

```blade
<x-layouts.app>
    @volt('home')
    <div>
        <x-page side="content" slug="home" />
    </div>
    @endvolt
</x-layouts.app>
```

---

## Contenuto Errato Presente

### Testo Hardcoded (da tradurre)

| Errato | Corretto |
|--------|----------|
| `Prevedi il Futuro` | `__('predict::home.hero.title.label')` |
| `Unisciti alla più grande community...` | `__('predict::home.hero.subtitle.label')` |
| `Esplora i Mercati` | `__('predict::home.hero.cta_explore.label')` |
| `Scopri di Più` | `__('predict::home.hero.cta_learn.label')` |
| `Mercati Attivi` | `__('predict::home.stats.markets.label')` |
| `Utenti` | `__('predict::home.stats.users.label')` |

### CSS Inline Errato

Tutto il CSS cinematico (`.cinematic-loader-ring`, `.cinematic-particle`, `.cinematic-orb`, `.animate-gradient`, ecc.) doveva essere in `app.css` del tema come classi riutilizzabili.

### JS Inline Errato

Tutto il JS (counter animation, mouse following, parallax) doveva essere in `app.js` del tema.

---

## Correzione Applicata

### Step 1: Riscrivere index.blade.php

```blade
{{-- Homepage Canonica - CMS-driven --}}
<x-layouts.app title="{{ __('predict::home.seo.title.label') }}">
    @volt('home')
    <div>
        <x-page side="content" slug="home" />
    </div>
    @endvolt
</x-layouts.app>
```

### Step 2: Muovere CSS in app.css

Il CSS cinematico è stato aggiunto a `Themes/TwentyOne/resources/css/app.css` come classi utility riutilizzabili:

- `.cinematic-*` classes
- `.card-kinetic`
- `.btn-kinetic`
- Animation keyframes

### Step 3: Muovere JS in app.js

Il JS cinematico è stato aggiunto a `Themes/TwentyOne/resources/js/app.js` per scroll-reveal e kinetic effects.

### Step 4: Definire contenuti in JSON

I contenuti homepage sono in `config/local/predict/database/content/pages/home.json` configurabili da Filament Builder.

---

## Riferimenti Documentazione

- [homepage-governance.md](homepage-governance.md) - Regole homepage
- [translation-prototype-rule.md](../../docs/project/translation-prototype-rule.md) - Pattern traduzioni
- [KINETIC_WEB_DESIGN_SPEC.md](KINETIC_WEB_DESIGN_SPEC.md) - Specifica kinetic design
- [docs/project/CMS_BLOCKS_FILAMENT_BUILDER_ARCHITECTURE.md](../../docs/project/CMS_BLOCKS_FILAMENT_BUILDER_ARCHITECTURE.md) - CMS con Filament Builder

---

## Lezione Appresa

### Regole Fondamentali

1. **MAI usare `@push('styles')` con centinaia di righe** - Il CSS va in `app.css`
2. **MAI usare `@push('scripts')` con decine di righe** - Il JS va in `app.js`
3. **MAI hardcodare testo** - Usa sempre `__('<namespace>::...')`
4. **Il tema deve essere agnostico** - Non contiene logica Predict-specifica
5. **JSON-driven** - Contenuti in file JSON configurabili

### Template @push('styles/scripts')

Usare `@push` SOLO per:
- Override temporanei di debug
- CSS critico inline per Above-the-fold
- Casi veramente estremi documentati

**NON USARE MAI per:**
- Animazioni complesse (vanno in app.css)
- Effetti JS persistenti (vanno in app.js)
- Contenuti configurabili (vanno in JSON)

---

**Aggiornato**: 2026-03-19  
**Stato**: CORRETTO
