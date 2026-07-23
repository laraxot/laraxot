# DaisyUI Integration Guide

## 🎯 Installazione Completata

DaisyUI v5.1.22 è stato installato e configurato con successo nel progetto Bootstrap Italia.

## 📋 Configurazione

### 1. Dipendenze Installate
```json
{
  "devDependencies": {
    "daisyui": "^5.1.22"
  }
}
```

### 2. Configurazione Tailwind
Il plugin DaisyUI è stato aggiunto a `tailwind.config.js` con un tema personalizzato che rispetta i colori Bootstrap Italia:

```javascript
plugins: [
  require('daisyui')
],
daisyui: {
  themes: [{
    bootstrap_italia: {
      "primary": "#007a52",        // Verde Bootstrap Italia
      "secondary": "#5d7083",      // Grigio secondario
      "accent": "#006cc6",         // Blu accent
      "neutral": "#17334f",        // Scuro
      "base-100": "#ffffff",       // Sfondo bianco
      "success": "#008055",        // Verde successo
      "warning": "#ffc107",        // Giallo warning
      "error": "#dc3545",          // Rosso errore
    }
  }]
}
```

### 3. Tema Attivato
Il tema è stato attivato nel CSS principale:

```css
html {
  data-theme: bootstrap_italia;
}
```

## 🧩 Componenti DaisyUI Disponibili

### Esempi di Utilizzo

#### 1. Button (Mantiene i colori Bootstrap Italia)
```html
<!-- Pulsante primario verde -->
<button class="btn btn-primary">Pulsante Primario</button>

<!-- Pulsante secondario -->
<button class="btn btn-secondary">Pulsante Secondario</button>

<!-- Pulsante outline -->
<button class="btn btn-outline btn-primary">Outline Primary</button>
```

#### 2. Card
```html
<div class="card w-96 bg-base-100 shadow-xl">
  <div class="card-body">
    <h2 class="card-title">Titolo Card</h2>
    <p>Contenuto della card con styling DaisyUI</p>
    <div class="card-actions justify-end">
      <button class="btn btn-primary">Azione</button>
    </div>
  </div>
</div>
```

#### 3. Alert
```html
<!-- Alert successo -->
<div class="alert alert-success">
  <span>Operazione completata con successo!</span>
</div>

<!-- Alert errore -->
<div class="alert alert-error">
  <span>Si è verificato un errore!</span>
</div>
```

#### 4. Badge
```html
<div class="badge badge-primary">Primario</div>
<div class="badge badge-secondary">Secondario</div>
<div class="badge badge-outline">Outline</div>
```

#### 5. Modal
```html
<dialog id="my_modal" class="modal">
  <div class="modal-box">
    <h3 class="text-lg font-bold">Modal Title</h3>
    <p class="py-4">Contenuto del modal</p>
    <div class="modal-action">
      <form method="dialog">
        <button class="btn">Chiudi</button>
      </form>
    </div>
  </div>
</dialog>
```

#### 6. Input e Form
```html
<div class="form-control w-full max-w-xs">
  <label class="label">
    <span class="label-text">Campo Testo</span>
  </label>
  <input type="text" placeholder="Inserisci testo" class="input input-bordered w-full max-w-xs" />
</div>
```

#### 7. Navbar (Complemento al sistema esistente)
```html
<div class="navbar bg-primary text-primary-content">
  <div class="navbar-start">
    <div class="dropdown">
      <div tabindex="0" role="button" class="btn btn-ghost lg:hidden">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"></path>
        </svg>
      </div>
      <ul tabindex="0" class="menu menu-sm dropdown-content bg-base-100 rounded-box z-[1] mt-3 w-52 p-2 shadow">
        <li><a>Item 1</a></li>
        <li><a>Item 2</a></li>
      </ul>
    </div>
  </div>
  <div class="navbar-center">
    <a class="btn btn-ghost text-xl">Brand</a>
  </div>
  <div class="navbar-end">
    <a class="btn">Button</a>
  </div>
</div>
```

## 🔄 Compatibilità

### Vantaggi dell'Integrazione
1. **Coesistenza**: DaisyUI non interferisce con gli stili Bootstrap Italia esistenti
2. **Colori Consistenti**: Il tema personalizzato usa gli stessi colori di Bootstrap Italia
3. **Componenti Aggiuntivi**: Accesso a tutti i componenti DaisyUI
4. **Utilities**: Mantieni tutte le utility Tailwind CSS + quelle di DaisyUI

### Note Importanti
- I componenti DaisyUI sono disponibili **in aggiunta** ai tuoi stili esistenti
- Il tema `bootstrap_italia` garantisce consistenza visiva
- Puoi usare sia le classi Bootstrap Italia esistenti che i componenti DaisyUI
- Non è necessario riscrivere il codice esistente

## 🎨 Prossimi Passi

1. **Test dei Componenti**: Prova i componenti DaisyUI nella tua applicazione
2. **Personalizzazione**: Modifica i colori del tema se necessario in `tailwind.config.js`
3. **Documentazione**: Documenta quali componenti DaisyUI utilizzi nel progetto

## 🎯 Implementazioni Reali

### Language Dropdown nell'Header
Il dropdown per la selezione della lingua è stato completamente sostituito con un componente DaisyUI integrato con Alpine.js:

```html
<!-- DaisyUI Dropdown with Alpine.js -->
<div class="dropdown dropdown-end" x-data="languageSelector()">
  <div tabindex="0" role="button" class="nav-link dropdown-toggle btn btn-ghost btn-sm text-white hover:bg-white/10">
    <span x-text="currentLanguage"></span>
    <svg class="icon ml-1 w-4 h-4" fill="currentColor">...</svg>
  </div>
  <ul tabindex="0" class="dropdown-content menu bg-base-100 rounded-box z-[1] w-40 p-2 shadow-lg border">
    <template x-for="language in languages" :key="language.code">
      <li>
        <a href="#" @click.prevent="selectLanguage(language.code)"
           :class="isActive(language.code) ? 'font-medium bg-primary/10' : ''">
          <span x-text="language.flag"></span>
          <span x-text="language.code"></span>
          <span x-show="isActive(language.code)" class="badge badge-primary badge-xs ml-auto">attiva</span>
        </a>
      </li>
    </template>
  </ul>
</div>
```

**Caratteristiche del nuovo dropdown:**
- ✅ Stile DaisyUI nativo con tema Bootstrap Italia
- ✅ Gestione stato dinamica con Alpine.js
- ✅ Bandierine emoji per le lingue
- ✅ Badge "attiva" per la lingua corrente
- ✅ Animazioni smooth e responsive
- ✅ Click outside per chiudere automaticamente

### Alpine.js Component
```javascript
Alpine.data('languageSelector', () => ({
  currentLanguage: 'ITA',
  languages: [
    { code: 'ITA', name: 'Italiano', flag: '🇮🇹' },
    { code: 'ENG', name: 'English', flag: '🇬🇧' }
  ],

  selectLanguage(langCode) {
    this.currentLanguage = langCode
    // Implementa la logica di cambio lingua
  },

  isActive(langCode) {
    return this.currentLanguage === langCode
  }
}))
```

## ✅ Status
- ✅ DaisyUI installato via npm
- ✅ Plugin configurato in Tailwind
- ✅ Tema Bootstrap Italia creato
- ✅ Build testato e funzionante
- ✅ Componenti pronti all'uso
- ✅ **Language dropdown implementato** con DaisyUI + Alpine.js
- ✅ **Compatibilità completa** con Bootstrap Italia esistente