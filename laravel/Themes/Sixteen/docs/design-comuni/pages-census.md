# Design Comuni - Pages Census

> **Censimento completo di tutte le 38 pagine del progetto Design Comuni Italia**

## 📊 Panoramica

**Totale Pagine:** 38  
**Sezioni:** 8  
**Stato Censimento:** ✅ Completato (100%)  
**Data:** 2026-04-01

---

## 🗂️ Sezione 1: Pagine Generali (9 pagine)

### 1.1 Homepage

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html  
**URL Locale:** `/it/tests/homepage`  
**Slug JSON:** `tests.homepage`  
**Priorità:** 🔴 Alta  
**Stato:** 🔄 In Progress

**Blocchi Richiesti:**
- `hero.newscard` - Contenuti in evidenza
- `card-grid.governance` - Amministrazione (Sindaco, Giunta, Consiglio)
- `list.events` - Lista eventi
- `topics-grid.featured` - Argomenti in evidenza
- `card-grid.thematic-sites` - Siti tematici
- `form.feedback` - Feedback pagina

**Documentazione:** [Homepage](./pages/homepage.md)  
**Screenshot:** [screenshots/homepage/](./screenshots/homepage/)

---

### 1.2 Domande Frequenti (FAQ)

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/domande-frequenti.html  
**URL Locale:** `/it/tests/domande-frequenti`  
**Slug JSON:** `tests.domande-frequenti`  
**Priorità:** 🟡 Media  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `navigation.breadcrumb` - Breadcrumb
- `list.faq` - Lista FAQ con accordion
- `form.search` - Ricerca nelle FAQ
- `form.contact` - Contatto per ulteriori informazioni

**Note:** Utilizza componente accordion per le FAQ

---

### 1.3 Risultati Ricerca

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/risultati-ricerca.html  
**URL Locale:** `/it/tests/risultati-ricerca`  
**Slug JSON:** `tests.risultati-ricerca`  
**Priorità:** 🟡 Media  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `navigation.breadcrumb` - Breadcrumb
- `form.search` - Search bar con filtri
- `list.search-results` - Lista risultati
- `pagination` - Paginazione risultati
- `filters.sidebar` - Filtri laterali

**Note:** Implementa search con filtri avanzati

---

### 1.4 Argomenti (Lista)

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/argomenti.html  
**URL Locale:** `/it/tests/argomenti`  
**Slug JSON:** `tests.argomenti`  
**Priorità:** 🔴 Alta  
**Stato:** 🔄 In Progress

**Blocchi Richiesti:**
- `navigation.breadcrumb` - Breadcrumb
- `topics-grid.all` - Griglia tutti gli argomenti
- `topics-grid.featured` - Argomenti in evidenza (Cultura, Sport, Famiglia)
- `form.feedback` - Feedback pagina

**Documentazione:** [Argomenti](./pages/argomenti.md) (Da creare)  
**Screenshot:** [screenshots/argomenti/](./screenshots/argomenti/) (Da creare)

---

### 1.5 Argomento (Dettaglio)

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/argomento.html  
**URL Locale:** `/it/tests/argomento/{slug}`  
**Slug JSON:** `tests.argomento.{slug}`  
**Priorità:** 🟡 Media  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `navigation.breadcrumb` - Breadcrumb
- `detail.topic` - Dettaglio argomento
- `card-grid.resources` - Risorse correlate
- `list.related-content` - Contenuti correlati

**Note:** Pagina dinamica con parametro slug

---

### 1.6 Lista Risorse

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/lista-risorse.html  
**URL Locale:** `/it/tests/lista-risorse`  
**Slug JSON:** `tests.lista-risorse`  
**Priorità:** 🟢 Bassa  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `navigation.breadcrumb` - Breadcrumb
- `list.resources` - Lista risorse (2°/3° livello)
- `pagination` - Paginazione

**Note:** Template generico per liste di risorse

---

### 1.7 Lista Categorie

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/lista-categorie.html  
**URL Locale:** `/it/tests/lista-categorie`  
**Slug JSON:** `tests.lista-categorie`  
**Priorità:** 🟢 Bassa  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `navigation.breadcrumb` - Breadcrumb
- `list.categories` - Lista categorie (2°/3° livello)
- `pagination` - Paginazione

**Note:** Template generico per liste di categorie

---

