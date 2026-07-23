# Design Comuni Pages Census - Blocchi Riusabili

## Panoramica Census

Totale pagine: **38** (9 Generali + 2 Amministrazione + 2 Novità + 3 Servizi + 2 Vivere il Comune + 8 Prenotazione Appuntamento + 2 Richiesta Assistenza + 7 Segnalazione Disservizio)

---

## Blocchi Comuni (RIUSABILI)

### 🔴 HEADER (100% - TUTTE LE PAGINE)
**Identico in tutte le 38 pagine**

```html
<div class="skiplink">...</div>
<header class="it-header-wrapper">
  <div class="it-header-slim-wrapper">...</div>
  <div class="it-nav-wrapper">
    <div class="it-header-center-wrapper">...</div>
    <div class="it-header-navbar-wrapper">...</div>
  </div>
</header>
```

| Page | Blocchi Unique | Note |
|------|---------------|------|
| Tutte le 38 | 1 | **RIUSARE 100%** |

---

### 🔴 FOOTER (100% - TUTTE LE PAGINE)
**Identico in tutte le 38 pagine**

```html
<footer class="it-footer">
  <div class="it-footer-main">
    <div class="row">
      <div class="col-md-3">Amministrazione</div>
      <div class="col-md-6">Categorie servizio</div>
      <div class="col-md-3">Novità + Vivere</div>
      <div class="col-md-9">Contatti</div>
      <div class="col-md-3">Social</div>
    </div>
  </div>
  <div class="it-footer-bottom">...</div>
</footer>
```

| Page | Blocchi Unique | Note |
|------|---------------|------|
| Tutte le 38 | 1 | **RIUSARE 100%** |

---

### 🟡 BREADCRUMBS (75% - 28/38 pagine)
**Presente in tutte tranne homepage e form flows**

```html
<div class="cmp-breadcrumbs">
  <nav aria-label="breadcrumb">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="homepage.html">Home</a></li>
      <li class="breadcrumb-item active">Pagina Corrente</li>
    </ol>
  </nav>
</div>
```

| Page | Presente |
|------|----------|
| homepage | ❌ |
| amministrazione | ✅ |
| novita | ✅ |
| servizi | ✅ |
| eventi | ✅ |
| argomenti | ✅ |
| argomento | ✅ |
| lista-risorse | ✅ |
| lista-categorie | ✅ |
| documenti-dati | ✅ |
| servizi-categoria | ✅ |
| servizio-dettaglio | ✅ |
| evento-dettaglio | ✅ |
| novita-dettaglio | ✅ |
| mappa-sito | ✅ |
| lista-risorse-categorie | ✅ |

**Componente riusabile**: `blocks/breadcrumbs/default.blade.php`

---

### 🟡 HERO SECTION (75% - 28/38 pagine)
**Presente in tutte le pagine lista e dettaglio**

```html
<div class="cmp-hero">
  <section class="it-hero-wrapper bg-white">
    <div class="it-hero-text-wrapper">
      <h1 data-element="page-name">Titolo Pagina</h1>
      <div class="hero-text"><p>Descrizione...</p></div>
    </div>
  </section>
</div>
```

**Componente riusabile**: `blocks/hero/page-hero.blade.php`

---

### 🟢 CARD SEMPLICE (60% - 23/38 pagine)
**Card con shadow per link categories**

```html
<div class="cmp-card-simple card-wrapper pb-0 rounded border border-light">
  <div class="card shadow-sm rounded">
    <div class="card-body">
      <a href="#" class="text-decoration-none">
        <h3 class="card-title t-primary">Titolo</h3>
      </a>
      <p class="text-secondary mb-0">Descrizione...</p>
    </div>
  </div>
</div>
```

**Componente riusabile**: `blocks/card/simple.blade.php`

---

### 🟢 NEWS CARD (30% - 11/38 pagine)
**Card per news/eventi con immagine**

```html
<div class="card-wrapper border border-light rounded shadow-sm">
  <div class="card no-after rounded">
    <div class="img-responsive-wrapper">
      <div class="img-responsive img-responsive-panoramic">
        <figure class="img-wrapper">
          <img src="..." title="titolo" alt="descrizione">
        </figure>
      </div>
    </div>
    <div class="card-body">
      <div class="category-top">
        <a class="category">CATEGORIA</a>
        <span class="data">DATA</span>
      </div>
      <a href="..."><h3 class="card-title">Titolo</h3></a>
      <p class="card-text">Descrizione...</p>
    </div>
  </div>
</div>
```

**Componente riusabile**: `blocks/card/news.blade.php`

---

### 🟢 RATING/FEEDBACK (60% - 23/38 pagine)
**Widget feedback con 5 stelle**

