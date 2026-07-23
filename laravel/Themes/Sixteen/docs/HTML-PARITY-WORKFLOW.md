# HTML Parity Workflow

Guida completa per raggiungere la parità strutturale HTML (≥90%) con le pagine Design Comuni.

---

## Panoramica

Questo documento descrive il flusso di lavoro per verificare e migliorare la parità HTML tra le pagine reference di Design Comuni e le nostre implementazioni locali.

### Obiettivo

Raggiungere **≥90% di parità strutturale** HTML confrontando:
- **Reference**: `https://italia.github.io/design-comuni-pagine-statiche/sito/<pagina>.html`
- **Local**: `http://127.0.0.1:8000/it/tests/<pagina>`

---

## Strumenti di Confronto

### 1. html-structure-compare.sh (Bash - Veloce)

Script bash per confronto rapido con scoring basato su firma elementi.

```bash
./bashscripts/html/html-structure-compare.sh segnalazione-dettaglio
```

**Cosa fa:**
- Estrae il BODY HTML pulito (no script, no style)
- Estrae struttura elementi (tag + classi + ID)
- Conta elementi per tag
- Estrae ID semantici
- Estrae tag HTML5 semantici
- Genera report Markdown con parity score

**Output:**
```
laravel/Themes/Sixteen/docs/prompts/<pagina>/body-structure-comparison/
├── reference.html          # HTML reference pulito
├── local.html              # HTML local pulito
├── reference-structure.txt # Firma elementi reference
├── local-structure.txt     # Firma elementi local
├── reference-elements.txt  # Conteggio tag reference
├── local-elements.txt      # Conteggio tag local
├── reference-ids.txt       # Lista ID reference
├── local-ids.txt           # Lista ID local
├── reference-semantic.txt  # Tag semantici reference
├── local-semantic.txt      # Tag semantici local
├── diff.txt                # Diff unificato
└── report.md               # Report completo
```

### 2. compare-html-body.py (Python - Dettagliato)

Script Python per analisi approfondita con parsing HTML completo.

```bash
python3 bashscripts/html/compare-html-body.py segnalazione-dettaglio
```

**Cosa fa:**
- Parsing HTML con HTMLParser Python
- Estrae elemento per elemento: tag, classi, ID
- Calcola parity score preciso
- Genera report JSON + Markdown

**Output:**
```
laravel/Themes/Sixteen/docs/prompts/<pagina>/body-structure-comparison/
├── reference.html           # HTML reference pulito
├── local.html               # HTML local pulito
├── reference-structure.json # Struttura completa reference
├── local-structure.json     # Struttura completa local
└── report.md                # Report dettagliato
```

---

## Come Interpretare il Report

### Parity Score

| Score | Valutazione | Descrizione |
|-------|-------------|-------------|
| ≥90% | ✅ PASS | Struttura quasi identica |
| 70-89% | ⚠️ PARZIALE | Buona base, mancano alcuni elementi |
| <70% | ❌ FAIL | Significative differenze strutturali |

### Sezioni del Report

1. **Riepilogo**: Metriche generali (elementi totali, comuni, mancanti, extra)
2. **Valutazione**: PASS/PARZIALE/FAIL con descrizione
3. **ID Semantici**: Lista ID reference vs local, evidenzia mancanti
4. **Tag Semantici HTML5**: Conta di header, main, footer, nav, ecc.
5. **Conteggio Elementi**: Top 20 tag più utilizzati
6. **Classi CSS**: Top 20 classi più utilizzate
7. **Elementi Mancanti**: Lista elementi nel reference ma non nel local
8. **Elementi Extra**: Lista elementi nel local ma non nel reference

---

## Flusso di Lavoro

### Step 1: Esegui Confronto

```bash
# Scegli uno dei due script
./bashscripts/html/html-structure-compare.sh segnalazione-dettaglio
# OPPURE
python3 bashscripts/html/compare-html-body.py segnalazione-dettaglio
```

### Step 2: Analizza Report

```bash
cat laravel/Themes/Sixteen/docs/prompts/segnalazione-dettaglio/body-structure-comparison/report.md
```

### Step 3: Identifica Problemi

Controlla nel report:
- **ID Mancanti**: Quali ID semantici mancano nel local?
- **Tag Semantici**: Reference ha `<header>`, local no?
- **Classi CSS**: Reference usa `container row col-12`, local usa flex custom?

### Step 4: Correggi Implementazione

Modifica i blocchi JSON o componenti blade per:
- Aggiungere ID mancanti
- Usare tag semantici corretti
- Usare stesse classi Bootstrap del reference

### Step 5: Ripeti

Riesegui lo script fino a raggiungere ≥90%

---

## Cosa Confrontare

### ✅ INCLUSO (Deve Corrispondere)

- **Tag HTML**: Tutti i tag dentro `<body>`
- **Classi CSS**: Nomi delle classi (Bootstrap Italia)
- **ID**: Identificatori semantici
- **Tag Semantici**: `<header>`, `<main>`, `<footer>`, `<nav>`, `<article>`, `<section>`, `<aside>`
- **Struttura**: Gerarchia degli elementi
- **Attributi data-**: `data-element`, `data-bs-toggle` (MAI usare data-bs-* nel nostro caso)

### ❌ ESCLUSO (Non Conta)

