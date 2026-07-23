# GitHub Issue: WCAG 2.1 AA Compliance - Tema Two

## Issue Title
**WCAG 2.1 AA Compliance: Risoluzione problemi accessibilità identificati da MAUVE++ e PageSpeed Insights**

## Labels
- `accessibility`
- `wcag`
- `bug`
- `theme-two`
- `high-priority`

## Description

### Contesto
Il tema Two del progetto sottana.net necessita di correzioni per raggiungere la conformità completa WCAG 2.1 Level AA, come richiesto dalle linee guida AGID per i siti web della pubblica amministrazione italiana.

### Problemi Identificati

Dai report di audit accessibilità (MAUVE++ e PageSpeed Insights), sono stati identificati i seguenti problemi:

#### 1. H44 - Label Elements (5 occorrenze)
- **Problema**: Select e checkbox senza label associati tramite attributo `for`
- **File interessati**:
  - `resources/views/components/blocks/services/search.blade.php`
  - `resources/views/components/livewire/blog-search-filters.blade.php`
  - `resources/views/components/blocks/services/enhanced-grid.blade.php`
- **Status**: ✅ Parzialmente risolto

#### 2. F78/G195 - Focus Visible (5 occorrenze)
- **Problema**: Focus indicator rimosso o non sufficientemente visibile
- **File interessati**: CSS globale, componenti interattivi
- **Status**: ✅ Risolto

#### 3. H30 - Link Purpose (26 occorrenze)
- **Problema**: Link senza testo descrittivo o aria-label
- **File interessati**: Header, footer, componenti social
- **Status**: ✅ Risolto

#### 4. C8 - Letter-spacing (1 occorrenza)
- **Problema**: Uso eccessivo di `letter-spacing` che causa problemi di leggibilità
- **File interessati**: Footer
- **Status**: ✅ Risolto

#### 5. C38 - Reflow (5 occorrenze)
- **Problema**: Layout non si adatta correttamente a viewport 320px
- **File interessati**: CSS globale, componenti responsive
- **Status**: ✅ Risolto

#### 6. G18 - Contrast Ratio (43 occorrenze)
- **Problema**: Contrasto insufficiente tra testo e sfondo (< 4.5:1)
- **File interessati**: Footer principalmente
- **Status**: ✅ Risolto

#### 7. H98 - Autocomplete (3 occorrenze)
- **Problema**: Form inputs senza attributo `autocomplete` appropriato
- **File interessati**: Form di contatto, select di ricerca
- **Status**: ✅ Risolto

#### 8. ARIA6 - aria-label Usage (26 occorrenze)
- **Problema**: aria-label su elementi che non possono essere trovati o che sovrascrivono label native
- **File interessati**: Vari componenti
- **Status**: ⚠️ Da verificare dopo deploy

### Soluzioni Implementate

Vedi documento completo: [`docs/wcag-compliance-plan.md`](docs/wcag-compliance-plan.md)

### Testing Richiesto

- [ ] Test completo con MAUVE++ dopo deploy
- [ ] Test con screen reader (NVDA/VoiceOver)
- [ ] Test navigazione completa da tastiera
- [ ] Verifica contrasto con strumenti automatici
- [ ] Test su viewport 320px
- [ ] Verifica autocomplete con browser

### Riferimenti

- [WCAG 2.1 Quick Reference](https://www.w3.org/WAI/WCAG21/quickref/)
- [MAUVE++ Validator](https://mauve.isti.cnr.it/)
- [Documentazione Completa](docs/wcag-compliance-plan.md)

---

## GitHub Discussion

### Titolo Discussion
**WCAG 2.1 AA Compliance Strategy - Tema Two: Best Practices e Pattern Riutilizzabili**

### Categoria
`Ideas` o `Q&A`

### Contenuto

## Strategia WCAG 2.1 AA per Tema Two

### Obiettivo
Condividere best practices, pattern riutilizzabili e strategie per mantenere la conformità WCAG 2.1 AA nel tema Two.

### Pattern Riutilizzabili

#### Form Input Pattern
```blade
<div>
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 mb-2">
        {{ $label }}
        @if($required)
            <span class="text-red-500" aria-label="Campo obbligatorio">*</span>
        @endif
    </label>
    <input type="{{ $type }}"
           id="{{ $id }}"
           name="{{ $name }}"
           autocomplete="{{ $autocomplete }}"
           {{ $required ? 'required aria-required="true"' : '' }}
           aria-describedby="{{ $id }}-help"
           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E5A96] focus:border-transparent">
    <p id="{{ $id }}-help" class="sr-only">{{ $helpText }}</p>
</div>
```

#### Link con Icona Pattern
```blade
<a href="{{ $url }}" 
   aria-label="{{ $ariaLabel ?? $text }}"
   class="... focus:outline-none focus:ring-2 focus:ring-[#1E5A96] focus:ring-offset-2">
    <svg aria-hidden="true">...</svg>
    @if(!$text)
        <span class="sr-only">{{ $ariaLabel }}</span>
    @else
        {{ $text }}
    @endif
</a>
```

### Domande Aperte

1. **Gestione Focus su Componenti Dinamici**: Come gestire correttamente il focus quando componenti vengono aggiunti/rimossi dinamicamente con Alpine.js/Livewire?

2. **Testing Automatizzato**: Quali strumenti consigliate per integrare test di accessibilità nel CI/CD?

3. **Pattern per Modali**: Quali pattern ARIA sono più efficaci per modali accessibili?

### Contributi

Tutti i contributi sono benvenuti! Se avete esperienza con WCAG 2.1 AA o avete identificato altri problemi, per favore condividete.

---

**Nota**: Questo issue e discussion sono stati creati come parte di un processo collaborativo multi-agente per garantire la massima qualità e conformità accessibilità.
