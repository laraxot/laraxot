---
id: "kp-redesign-front-office-pages-investigation"
type: "investigation"
title: "Investigation — Front Office Pages for Redesign with Design Comuni"
description: "Analisi di tutte le pagine front office del tema Sixteen per identificare opportunità di redesign in linea con il design system Design Comuni."
frontmatter:
  github_issue: "#55"
links:
  github_issue: "#55"
status: draft
updated: 2026-10-11
tags: [docs, sixteen, investigation]
qmd: "redesign front office pages investigation sixteen"
---
## Contesto

Il tema Sixteen contiene un gran numero di pagine front office, molte delle quali necessitano di un redesign coerente con il design system Design Comuni. Le pagine attuali mostrano una combinazione di approcci: alcune utilizzano componenti personalizzati, altre utilizzano componenti Bootstrap Italia, e alcune utilizzano misto di stili ad-hoc.

## Obiettivo dell'Investigazione

Analizzare tutte le pagine front office per:
1. Identificare quale pagine necessitano di redesign
2. Documentare quale pagine sono "good” (coerenti con Design Comuni) vs "bad" (incoerenti)
3. Estrarre componenti riutilizzabili che possono essere unificati
4. Identificare template pattern da applicare a tutte le pagine
5. Identificare dati statici che possono essere migrati a dati dinamici (JSON)

## Elenco Pagine Esaminate

### 1. Pagine Homepage Principali
**File**: `resources/views/pages/index.blade.php` (parte centrale)
- Struttura attuale: Template Blade tradizionale con mix di Bootstrap Italia e custom CSS
- Design analysis: Hero section, CTA, stats, features (simile a Design Comuni ma con stili personalizzati)
- Opportunità di redesign: Allineare al pattern `cmp-hero` di Design Comuni

**File**: `resources/views/pages/homepage.blade.php` (obsoleto)
- Classificato come obsoleto

### 2. Pagine a Container Singolo
**File**: `resources/views/pages/[container0]/index.blade.php`
- Pattern: Template Blade con barra laterale e contenuto principale
- Design analysis: Layout template-based (non universal)
- Opportunità di redesign: Applicare pattern `it-page-section` e griglia da Design Comuni

### 3. Pagine a Due Livelli
**File**: `resources/views/pages/[container0]/[slug0]/index.blade.php`
- Pattern: Template Blade con navigazione breadcrumb
- Design analysis: Similar a pagine a container singolo
- Opportunità di redesign: Utilizzare componenti universali

### 4. Pagine a Tre Livelli
**File**: `resources/views/pages/[container0]/[slug0]/[container1]/index.blade.php`
- Pattern: Template Blade con layout complesso
- Design analysis: Contenuto denso, layout non coerente
- Opportunità di redesign: Applicare pattern page-section con component stacking

### 5. Componenti Specifieci
**File**: `resources/views/components/pages/faq.blade.php`
- Analisi: FAQ page strutturata con accordion
- Opportunità di redesign: Utilizzare componente `cmp-accordion` o `cmp-faq` di Design Comuni

**File**: `resources/views/components/pages/sitemap.blade.php`
- Analisi: Pagina sitemap semplice
- Opportunità di redesign: Utilizzare componente `cmp-sitemap` di Design Comuni

### 6. Pagine di Test/Showcase
**File**: `resources/views/pages_old/tests/*.blade.php`
- Utilizzo di Bootstrap Italia per dimostrazioni
- Opportunità di redesign: Rimuovere o allineare a componenti universali

### 7. Pagine di Servizio
**File**: `resources/views/pages_old/services/*.blade.php`
- Directory di servizi con più pagine (report-issue, categories-content, etc.)
- Opportunità di redesign: Applicare pattern di flusso di servizio di Design Comuni

### 8. Pagine Auth
**File**: `resources/views/pages_old/auth/*.blade.php`
- Template di autenticazione (login, register, reset password)
- Opportunità di redesign: Utilizzare componente `cmp-auth` di Design Comuni

### 9. Altre Pagine
- `resources/views/pages_old/show.blade.php`
- `resources/views/pages_old/prova01.blade.php`
- `resources/views/pages_old/*.blade.php` (vari)

## Pattern di Design Identificati