- **Tag `<script>`**: JavaScript esterno
- **Tag `<style>`**: CSS inline
- **Commenti HTML**: `<!-- commento -->`
- **Whitespace**: Spazi, newlines
- **Testo**: Contenuto testivo (gestito da traduzioni)
- **Attributi href/src**: URL specifici

---

## Regole Chiave

### 1. Bootstrap Classes SI, Bootstrap CSS/JS NO

✅ **FARE:**
```html
<!-- Stesse classi Bootstrap del reference -->
<div class="container">
  <div class="row">
    <div class="col-12">
      <button class="btn btn-primary">...</button>
    </div>
  </div>
</div>
```

```css
/* style-apply.css - Styling con Tailwind @apply */
.container {
  @apply max-w-7xl mx-auto px-4 sm:px-6 lg:px-8;
}
.row {
  @apply flex flex-wrap -mx-4;
}
.col-12 {
  @apply w-full px-4;
}
```

❌ **NON FARE:**
```html
<!-- MAI caricare Bootstrap CSS/JS -->
<link href="bootstrap-italia.min.css" rel="stylesheet"> <!-- NO -->
<script src="bootstrap-italia.bundle.min.js"></script> <!-- NO -->

<!-- MAI usare data-bs-* -->
<div data-bs-toggle="collapse">...</div> <!-- NO, usa Alpine.js -->

<!-- MAI inventare classi custom al posto di Bootstrap -->
<div class="my-custom-container">...</div> <!-- NO, usa container -->
```

### 2. Traduzioni Multilingua

✅ **CORRETTO:**
```blade
{{ __('fixcity::segnalazione.fields.title.label') }}
```

❌ **SBAGLIATO:**
```blade
Invia  <!-- NO: hardcoded Italian -->
Submit <!-- NO: hardcoded English -->
{{ __('fixcity::segnalazione.title') }} <!-- NO: manca .collection. -->
{{ 'SEGNALAZIONE::SEGNALAZIONE.ELENCO.TITLE' }} <!-- NO: namespace sbagliato -->
```

**Formato traduzioni:** `namespace::contesto.collezione.elemento.tipo`

Esempi corretti:
- `fixcity::segnalazione.fields.title.label`
- `fixcity::rating.fields.star.5.label`
- `fixcity::servizio.heading.description.label`

### 3. Layout Corretto

✅ **CORRETTO:**
```blade
<x-layouts.app :title="$pageTitle">
  @volt('tests.view')
  <div class="cms-view-wrapper">
    @php
      $blocks = \Modules\Cms\Models\Page::getBlocksBySlug($this->pageSlug, 'content');
    @endphp
    <div class="page-content content" data-slug="{{ $this->pageSlug }}" data-side="content">
      @foreach($blocks as $block)
        @include($block->view, array_merge(['data' => []], $block->data))
      @endforeach
    </div>
  </div>
  @endvolt
</x-layouts.app>
```

❌ **SBAGLIATO:**
```blade
<x-layouts.design-comuni>  <!-- NO: layout non deve esistere -->
@php ... @endphp  <!-- NO: logica inline, usa Volt component -->
```

---

## Esempio Pratico

### Confronto per segnalazione-dettaglio

```bash
# Esegui confronto
python3 bashscripts/html/compare-html-body.py segnalazione-dettaglio

# Leggi report
cat laravel/Themes/Sixteen/docs/prompts/segnalazione-dettaglio/body-structure-comparison/report.md
```

### Interpretazione

Se il report dice:
```
Parity Score: 65%
ID Mancanti: main-container, footer, search-modal
Tag Semantici: reference ha <header>, <main>, <footer>; local no
```

Allora:
1. Aggiungi `id="main-container"` al container principale
2. Aggiungi tag `<header>` per l'header
3. Aggiungi tag `<footer>` per il footer
4. Aggiungi `id="search-modal"` se presente nel reference

### Verifica

```bash
# Riesegui confronto dopo correzioni
./bashscripts/html/html-structure-compare.sh segnalazione-dettaglio

# Verifica nuovo score
grep "Parity Score" laravel/Themes/Sixteen/docs/prompts/segnalazione-dettaglio/body-structure-comparison/report.md
```

---

## Risoluzione Problemi

### Script non trova tema
```bash
# Verifica struttura temi
ls -la laravel/Themes/
```

### Fetch local fallisce
```bash
# Avvia server Laravel
cd laravel && php artisan serve
```

### Score basso (<70%)
1. Controlla ID mancanti nel report
2. Verifica tag semantici HTML5
3. Confronta classi CSS principali
4. Studia reference HTML originale

### Traduzioni mancanti
```bash
# Cerca testo hardcoded
grep -r "Invia\|Grazie\|Submit" laravel/Themes/Sixteen/resources/views/
```

---

## Documentazione Correlata

- [HTML Structure Comparison Scripts](../../../bashscripts/docs/html/html-structure-compare.md)
- [Bash Scripts Index](../../../bashscripts/docs/INDEX.md)
- [Block Implementation Guide](./BLOCK_IMPLEMENTATION_GUIDE.md)
- [Design Comuni Block Analysis](./design-comuni/BLOCK_ANALYSIS.md)
- [Page Routing Architecture](./architecture/PAGE_ROUTING_ARCHITECTURE.md)

---

**Version:** 1.0
**Last Updated:** 2026-04-08
**Status:** ✅ Active workflow
**Author:** HTML Parity Team
