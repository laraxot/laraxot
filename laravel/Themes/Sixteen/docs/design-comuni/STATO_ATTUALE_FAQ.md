# Stato Attuale FAQ - Analisi e Piano di Fix

## Data: 2026-04-03

## 📊 Stato Attuale

| Metrica | Valore | Target |
|---------|--------|--------|
| HTML righe | 904 | 1303 |
| Match % | 69.4% | 90%+ |
| Differenza | -399 righe | <130 righe |

## ❌ Problemi Identificati

### 1. Accordion Blade Template SBAGLIATO

**File**: `laravel/Themes/Sixteen/resources/views/components/blocks/accordion/default.blade.php`

**Problema**: Il file è stato sovrascritto con una versione che:
- ❌ Usa SVG inline invece di sprite SVG (`<path d="M7 10l5 5 5-5H7z"/>`)
- ❌ Ha struttura diversa dal reference (manca `button-wrapper`, `icon-wrapper`)
- ❌ Classi Tailwind dirette invece di classi Bootstrap Italia
- ❌ Manca `title-snall-semi-bold py-3`
- ❌ Manca icona `it-expand` dallo sprite
- ❌ Usa `x-transition` invece di CSS max-height
- ❌ Struttura HTML non match reference

**Reference Structure**:
```html
<button class="accordion-button collapsed title-snall-semi-bold py-3">
  <div class="button-wrapper">
    Domanda
    <div class="icon-wrapper">
      <svg class="icon icon-xs me-1 icon-primary">
        <use href="#it-expand"></use>
      </svg>
      <span class=""></span>
    </div>
  </div>
</button>
```

**Current Structure** (SBAGLIATA):
```html
<button class="accordion-button w-full px-4 py-4 ...">
  <span>Domanda</span>
  <svg class="w-5 h-5 ..." viewBox="0 0 24 24">
    <path d="M7 10l5 5 5-5H7z"/>
  </svg>
</button>
```

### 2. Alpine.js NON Funziona

**Sintomi**:
- ❌ Icona freccia non appare
- ❌ Accordion non si apre al click
- ❌ Attributi `@click`, `:class`, `x-show` non processati

**Possibili Cause**:
1. Alpine.js caricato DOPO il parsing HTML (deve essere nel `<head>`)
2. `Alpine.start()` chiamato troppo tardi
3. Conflitto con altri script

### 3. CSS Mancanti

**File**: `laravel/Themes/Sixteen/resources/css/components/design-comuni.css`

**Classi Mancanti**:
- ❌ `.title-snall-semi-bold` (typo intenzionale dal reference)
- ❌ `.button-wrapper` (flex layout)
- ❌ `.icon-wrapper` (icon positioning)
- ❌ `.cmp-accordion.faq` styles
- ❌ `[x-cloak]` style

## 🎯 Piano di Fix

### Priorità 1: Fix Accordion Blade Template

1. ✅ Ripristinare struttura corretta con `button-wrapper` + `icon-wrapper`
2. ✅ Usare sprite SVG `#it-expand` invece di inline path
3. ✅ Aggiungere classi Bootstrap Italia corrette
4. ✅ Mantenere Alpine.js per interattività
5. ✅ IDs corretti (`headingfaq-N`, `collapsefaq-N`)

### Priorità 2: Fix Alpine.js Loading

1. ⏳ Verificare che Alpine sia nel `<head>`
2. ⏳ Verificare `Alpine.start()` chiamato correttamente
3. ⏳ Testare interattività nel browser

### Priorità 3: CSS Completi

1. ⏳ Aggiungere tutte le classi mancanti
2. ⏳ Verificare `[x-cloak]`
3. ⏳ Testare transizioni

### Priorità 4: Test Finale

1. ⏳ Screenshot comparativi
2. ⏳ Test interattività
3. ⏳ Test responsive
4. ⏳ Aggiornare documentazione

## 📝 File da Modificare

1. ✅ `laravel/Themes/Sixteen/resources/views/components/blocks/accordion/default.blade.php`
2. ⏳ `laravel/Themes/Sixteen/resources/css/components/design-comuni.css`
3. ⏳ `laravel/Themes/Sixteen/resources/js/app.js` (verificare Alpine loading)
4. ⏳ `laravel/Themes/Sixteen/resources/views/components/layouts/main.blade.php` (spostare JS nel head?)

## 📊 Target Finale

| Componente | Attuale | Target |
|-----------|---------|--------|
| Breadcrumb | ✅ 100% | ✅ 100% |
| Hero | ✅ 98% | ✅ 100% |
| Search | ✅ 90% | ✅ 95% |
| **Accordion** | **❌ 40%** | **✅ 95%** |
| **Totale** | **69.4%** | **✅ 90%+** |

---

**Stato**: ⚠️ CRITICO - Accordion da rifare completamente  
**Prossimo Step**: Fix blade template accordion
