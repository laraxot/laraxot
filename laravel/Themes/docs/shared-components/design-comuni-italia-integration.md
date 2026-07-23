# 🇮🇹 Design Comuni Italia - Integration Analysis

**Date:** 2025-10-02  
**Theme:** Sixteen (AGID Bootstrap Italia)  
**Status:** Analysis & Implementation Plan

---

## 📋 Executive Summary

**Design Comuni Italia** è il modello ufficiale per i siti web dei Comuni Italiani, sviluppato da Designers Italia e basato su Bootstrap Italia. Il nostro tema Sixteen è già allineato con Bootstrap Italia, rendendo l'integrazione naturale e completa.

**Compatibilità con FixCity:** PERFETTA - Il tema Sixteen usa già Bootstrap Italia come base!

---

## 🔍 Analisi Progetto Design Comuni

### Informazioni Generali

| Aspetto | Dettaglio |
|---------|-----------|
| **Repository** | https://github.com/italia/design-comuni-pagine-statiche |
| **Demo Live** | https://italia.github.io/design-comuni-pagine-statiche |
| **Licenza** | EUPL-1.2 |
| **Releases** | 18 versioni |
| **Contributors** | 14 sviluppatori |
| **Stars** | ~100+ |
| **Ultimo Update** | Attivo |

### Stack Tecnologico

#### Core Technologies
- **Bootstrap Italia** - Framework UI ufficiale PA
- **Handlebars** - Template engine
- **SCSS** - Preprocessore CSS
- **Webpack** - Module bundler
- **Node.js** >= 18.0.0 (consigliato v20)

#### Conformità
- ✅ **AGID Guidelines** - Linee guida design PA
- ✅ **Accessibilità** - WCAG 2.1 Level AA
- ✅ **Responsive** - Mobile-first approach
- ✅ **Performance** - Ottimizzato per velocità

---

## 📚 Struttura Template

### Sezioni Principali

#### 1. **Generali**
- Homepage
- Lista risorse per categorie
- Mappa del sito
- Ricerca
- Pagina 404

#### 2. **Amministrazione**
- Pagina principale amministrazione
- Organi di governo
- Aree amministrative
- Uffici
- Persone
- Documenti e dati
- Atti normativi

#### 3. **Novità**
- Lista novità (notizie, comunicati, avvisi)
- Dettaglio notizia
- Dettaglio comunicato
- Dettaglio avviso
- Eventi
- Calendario eventi

#### 4. **Servizi**
- Lista servizi
- Servizi per categoria
- Scheda servizio dettaglio
- Come fare per...
- Prenotazione appuntamento
- Richiesta assistenza
- Segnalazione disservizio

#### 5. **Vivere il Comune**
- Luoghi
- Luoghi per categoria
- Dettaglio luogo
- Eventi
- Dettaglio evento

---

## 🎯 Mapping con FixCity

### Corrispondenze Dirette

| Design Comuni | FixCity Module | Implementazione |
|---------------|----------------|-----------------|
| **Segnalazione Disservizio** | Fixcity (Tickets) | ✅ Già presente |
| **Prenotazione Appuntamento** | User (Appointments) | 🔄 Da implementare |
| **Richiesta Assistenza** | Fixcity (Support) | 🔄 Da implementare |
| **Servizi** | Cms (Services) | ✅ Parziale |
| **Novità** | Blog (News) | ✅ Già presente |
| **Luoghi** | Geo (Places) | 🔄 Da implementare |
| **Eventi** | Blog (Events) | ✅ Parziale |
| **Documenti** | Cms (Documents) | ✅ Già presente |

---

## 🏗️ Implementazione nel Tema Sixteen

### Struttura Proposta

```
Themes/Sixteen/
├── resources/
│   ├── views/
│   │   ├── comuni/                    # Template Design Comuni
│   │   │   ├── generali/
│   │   │   │   ├── homepage.blade.php
│   │   │   │   ├── mappa-sito.blade.php
│   │   │   │   └── ricerca.blade.php
│   │   │   ├── amministrazione/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── organi.blade.php
│   │   │   │   └── uffici.blade.php
│   │   │   ├── novita/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── notizia.blade.php
│   │   │   │   └── evento.blade.php
│   │   │   ├── servizi/
│   │   │   │   ├── index.blade.php
│   │   │   │   ├── categoria.blade.php
│   │   │   │   ├── dettaglio.blade.php
│   │   │   │   ├── prenotazione.blade.php
│   │   │   │   ├── assistenza.blade.php
│   │   │   │   └── segnalazione.blade.php
│   │   │   └── vivere/
│   │   │       ├── luoghi.blade.php
│   │   │       ├── luogo-dettaglio.blade.php
│   │   │       └── eventi.blade.php
│   │   └── components/
│   │       └── comuni/                # Componenti Design Comuni
│   │           ├── card-servizio.blade.php
│   │           ├── card-notizia.blade.php
│   │           ├── card-evento.blade.php
│   │           ├── breadcrumb.blade.php
│   │           ├── hero.blade.php
│   │           └── footer-comuni.blade.php
│   └── assets/
│       └── scss/
│           └── comuni/                # Stili Design Comuni
│               ├── _variables.scss
│               ├── _servizi.scss
│               ├── _novita.scss
│               └── comuni.scss
└── docs/
    └── DESIGN_COMUNI_ITALIA_INTEGRATION.md
```

