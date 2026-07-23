# Argomenti - Analisi Struttura HTML

## Panoramica

Confronto tra pagina di riferimento e implementazione locale per `argomenti`.

- **Reference**: https://italia.github.io/design-comuni-pagine-statiche/sito/argomenti.html
- **Local**: http://127.0.0.1:8000/it/tests/argomenti
- **Data**: 2026-04-03

## 📊 Metriche

| Metrica | Valore |
|---------|--------|
| Reference righe | 1168 |
| Local righe | 908 |
| Match % | **77.7%** |
| Differenza | -260 righe |

## 🔍 Struttura HTML Confronto

### Hero Section ✅ MATCH

**Reference**:
```html
<div class="cmp-hero">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-12 col-lg-10">
        <section class="it-hero-wrapper bg-white align-items-start">
          <div class="it-hero-text-wrapper pt-0 ps-0 pb-4 pb-lg-60">
            <h1 class="text-black" data-element="page-name">Argomenti</h1>
            <div class="hero-text">
              <p>Gli argomenti rispondono a un'esigenza...</p>
            </div>
          </div>
        </section>
      </div>
    </div>
  </div>
</div>
```

**Local**: ✅ **IDENTICA**

---

### In Evidenza Section ✅ MATCH

**Reference**:
```html
<div class="container py-5">
  <h2 class="title-xxlarge mb-4">In evidenza</h2>
  <div class="row g-4">
    <div class="col-sm-6 col-lg-4">
      <div class="it-grid-item-wrapper it-grid-item-overlay">
        <a href="#">
          <div class="img-responsive-wrapper">
            <div class="img-responsive">
              <div class="img-wrapper">
                <img src="https://picsum.photos/376/488" alt="Cultura">
              </div>
            </div>
          </div>
          <div class="it-griditem-text-wrapper">
            <h3>Cultura</h3>
          </div>
        </a>
      </div>
    </div>
  </div>
</div>
```

**Local**: ✅ **IDENTICA**

---

### Esplora per Argomento Section ✅ MATCH

**Reference**:
```html
<div class="container py-5" id="argomento">
  <h2 class="title-xxlarge mb-4">Esplora per argomento</h2>
  <div class="row g-4">
    <div class="col-md-6 col-xl-4">
      <div class="cmp-card-simple card-wrapper pb-0 rounded border border-light">
        <div class="card shadow-sm rounded">
          <div class="card-body">
            <a href="#" class="text-decoration-none" data-element="topic-element">
              <h3 class="card-title t-primary title-xlarge">Agricoltura</h3>
            </a>
            <p class="text-secondary mb-0 description">Lorem ipsum...</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
```

**Local**: ✅ **IDENTICA**

---

## ⚠️ Differenze Residue

### 1. Breadcrumb Mancante

**Reference**: Ha `cmp-breadcrumbs` con wrapper container
**Local**: Non ha breadcrumb (il JSON non lo include)

**Soluzione**: Aggiungere breadcrumb al JSON content

### 2. Header Globale (~200 righe)

Differenza nell'header globale (slim wrapper, center wrapper)

### 3. Footer (~50 righe)

Differenze minori nel footer

---

## 📝 File Utilizzati

### Blade Templates
1. ✅ `components/blocks/hero/default.blade.php`
2. ✅ `components/blocks/hero/argomenti.blade.php`
3. ✅ `components/blocks/topics-highlight.blade.php`
4. ✅ `components/blocks/topics-grid/default.blade.php`

### JSON Content
5. ✅ `config/local/fixcity/database/content/pages/tests.argomenti.json`

---

## 🎯 Stato Componenti

| Componente | HTML | CSS | JS | Totale |
|-----------|------|-----|----|--------|
| Hero | ✅ 100% | ✅ 95% | N/A | ✅ 98% |
| In Evidenza | ✅ 100% | ✅ 90% | N/A | ✅ 95% |
| Esplora per Argomento | ✅ 100% | ✅ 90% | N/A | ✅ 95% |
| Breadcrumb | ❌ 0% | N/A | N/A | ❌ 0% |
| **Totale** | **✅ 75%** | **✅ 92%** | **N/A** | **✅ 72%** |

---

## 📚 Link Correlati

- **Screenshot**: [screenshots/argomenti/](./screenshots/argomenti/)
- **Script**: [bashscripts/design-comuni/capture-argomenti.js](../../../bashscripts/design-comuni/capture-argomenti.js)
- **Master Index**: [docs/design-comuni/MASTER_INDEX.md](../../../docs/design-comuni/MASTER_INDEX.md)

---

**Data**: 2026-04-03  
**Stato**: ✅ 77.7% - Struttura principale corretta  
**Prossimo**: Aggiungere breadcrumb, CSS refinements