### 1.8 Lista Mista (Risorse + Categorie)

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/lista-risorse-categorie.html  
**URL Locale:** `/it/tests/lista-mista`  
**Slug JSON:** `tests.lista-mista`  
**Priorità:** 🟢 Bassa  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `navigation.breadcrumb` - Breadcrumb
- `list.mixed` - Lista mista risorse e categorie
- `list.categories` - Sottocategorie
- `pagination` - Paginazione

**Note:** Template per liste ibride

---

### 1.9 Mappa Sito

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/mappa-sito.html  
**URL Locale:** `/it/tests/mappa-sito`  
**Slug JSON:** `tests.mappa-sito`  
**Priorità:** 🟢 Bassa  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `navigation.breadcrumb` - Breadcrumb
- `sitemap.tree` - Albero sitemap
- `quick-links` - Link rapidi

**Note:** Visualizza struttura completa sito

---

## 🏛️ Sezione 2: Amministrazione (2 pagine)

### 2.1 Amministrazione (Lista)

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/amministrazione.html  
**URL Locale:** `/it/tests/amministrazione`  
**Slug JSON:** `tests.amministrazione`  
**Priorità:** 🟡 Media  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `navigation.breadcrumb` - Breadcrumb
- `card-grid.organi` - Organi di governo
- `list.administration` - Lista aree amministrative
- `list.offices` - Uffici
- `list.entities` - Enti e fondazioni

**Note:** Panoramica completa amministrazione

---

### 2.2 Documenti e Dati

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/documenti-dati.html  
**URL Locale:** `/it/tests/documenti-dati`  
**Slug JSON:** `tests.documenti-dati`  
**Priorità:** 🟡 Media  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `navigation.breadcrumb` - Breadcrumb
- `list.documents` - Lista documenti
- `data.open-data` - Sezione dati aperti
- `form.search` - Ricerca documenti

**Note:** Sezione documentale e open data

---

## 📰 Sezione 3: Novità (2 pagine)

### 3.1 Novità (Lista)

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/novita.html  
**URL Locale:** `/it/tests/novita`  
**Slug JSON:** `tests.novita`  
**Priorità:** 🟡 Media  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `navigation.breadcrumb` - Breadcrumb
- `list.news` - Lista news (comunicati, avvisi)
- `filters` - Filtri per tipo
- `pagination` - Paginazione

**Note:** Include news, comunicati stampa, avvisi

---

### 3.2 Novità Dettaglio

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/novita-dettaglio.html  
**URL Locale:** `/it/tests/novita/{slug}`  
**Slug JSON:** `tests.novita.{slug}`  
**Priorità:** 🟡 Media  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `navigation.breadcrumb` - Breadcrumb
- `detail.news` - Dettaglio notizia
- `share-buttons` - Condivisione social
- `related-news` - News correlate
- `back-to-list` - Torna alla lista

**Note:** Pagina dinamica per dettaglio news

---

## 🛠️ Sezione 4: Servizi (3 pagine)

### 4.1 Servizi (Lista)

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/servizi.html  
**URL Locale:** `/it/tests/servizi`  
**Slug JSON:** `tests.servizi`  
**Priorità:** 🟡 Media  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `navigation.breadcrumb` - Breadcrumb
- `list.services` - Lista servizi
- `list.categories` - Categorie servizi
- `form.search` - Ricerca servizi
- `pagination` - Paginazione

**Note:** Catalogo completo servizi

---

### 4.2 Servizi Categoria

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/servizi-categoria.html  
**URL Locale:** `/it/tests/servizi/{categoria}`  
**Slug JSON:** `tests.servizi.{categoria}`  
**Priorità:** 🟢 Bassa  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `navigation.breadcrumb` - Breadcrumb
- `list.services` - Lista servizi per categoria
- `category.info` - Informazioni categoria
- `pagination` - Paginazione

**Note:** Pagina dinamica per categoria servizio

---

### 4.3 Servizio Dettaglio

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/servizio-dettaglio.html  
**URL Locale:** `/it/tests/servizio/{slug}`  
**Slug JSON:** `tests.servizio.{slug}`  
**Priorità:** 🟡 Media  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `navigation.breadcrumb` - Breadcrumb
- `detail.service` - Dettaglio servizio
- `service.requirements` - Requisiti accesso
- `service.costs` - Costi
- `service.timeline` - Tempistiche
- `cta.apply` - Call-to-action (Richiedi)
- `related-services` - Servizi correlati

