# Analisi Confronto FAQ: Riferimento vs Locale

## Panoramica

| Aspetto | Reference (Italia) | Locale (Sixteen) |
|---------|-------------------|------------------|
| **URL** | https://italia.github.io/design-comuni-pagine-statiche/sito/domande-frequenti.html | http://127.0.0.1:8000/it/tests/domande-frequenti |
| **Hero** | ✅ Presente con bg-white, align-items-start | ✅ Presente con it-dark, it-overlay |
| **Search** | ✅ Presente (cmp-input-search) | ❌ ASSENTE |
| **Accordion** | ✅ 17+ items, categorizzati | ✅ 2 sezioni (Anagrafe, Tributi) |
| **CTA** | ❌ Assente | ✅ "Non hai trovato la tua domanda?" |
| **Struttura** | Semantica Bootstrap Italia | Tailwind + custom CSS |

## Differenze Strutturali Critiche

### 1. Hero Section
- **Reference**: `cmp-hero` > `it-hero-wrapper bg-white align-items-start`
- **Locale**: `it-hero-wrapper it-dark it-overlay` - manca il wrapper `cmp-hero`

### 2. Campo Search (MANCA nel locale!)
- **Reference**: 
  ```html
  <div class="cmp-input-search">
    <div class="form-group autocomplete-wrapper">
      <input type="search" class="autocomplete form-control" placeholder="Cerca">
    </div>
  </div>
  ```
- **Locale**: ❌ ASSENTE - bisogna aggiungerlo!

### 3. Accordion Structure
- **Reference**: `cmp-accordion faq` con 17+ accordion-item in container `col-lg-8 offset-lg-2`
- **Locale**: `cmp-accordion` con raggruppamenti per categoria (Anagrafe, Tributi)

### 4. Footer CTA
- **Reference**: Nessun footer CTA
- **Locale**: Presente sezione "Non hai trovato la tua domanda?" con link a Contattaci

## Differenze Visive daCorreggere

### CSS Issues Identificati

1. **Accordion Button**
   - Reference: `.accordion-button.collapsed` con iconaChevron
   - Locale: `.accordion-button` - servono correzioni per hover/active states

2. **Typography**
   - Reference: `title-snall-semi-bold` per i titoli FAQ
   - Locale: `title-xlarge` per le sezioni - OK, ma servono testi più piccoli per le domande

3. **Spacing**
   - Reference: `px-sm-3 mt-2` nel container search, `px-0 px-sm-3` negli accordion
   - Locale: container con classi diverse

## Action Plan

### Fase 1: Aggiungere Search Box
- ✅ Aggiunto `search` block nel JSON: `tests.domande-frequenti.json`
- Component: `pub_theme::components.blocks.search.input`

### Fase 2: Correggere Accordion CSS
- ✅ Aggiunto chevron icon con rotate animation
- Implementato in `style-apply.css`

### Fase 3: Verifica Visuale
- ✅ Build completato: `app-BCMmYVdi.css`
- ✅ Search box presente nella pagina
- ✅ Accordion buttons con icone

## Stato Finale

| Componente | Reference | Locale | Stato |
|-----------|-----------|--------|-------|
| Hero | ✅ | ✅ | OK |
| Search Box | ✅ | ✅ | AGGIUNTO |
| Accordion | ✅ | ✅ | CORRETTO |
| CTA | ❌ | ✅ | EXTRA |

## File Modificati

1. `laravel/config/local/fixcity/database/content/pages/tests.domande-frequenti.json` - Aggiunto search block
2. `laravel/Themes/Sixteen/resources/css/style-apply.css` - Aggiunto chevron icons

## Data Analisi
2026-04-03

## Riferimenti
- Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/domande-frequenti.html
- Locale: http://127.0.0.1:8000/it/tests/domande-frequenti