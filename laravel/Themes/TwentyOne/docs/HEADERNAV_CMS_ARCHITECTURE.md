# HeaderNav CMS Architecture - Filosofia, Religione, Politica e Zen

**Data**: 2026-03-23  
**Stato**: ✅ OBBLIGATORIO  
**Version**: 1.0

---

## 🎯 FILOSOFIA: Il Header come Composable

### Il Principio Fondamentale

> **"Il Header non è un file PHP, è un orchestratore di blocchi CMS."**

Il sistema HeaderNav del progetto Base Predict adotta una filosofia **CMS-driven**:
- Il layout è definito da un file JSON di configurazione
- I blocchi (blocks) sono componenti modularmente riutilizzabili
- Il tema non contain hardcoded layout, ma legge la configurazione

### Perché JSON?

**APPROCCIO TRADIZIONALE** (❌):
```php
// header.blade.php
<nav>
    <logo />
    <menu />
    <search />
    <auth />
</nav>
```

**APPROCCIO CMS** (✅):
```json
// header.json
{
    "blocks": {
        "it": [
            { "type": "logo", "data": { "position": "left" }},
            { "type": "dropdown", "data": { "position": "left" }},
            { "type": "search", "data": { "position": "center" }},
            { "type": "dark_mode_toggle", "data": { "position": "right" }},
            { "type": "credits", "data": { "position": "right" }},
            { "type": "auth", "data": { "position": "right" }}
        ]
    }
}
```

---

## 📜 RELIGIONE: I 10 COMANDAMENTI DEL HEADER

### 1. Il JSON è la Sacra Scrittura

**OBBLIGATORIO**:
- Ogni header section ha un file JSON in `config/local/predict/database/content/sections/`
- Il JSON definisce blocchi, posizioni, e metadati
- Il codice PHP legge il JSON, non il contrario

### 2. Ogni Blocco è un Component

**STRUTTURA**:
```
{
    "type": "dark_mode_toggle",
    "data": {
        "view": "pub_theme::components.blocks.ui.dark-mode-toggle",
        "position": "right",
        "size": "md",
        "variant": "icon-only"
    }
}
```

- `type`: Identificativo univoco del blocco
- `data.view`: View Blade completa (namespace `pub_theme::`)
- `data.position`: `left`, `center`, `right`
- `data.*`: Parametri specifici del blocco

### 3. Il Position Raggruppamento

```php
$pos = collect($blocks)->groupBy('data.position');
```

I blocchi vengono raggruppati per posizione e renderizzati in tre colonne:
- **left**: Logo, Menu, Leaderboard
- **center**: Search
- **right**: Dark Mode, Credits, Language, Auth, Notifications

### 4. View Namespace Standard

**PATTERN**:
```
pub_theme::components.blocks.<category>.<variant>
```

**ESEMPI**:
- `pub_theme::components.blocks.logo.simple`
- `pub_theme::components.blocks.search.simple`
- `pub_theme::components.blocks.ui.dark-mode-toggle`
- `pub_theme::components.blocks.auth.simple`

### 5. Il Blocco è un Wrapper

Ogni blocco nel tema è un **wrapper** che chiama il componente UI:

```blade
{{-- Themes/TwentyOne/resources/views/components/blocks/ui/dark-mode-toggle.blade.php --}}
@props([
    'size' => 'md',
    'position' => 'header-right',
    'show_label' => false,
    'variant' => 'icon-only'
])

<div class="dark-mode-toggle-block">
    <x-ui.dark-mode-toggle 
        :size="$size"
        :position="$position" 
        :show_label="$show_label"
        :variant="$variant"
    />
</div>
```

### 6. Il Componente UI è nel Modulo

Il componente reale è nel **Modulo UI**, non nel tema:

```
Modules/UI/
└── app/
    └── View/Components/
        └── DarkModeSwitcher.php        ← Componente Logico
└── resources/views/
    └── components/
        └── dark-mode-switcher.blade.php ← View
```

### 7. Graceful Degradation

```php
@foreach($pos->get('right') as $block)
@php
    try {
        echo view($block->view, $block->data)->render();
    } catch (\Throwable $e) {
        // Skip blocks whose optional datasource is not available yet.
    }
@endphp
@endforeach
```

Se un blocco fallisce, gli altri continuano a funzionare.

### 8. Position è Obbligatorio

Ogni blocco DEVE avere `position` nel JSON:
- `left` → Prima colonna
- `center` → Colonna centrale
- `right` → Ultima colonna

### 9. Multilingua

```json
{
    "blocks": {
        "it": [...],
        "en": [...]
    }
}
```

Il sistema legge `$locale` e usa l'array appropriato.

### 10. Type è Univoco

```json
{ "type": "dark_mode_toggle" }
```

Il `type` identifica il blocco nel CMS e deve essere univoco nella section.

---

## 🏛 POLITICA: Gestione del Potere

### Chi Controlla Cosa

| Layer | Responsabilità |
|-------|----------------|
| **CMS JSON** | Quali blocchi mostrare, in che ordine, con quali parametri |
| **Theme Wrapper** | Layout, posizionamento CSS, responsive |
| **Module UI** | Comportamento, logica, accessibilità |
| **Filament** | Backend per editare il JSON |