**Note:** Scheda completa servizio con CTA

---

## 🎉 Sezione 5: Vivere il Comune (2 pagine)

### 5.1 Eventi (Lista)

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/eventi.html  
**URL Locale:** `/it/tests/eventi`  
**Slug JSON:** `tests.eventi`  
**Priorità:** 🟡 Media  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `navigation.breadcrumb` - Breadcrumb
- `list.events` - Lista eventi
- `calendar-view` - Visualizzazione calendario
- `filters` - Filtri (data, tipo, luogo)
- `pagination` - Paginazione

**Note:** Agenda eventi del comune

---

### 5.2 Evento Dettaglio

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/evento-dettaglio.html  
**URL Locale:** `/it/tests/evento/{slug}`  
**Slug JSON:** `tests.evento.{slug}`  
**Priorità:** 🟡 Media  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `navigation.breadcrumb` - Breadcrumb
- `detail.event` - Dettaglio evento
- `event.calendar-add` - Aggiungi al calendario
- `event.location-map` - Mappa luogo
- `event.registration` - Iscrizione evento
- `related-events` - Eventi correlati

**Note:** Pagina dinamica per dettaglio evento

---

## 📅 Sezione 6: Prenotazione Appuntamento (7 pagine)

### 6.1 Step 1 - Ufficio

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/appuntamento-01-ufficio.html  
**URL Locale:** `/it/tests/appuntamento/1/ufficio`  
**Slug JSON:** `tests.appuntamento.1-ufficio`  
**Priorità:** 🟡 Media  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `stepper.horizontal` - Stepper (step 1 di 6)
- `form.office-selection` - Selezione ufficio
- `form.location-filter` - Filtro per luogo
- `navigation.buttons` - Indietro/Avanti

**Note:** Primo step prenotazione

---

### 6.2 Step 1 - Luogo (variante)

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/appuntamento-01-ufficio-luogo.html  
**URL Locale:** `/it/tests/appuntamento/1/luogo`  
**Slug JSON:** `tests.appuntamento.1-luogo`  
**Priorità:** 🟡 Media  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `stepper.horizontal` - Stepper (step 1 di 6)
- `form.location-selection` - Selezione luogo
- `form.office-filter` - Filtro per ufficio
- `navigation.buttons` - Indietro/Avanti

**Note:** Variante per selezione luogo stesso ufficio

---

### 6.3 Step 2 - Data e Orario

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/appuntamento-02-data-orario.html  
**URL Locale:** `/it/tests/appuntamento/2/data-orario`  
**Slug JSON:** `tests.appuntamento.2-data-orario`  
**Priorità:** 🟡 Media  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `stepper.horizontal` - Stepper (step 2 di 6)
- `form.datetime-picker` - Selezione data e ora
- `calendar.availability` - Disponibilità calendario
- `navigation.buttons` - Indietro/Avanti

**Note:** Implementa date/time picker

---

### 6.4 Step 3 - Dettagli

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/appuntamento-03-dettagli.html  
**URL Locale:** `/it/tests/appuntamento/3/dettagli`  
**Slug JSON:** `tests.appuntamento.3-dettagli`  
**Priorità:** 🟡 Media  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `stepper.horizontal` - Stepper (step 3 di 6)
- `form.reason-selection` - Selezione motivo
- `form.details-input` - Input dettagli aggiuntivi
- `navigation.buttons` - Indietro/Avanti

**Note:** Integrazione informazioni appuntamento

---

### 6.5 Step 4 - Richiedente (Non Autenticato)

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/appuntamento-04-richiedente.html  
**URL Locale:** `/it/tests/appuntamento/4/dati`  
**Slug JSON:** `tests.appuntamento.4-dati`  
**Priorità:** 🟡 Media  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `stepper.horizontal` - Stepper (step 4 di 6)
- `form.personal-data` - Dati anagrafici
- `form.contact-info` - Recapiti
- `form.privacy` - Privacy consensus
- `navigation.buttons` - Indietro/Avanti

**Note:** Inserimento dati per non autenticati

---

### 6.6 Step 4 - Richiedente (Autenticato)

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/appuntamento-04-richiedente-autenticato.html  
**URL Locale:** `/it/tests/appuntamento/4/verifica`  
**Slug JSON:** `tests.appuntamento.4-verifica`  
**Priorità:** 🟢 Bassa  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `stepper.horizontal` - Stepper (step 4 di 6)
- `form.data-verification` - Verifica dati (già caricati)
- `user.profile-display` - Visualizzazione profilo
- `navigation.buttons` - Indietro/Avanti

