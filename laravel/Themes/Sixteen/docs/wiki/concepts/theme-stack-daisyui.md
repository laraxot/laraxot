---
name: Theme Stack - DaisyUI + Tailwind + Alpine + Lit
description: Architettura frontend per Sixteen theme con DaisyUI, Design Comuni Italia e componenti moderni
metadata:
  type: decision
---

# Theme Stack - DaisyUI + Tailwind + Alpine + Lit

## Stack Tecnologico

```yaml
Sixteen Theme:
  ├── DaisyUI 4.x                 # Componenti UI pronti (buttons, cards, modals)
  ├── Tailwind CSS v4             # Utilities e personalizzazione
  ├── Alpine.js                   # Interattività lato client (Livewire/Filament già carica)
  ├── Lit 3                      # Componenti complessi (mappe, coordinate picker)
  └── Design Comuni Italia       # Naming convention e semantica HTML
```

## Principi Fondamentali

1. **Mantenere Naming Consistency**
   - Classi CSS: `btn`, `btn-primary`, `form-control`, `card`, `modal`
   - HTML semantico: `<button>`, `<form>`, `<dialog>`, `<section>`
   - Struttura identica a Design Comuni Italia

2. **Component Mapping**
   ```javascript
   // Bootstrap Italia → DaisyUI + Alpine
   Bootstrap Dropdown → DaisyUI Dropdown + Alpine
   Bootstrap Modal → DaisyUI Modal + Alpine
   Bootstrap Carousel → DaisyUI Carousel + Alpine
   ```

3. **Responsabilità Chiare**
   - DaisyUI: Componenti base e stili
   - Tailwind: Customizzazione e utilities
   - Alpine: Interactivity leggera
   - Lit: Componenti complessi e custom
   - Filament: Form e admin interface

## Implementazione

### Import DaisyUI in Vite
```javascript
// Themes/Sixteen/resources/js/app.js
import 'daisyui/dist/full.css'
import '@tailwindcss/typography'
```

### Configurazione Tailwind
```javascript
// Themes/Sixteen/tailwind.config.js
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./node_modules/daisyui/dist/**/*.css"
  ],
  theme: {
    extend: {},
  },
  plugins: [require("daisyui")],
  daisyui: {
    themes: ["design-comuni", "dark"],
  }
}
```

### Componenti DaisyUI nel Tema
```html
<!-- Esempio: Button Design Comuni Italia -->
<button class="btn btn-primary">
  <span class="icon icon-primary">plus</span>
  Aggiungi Segnalazione
</button>

<!-- Esempio: Card -->
<div class="card">
  <div class="card-body">
    <h2 class="card-title">Titolo Card</h2>
    <p class="card-text">Contenuto della card</p>
  </div>
</div>
```

## Migrazione Progressiva

1. **Fase 1**: Sostituire CSS di Bootstrap Italia con DaisyUI
2. **Fase 2**: Rimpiazzare JS di Bootstrap con Alpine
3. **Fase 3**: Ottimizzare con Tailwind utilities dove necessario
4. **Fase 4**: Mantenere solo componenti custom in Lit

## Best Practice: @apply per Alias Puliti

**Perché @apply è la soluzione migliore:**
- **Pulizia HTML**: Mantiene le classi semantiche (`btn`, `card`) senza duplicazioni
- **Centralizzazione**: Tutti gli stili sono definiti in un unico file/config
- **Manutenibilità**: Modificare uno stile in un punto si propaga ovunque
- **Performance**: Tailwind compila solo le classi utilizzate, senza overhead

**Esempio di implementazione:**

```javascript
// Themes/Sixteen/tailwind.config.js
module.exports = {
  theme: {
    extend: {
      // Alias per classi Bootstrap Italia
      'btn': {
        '@apply': ['rounded-lg', 'px-4', 'py-2', 'font-medium', 'text-white', 'bg-blue-600', 'hover:bg-blue-700', 'transition-colors', 'duration-200']
      },
      'btn-primary': {
        '@apply': ['btn', 'bg-blue-600']
      },
      'btn-secondary': {
        '@apply': ['btn', 'bg-gray-600']
      },
      'card': {
        '@apply': ['bg-white', 'border', 'border-gray-200', 'rounded-lg', 'p-4', 'shadow-sm']
      },
      'form-control': {
        '@apply': ['block', 'w-full', 'mt-1', 'mb-2', 'p-2', 'border', 'border-gray-300', 'rounded', 'focus:ring-2', 'focus:ring-blue-500', 'focus:border-blue-500']
      },
      // Aggiungere altri alias come necessario...
    }
  },
  plugins: [require("daisyui")],
  daisyui: {
    themes: ["design-comuni", "dark"],
  }
}
```

**Utilizzo nei template Blade:**
```html
<!-- Stesse classi di Bootstrap Italia, ma con stile Tailwind -->
<button class="btn btn-primary">Salva</button>
<div class="card">
  <div class="card-body">Contenuto</div>
</div>
<input type="text" class="form-control" placeholder="Nome">
```

Questo approccio garantisce:
- ✅ Compatibilità al 100% con le classi esistenti
- ✅ Stile moderno con Tailwind
- ✅ Facile manutenzione
- ✅ Performance ottimizzate

## Vantaggi

- **Consistenza**: Classi riconoscibili per chi conosce Bootstrap Italia
- **Modernità**: Stack aggiornato con performance migliori
- **Mantenibilità**: Meno CSS custom, più componenti riutilizzabili
- **Personalizzazione**: Controllo totale con Tailwind DaisyUI plugins

## Risorse

- [DaisyUI Documentation](https://daisyui.com/)
- [Design Comuni Italia](https://github.com/italia/design-comuni-pagine-statiche)
- [Tailwind CSS v4](https://tailwindcss.com/docs/v4-beta)
