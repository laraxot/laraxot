# Alpine.js Integration Guide

## 🎯 Setup Completato

Alpine.js v3.15.0 è stato installato e configurato con successo nel progetto Bootstrap Italia.

## 📋 Configurazione

### 1. Installazione
```bash
npm install alpinejs
```

### 2. File app.js Popolato
Il file `/src/app.js` ora contiene:
- Import di Alpine.js come modulo ES6
- Configurazione di tutti i componenti Bootstrap Italia
- Inizializzazione di Alpine

### 3. HTML Aggiornato
- ✅ Rimosso CDN Alpine.js: `<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>`
- ✅ Aggiunto `type="module"` al tag script: `<script src="/src/app.js" type="module"></script>`

## 🧩 Componenti Alpine.js Disponibili

### 1. Dropdown
```html
<div x-data="dropdown()">
  <button @click="toggle()">Menu</button>
  <div x-show="open" @click.outside="close()">
    <!-- Dropdown content -->
  </div>
</div>
```

### 2. Mobile Navigation
```html
<div x-data="mobileNav()">
  <button @click="toggle()" class="custom-navbar-toggler">
    <svg class="icon">...</svg>
  </button>
  <div x-show="isOpen" class="navbar-collapsable">
    <!-- Mobile menu content -->
  </div>
</div>
```

### 3. Tabs
```html
<div x-data="tabs()">
  <ul class="nav nav-tabs">
    <li class="nav-item">
      <a @click="setActiveTab('tab1')"
         :class="isActive('tab1') ? 'nav-link active' : 'nav-link'">
        Tab 1
      </a>
    </li>
  </ul>
  <div class="tab-content">
    <div x-show="isActive('tab1')" class="tab-pane">
      Content 1
    </div>
  </div>
</div>
```

### 4. Modal
```html
<div x-data="modal()">
  <button @click="open()">Open Modal</button>

  <div x-show="isOpen" class="modal" @click="closeOnClick($event)">
    <div class="modal-dialog">
      <div class="modal-content">
        <button @click="close()" class="btn-close"></button>
        <!-- Modal content -->
      </div>
    </div>
  </div>
</div>
```

### 5. Accordion
```html
<div x-data="accordion()">
  <div class="accordion-item">
    <button @click="toggle('section1')" class="accordion-button">
      <span>Section 1</span>
      <svg :class="isOpen('section1') ? 'rotate-180' : ''">...</svg>
    </button>
    <div x-show="isOpen('section1')" class="accordion-collapse">
      <!-- Accordion content -->
    </div>
  </div>
</div>
```

### 6. Rating System
```html
<div x-data="rating()">
  <fieldset class="rating">
    <template x-for="star in [1,2,3,4,5]" :key="star">
      <label @click="setRating(star)"
             @mouseenter="hover(star)"
             @mouseleave="resetHover()"
             :class="getStarClass(star)"
             class="rating-star">
        <svg>...</svg>
      </label>
    </template>
  </fieldset>

  <div x-show="showForm">
    <!-- Rating feedback form -->
  </div>

  <div x-show="submitted">
    <p>Grazie per il tuo feedback!</p>
  </div>
</div>
```

### 7. Search
```html
<div x-data="search()">
  <input x-model="query"
         @input="updateSuggestions()"
         @keydown.enter="search()"
         placeholder="Cerca nel sito">

  <div x-show="filteredSuggestions.length > 0">
    <ul>
      <template x-for="suggestion in filteredSuggestions" :key="suggestion">
        <li x-text="suggestion" @click="query = suggestion; search()"></li>
      </template>
    </ul>
  </div>
</div>
```

### 8. Category Filters (Per Segnalazioni)
```html
<div x-data="categoryFilters()">
  <fieldset>
    <legend>Categoria</legend>
    <div class="categoy-list">
      <ul>
        <li>
          <div class="checkbox-body">
            <input type="checkbox"
                   id="water"
                   @change="toggleCategory('acqua')"
                   :checked="isSelected('acqua')">
            <label for="water">Acqua, allagamenti (21)</label>
          </div>
        </li>
      </ul>
    </div>
  </fieldset>

  <div x-show="hasActiveFilters">
    <button @click="clearAllFilters()">Rimuovi tutti i filtri</button>
  </div>

  <div x-text="`${totalResults} Risultati`"></div>
</div>
```

## 🎮 Funzionalità Avanzate

### Keyboard Navigation
- **ESC**: Chiude modali e dropdown aperti
- **Enter**: Conferma ricerche e azioni

### Accessibility
- Gestione automatica del focus
- Supporto per screen readers
- Aria attributes dinamici

### Performance
- Alpine.js carica solo quando necessario
- Gestione efficiente dello stato
- Event listener ottimizzati

## 🔧 Personalizzazione

### Aggiungere Nuovi Componenti
```javascript
// In app.js, dentro document.addEventListener('alpine:init', () => {})
Alpine.data('mioComponente', () => ({
  proprieta: 'valore',

  metodo() {
    // Logica del componente
  }
}))
```

### Debugging
Alpine è disponibile globalmente come `window.Alpine` per debugging:
```javascript
// Console del browser
Alpine.version // Versione corrente
document.querySelector('[x-data]')._x_dataStack[0] // Stato componente
```

## ✅ Benefici dell'Integrazione

1. **No CDN**: Controllo completo delle dipendenze
2. **Bundle Ottimizzato**: Solo il codice necessario
3. **Type Safety**: Possibilità di aggiungere TypeScript
4. **Performance**: Caricamento più veloce
5. **Offline Support**: Funziona anche offline
6. **Version Control**: Versione fissa e controllata

## 🚀 Utilizzo Pratico

Per utilizzare questi componenti nell'HTML esistente, aggiungi semplicemente:

1. L'attributo `x-data` con il nome del componente
2. Gli attributi Alpine.js necessari (`x-show`, `@click`, etc.)
3. Le classi CSS Bootstrap Italia esistenti

Esempio completo nella pagina delle segnalazioni:
```html
<!-- Mobile filter button -->
<button x-data="modal()"
        @click="open()"
        class="btn p-0 pe-2 d-lg-none">
  <span>Filtra</span>
</button>
```

Il sistema è completamente compatibile con Bootstrap Italia e non richiede modifiche al CSS esistente!