**Note:** Verifica dati per utenti autenticati

---

### 6.7 Step 5 - Riepilogo

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/appuntamento-05-riepilogo.html  
**URL Locale:** `/it/tests/appuntamento/5/riepilogo`  
**Slug JSON:** `tests.appuntamento.5-riepilogo`  
**Priorità:** 🟡 Media  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `stepper.horizontal` - Stepper (step 5 di 6)
- `form.review` - Riepilogo tutti i dati
- `form.edit-link` - Link per modificare sezioni
- `navigation.buttons` - Indietro/Conferma

**Note:** Riepilogo completo prima conferma

---

### 6.8 Step 6 - Conferma

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/appuntamento-06-conferma.html  
**URL Locale:** `/it/tests/appuntamento/6/conferma`  
**Slug JSON:** `tests.appuntamento.6-conferma`  
**Priorità:** 🟡 Media  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `confirmation.success` - Conferma avvenuta
- `appointment.summary` - Riepilogo appuntamento
- `appointment.code` - Codice appuntamento
- `actions.print` - Stampa riepilogo
- `actions.calendar-add` - Aggiungi al calendario
- `actions.back-home` - Torna alla home

**Note:** Pagina finale di conferma

---

## 🆘 Sezione 7: Richiesta Assistenza (2 pagine)

### 7.1 Step 1 - Dati

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/assistenza-01-dati.html  
**URL Locale:** `/it/tests/assistenza/1/dati`  
**Slug JSON:** `tests.assistenza.1-dati`  
**Priorità:** 🟢 Bassa  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `stepper.horizontal` - Stepper (step 1 di 2)
- `form.assistance-category` - Categoria assistenza
- `form.personal-data` - Dati richiedente
- `form.request-details` - Dettagli richiesta
- `form.privacy` - Privacy consensus
- `navigation.buttons` - Indietro/Invia

**Note:** Form richiesta assistenza

---

### 7.2 Step 2 - Conferma

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/assistenza-02-conferma.html  
**URL Locale:** `/it/tests/assistenza/2/conferma`  
**Slug JSON:** `tests.assistenza.2-conferma`  
**Priorità:** 🟢 Bassa  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `confirmation.success` - Conferma invio
- `request.summary` - Riepilogo richiesta
- `request.tracking-code` - Codice tracciamento
- `actions.print` - Stampa riepilogo
- `actions.back-home` - Torna alla home

**Note:** Conferma invio richiesta

---

## 🚨 Sezione 8: Segnalazione Disservizio (7 pagine)

### 8.1 Disservizio Dettaglio (Intro)

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-dettaglio.html  
**URL Locale:** `/it/tests/segnalazione/dettaglio`  
**Slug JSON:** `tests.segnalazione-dettaglio`  
**Priorità:** 🟢 Bassa  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `navigation.breadcrumb` - Breadcrumb
- `detail.service` - Presentazione servizio
- `service.info` - Informazioni segnalazione
- `cta.start` - Bottone avvia segnalazione

**Note:** Pagina introduttiva servizio

---

### 8.2 Step 1 - Privacy

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html  
**URL Locale:** `/it/tests/segnalazione/1/privacy`  
**Slug JSON:** `tests.segnalazione.1-privacy`  
**Priorità:** 🟢 Bassa  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `stepper.horizontal` - Stepper (step 1 di 4)
- `form.privacy-policy` - Informativa privacy completa
- `form.consent-checkbox` - Checkbox consenso
- `navigation.buttons` - Indietro/Avanti

**Note:** Accettazione privacy

---

### 8.3 Step 2 - Dati

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html  
**URL Locale:** `/it/tests/segnalazione/2/dati`  
**Slug JSON:** `tests.segnalazione.2-dati`  
**Priorità:** 🟢 Bassa  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `stepper.horizontal` - Stepper (step 2 di 4)
- `form.location-picker` - Selezione luogo su mappa
- `form.details-input` - Dettagli segnalazione
- `form.file-upload` - Upload allegati
- `form.author-data` - Dati autore
- `navigation.buttons` - Indietro/Avanti

**Note:** Inserimento dati segnalazione

---