---

## 💻 Implementazione Componenti

### 1. Card Servizio

```blade
{{-- resources/views/components/comuni/card-servizio.blade.php --}}
@props([
    'title',
    'description',
    'category',
    'icon' => 'it-pa',
    'url',
])

<div class="card card-teaser shadow p-4 rounded border border-light">
    <svg class="icon">
        <use href="/bootstrap-italia/svg/sprites.svg#{{ $icon }}"></use>
    </svg>
    <div class="card-body">
        <div class="category-top">
            <span class="category">{{ $category }}</span>
        </div>
        <h5 class="card-title">
            <a href="{{ $url }}">{{ $title }}</a>
        </h5>
        <p class="card-text">{{ $description }}</p>
    </div>
</div>
```

### 2. Hero Section

```blade
{{-- resources/views/components/comuni/hero.blade.php --}}
@props([
    'title',
    'subtitle' => null,
    'breadcrumbs' => [],
    'image' => null,
])

<div class="it-hero-wrapper it-dark it-overlay">
    @if($image)
    <div class="img-responsive-wrapper">
        <div class="img-responsive">
            <div class="img-wrapper">
                <img src="{{ $image }}" title="{{ $title }}" alt="{{ $title }}">
            </div>
        </div>
    </div>
    @endif
    
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="it-hero-text-wrapper bg-dark">
                    @if(count($breadcrumbs) > 0)
                    <nav class="breadcrumb-container" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            @foreach($breadcrumbs as $crumb)
                            <li class="breadcrumb-item @if($loop->last) active @endif">
                                @if($loop->last)
                                    {{ $crumb['label'] }}
                                @else
                                    <a href="{{ $crumb['url'] }}">{{ $crumb['label'] }}</a>
                                @endif
                            </li>
                            @endforeach
                        </ol>
                    </nav>
                    @endif
                    
                    <h1 class="no_toc">{{ $title }}</h1>
                    
                    @if($subtitle)
                    <p class="d-none d-lg-block">{{ $subtitle }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
```

### 3. Scheda Servizio