```html
<div class="cmp-rating" id="rating">
  <div class="card shadow card-wrapper">
    <div class="cmp-rating__card-first">
      <div class="card-header">
        <h2>Quanto sono chiare le informazioni su questa pagina?</h2>
      </div>
      <div class="card-body">
        <fieldset class="rating">
          <input type="radio" id="star5" name="rating" value="5">
          <label for="star5">5 stelle</label>
          <!-- ... -->
        </fieldset>
      </div>
    </div>
  </div>
</div>
```

**Componente riusabile**: `blocks/feedback/rating.blade.php`

---

### 🟢 CONTACTS (60% - 23/38 pagine)
**Sezione contatti con link utili**

```html
<div class="cmp-contacts">
  <div class="card w-100">
    <div class="card-body">
      <h2>Contatta il comune</h2>
      <ul class="contact-list p-0">
        <li><a class="list-item" href="#">
          <svg class="icon">...</svg><span>Testo</span>
        </a></li>
      </ul>
    </div>
  </div>
</div>
```

**Componente riusabile**: `blocks/contacts/default.blade.php`

---

### 🟢 SEARCH (40% - 15/38 pagine)
**Search box con autocomplete**

```html
<div class="cmp-input-search">
  <div class="form-group autocomplete-wrapper">
    <div class="input-group">
      <input type="search" class="autocomplete form-control" placeholder="Cerca...">
      <button class="btn btn-primary">Invio</button>
      <span class="autocomplete-icon">
        <svg class="icon"><use href="...#it-search"></use></svg>
      </span>
    </div>
  </div>
</div>
```

**Componente riusabile**: `blocks/search/autocomplete.blade.php`

---

### 🟢 PAGINATION (20% - 8/38 pagine)
**Paginazione liste**

```html
<nav class="pagination-wrapper" aria-label="Navigazione">
  <ul class="pagination">
    <li class="page-item disabled">
      <a class="page-link"><svg class="icon">...</svg></a>
    </li>
    <li class="page-item"><a class="page-link">1</a></li>
    <li class="page-item"><a class="page-link">2</a></li>
  </ul>
</nav>
```

**Componente riusabile**: `blocks/navigation/pagination.blade.php`

---

## Pagine Speciali (FORM FLOWS)

### Prenotazione Appuntamento (8 pagine)
Flow multi-step con form fields diversi per ogni step:
- `appuntamento-01-ufficio.html` - Select ufficio
- `appuntamento-01-ufficio-luogo.html` - Select luogo
- `appuntamento-02-data-orario.html` - Date picker
- `appuntamento-03-dettagli.html` - Form dettagli
- `appuntamento-04-richiedente.html` - Anagrafica non autenticato
- `appuntamento-04-richiedente-autenticato.html` - Anagrafica autenticato
- `appuntamento-05-riepilogo.html` - Riepilogo
- `appuntamento-06-conferma.html` - Conferma

**Pattern**: Usare `blocks/form/step-wizard.blade.php` con slot per contenuto variabile

### Richiesta Assistenza (2 pagine)
- `assistenza-01-dati.html` - Form inserimento
- `assistenza-02-conferma.html` - Conferma

**Pattern**: `blocks/form/assistenza.blade.php`

### Segnalazione Disservizio (7 pagine)
- `segnalazione-dettaglio.html` - Scheda servizio
- `segnalazione-01-privacy.html` - Privacy consent
- `segnalazione-02-dati.html` - Form segnalazione
- `segnalazione-03-riepilogo.html` - Riepilogo
- `segnalazione-04-conferma.html` - Conferma
- `segnalazione-area-personale.html` - Area personale
- `ticket-list.html` - Elenco + mappa

**Pattern**: `blocks/form/segnalazione.blade.php`

---

## Riepilogo Componenti Riusabili

| # | Componente | Pagine | % Riuso |
|---|------------|--------|---------|
| 1 | `blocks/header/default` | 38 | 100% |
| 2 | `blocks/footer/default` | 38 | 100% |
| 3 | `blocks/breadcrumbs/default` | 28 | 75% |
| 4 | `blocks/hero/page-hero` | 28 | 75% |
| 5 | `blocks/card/simple` | 23 | 60% |
| 6 | `blocks/feedback/rating` | 23 | 60% |
| 7 | `blocks/contacts/default` | 23 | 60% |
| 8 | `blocks/search/autocomplete` | 15 | 40% |
| 9 | `blocks/card/news` | 11 | 30% |
| 10 | `blocks/navigation/pagination` | 8 | 20% |

---

## Prossimo Step

1. Creare i 10 componenti riusabili in `resources/views/components/blocks/`
2. Mappare ogni pagina al JSON con composizione di blocchi
3. Testare una pagina (homepage) come proof-of-concept