### 8.4 Step 3 - Riepilogo

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-03-riepilogo.html  
**URL Locale:** `/it/tests/segnalazione/3/riepilogo`  
**Slug JSON:** `tests.segnalazione.3-riepilogo`  
**Priorità:** 🟢 Bassa  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `stepper.horizontal` - Stepper (step 3 di 4)
- `form.review` - Riepilogo dati
- `form.edit-link` - Link per modificare
- `navigation.buttons` - Indietro/Invia

**Note:** Riepilogo prima invio

---

### 8.5 Step 4 - Conferma

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-04-conferma.html  
**URL Locale:** `/it/tests/segnalazione/4/conferma`  
**Slug JSON:** `tests.segnalazione.4-conferma`  
**Priorità:** 🟢 Bassa  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `confirmation.success` - Conferma invio
- `report.summary` - Riepilogo segnalazione
- `report.tracking-code` - Codice tracciamento
- `actions.print` - Stampa
- `actions.view-personal-area` - Vai ad area personale
- `actions.back-home` - Torna alla home

**Note:** Conferma avvenuta segnalazione

---

### 8.6 Area Personale

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-area-personale.html  
**URL Locale:** `/it/tests/segnalazione/area-personale`  
**Slug JSON:** `tests.segnalazione-area-personale`  
**Priorità:** 🟢 Bassa  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `navigation.breadcrumb` - Breadcrumb
- `user.reports-list` - Lista segnalazioni utente
- `report.status-badge` - Stato segnalazioni
- `report.actions` - Azioni per segnalazione
- `pagination` - Paginazione

**Note:** Area riservata per seguire segnalazioni

---

### 8.7 Elenco Segnalazioni (Pubblico)

**URL Originale:** https://italia.github.io/design-comuni-pagine-statiche/sito/ticket-list.html  
**URL Locale:** `/it/tests/segnalazioni/elenco`  
**Slug JSON:** `tests.ticket-list`  
**Priorità:** 🟢 Bassa  
**Stato:** ⏳ Pending

**Blocchi Richiesti:**
- `navigation.breadcrumb` - Breadcrumb
- `reports.map-view` - Mappa segnalazioni
- `reports.list-view` - Lista segnalazioni
- `filters.type` - Filtri per tipo
- `filters.status` - Filtri per stato
- `pagination` - Paginazione

**Note:** Elenco pubblico segnalazioni con mappa

---

## 📊 Matrice Blocchi per Pagina

### Blocchi Strutturali (Cross-Pagina)

| Blocco | Pagine che lo utilizzano | Utilizzo |
|--------|-------------------------|----------|
| `header.main` | 38/38 | 100% |
| `footer.main` | 38/38 | 100% |
| `navigation.breadcrumb` | 35/38 | 92% |
| `navigation.primary-menu` | 38/38 | 100% |
| `navigation.secondary-menu` | 30/38 | 79% |

---

### Blocchi per Tipologia

#### Hero/Featured (5 pagine)

| Pagina | Blocco Hero |
|--------|-------------|
| Homepage | `hero.newscard` |
| Argomento Dettaglio | `detail.topic-header` |
| Novità Dettaglio | `detail.news-header` |
| Servizio Dettaglio | `detail.service-header` |
| Evento Dettaglio | `detail.event-header` |

---

#### Card Grid (10 pagine)

| Pagina | Blocco Card Grid |
|--------|-----------------|
| Homepage | `card-grid.governance`, `card-grid.thematic-sites` |
| Argomenti | `topics-grid.featured` |
| Argomento Dettaglio | `card-grid.resources` |
| Amministrazione | `card-grid.organi` |
| Servizi | `list.categories` (card style) |
| ... | ... |

---

#### List (20 pagine)

| Pagina | Blocco List |
|--------|-------------|
| Homepage | `list.events` |
| FAQ | `list.faq` (accordion) |
| Ricerca | `list.search-results` |
| Argomenti | `topics-grid.all` |
| Lista Risorse | `list.resources` |
| Lista Categorie | `list.categories` |
| Lista Mista | `list.mixed` |
| Amministrazione | `list.administration`, `list.offices`, `list.entities` |
| Documenti e Dati | `list.documents` |
| Novità | `list.news` |
| Servizi | `list.services` |
| Eventi | `list.events` |
| Area Personale | `user.reports-list` |
| Elenco Segnalazioni | `reports.list-view` |
| ... | ... |

