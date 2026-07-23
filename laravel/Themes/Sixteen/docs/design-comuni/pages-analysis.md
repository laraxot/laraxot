# Design Comuni Pages Analysis

**Analysis Date:** 2026-03-30  
**Source:** [Design Comuni Pagine Statiche v2.4.0](https://italia.github.io/design-comuni-pagine-statiche/)  
**Purpose:** Document HTML structure, identify blocks needed, and map to CMS JSON structure for implementation

---

## Table of Contents

1. [Pages Overview](#pages-overview)
2. [Common Structure](#common-structure)
3. [Page-by-Page Analysis](#page-by-page-analysis)
4. [Block Types Catalog](#block-types-catalog)
5. [CMS JSON Structure](#cms-json-structure)
6. [Implementation Priority](#implementation-priority)
7. [Reusability Matrix](#reusability-matrix)

---

## Pages Overview

| # | Page Name | Template File | Type | Complexity |
|---|-----------|---------------|------|------------|
| 1 | Homepage | `homepage.html` | Landing | High |
| 2 | Argomenti | `argomenti.html` | Listing | Medium |
| 3 | Appuntamento Conferma | `appuntamento-06-conferma.html` | Transactional | Medium |
| 4 | Servizio Dettaglio | `servizio-dettaglio.html` | Detail | High |
| 5 | Notizia | `novita-dettaglio.html` | Detail | Medium |
| 6 | Evento | `evento-dettaglio.html` | Detail | High |
| 7 | Amministrazione | `amministrazione.html` | Listing | Medium |

---

## Common Structure

All pages share a consistent structure:

```
<body>
  ├── Skip Links (Vai ai contenuti, Vai al footer)
  ├── Header Wrapper
  │   ├── Top Bar (Regione, Lingua, Login)
  │   ├── Brand Zone (Comune, Slogan, Social)
  │   └── Main Navigation
  ├── Main Content
  │   ├── Breadcrumb
  │   ├── Page Title Section
  │   ├── Content Sections (variable per page type)
  │   └── Feedback/Rating Section
  └── Footer
      ├── Quick Links (Contatta, Problemi, Cerca)
      ├── Site Map Columns
      └── Legal & Social
```

---

## Page-by-Page Analysis

### 1. Homepage (`homepage.html`)

#### HTML Structure

```html
<body>
  <!-- Skip Links -->
  <div class="skip-links">
    <a href="#main-content">Vai ai contenuti</a>
    <a href="#footer">Vai al footer</a>
  </div>

  <!-- Header -->
  <header class="it-header-wrapper">
    <div class="it-header-slim-wrapper">
      <!-- Top Bar: Regione, Lingua, Login -->
    </div>
    <div class="it-nav-wrapper">
      <!-- Brand, Social, Search, Navigation -->
    </div>
  </header>

  <!-- Main Content -->
  <main id="main-content">
    <!-- Section: Contenuti in Evidenza -->
    <section class="it-hero-section">
      <!-- Featured news card with image, date, title, description -->
    </section>

    <!-- Section: Organi di governo -->
    <section class="it-governance-section">
      <!-- Cards: Sindaco, Giunta, Consiglio -->
    </section>

    <!-- Section: Eventi -->
    <section class="it-events-section">
      <!-- Calendar widget with upcoming events -->
    </section>

    <!-- Section: Argomenti in Evidenza -->
    <section class="it-topics-section">
      <!-- Topic cards grid -->
    </section>

    <!-- Section: Siti Tematici -->
    <section class="it-thematic-sites-section">
      <!-- Links to thematic sites -->
    </section>

    <!-- Section: Search & Feedback -->
    <section class="it-feedback-section">
      <!-- Search, Rating, Feedback form -->
    </section>
  </main>

  <!-- Footer -->
  <footer id="footer" class="it-footer">
    <!-- Quick links, Site map, Contacts, Legal -->
  </footer>
</body>
```

#### Blocks Needed

| Block Type | Description | Reusable |
|------------|-------------|----------|
| `header.full` | Complete header with top bar, brand, nav | Yes |
| `hero.featured` | Large featured content card | Yes |
| `governance.grid` | 3-column governance cards | Yes |
| `events.calendar` | Calendar widget with events list | Yes |
| `topics.grid` | Topic cards in grid layout | Yes |
| `thematic-sites.list` | Thematic site links | Yes |
| `feedback.form` | Rating + feedback form | Yes |
| `footer.full` | Complete footer | Yes |

#### CMS JSON Structure

```json
{
  "page": "homepage",
  "blocks": [
    {
      "type": "hero.featured",
      "data": {
        "title": "Estate in città",
        "date": "2022-09-15",
        "description": "Lorem ipsum...",
        "link": "/novita/estate-in-citta",
        "image": "/images/hero-estate.jpg"
      }
    },
    {
      "type": "governance.grid",
      "data": {
        "title": "Organi di governo",
        "items": [
          {
            "title": "Sindaco",
            "name": "Mario Rossi",
            "link": "/amministrazione/sindaco"
          },
          {
            "title": "La Giunta Comunale",
            "link": "/amministrazione/giunta"
          },
          {
            "title": "Il Consiglio Comunale",
            "link": "/amministrazione/consiglio"
          }
        ]
      }
    },
    {
      "type": "events.calendar",
      "data": {
        "title": "Eventi",
        "month": "Settembre 2022",
        "events": []
      }
    },
    {
      "type": "topics.grid",
      "data": {
        "title": "Argomenti in evidenza",
        "highlighted": ["Trasporto pubblico", "Mobilità", "Animali domestici", "Sport"],
        "others": ["Associazioni", "Tasse", "..."]
      }
    },
    {
      "type": "thematic-sites.list",
      "data": {
        "title": "Siti tematici",
        "sites": [
          {"name": "Mobilità", "link": "/tematici/mobilita"},
          {"name": "Turismo", "link": "/tematici/turismo"},
          {"name": "Musei civici", "link": "/tematici/musei"}
        ]
      }
    }
  ]
}
```

---

### 2. Argomenti (`argomenti.html`)

#### HTML Structure

```html
<main>
  <!-- Breadcrumb -->
  <nav class="breadcrumb">
    <ol>
      <li><a href="/">Home</a></li>
      <li class="active">Lista Argomenti</li>
    </ol>
  </nav>

  <!-- Page Title -->
  <section class="it-page-title">
    <h1>ARGOMENTI</h1>
    <p class="lead">Gli argomenti rispondono...</p>
  </section>

  <!-- Section: In Evidenza -->
  <section class="it-highlighted-topics">
    <h2>IN EVIDENZA</h2>
    <div class="topics-grid">
      <!-- Topic cards: CULTURA, SPORT, FAMIGLIA -->
    </div>
  </section>

  <!-- Section: Esplora per Argomento -->
  <section class="it-topics-explorer">
    <h2>ESPLORA PER ARGOMENTO</h2>
    <div class="topics-list">
      <!-- All topics with descriptions -->
    </div>
  </section>

  <!-- Feedback Section -->
  <section class="it-feedback-section">
    <!-- Rating form -->
  </section>

  <!-- Quick Links Section -->
  <section class="it-quick-links">
    <!-- Contatta, Problemi, Cerca, Forse stavi cercando -->
  </section>
</main>
```

#### Blocks Needed

| Block Type | Description | Reusable |
|------------|-------------|----------|
| `breadcrumb` | Navigation breadcrumb | Yes |
| `page.title` | H1 + lead paragraph | Yes |
| `topics.highlighted` | Highlighted topics section | Yes |
| `topics.explorer` | Full topics list with descriptions | Yes |
| `quick-links.grid` | Quick action cards | Yes |

#### CMS JSON Structure

```json
{
  "page": "argomenti",
  "breadcrumb": [
    {"label": "Home", "url": "/"},
    {"label": "Lista Argomenti", "url": null}
  ],
  "blocks": [
    {
      "type": "page.title",
      "data": {
        "title": "ARGOMENTI",
        "subtitle": "Gli argomenti rispondono a esigenze di classificazione..."
      }
    },
    {
      "type": "topics.highlighted",
      "data": {
        "title": "IN EVIDENZA",
        "topics": [
          {"name": "CULTURA", "slug": "cultura", "count": 12},
          {"name": "SPORT", "slug": "sport", "count": 8},
          {"name": "FAMIGLIA", "slug": "famiglia", "count": 15}
        ]
      }
    },
    {
      "type": "topics.explorer",
      "data": {
        "title": "ESPLORA PER ARGOMENTO",
        "topics": [
          {
            "name": "AGRICOLTURA",
            "slug": "agricoltura",
            "description": "Lorem ipsum dolor sit amet...",
            "count": 5
          }
          // ... all topics
        ]
      }
    }
  ]
}
```

---

### 3. Appuntamento Conferma (`appuntamento-06-conferma.html`)

#### HTML Structure

```html
<main>
  <!-- Breadcrumb -->
  <nav class="breadcrumb">
    <ol>
      <li><a href="/">Home</a></li>
      <li><a href="/prenotazione">Prenotazione appuntamento</a></li>
      <li class="active">Conferma</li>
    </ol>
  </nav>

  <!-- Confirmation Banner -->
  <section class="it-confirmation-banner">
    <h1>APPUNTAMENTO CONFERMATO</h1>
    <div class="confirmation-details">
      <p class="date">Giovedì 11 marzo 2022 dalle ore 10:00 alle ore 10:30</p>
      <p class="email">giulia.bianchi@gmail.com</p>
    </div>
  </section>

  <!-- Page Index -->
  <nav class="it-page-index">
    <h2>INDICE DELLA PAGINA</h2>
    <ul>
      <li><a href="#cosa-serve">Cosa serve</a></li>
      <li><a href="#indirizzo">Indirizzo</a></li>
      <li><a href="#calendario">Aggiungi al calendario</a></li>
    </ul>
  </nav>

  <!-- Content Sections -->
  <section id="cosa-serve" class="it-content-section">
    <h2>COSA SERVE</h2>
    <ul>
      <li>Documento di identità</li>
    </ul>
  </section>

  <section id="indirizzo" class="it-content-section">
    <h2>INDIRIZZO</h2>
    <div class="office-card">
      <h3>Ufficio anagrafe 03</h3>
      <p>Via Grazia Deledda 9/a - 20127 Milano</p>
      <p>anagrafe@comune.it</p>
    </div>
  </section>

  <section id="calendario" class="it-content-section">
    <h2>AGGIUNGI AL TUO CALENDARIO</h2>
    <div class="calendar-options">
      <a href="#" class="btn">Outlook</a>
      <a href="#" class="btn">Google Calendar</a>
      <a href="#" class="btn">iCloud Calendar</a>
    </div>
  </section>

  <!-- Feedback Section -->
  <section class="it-feedback-section">
    <!-- Rating form -->
  </section>
</main>
```

#### Blocks Needed

| Block Type | Description | Reusable |
|------------|-------------|----------|
| `confirmation.banner` | Success confirmation with details | Yes |
| `page.index` | Table of contents with anchors | Yes |
| `content.section` | Generic content section with heading | Yes |
| `office.card` | Office information card | Yes |
| `calendar.export` | Calendar export buttons | Yes |

#### CMS JSON Structure

```json
{
  "page": "appuntamento-conferma",
  "breadcrumb": [
    {"label": "Home", "url": "/"},
    {"label": "Prenotazione appuntamento", "url": "/prenotazione"},
    {"label": "Conferma", "url": null}
  ],
  "blocks": [
    {
      "type": "confirmation.banner",
      "data": {
        "title": "APPUNTAMENTO CONFERMATO",
        "datetime": {
          "start": "2022-03-11T10:00:00",
          "end": "2022-03-11T10:30:00",
          "display": "Giovedì 11 marzo 2022 dalle ore 10:00 alle ore 10:30"
        },
        "email": "giulia.bianchi@gmail.com",
        "booking_id": "ABC123"
      }
    },
    {
      "type": "page.index",
      "data": {
        "title": "INDICE DELLA PAGINA",
        "items": [
          {"label": "Cosa serve", "anchor": "cosa-serve"},
          {"label": "Indirizzo", "anchor": "indirizzo"},
          {"label": "Aggiungi al calendario", "anchor": "calendario"}
        ]
      }
    },
    {
      "type": "content.section",
      "data": {
        "id": "cosa-serve",
        "title": "COSA SERVE",
        "content": "<ul><li>Documento di identità</li></ul>"
      }
    },
    {
      "type": "office.card",
      "data": {
        "id": "indirizzo",
        "name": "Ufficio anagrafe 03",
        "address": "Via Grazia Deledda 9/a - 20127 Milano",
        "email": "anagrafe@comune.it"
      }
    },
    {
      "type": "calendar.export",
      "data": {
        "id": "calendario",
        "title": "AGGIUNGI AL TUO CALENDARIO",
        "providers": ["outlook", "google", "icloud"],
        "event": {
          "title": "Appuntamento Anagrafe",
          "start": "2022-03-11T10:00:00",
          "end": "2022-03-11T10:30:00"
        }
      }
    }
  ]
}
```

---

### 4. Servizio Dettaglio (`servizio-dettaglio.html`)

#### HTML Structure

```html
<main>
  <!-- Breadcrumb -->
  <nav class="breadcrumb">
    <ol>
      <li><a href="/">Home</a></li>
      <li><a href="/servizi">Servizi</a></li>
      <li><a href="/servizi/educazione">Educazione e formazione</a></li>
      <li class="active">Iscrizione alla scuola dell'infanzia</li>
    </ol>
  </nav>

  <!-- Page Header -->
  <section class="it-page-header">
    <h1>ISCRIZIONE ALLA SCUOLA DELL'INFANZIA</h1>
    <span class="status-badge">Servizio attivo</span>
    <p class="lead">Come iscrivere i propri figli...</p>
    
    <!-- CTA -->
    <a href="#" class="btn btn-primary">Richiesta di iscrizione online</a>
    
    <!-- Social Share -->
    <div class="it-social-share">
      <span>Condividi</span>
      <a href="#" class="social-btn facebook">Facebook</a>
      <a href="#" class="social-btn twitter">Twitter</a>
      <a href="#" class="social-btn linkedin">Linkedin</a>
      <a href="#" class="social-btn whatsapp">Whatsapp</a>
    </div>
    
    <!-- Utility Actions -->
    <div class="it-page-actions">
      <button class="btn btn-link">Vedi azioni</button>
      <ul>
        <li><a href="#">Stampa</a></li>
        <li><a href="#">Ascolta</a></li>
        <li><a href="#">Invia</a></li>
      </ul>
    </div>
    
    <!-- Tags -->
    <div class="it-page-tags">
      <span>Argomenti</span>
      <a href="#">Educazione</a>
      <a href="#">Famiglia</a>
      <a href="#">Scuola materna</a>
      <a href="#">Bambini 3 - 5 anni</a>
    </div>
  </section>

  <!-- Page Index -->
  <nav class="it-page-index">
    <h2>INDICE DELLA PAGINA</h2>
    <ul>
      <li><a href="#a-chi-e-rivolto">A chi è rivolto</a></li>
      <li><a href="#descrizione">Descrizione</a></li>
      <li><a href="#come-fare">Come fare</a></li>
      <li><a href="#cosa-serve">Cosa serve</a></li>
      <li><a href="#cosa-si-ottiene">Cosa si ottiene</a></li>
      <li><a href="#tempi-e-scadenze">Tempi e scadenze</a></li>
      <li><a href="#quanto-costa">Quanto costa</a></li>
      <li><a href="#accedi-al-servizio">Accedi al servizio</a></li>
      <li><a href="#ulteriori-informazioni">Ulteriori informazioni</a></li>
      <li><a href="#contatti">Contatti</a></li>
    </ul>
  </nav>

  <!-- Content Sections -->
  <section id="a-chi-e-rivolto" class="it-content-section">
    <h2>A CHI È RIVOLTO</h2>
    <ul>
      <li>Bambini di età compresa tra 3 e 5 anni</li>
    </ul>
  </section>

  <section id="descrizione" class="it-content-section">
    <h2>DESCRIZIONE</h2>
    <p>Lorem ipsum...</p>
  </section>

  <section id="come-fare" class="it-content-section">
    <h2>COME FARE</h2>
    <p>Instructions...</p>
  </section>

  <section id="cosa-serve" class="it-content-section">
    <h2>COSA SERVE</h2>
    <ul>
      <li>Documento di identità</li>
      <li>Codice fiscale</li>
    </ul>
  </section>

  <section id="tempi-e-scadenze" class="it-content-section">
    <h2>TEMPI E SCADENZE</h2>
    <div class="it-timeline">
      <!-- Timeline with months: GEN, FEB, MAR, APR -->
    </div>
  </section>

  <section id="quanto-costa" class="it-content-section">
    <h2>QUANTO COSTA</h2>
    <p>Cost information...</p>
    <a href="#">Tabella rette e contributi</a>
  </section>

  <section id="accedi-al-servizio" class="it-content-section">
    <h2>ACCEDI AL SERVIZIO</h2>
    <div class="service-actions">
      <a href="#" class="btn btn-primary">Richiedi iscrizione online</a>
      <a href="#" class="btn btn-outline">Prenota appuntamento</a>
    </div>
  </section>

  <section id="contatti" class="it-content-section">
    <h2>CONTATTI</h2>
    <div class="contact-card">
      <p>Address, Phone, Email...</p>
    </div>
  </section>

  <!-- Tags Section -->
  <section class="it-tags-section">
    <h3>Argomenti</h3>
    <a href="#">Educazione</a>
    <a href="#">Famiglia</a>
    <a href="#">Scuola materna</a>
    <a href="#">Bambini 3 - 5 anni</a>
  </section>

  <!-- Last Update -->
  <small class="last-update">
    Pagina aggiornata il 14/04/2022
  </small>

  <!-- Feedback Section -->
  <section class="it-feedback-section">
    <!-- Rating form -->
  </section>

  <!-- Related Content -->
  <section class="it-related-content">
    <div class="related-grid">
      <!-- Amministrazione, Servizi, Documenti, Notizie -->
    </div>
  </section>
</main>
```

#### Blocks Needed

| Block Type | Description | Reusable |
|------------|-------------|----------|
| `page.header.service` | Service page header with status, CTA, share | Yes |
| `service.timeline` | Timeline for deadlines | Yes |
| `service.actions` | Service action buttons | Yes |
| `content.section` | Generic content section | Yes |
| `contact.card` | Contact information card | Yes |
| `tags.list` | Topic tags | Yes |
| `related-content.grid` | Related content cards | Yes |

#### CMS JSON Structure

```json
{
  "page": "servizio-dettaglio",
  "breadcrumb": [
    {"label": "Home", "url": "/"},
    {"label": "Servizi", "url": "/servizi"},
    {"label": "Educazione e formazione", "url": "/servizi/educazione"},
    {"label": "Iscrizione alla scuola dell'infanzia", "url": null}
  ],
  "service": {
    "title": "ISCRIZIONE ALLA SCUOLA DELL'INFANZIA",
    "status": "active",
    "status_label": "Servizio attivo",
    "subtitle": "Come iscrivere i propri figli...",
    "cta": {
      "label": "Richiesta di iscrizione online",
      "url": "/servizi/iscrizione/online"
    },
    "tags": ["Educazione", "Famiglia", "Scuola materna", "Bambini 3 - 5 anni"],
    "last_update": "2022-04-14"
  },
  "blocks": [
    {
      "type": "page.index",
      "data": {
        "title": "INDICE DELLA PAGINA",
        "items": [
          {"label": "A chi è rivolto", "anchor": "a-chi-e-rivolto"},
          {"label": "Descrizione", "anchor": "descrizione"},
          {"label": "Come fare", "anchor": "come-fare"},
          {"label": "Cosa serve", "anchor": "cosa-serve"},
          {"label": "Cosa si ottiene", "anchor": "cosa-si-ottiene"},
          {"label": "Tempi e scadenze", "anchor": "tempi-e-scadenze"},
          {"label": "Quanto costa", "anchor": "quanto-costa"},
          {"label": "Accedi al servizio", "anchor": "accedi-al-servizio"},
          {"label": "Ulteriori informazioni", "anchor": "ulteriori-informazioni"},
          {"label": "Contatti", "anchor": "contatti"}
        ]
      }
    },
    {
      "type": "content.section",
      "data": {
        "id": "a-chi-e-rivolto",
        "title": "A CHI È RIVOLTO",
        "content": "<ul><li>Bambini di età compresa tra 3 e 5 anni</li></ul>"
      }
    },
    {
      "type": "service.timeline",
      "data": {
        "id": "tempi-e-scadenze",
        "title": "TEMPI E SCADENZE",
        "events": [
          {"month": "GEN", "label": "Apertura iscrizioni"},
          {"month": "FEB", "label": "Scadenza graduatorie"},
          {"month": "MAR", "label": "Pubblicazione graduatorie"},
          {"month": "APR", "label": "Inserimenti"}
        ]
      }
    },
    {
      "type": "service.actions",
      "data": {
        "id": "accedi-al-servizio",
        "title": "ACCEDI AL SERVIZIO",
        "actions": [
          {"label": "Richiedi iscrizione online", "url": "/online", "type": "primary"},
          {"label": "Prenota appuntamento", "url": "/prenota", "type": "outline"}
        ]
      }
    }
  ],
  "related": {
    "amministrazione": {"title": "Area Servizi Civici", "url": "/amministrazione/servizi-civici"},
    "services": [
      {"title": "Pagamento retta scolastica", "url": "/servizi/pagamento-retta"},
      {"title": "Servizio di trasporto scolastico", "url": "/servizi/trasporto"}
    ],
    "documents": [
      {"title": "Attestazione ISEE", "url": "/documenti/isee"},
      {"title": "Carta dei servizi educativi", "url": "/documenti/carta-servizi"}
    ],
    "news": [
      {"title": "Notizia 1", "date": "2022-04-10", "url": "/novita/1"}
    ]
  }
}
```

---

### 5. Notizia (`novita-dettaglio.html`)

#### HTML Structure

```html
<main>
  <!-- Breadcrumb -->
  <nav class="breadcrumb">
    <ol>
      <li><a href="/">Home</a></li>
      <li><a href="/novita">Novità</a></li>
      <li><a href="/novita/notizie">Notizie</a></li>
      <li class="active">Stralcio automatico dei debiti...</li>
    </ol>
  </nav>

  <!-- Page Header -->
  <section class="it-page-header">
    <h1>STRALCIO AUTOMATICO DEI DEBITI FINO A MILLE EURO</h1>
    
    <!-- Meta Info -->
    <div class="it-news-meta">
      <span class="section">DETTAGLI DELLA NOTIZIA</span>
      <p class="description">Delibera del Consiglio comunale...</p>
      <span class="date">31 gennaio 2023</span>
      <span class="reading-time">1 min</span>
    </div>
    
    <!-- Social Share -->
    <div class="it-social-share">
      <span>Condividi</span>
      <a href="#" class="social-btn facebook">Facebook</a>
      <a href="#" class="social-btn twitter">Twitter</a>
      <a href="#" class="social-btn linkedin">Linkedin</a>
      <a href="#" class="social-btn whatsapp">Whatsapp</a>
    </div>
    
    <!-- Utility Actions -->
    <div class="it-page-actions">
      <button class="btn btn-link">Vedi azioni</button>
      <ul>
        <li><a href="#">Stampa</a></li>
        <li><a href="#">Ascolta</a></li>
        <li><a href="#">Invia</a></li>
      </ul>
    </div>
    
    <!-- Tags -->
    <div class="it-page-tags">
      <span>Argomenti</span>
      <a href="#">Cultura</a>
      <a href="#">Muoversi</a>
    </div>
    
    <!-- Featured Image -->
    <figure>
      <img src="..." alt="...">
      <figcaption>Una didascalia per l'immagine sopra.</figcaption>
    </figure>
  </section>

  <!-- Page Index -->
  <nav class="it-page-index">
    <h2>INDICE DELLA PAGINA</h2>
    <ul>
      <li><a href="#descrizione">Descrizione</a></li>
      <li><a href="#a-cura-di">A cura di</a></li>
    </ul>
  </nav>

  <!-- Content Sections -->
  <section id="descrizione" class="it-content-section">
    <h2>DESCRIZIONE</h2>
    <p>Content about L. 197/2022...</p>
    <a href="https://www.ader.it">Link esterno</a>
  </section>

  <section id="a-cura-di" class="it-office-section">
    <h2>A CURA DI</h2>
    <div class="office-card">
      <h3>UFFICIO IMPOSTE</h3>
      <p>Via dei Transiti 9, 00100 Roma</p>
    </div>
  </section>

  <!-- People Section -->
  <section class="it-people-section">
    <h2>PERSONE</h2>
    <ul>
      <li>Mario Rossi</li>
      <li>Alessandro Bianchi</li>
    </ul>
  </section>

  <!-- Last Update -->
  <small class="last-update">
    ULTIMO AGGIORNAMENTO: 10/06/2019, 16:00
  </small>

  <!-- Feedback Section -->
  <section class="it-feedback-section">
    <!-- Rating form -->
  </section>
</main>
```

#### Blocks Needed

| Block Type | Description | Reusable |
|------------|-------------|----------|
| `page.header.news` | News page header with meta, share, image | Yes |
| `news.meta` | Date, reading time, section | Yes |
| `content.section` | Generic content section | Yes |
| `office.card` | Office information | Yes |
| `people.list` | People mentioned | Yes |

#### CMS JSON Structure

```json
{
  "page": "notizia-dettaglio",
  "breadcrumb": [
    {"label": "Home", "url": "/"},
    {"label": "Novità", "url": "/novita"},
    {"label": "Notizie", "url": "/novita/notizie"},
    {"label": "Stralcio automatico dei debiti fino a mille euro", "url": null}
  ],
  "news": {
    "title": "STRALCIO AUTOMATICO DEI DEBITI FINO A MILLE EURO",
    "section": "DETTAGLI DELLA NOTIZIA",
    "description": "Delibera del Consiglio comunale...",
    "date": "2023-01-31",
    "reading_time": "1 min",
    "tags": ["Cultura", "Muoversi"],
    "image": {
      "src": "/images/news-debiti.jpg",
      "alt": "...",
      "caption": "Una didascalia per l'immagine sopra."
    },
    "last_update": "2019-06-10T16:00:00"
  },
  "blocks": [
    {
      "type": "page.index",
      "data": {
        "title": "INDICE DELLA PAGINA",
        "items": [
          {"label": "Descrizione", "anchor": "descrizione"},
          {"label": "A cura di", "anchor": "a-cura-di"}
        ]
      }
    },
    {
      "type": "content.section",
      "data": {
        "id": "descrizione",
        "title": "DESCRIZIONE",
        "content": "<p>Content about L. 197/2022...</p><a href='https://www.ader.it'>Link esterno</a>"
      }
    },
    {
      "type": "office.card",
      "data": {
        "id": "a-cura-di",
        "title": "A CURA DI",
        "name": "UFFICIO IMPOSTE",
        "address": "Via dei Transiti 9, 00100 Roma"
      }
    },
    {
      "type": "people.list",
      "data": {
        "title": "PERSONE",
        "people": ["Mario Rossi", "Alessandro Bianchi"]
      }
    }
  ]
}
```

---

### 6. Evento (`evento-dettaglio.html`)

#### HTML Structure

```html
<main>
  <!-- Breadcrumb -->
  <nav class="breadcrumb">
    <ol>
      <li><a href="/">Home</a></li>
      <li><a href="/vivere">Vivere il Comune</a></li>
      <li><a href="/vivere/eventi">Eventi</a></li>
      <li class="active">363^ Festa di Sant'Efisio</li>
    </ol>
  </nav>

  <!-- Page Header -->
  <section class="it-page-header">
    <h1>363^ FESTA DI SANT'EFISIO</h1>
    <div class="it-event-subtitle">
      FESTIVAL - DAL 01 MAGGIO 2019 AL 04 MAGGIO 2019
    </div>
    <p class="lead">Il 1° maggio 2019 Cagliari e tutta la Sardegna festeggiano...</p>
    
    <!-- Social Share -->
    <div class="it-social-share">
      <span>Condividi</span>
      <a href="#" class="social-btn facebook">Facebook</a>
      <a href="#" class="social-btn twitter">Twitter</a>
      <a href="#" class="social-btn linkedin">Linkedin</a>
      <a href="#" class="social-btn whatsapp">Whatsapp</a>
    </div>
    
    <!-- Utility Actions -->
    <div class="it-page-actions">
      <button class="btn btn-link">Vedi azioni</button>
      <ul>
        <li><a href="#">Stampa</a></li>
        <li><a href="#">Ascolta</a></li>
        <li><a href="#">Invia</a></li>
      </ul>
    </div>
    
    <!-- Tags -->
    <div class="it-page-tags">
      <span>ARGOMENTI</span>
      <a href="#">Cultura</a>
      <a href="#">Vivere la città</a>
    </div>
    
    <!-- Featured Image -->
    <figure>
      <img src="..." alt="...">
      <figcaption>Una didascalia per l'immagine sopra.</figcaption>
    </figure>
  </section>

  <!-- Page Index -->
  <nav class="it-page-index">
    <h2>INDICE DELLA PAGINA</h2>
    <ul>
      <li><a href="#cos-e">Cos'è</a></li>
      <li><a href="#a-chi-e-rivolto">A chi è rivolto</a></li>
      <li><a href="#luogo">Luogo</a></li>
      <li><a href="#date-e-orari">Date e orari</a></li>
      <li><a href="#costi">Costi</a></li>
      <li><a href="#allegati">Allegati</a></li>
      <li><a href="#appuntamenti">Appuntamenti</a></li>
      <li><a href="#contatti">Contatti</a></li>
      <li><a href="#patrocinato">Patrocinato da</a></li>
      <li><a href="#sponsor">Sponsor</a></li>
    </ul>
  </nav>

  <!-- Content Sections -->
  <section id="cos-e" class="it-content-section">
    <h2>COS'È?</h2>
    <p>Content about Sant'Efisio history...</p>
  </section>

  <section class="it-content-section">
    <h2>PARTECIPERANNO</h2>
    <ul>
      <li>Valerio Alfio Boi</li>
      <li>Marco Murgia</li>
    </ul>
  </section>

  <section class="it-gallery-section">
    <h2>GALLERIA DI IMMAGINI</h2>
    <div class="it-carousel">
      <!-- 6 images carousel -->
    </div>
  </section>

  <section class="it-video-section">
    <h2>VIDEO</h2>
    <div class="it-video-wrapper">
      <video>...</video>
      <p>Trascrizione del video...</p>
    </div>
  </section>

  <section id="luogo" class="it-content-section">
    <h2>LUOGO</h2>
    <h3>CAGLIARI</h3>
    <p>Via Sant'Efisio, 14 - 09124</p>
    <a href="#">Ulteriori dettagli</a>
  </section>

  <section id="date-e-orari" class="it-content-section">
    <h2>DATE E ORARI</h2>
    <div class="it-timeline">
      <div class="timeline-item">
        <span class="date">01 MAG</span>
        <span class="time">09:00</span>
        <span class="label">INIZIO EVENTO</span>
      </div>
      <div class="timeline-item">
        <span class="date">04 MAG</span>
        <span class="time">18:00</span>
        <span class="label">FINE EVENTO</span>
      </div>
    </div>
    <p>Info on detailed program...</p>
  </section>

  <section id="costi" class="it-content-section">
    <h2>COSTI</h2>
    <div class="pricing-grid">
      <div class="card">
        <div class="card-body">
          <h3>TRIBUNA 1 E 4</h3>
          <p class="price">€ 30</p>
          <p>Description...</p>
        </div>
      </div>
      <div class="card">
        <div class="card-body">
          <h3>TRIBUNA 3</h3>
          <p class="price">€ 25</p>
          <p>Description...</p>
        </div>
      </div>
      <!-- More pricing cards -->
    </div>
  </section>

  <section id="allegati" class="it-content-section">
    <h2>ALLEGATI</h2>
    <ul>
      <li>
        <a href="#">
          <span class="icon">PDF</span>
          <span>LOCANDINA SANT'EFISIO 2019</span>
          <span class="size">1MB</span>
        </a>
      </li>
    </ul>
  </section>

  <section id="appuntamenti" class="it-content-section">
    <h2>APPUNTAMENTI</h2>
    <div class="events-list">
      <div class="event-card">
        <div class="card-body">
          <small>31 dicembre</small>
          <h3>IL BALLO DELL'ISOLA IN FESTA</h3>
          <a href="#">Leggi di più...</a>
        </div>
      </div>
      <!-- More events -->
    </div>
  </section>

  <section id="contatti" class="it-content-section">
    <h2>CONTATTI</h2>
    <div class="contact-grid">
      <div class="contact-card">
        <h3>Arciconfraternita</h3>
        <p>Address, Phone, Website, Email</p>
      </div>
      <div class="contact-card">
        <h3>UFFICIO DELLE ATTIVITÀ PRODUTTIVE</h3>
        <p>Address, Phone, Email</p>
      </div>
    </div>
  </section>

  <section class="it-sponsors-section">
    <h2>PATROCINATO DA</h2>
    <ul>
      <li>Regione Autonome della Sardegna</li>
    </ul>
    <h2>SPONSOR</h2>
    <ul>
      <li>Provincia di Cagliari</li>
      <li>Sogaer</li>
      <!-- More sponsors -->
    </ul>
  </section>

  <!-- Last Update -->
  <small class="last-update">
    ULTIMO AGGIORNAMENTO: 10/06/2019, 16:00
  </small>

  <!-- Feedback Section -->
  <section class="it-feedback-section">
    <!-- Rating form -->
  </section>
</main>
```

#### Blocks Needed

| Block Type | Description | Reusable |
|------------|-------------|----------|
| `page.header.event` | Event page header with dates, share | Yes |
| `event.timeline` | Event timeline with dates/times | Yes |
| `gallery.carousel` | Image gallery carousel | Yes |
| `video.section` | Video with transcription | Yes |
| `pricing.grid` | Pricing cards grid | Yes |
| `attachments.list` | Downloadable attachments | Yes |
| `events.list` | Related events list | Yes |
| `sponsors.list` | Sponsors/patrons list | Yes |

#### CMS JSON Structure

```json
{
  "page": "evento-dettaglio",
  "breadcrumb": [
    {"label": "Home", "url": "/"},
    {"label": "Vivere il Comune", "url": "/vivere"},
    {"label": "Eventi", "url": "/vivere/eventi"},
    {"label": "363^ Festa di Sant'Efisio", "url": null}
  ],
  "event": {
    "title": "363^ FESTA DI SANT'EFISIO",
    "subtitle": "FESTIVAL - DAL 01 MAGGIO 2019 AL 04 MAGGIO 2019",
    "description": "Il 1° maggio 2019 Cagliari e tutta la Sardegna festeggiano...",
    "dates": {
      "start": "2019-05-01T09:00:00",
      "end": "2019-05-04T18:00:00",
      "display_start": "01 MAGGIO 2019",
      "display_end": "04 MAGGIO 2019"
    },
    "tags": ["Cultura", "Vivere la città"],
    "image": {
      "src": "/images/sant-efisio.jpg",
      "caption": "Una didascalia per l'immagine sopra."
    },
    "last_update": "2019-06-10T16:00:00"
  },
  "blocks": [
    {
      "type": "page.index",
      "data": {
        "title": "INDICE DELLA PAGINA",
        "items": [
          {"label": "Cos'è", "anchor": "cos-e"},
          {"label": "A chi è rivolto", "anchor": "a-chi-e-rivolto"},
          {"label": "Luogo", "anchor": "luogo"},
          {"label": "Date e orari", "anchor": "date-e-orari"},
          {"label": "Costi", "anchor": "costi"},
          {"label": "Allegati", "anchor": "allegati"},
          {"label": "Appuntamenti", "anchor": "appuntamenti"},
          {"label": "Contatti", "anchor": "contatti"},
          {"label": "Patrocinato da", "anchor": "patrocinato"},
          {"label": "Sponsor", "anchor": "sponsor"}
        ]
      }
    },
    {
      "type": "content.section",
      "data": {
        "id": "cos-e",
        "title": "COS'È?",
        "content": "<p>Content about Sant'Efisio history...</p>"
      }
    },
    {
      "type": "gallery.carousel",
      "data": {
        "title": "GALLERIA DI IMMAGINI",
        "images": [
          {"src": "/img/1.jpg", "alt": "Festa di Sant'Efisio"},
          {"src": "/img/2.jpg", "alt": "Festa di Sant'Efisio"}
        ]
      }
    },
    {
      "type": "video.section",
      "data": {
        "title": "VIDEO",
        "video_url": "...",
        "transcription": "<p>Trascrizione del video...</p>"
      }
    },
    {
      "type": "event.timeline",
      "data": {
        "id": "date-e-orari",
        "title": "DATE E ORARI",
        "events": [
          {"date": "01 MAG", "time": "09:00", "label": "INIZIO EVENTO"},
          {"date": "04 MAG", "time": "18:00", "label": "FINE EVENTO"}
        ]
      }
    },
    {
      "type": "pricing.grid",
      "data": {
        "id": "costi",
        "title": "COSTI",
        "items": [
          {"title": "TRIBUNA 1 E 4", "price": "€ 30", "description": "..."},
          {"title": "TRIBUNA 3", "price": "€ 25", "description": "..."}
        ]
      }
    },
    {
      "type": "attachments.list",
      "data": {
        "id": "allegati",
        "title": "ALLEGATI",
        "files": [
          {"name": "LOCANDINA SANT'EFISIO 2019", "type": "PDF", "size": "1MB", "url": "/docs/locandina.pdf"}
        ]
      }
    },
    {
      "type": "sponsors.list",
      "data": {
        "patrons": ["Regione Autonome della Sardegna"],
        "sponsors": ["Provincia di Cagliari", "Sogaer"]
      }
    }
  ]
}
```

---

### 7. Amministrazione (`amministrazione.html`)

#### HTML Structure

```html
<main>
  <!-- Breadcrumb -->
  <nav class="breadcrumb">
    <ol>
      <li><a href="/">Home</a></li>
      <li class="active">Amministrazione</li>
    </ol>
  </nav>

  <!-- Page Title -->
  <section class="it-page-title">
    <h1>AMMINISTRAZIONE</h1>
    <p class="lead">Tutte le informazioni sulla struttura amministrativa...</p>
  </section>

  <!-- Section: In Evidenza -->
  <section class="it-highlighted-section">
    <h2>IN EVIDENZA</h2>
    <div class="cards-grid">
      <div class="card">
        <h3>SINDACO</h3>
        <p>Description...</p>
      </div>
      <div class="card">
        <h3>TURISMO</h3>
        <p>Description...</p>
      </div>
      <div class="card">
        <h3>PIANIFICAZIONE STRATEGICA E TERRITORIALE</h3>
        <p>Description...</p>
      </div>
    </div>
  </section>

  <!-- Section: Esplora l'Amministrazione -->
  <section class="it-explorer-section">
    <h2>ESPLORA L'AMMINISTRAZIONE</h2>
    <div class="explorer-grid">
      <a href="#" class="explorer-card">
        <h3>AREE AMMINISTRATIVE</h3>
      </a>
      <a href="#" class="explorer-card">
        <h3>DOCUMENTI E DATI</h3>
      </a>
      <a href="#" class="explorer-card">
        <h3>ENTI E FONDAZIONI</h3>
      </a>
      <a href="#" class="explorer-card">
        <h3>ORGANI DI GOVERNO</h3>
      </a>
      <a href="#" class="explorer-card">
        <h3>PERSONALE AMMINISTRATIVO</h3>
      </a>
      <a href="#" class="explorer-card">
        <h3>POLITICI</h3>
      </a>
      <a href="#" class="explorer-card">
        <h3>UFFICI</h3>
      </a>
    </div>
  </section>

  <!-- Feedback Section -->
  <section class="it-feedback-section">
    <!-- Rating form -->
  </section>

  <!-- Quick Links Section -->
  <section class="it-quick-links">
    <!-- Contatta, Problemi, Cerca, Forse stavi cercando -->
  </section>
</main>
```

#### Blocks Needed

| Block Type | Description | Reusable |
|------------|-------------|----------|
| `highlighted.cards` | Highlighted content cards | Yes |
| `explorer.grid` | Navigation explorer cards | Yes |

#### CMS JSON Structure

```json
{
  "page": "amministrazione",
  "breadcrumb": [
    {"label": "Home", "url": "/"},
    {"label": "Amministrazione", "url": null}
  ],
  "blocks": [
    {
      "type": "page.title",
      "data": {
        "title": "AMMINISTRAZIONE",
        "subtitle": "Tutte le informazioni sulla struttura amministrativa..."
      }
    },
    {
      "type": "highlighted.cards",
      "data": {
        "title": "IN EVIDENZA",
        "cards": [
          {
            "title": "SINDACO",
            "description": "Description...",
            "url": "/amministrazione/sindaco"
          },
          {
            "title": "TURISMO",
            "description": "Description...",
            "url": "/amministrazione/turismo"
          },
          {
            "title": "PIANIFICAZIONE STRATEGICA E TERRITORIALE",
            "description": "Description...",
            "url": "/amministrazione/pianificazione"
          }
        ]
      }
    },
    {
      "type": "explorer.grid",
      "data": {
        "title": "ESPLORA L'AMMINISTRAZIONE",
        "items": [
          {"title": "AREE AMMINISTRATIVE", "url": "/amministrazione/aree"},
          {"title": "DOCUMENTI E DATI", "url": "/amministrazione/documenti"},
          {"title": "ENTI E FONDAZIONI", "url": "/amministrazione/enti"},
          {"title": "ORGANI DI GOVERNO", "url": "/amministrazione/organi"},
          {"title": "PERSONALE AMMINISTRATIVO", "url": "/amministrazione/personale"},
          {"title": "POLITICI", "url": "/amministrazione/politici"},
          {"title": "UFFICI", "url": "/amministrazione/uffici"}
        ]
      }
    }
  ]
}
```

---

## Block Types Catalog

### Header/Footer Blocks (Global)

| Block Type | Description | Used In |
|------------|-------------|---------|
| `header.full` | Complete header with top bar, brand, nav, search | All pages |
| `footer.full` | Complete footer with quick links, sitemap, contacts, legal | All pages |
| `breadcrumb` | Navigation breadcrumb | All pages except homepage |
| `feedback.form` | Rating + feedback form | All pages |

### Content Blocks

| Block Type | Description | Used In |
|------------|-------------|---------|
| `page.title` | H1 + lead paragraph | Argomenti, Amministrazione |
| `page.header.service` | Service header with status, CTA, share, actions, tags | Servizio Dettaglio |
| `page.header.news` | News header with meta, share, actions, tags, image | Notizia |
| `page.header.event` | Event header with dates, share, actions, tags, image | Evento |
| `confirmation.banner` | Success confirmation with details | Appuntamento Conferma |
| `page.index` | Table of contents with anchors | Servizio, Notizia, Evento, Appuntamento |
| `content.section` | Generic content section with heading | All detail pages |

### Specialized Blocks

| Block Type | Description | Used In |
|------------|-------------|---------|
| `hero.featured` | Large featured content card | Homepage |
| `governance.grid` | 3-column governance cards | Homepage |
| `events.calendar` | Calendar widget with events list | Homepage |
| `topics.grid` | Topic cards in grid layout | Homepage, Argomenti |
| `topics.highlighted` | Highlighted topics section | Argomenti |
| `topics.explorer` | Full topics list with descriptions | Argomenti |
| `thematic-sites.list` | Thematic site links | Homepage |
| `highlighted.cards` | Highlighted content cards | Amministrazione |
| `explorer.grid` | Navigation explorer cards | Argomenti, Amministrazione |

### Service/Event Blocks

| Block Type | Description | Used In |
|------------|-------------|---------|
| `service.timeline` | Timeline for deadlines | Servizio |
| `service.actions` | Service action buttons | Servizio |
| `event.timeline` | Event timeline with dates/times | Evento |
| `gallery.carousel` | Image gallery carousel | Evento |
| `video.section` | Video with transcription | Evento |
| `pricing.grid` | Pricing cards grid | Evento |
| `attachments.list` | Downloadable attachments | Servizio, Evento |
| `events.list` | Related events list | Evento |
| `sponsors.list` | Sponsors/patrons list | Evento |

### Utility Blocks

| Block Type | Description | Used In |
|------------|-------------|---------|
| `office.card` | Office information card | Servizio, Notizia, Appuntamento, Evento |
| `contact.card` | Contact information card | Servizio, Evento |
| `contact.grid` | Multiple contact cards | Evento |
| `people.list` | People mentioned | Notizia |
| `tags.list` | Topic tags | Servizio, Notizia, Evento |
| `calendar.export` | Calendar export buttons | Appuntamento |
| `quick-links.grid` | Quick action cards | Argomenti, Amministrazione |
| `related-content.grid` | Related content cards | Servizio |

---

## CMS JSON Structure

### Global Structure

```json
{
  "page": "page-type",
  "breadcrumb": [
    {"label": "Home", "url": "/"},
    {"label": "Section", "url": "/section"},
    {"label": "Current", "url": null}
  ],
  "blocks": [
    {
      "type": "block.type",
      "data": {
        // Block-specific data
      }
    }
  ]
}
```

### Block Data Patterns

#### Content Section
```json
{
  "type": "content.section",
  "data": {
    "id": "section-id",
    "title": "SECTION TITLE",
    "content": "<p>HTML content...</p>"
  }
}
```

#### Card Grid
```json
{
  "type": "topics.grid",
  "data": {
    "title": "SECTION TITLE",
    "items": [
      {"title": "Card Title", "description": "...", "url": "/link"}
    ]
  }
}
```

#### Timeline
```json
{
  "type": "service.timeline",
  "data": {
    "id": "timeline-id",
    "title": "TIMELINE TITLE",
    "events": [
      {"month": "GEN", "label": "Event label"}
    ]
  }
}
```

---

## Implementation Priority

### Phase 1: Foundation (Week 1-2)

| Priority | Block Type | Complexity | Dependencies |
|----------|------------|------------|--------------|
| P0 | `header.full` | High | None |
| P0 | `footer.full` | High | None |
| P0 | `breadcrumb` | Low | None |
| P0 | `feedback.form` | Medium | None |
| P0 | `content.section` | Low | None |
| P1 | `page.title` | Low | None |
| P1 | `page.index` | Medium | None |

### Phase 2: Homepage (Week 2-3)

| Priority | Block Type | Complexity | Dependencies |
|----------|------------|------------|--------------|
| P0 | `hero.featured` | Medium | None |
| P0 | `governance.grid` | Medium | None |
| P1 | `events.calendar` | High | None |
| P1 | `topics.grid` | Medium | None |
| P2 | `thematic-sites.list` | Low | None |

### Phase 3: Listing Pages (Week 3-4)

| Priority | Block Type | Complexity | Dependencies |
|----------|------------|------------|--------------|
| P0 | `topics.highlighted` | Medium | `topics.grid` |
| P0 | `topics.explorer` | Medium | `topics.grid` |
| P0 | `highlighted.cards` | Medium | None |
| P0 | `explorer.grid` | Medium | None |
| P1 | `quick-links.grid` | Low | None |

### Phase 4: Detail Pages (Week 4-6)

| Priority | Block Type | Complexity | Dependencies |
|----------|------------|------------|--------------|
| P0 | `page.header.service` | High | None |
| P0 | `page.header.news` | High | None |
| P0 | `page.header.event` | High | None |
| P0 | `office.card` | Low | None |
| P0 | `contact.card` | Low | None |
| P1 | `service.timeline` | Medium | None |
| P1 | `service.actions` | Low | None |
| P1 | `event.timeline` | Medium | None |
| P1 | `tags.list` | Low | None |
| P2 | `gallery.carousel` | High | None |
| P2 | `video.section` | Medium | None |
| P2 | `pricing.grid` | Medium | None |
| P2 | `attachments.list` | Low | None |
| P2 | `events.list` | Medium | None |
| P2 | `sponsors.list` | Low | None |
| P2 | `people.list` | Low | None |
| P3 | `calendar.export` | Low | None |
| P3 | `confirmation.banner` | Low | None |
| P3 | `related-content.grid` | Medium | None |

---

## Reusability Matrix

### Block Reusability Across Pages

| Block Type | Homepage | Argomenti | Appuntamento | Servizio | Notizia | Evento | Amministrazione | Total |
|------------|----------|-----------|--------------|----------|---------|--------|-----------------|-------|
| `header.full` | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | 7/7 |
| `footer.full` | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | 7/7 |
| `breadcrumb` | - | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | 6/7 |
| `feedback.form` | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | ✓ | 7/7 |
| `content.section` | - | - | ✓ | ✓ | ✓ | ✓ | - | 4/7 |
| `page.title` | - | ✓ | - | - | - | - | ✓ | 2/7 |
| `page.index` | - | - | ✓ | ✓ | ✓ | ✓ | - | 4/7 |
| `office.card` | - | - | ✓ | ✓ | ✓ | ✓ | - | 4/7 |
| `contact.card` | - | - | - | ✓ | - | ✓ | - | 2/7 |
| `tags.list` | - | - | - | ✓ | ✓ | ✓ | - | 3/7 |
| `topics.grid` | ✓ | ✓ | - | - | - | - | - | 2/7 |
| `highlighted.cards` | - | - | - | - | - | - | ✓ | 1/7 |
| `explorer.grid` | - | ✓ | - | - | - | - | ✓ | 2/7 |

### Reusability Score

| Score Range | Block Count | Percentage |
|-------------|-------------|------------|
| High (5-7 pages) | 4 blocks | Foundation blocks |
| Medium (3-4 pages) | 5 blocks | Content blocks |
| Low (1-2 pages) | 20+ blocks | Specialized blocks |

### Key Insights

1. **Foundation blocks** (header, footer, breadcrumb, feedback) are 100% reusable - implement once, use everywhere
2. **Content blocks** (content.section, page.index, office.card) are 50-60% reusable - core building blocks
3. **Specialized blocks** are page-specific - implement only when needed
4. **Total unique block types**: ~30
5. **Blocks needed for MVP** (Homepage + 1 detail page): ~15

---

## Next Steps

1. **Implement foundation blocks** first (header, footer, breadcrumb, feedback)
2. **Build Homepage** to validate block system
3. **Create block registry** in CMS JSON structure
4. **Implement detail page blocks** incrementally
5. **Test reusability** across different page types

---

*Document created: 2026-03-30*  
*Source: Design Comuni Pagine Statiche v2.4.0*