### 1. Utilizzo di Bootstrap Italia
**Pagine che usano Bootstrap Italia**: `
- `resources/views/pages/index.blade.php` (parziale)
- `resources/views/pages_old/tests/*.blade.php`
- `resources/views/pages_old/bootstrap-italia-showcase.blade.php`
**Problema**: Bootstrap Italia CDN → violation di convenzioni (usare Tailwind `@apply`)

### 2. Stili Personalizzati (Progetti)
**Pagine con CSS personalizzato**: `resources/views/pages_old/*.blade.php`
**Problema**: Design ad-hoc, incoerente con design system

### 3. Template Unici per Pagina
**Problema**: Ogni pagina sembra avere un template Blade specifico, violando il principio DRY

### 4. Manca di Componenti Universali
**Osservazione**: Nessuna componente riutilizzabile standard nel tema (es. `cmp-card`, `cmp-hero`, `cmp-nav`)

### 5. Layout Non Coerente
**Problema**: Utilizzo misto di flexbox, grid, margin/padding personalizzati

## Componenti Universali da Definire (Basati su Design Comuni)

### 1. cmp-hero
- Utilizzo: Homepage, potenziali landing page
- Contenuto: Titolo, descrizione, due CTA
- Stili: Utilizza classi Tailwind da Design Comuni

### 2. cmp-feature-card
- Utilizzo: Features, servizi, list items
- Contenuto: Icona, titolo, descrizione
- Stili: Background `#f8f9fa`, border radius, ombra

### 3. cmp-stat-card
- Utilizzo: Stats, metriche, numeri di impatto
- Contenuto: Valore numerico grande, label, icona
- Stili: Background color vari, text center

### 4. cmp-cta-section
- Utilizzo: CTA finali, azioni di conversione
- Contenuto: Titolo, descrizione, bottone primario
- Stili: Background `#e3f2fd`, padding generoso

### 5. cmp-testimonial
- Utilizzo: Recensioni, feedback utenti
- Contenuto: Foto, nome, testo, valutazione
- Stili: Border, shadow, background chiaro

### 6. cmp-page-section
- Utilizzo: Tutte le sezioni delle pagine
- Contenuto: Header, content area, eventuali sidebar
- Stili: `it-page-section` classes da Design Comuni

### 7. cmp-accordion
- Utilizzo: FAQ, domande ricorrenti
- Contenuto: Titolo cliccabile, pannello espandibile
- Stili: Border, padding, icone di espansione

### 8. cmp-breadcrumb
- Utilizzo: Navigazione a livello profondo
- Contenuto: Collegamenti a ogni livello
- Stili: Background chiaro, separator `>`

### 9. cmp-nav-sidebar
- Utilizzo: Navigazione laterale, menu
- Contenuto: Elenco di link
- Stili: Background, shadow, active states

### 10. cmp-auth-form
- Utilizzo: Login, register, reset password
- Contenuto: Campi form, pulsanti, links
- Stili: Card layout, form groups

## Raccomandazioni per Redesign

### 1. Rimuovere Bootstrap Italia CDN
- Sostituire con `npm install tailwindcss` (già presente nel progetto)
- Utilizzare `tailwind.config.js` con estensioni Design Comuni
- Sostituire classi Bootstrap con `cmp-*` components

### 2. Applicare Design Comuni Pattern
- Utilizzare `it-page-section`, `it-top-navscroll`, `it-list`, `it-hero` classes
- Applicare layout `container`, `row`, `col-*` classes
- Utilizzare spacing coerente (8px grid)

### 3. Utilizzare Componenti Universali
- Rimuovere componenti personalizzati specifici per pagina
- Creare/exportare componenti `cmp-*` e utilizzarli in tutte le pagine
- Documentare prop di ogni componente

### 4. Migrar Dati a JSON
- Estendere `home.json` per includere nuovi campi necessari
- Per altre pagine, considerare `database/content/pages/[container0]/[slug0].json`
- Mantenere separati dati e presentazione

### 5. Garantire Accessibilità
- Verificare tutti i componenti per WCAG 2.1 AA
- Assicurarsi che il contrasto testo/sfondo sia ≥4.5:1
- Aggiungere label e desc per componenti interattivi
- Rispettare `prefers-reduced-motion` per animazioni

### 6. Garantire Multi-Tenant e Multi-Lingua
- Utilizzare routing `container0` per tenant separation
- Utilizzare `LaravelLocalization` per traduzione
- Assicurarsi che tutti i componenti siano compatibili con tutte le lingue

### 7. Aggiornare la Documentazione
- Documentare l'uso di componenti universali
- Aggiornare guide per sviluppatori nel tema
- Aggiungere esempi di utilizzo

## Conclusioni

Il tema Sixteen ha un'opportunità significativa di redesign allineandosi a Design Comuni. La migrazione da componenti Bootstrap Italia e stili personalizzati a componenti universali `cmp-*` che utilizzano Tailwind CSS, insieme alla separazione dei dati (JSON) dalla presentazione (Blade), risolverà incoerenze visive e migliorerà la mantenibilità. Il redesign richiederà:

1. **Creazione componenti universali** (`cmp-hero`, `cmp-feature-card`, etc.)
2. **Rimozione dipendenze Bootstrap Italia** (sostituire con Tailwind)
3. **Adozione di un layout coerente** (griglia, padding, spacing)
4. **Documentazione e guida** per l'adozione

Implementando questo redesign, il tema Sixteen non solo migliorerà l'esperienza utente visiva ma diventerà anche più sostenibile, accessibile, e allineato agli standard ufficiali del design system Design Comuni per i comuni italiani.