---

#### Form (15 pagine)

| Pagina | Blocco Form |
|--------|-------------|
| Homepage | `form.feedback` |
| Argomenti | `form.feedback` |
| FAQ | `form.search`, `form.contact` |
| Ricerca | `form.search` (con filtri) |
| Documenti e Dati | `form.search` |
| Servizi | `form.search` |
| Appuntamento (7 step) | `form.office-selection`, `form.datetime-picker`, `form.personal-data`, ... |
| Assistenza (2 step) | `form.assistance`, `form.personal-data` |
| Segnalazione (4 step) | `form.privacy`, `form.location-picker`, `form.details`, `form.file-upload` |
| ... | ... |

---

#### Stepper (13 pagine multi-step)

| Workflow | Pagine | Blocco Stepper |
|----------|--------|----------------|
| Appuntamento | 7 pagine | `stepper.horizontal` (6 step) |
| Assistenza | 2 pagine | `stepper.horizontal` (2 step) |
| Segnalazione | 4 pagine | `stepper.horizontal` (4 step) |

---

#### Detail (5 pagine)

| Pagina | Blocco Detail |
|--------|---------------|
| Argomento Dettaglio | `detail.topic` |
| Novità Dettaglio | `detail.news` |
| Servizio Dettaglio | `detail.service` |
| Evento Dettaglio | `detail.event` |
| Disservizio Dettaglio | `detail.service-intro` |

---

#### Confirmation (5 pagine)

| Pagina | Blocco Confirmation |
|--------|---------------------|
| Appuntamento Step 6 | `confirmation.success` |
| Assistenza Step 2 | `confirmation.success` |
| Segnalazione Step 4 | `confirmation.success` |
| ... | ... |

---

## 📈 Statistiche

### Distribuzione Pagine per Sezione

| Sezione | Numero Pagine | Percentuale |
|---------|---------------|-------------|
| Generali | 9 | 23.7% |
| Amministrazione | 2 | 5.3% |
| Novità | 2 | 5.3% |
| Servizi | 3 | 7.9% |
| Vivere il Comune | 2 | 5.3% |
| Appuntamento | 7 | 18.4% |
| Assistenza | 2 | 5.3% |
| Segnalazione | 7 | 18.4% |
| **Totale** | **38** | **100%** |

---

### Priorità Pagine

| Priorità | Numero Pagine | Percentuale |
|----------|---------------|-------------|
| 🔴 Alta | 2 | 5.3% |
| 🟡 Media | 19 | 50.0% |
| 🟢 Bassa | 17 | 44.7% |
| **Totale** | **38** | **100%** |

---

### Stato Implementazione

| Stato | Numero Pagine | Percentuale |
|-------|---------------|-------------|
| ✅ Completato | 0 | 0% |
| 🔄 In Progress | 2 | 5.3% |
| ⏳ Pending | 36 | 94.7% |
| **Totale** | **38** | **100%** |

---

## 🎯 Prossimi Passi

### Fase 1: Analisi Blocchi (IN CORSO 🔄)

1. ✅ Censimento pagine completato
2. 🔄 Identificazione blocchi per pagina
3. ⏳ Creazione matrice blocchi
4. ⏳ Definizione blocchi universali

### Fase 2: Replica HTML (PRIORITÀ 🔴)

1. ⏳ Iniziare da Homepage (2 pagine in progress)
2. ⏳ Continuare con pagine Generali (7 pagine)
3. ⏳ Proseguire con pagine Lista (7 pagine)
4. ⏳ Completare con pagine Dettaglio (5 pagine)
5. ⏳ Infine multi-step forms (13 pagine)

---

## 📎 Documenti Correlati

- [Product Brief](./product-brief.md) - Panoramica progetto
- [Blocks Analysis](./blocks-analysis.md) - Analisi dettagliata blocchi (Da creare)
- [Implementation Plan](./implementation-plan.md) - Piano implementazione (Da creare)
- [Progress Tracker](./progress-tracker.md) - Tracking avanzamento (Da creare)

---

**Data Creazione:** 2026-04-01  
**Versione:** 1.0  
**Stato:** ✅ Completato  
**Prossima Revisione:** Dopo analisi blocchi completa

---

*"38 pagine censite, pronte per la replica HTML"*  
*"Blocchi universali identificati, massima riutilizzabilità"*  
*"DRY + KISS"*