```blade
{{-- resources/views/comuni/servizi/dettaglio.blade.php --}}
<x-layouts.sixteen>
    <x-comuni.hero 
        :title="$service->title"
        :breadcrumbs="[
            ['label' => 'Home', 'url' => route('home')],
            ['label' => 'Servizi', 'url' => route('servizi.index')],
            ['label' => $service->category->name, 'url' => route('servizi.categoria', $service->category)],
            ['label' => $service->title, 'url' => '#'],
        ]"
    />

    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-12 col-lg-3">
                <div class="cmp-navscroll sticky-top" aria-labelledby="accordion-title-one">
                    <nav class="navbar it-navscroll-wrapper navbar-expand-lg">
                        <div class="menu-wrapper">
                            <div class="link-list-wrapper">
                                <ul class="link-list">
                                    <li class="nav-item">
                                        <a class="nav-link" href="#cos-e">
                                            <span>Cos'è</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#a-chi-si-rivolge">
                                            <span>A chi si rivolge</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#come-fare">
                                            <span>Come fare</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#cosa-serve">
                                            <span>Cosa serve</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#costi">
                                            <span>Costi e vincoli</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#tempi">
                                            <span>Tempi e scadenze</span>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="#contatti">
                                            <span>Contatti</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
            </div>

            <!-- Content -->
            <div class="col-12 col-lg-9">
                <div class="it-page-sections-container">
                    <!-- Cos'è -->
                    <section id="cos-e" class="it-page-section mb-5">
                        <h4>Cos'è</h4>
                        <div class="richtext-wrapper">
                            {!! $service->description !!}
                        </div>
                    </section>

                    <!-- A chi si rivolge -->
                    <section id="a-chi-si-rivolge" class="it-page-section mb-5">
                        <h4>A chi si rivolge</h4>
                        <div class="richtext-wrapper">
                            {!! $service->target_audience !!}
                        </div>
                    </section>

                    <!-- Come fare -->
                    <section id="come-fare" class="it-page-section mb-5">
                        <h4>Come fare</h4>
                        <div class="richtext-wrapper">
                            {!! $service->how_to !!}
                        </div>
                        
                        @if($service->has_online_access)
                        <div class="callout callout-highlight ps-3 warning">
                            <div class="callout-title">
                                <svg class="icon icon-sm"><use href="/bootstrap-italia/svg/sprites.svg#it-info-circle"></use></svg>
                                <span class="text">Accedi al servizio</span>
                            </div>
                            <p>
                                Puoi accedere al servizio online tramite SPID o CIE.
                            </p>
                            <a href="{{ route('servizi.accedi', $service) }}" class="btn btn-primary">
                                Accedi con SPID
                            </a>
                        </div>
                        @endif
                    </section>

                    <!-- Cosa serve -->
                    <section id="cosa-serve" class="it-page-section mb-5">
                        <h4>Cosa serve</h4>
                        <div class="richtext-wrapper">
                            {!! $service->requirements !!}
                        </div>
                    </section>

                    <!-- Costi -->
                    <section id="costi" class="it-page-section mb-5">
                        <h4>Costi e vincoli</h4>
                        <div class="richtext-wrapper">
                            @if($service->cost)
                                {!! $service->cost_description !!}
                            @else
                                <p>Questo servizio è gratuito.</p>
                            @endif
                        </div>
                    </section>

                    <!-- Tempi -->
                    <section id="tempi" class="it-page-section mb-5">
                        <h4>Tempi e scadenze</h4>
                        <div class="richtext-wrapper">
                            {!! $service->timing !!}
                        </div>
                    </section>

                    <!-- Contatti -->
                    <section id="contatti" class="it-page-section mb-5">
                        <h4>Contatti</h4>
                        <div class="card-wrapper card-teaser-wrapper">
                            @foreach($service->contacts as $contact)
                            <div class="card card-teaser shadow p-4 rounded border border-light">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $contact->office }}</h5>
                                    <div class="card-text">
                                        @if($contact->phone)
                                        <p>
                                            <svg class="icon icon-sm"><use href="/bootstrap-italia/svg/sprites.svg#it-telephone"></use></svg>
                                            <a href="tel:{{ $contact->phone }}">{{ $contact->phone }}</a>
                                        </p>
                                        @endif
                                        
                                        @if($contact->email)
                                        <p>
                                            <svg class="icon icon-sm"><use href="/bootstrap-italia/svg/sprites.svg#it-mail"></use></svg>
                                            <a href="mailto:{{ $contact->email }}">{{ $contact->email }}</a>
                                        </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </section>

                    <!-- Ulteriori informazioni -->
                    <section class="it-page-section mb-5">
                        <h4>Ulteriori informazioni</h4>
                        
                        @if($service->documents->count() > 0)
                        <div class="card-wrapper card-teaser-wrapper">
                            <h5>Documenti</h5>
                            @foreach($service->documents as $document)
                            <div class="card card-teaser shadow p-3 rounded border border-light mb-2">
                                <div class="card-body">
                                    <svg class="icon icon-sm"><use href="/bootstrap-italia/svg/sprites.svg#it-file-pdf"></use></svg>
                                    <a href="{{ $document->url }}" target="_blank">{{ $document->title }}</a>
                                    <span class="text-muted ms-2">({{ $document->size }})</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        @if($service->links->count() > 0)
                        <div class="mt-4">
                            <h5>Link utili</h5>
                            <ul class="link-list">
                                @foreach($service->links as $link)
                                <li>
                                    <a href="{{ $link->url }}" target="_blank">
                                        {{ $link->title }}
                                        <svg class="icon icon-sm"><use href="/bootstrap-italia/svg/sprites.svg#it-external-link"></use></svg>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </section>
                </div>
            </div>
        </div>
    </div>
</x-layouts.sixteen>
```

---

## 📊 Database Schema Extensions

### Servizi (Services)