### Il Flusso di Potere

```
Filament (Admin)
    ↓ Edita JSON
config/local/.../header.json
    ↓ Definisce blocks
<x-section slug="header" />
    ↓ Renderizza
Themes/TwentyOne/resources/views/components/sections/header.blade.php
    ↓ Legge JSON e raggruppa per position
Renderizza blocchi in ordine
    ↓ Wrapper chiama
pub_theme::components.blocks.ui.dark-mode-toggle
    ↓ Chiama componente
<x-ui.dark-mode-toggle />
    ↓ Renderizza output
Modules/UI/.../dark-mode-switcher.blade.php
```

---

## 🧘 ZEN: L'Arte della Semplificazione

### Lo Zen del Position

```php
$pos = collect($blocks)->groupBy('data.position');
```

Il codice è **minimale**:
- Una riga per raggruppare
- Tre iterazioni per left/center/right
- Zero logica di business

### Lo Zen del Fallback

```php
try {
    echo view($block->view, $block->data)->render();
} catch (\Throwable $e) {
    // Skip
}
```

Se un blocco manca, la navigazione continua. Nessun errore blocking.

### Lo Zen del Configurabile

**TUTTO è configurabile**:
- Posizione (left/center/right)
- Variante (icon-only, with-label)
- Size (sm, md, lg)
- Model (per dropdown dinamici)

**NESSUNO hardcoded**.

---

## 🌍 Dark Mode Toggle - Best Practices UX/UI

### Ricerca UX (2024-2025)

Secondo le best practices UI/UX 2025:

1. **Icona Sun/Moon**: Toggle iconico con sun (light) e moon (dark)
2. **Posizionamento**: Header-right, accanto agli altri elementi di navigazione
3. **Transizione**: Animazione fluida 200-300ms
4. **Persistenza**: localStorage per ricordare la preferenza
5. **System Preference**: Rispetta `prefers-color-scheme` come default

### Pattern Implementato

**Component**:
```blade
<button 
    x-data="{ darkMode: {{ $darkMode ? 'true' : 'false' }} }"
    @click="darkMode = !darkMode; $dispatch('darkModeUpdated', { darkMode })"
    class="flex items-center justify-center w-10 h-10 rounded-full transition-colors"
>
    <template x-if="darkMode">
        <x-heroicon-o-moon class="w-5 h-5" />
    </template>
    <template x-if="!darkMode">
        <x-heroicon-o-sun class="w-5 h-5" />
    </template>
</button>
```

**JavaScript**:
```js
const applyTheme = (theme) => {
    if (theme === 'dark') {
        html.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    } else {
        html.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }
};
```

### File Associati

| File | Ruolo |
|------|-------|
| `config/local/predict/database/content/sections/header.json` | Configurazione CMS |
| `Themes/TwentyOne/resources/views/components/sections/header.blade.php` | Renderizzazione |
| `Themes/TwentyOne/resources/views/components/blocks/ui/dark-mode-toggle.blade.php` | Wrapper |
| `Themes/TwentyOne/resources/js/dark-mode.js` | Logica JS |
| `Modules/UI/app/View/Components/DarkModeSwitcher.php` | Componente PHP |
| `Modules/UI/resources/views/components/dark-mode-switcher.blade.php` | View UI |

---

## 📋 CHECKLIST IMPLEMENTAZIONE

### Prima di Aggiungere un Blocco

- [ ] Il blocco ha un `type` univoco nel JSON?
- [ ] Il blocco ha una `position` (left/center/right)?
- [ ] La view segue il pattern `pub_theme::components.blocks.*`?
- [ ] Il wrapper passa tutte le props al componente?
- [ ] Il componente è accessibile (ARIA, focus)?

### Per Dark Mode Toggle

- [ ] Icona sun per light mode?
- [ ] Icona moon per dark mode?
- [ ] Transizione CSS fluida?
- [ ] localStorage persistence?
- [ ] System preference fallback?
- [ ] Posizione in header-right?

---

## 📚 RIFERIMENTI

### Interni
- `Themes/TwentyOne/docs/THEME_PHILOSOPHY_ZEN.md` - Filosofia del tema
- `Themes/TwentyOne/docs/THEME_ARCHITECTURE_ZEN_BLADE_NAMING.md` - Naming convention
- `Modules/Predict/docs/ARCHITECTURE_PHILOSOPHY_ZEN.md` - Filosofia del modulo
- `docs/project/CMS_JSON_ARCHITECTURE.md` - Pattern CMS JSON

### Esterni
- [UI Design Best Practices 2025](https://www.uinkits.com/blog-post/best-dark-mode-ui-design-examples-and-best-practices-in-2025)
- [Tailwind Dark Mode Guide](https://magicui.design/blog/tailwind-dark-mode)
- [LogRocket Dark Mode UX](https://blog.logrocket.com/ux-design/dark-mode-ui-design-best-practices-and-examples)

---

**Ultimo Aggiornamento**: 2026-03-23
**Stato**: ✅ OBBLIGATORIO
**Enforcement**: Code Review + Pre-commit Hook
