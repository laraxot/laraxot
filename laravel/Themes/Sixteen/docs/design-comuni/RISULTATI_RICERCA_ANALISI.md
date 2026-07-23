# Analisi Struttura HTML - Risultati Ricerca

## Panoramica

Confronto tra pagina di riferimento e implementazione locale per `risultati-ricerca`.

- **Reference**: https://italia.github.io/design-comuni-pagine-statiche/sito/risultati-ricerca.html
- **Local**: http://127.0.0.1:8000/it/tests/risultati-ricerca
- **Data**: 2026-04-03

## 📊 Metriche Iniziali

| Metrica | Valore |
|---------|--------|
| Reference righe | 1614 |
| Local righe | 599 |
| Differenza | -1015 righe |
| Match % | 37.1% |

## 🔍 Differenze Strutturali Principali

### 1. Struttura Generale

**Reference**:
```html
<main>
  <h1 class="visually-hidden" id="search-container">Ricerca</h1>
  <div class="container" id="main-container">
    <!-- Breadcrumb -->
  </div>
  
  <div class="container">
    <!-- Search input (cmp-input-search-button) -->
  </div>
  
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-3 d-none d-lg-block scroll-filter-wrapper">
        <!-- Filters SIDEBAR LEFT -->
      </div>
      <div class="col-lg-9">
        <!-- Results -->
      </div>
    </div>
  </div>
</main>
```

**Local**:
```html
<main id="main-container" data-page="risultati-ricerca">
  <div class="cmp-breadcrumbs">
    <!-- Breadcrumb (OK) -->
  </div>
  
  <div class="container">
    <div class="row">
      <div class="col-12">
        <!-- Search results header -->
      </div>
    </div>
  </div>
  
  <div class="container">
    <div class="row">
      <div class="col-12 col-lg-8">
        <!-- Results -->
      </div>
      <div class="col-12 col-lg-4">
        <!-- Filters SIDEBAR RIGHT (WRONG!) -->
      </div>
    </div>
  </div>
</main>
```

### 2. Differenze Critiche

| Elemento | Reference | Local | Match |
|----------|-----------|-------|-------|
| h1.visually-hidden#search-container | ✅ | ❌ | ❌ |
| Search input (cmp-input-search-button) | ✅ | ❌ | ❌ |
| Filters sidebar position | LEFT (col-lg-3) | RIGHT (col-lg-4) | ❌ |
| Results column width | col-lg-9 | col-lg-8 | ❌ |
| Filter categories | Unità organizzativa, Persona pubblica, Documenti, Servizi | Servizi, Novità, Eventi | ❌ |
| Card structure | cmp-card-latest-messages | cmp-card-latest-messages | ✅ |
| Rating section | ✅ | ❌ | ❌ |
| Pagination (load more button) | ✅ | ❌ | ❌ |

## 🎯 Piano di Fix

### Priorità 1: Fix Search Results Template
1. ✅ Aggiungere `h1.visually-hidden#search-container`
2. ✅ Aggiungere search input `cmp-input-search-button`
3. ✅ Spostare filtri a SINISTRA (col-lg-3)
4. ✅ Risultati a DESTRA (col-lg-9)
5. ✅ Aggiungere categorie filtro corrette
6. ✅ Aggiungere sezione rating
7. ✅ Aggiungere pagination "Carica altri risultati"

### Priorità 2: CSS
1. ⏳ Stili per `cmp-search-results`
2. ⏳ Stili per `cmp-input-search-button`
3. ⏳ Stili per filtri sidebar
4. ⏳ Stili per `cmp-card-latest-messages`
5. ⏳ Stili per rating section

### Priorità 3: Alpine.js
1. ⏳ Toggle filtri
2. ⏳ Load more results button
3. ⏳ Rating interaction

---

**Data**: 2026-04-03
**Stato**: ⚠️ 37.1% - Da fixare struttura principale