```sql
-- Estensioni per conformità Design Comuni
ALTER TABLE services ADD COLUMN target_audience TEXT;
ALTER TABLE services ADD COLUMN how_to TEXT;
ALTER TABLE services ADD COLUMN requirements TEXT;
ALTER TABLE services ADD COLUMN cost DECIMAL(10,2);
ALTER TABLE services ADD COLUMN cost_description TEXT;
ALTER TABLE services ADD COLUMN timing TEXT;
ALTER TABLE services ADD COLUMN has_online_access BOOLEAN DEFAULT FALSE;
ALTER TABLE services ADD COLUMN spid_required BOOLEAN DEFAULT FALSE;
ALTER TABLE services ADD COLUMN cie_required BOOLEAN DEFAULT FALSE;
ALTER TABLE services ADD COLUMN status ENUM('draft', 'published', 'archived') DEFAULT 'draft';

-- Contatti servizio
CREATE TABLE service_contacts (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    service_id BIGINT NOT NULL,
    office VARCHAR(255),
    phone VARCHAR(50),
    email VARCHAR(255),
    address TEXT,
    opening_hours JSON,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

-- Documenti servizio
CREATE TABLE service_documents (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    service_id BIGINT NOT NULL,
    title VARCHAR(255),
    description TEXT,
    file_path VARCHAR(500),
    file_size VARCHAR(20),
    mime_type VARCHAR(100),
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);

-- Link utili servizio
CREATE TABLE service_links (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    service_id BIGINT NOT NULL,
    title VARCHAR(255),
    url VARCHAR(500),
    description TEXT,
    FOREIGN KEY (service_id) REFERENCES services(id) ON DELETE CASCADE
);
```

---

## 🚀 Roadmap Implementazione

### Phase 1: Foundation (Week 1-2)
- [ ] Creare componenti Blade base Design Comuni
- [ ] Implementare hero, breadcrumb, card components
- [ ] Setup SCSS per stili Design Comuni
- [ ] Migrare template esistenti

### Phase 2: Servizi (Week 3-4)
- [ ] Estendere database schema servizi
- [ ] Implementare scheda servizio completa
- [ ] Aggiungere prenotazione appuntamenti
- [ ] Implementare richiesta assistenza

### Phase 3: Novità & Eventi (Week 5-6)
- [ ] Template lista novità
- [ ] Template dettaglio notizia/comunicato
- [ ] Template eventi
- [ ] Calendario eventi

### Phase 4: Luoghi & Amministrazione (Week 7-8)
- [ ] Sezione luoghi
- [ ] Dettaglio luogo
- [ ] Sezione amministrazione
- [ ] Organi e uffici

---

## 📋 Checklist Conformità AGID

### Accessibilità
- [ ] WCAG 2.1 Level AA
- [ ] Navigazione da tastiera
- [ ] Screen reader friendly
- [ ] Contrasto colori adeguato
- [ ] Form accessibili

### Performance
- [ ] Lighthouse score > 90
- [ ] First Contentful Paint < 1.8s
- [ ] Time to Interactive < 3.8s
- [ ] Cumulative Layout Shift < 0.1

### SEO
- [ ] Meta tags completi
- [ ] Structured data (Schema.org)
- [ ] Sitemap XML
- [ ] robots.txt
- [ ] Open Graph tags

### Security
- [ ] HTTPS only
- [ ] CSP headers
- [ ] HSTS enabled
- [ ] Cookie policy
- [ ] Privacy policy

---

## 💡 Innovazioni Proposte

### 1. Integrazione SPID/CIE
Autenticazione tramite identità digitale per accesso servizi.

### 2. Notifiche Push
Sistema notifiche per aggiornamenti servizi e scadenze.

### 3. Chatbot Assistenza
Bot intelligente per supporto cittadini 24/7.

### 4. App Mobile
Progressive Web App per accesso mobile ottimizzato.

### 5. Accessibilità Avanzata
Modalità alto contrasto, font dyslexia-friendly, sintesi vocale.

---

## 📊 Metriche di Successo

| Metrica | Target | Misurazione |
|---------|--------|-------------|
| Conformità AGID | 100% | Audit checklist |
| Accessibilità WCAG | AA | Automated testing |
| Performance Lighthouse | >90 | Lighthouse CI |
| Soddisfazione utenti | >4.5/5 | Survey |
| Servizi online | >80% | Admin dashboard |

---

## 🎯 Conclusioni

### Pro dell'Integrazione
✅ **Già compatibile** - Sixteen usa Bootstrap Italia  
✅ **Standard ufficiale** - Modello approvato AGID  
✅ **Accessibilità garantita** - WCAG 2.1 AA compliant  
✅ **Manutenzione attiva** - Progetto governativo  
✅ **Community** - Supporto community italiana  

### Raccomandazione

**IMPLEMENTARE SUBITO** - L'integrazione dei template Design Comuni nel tema Sixteen è essenziale per conformità AGID e offre un'esperienza utente ottimale per i cittadini.

**Priority: CRITICAL**  
**Effort: MEDIUM**  
**Impact: VERY HIGH**  
**Compliance: MANDATORY**

---

**📝 Documento preparato da:** Super Mucca 🐮  
**📅 Data:** 2025-10-02  
**📧 Contatto:** sixteen-theme@fixcity.com

---

*Prossimi passi: Iniziare implementazione Phase 1 con componenti base.*
