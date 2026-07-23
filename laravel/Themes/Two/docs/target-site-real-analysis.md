# Analisi Completa Sito Target - Radioprotezione
## https://lightseagreen-dogfish-560272.hostingersite.com/

**Data**: 6 Febbraio 2026
**Sito**: Radioprotezione e Sicurezza Radiologica per Studi Dentistici e Veterinari
**Obiettivo**: Replicare e migliorare il design per TechPlanner

---

## 1. Overview del Sito Target

Il sito target è un sito professionale B2B per servizi di radioprotezione, con:
- **Design moderno e professionale**
- **Struttura chiaria e gerarchica**
- **Focalizzazione su trust e competenza**
- **Lead generation forte**
- **SEO ottimizzato**

---

## 2. Color Scheme e Palette

### Colori Identificati
Analizzando il sito, i colori principali sono:

#### Primary Colors
- **Header/Hero**: Sfondo bianco con elementi accent
- **Section Cards**: Bianco con bordi leggeri
- **Accent**: Probabilmente blu professionale (standard per servizi medici/tecnici)
- **Text**: #333333 (nero scuro per readability)
- **Secondary Text**: #666666 (grigio medio)

#### Background Colors
- **Main Background**: #FFFFFF (bianco puro)
- **Section Backgrounds**: Alternanza tra bianco e grigio chiaro (#F9FAFB)
- **Card Background**: #FFFFFF con ombra

### Raccomandazione per TechPlanner
Implementare un color scheme professionale:
```css
--primary: #3B82F6 (blu professionale)
--primary-dark: #2563EB
--primary-light: #60A5FA
--secondary: #10B981 (verde per success/trust)
--accent: #F59E0B (arancione per CTAs)
--text-primary: #1F2937
--text-secondary: #4B5563
--bg-primary: #FFFFFF
--bg-secondary: #F9FAFB
--bg-tertiary: #F3F4F6
```

---

## 3. Tipografia

### Font Stack
Il sito usa probabilmente:
- **Font family**: System fonts o Google Fonts (probabilmente Inter o Roboto)
- **Heading Weights**: 600-700 (semibold/bold)
- **Body Weight**: 400 (regular)
- **Line Height**: 1.6 per readability

### Hierarchy
```css
H1: ~48px (desktop), 32px (mobile)
H2: ~36px (desktop), 28px (mobile)
H3: ~24px
H4: ~20px
Body: ~16px
Small: ~14px
```

---

## 4. Layout e Struttura

### Sezioni Principali

#### 1. Header/Navigation
- Logo a sinistra
- Menu navigation a destra
- Background bianco
- Border bottom sottile
- Sticky? Probabilmente

#### 2. Hero Section
- Background bianco
- Title grande e bold
- Subtitle descrittivo
- 3-4 card di servizio orizzontali
- Ogni card con: icona, titolo, breve descrizione, CTA "Scopri di più"

#### 3. "Perché è Critico" Section
- Background grigio chiaro (#F9FAFB)
- Title sezione
- 4 card con: icona, titolo, breve testo
- Focus su rischi, obblighi, sicurezza, responsabilità

#### 4. "Settori di Specializzazione"
- Background bianco
- Title sezione
- Subtitle
- 2 colonne (Odontoiatria | Veterinaria)
- Ogni colonna con lista di servizi e immagine

#### 5. "Cosa Controlliamo"
- Background grigio chiaro
- Card centrale con checklist
- Icone + testo per ogni punto
- Layout centrale focalizzato

#### 6. "Dicono di Noi" (Testimonials)
- Background bianco
- Grid 4 colonne
- Ogni testimonial: foto, nome, ruolo, location, quote, data
- Design clean e professionale

#### 7. "Risorse Utili"
- Background grigio chiaro
- 2 card (Guide PDF)
- Icone + titolo + descrizione

#### 8. Newsletter Section
- Background bianco
- Form email capture
- CTA "Iscriviti"

---

## 5. Componenti UI Identificati

### 1. Service Cards (Hero)
```html
<div class="service-card">
    <icon />
    <h3>Titolo Servizio</h3>
    <p>Descrizione breve...</p>
    <a href="#">Scopri di più →</a>
</div>
```
**Style**: White background, border, hover effect, icon top-left

### 2. Info Cards ("Perché è Critico")
```html
<div class="info-card">
    <icon />
    <h4>Titolo</h4>
    <p>Testo</p>
</div>
```
**Style**: Grid layout, icon prominent, clean design

### 3. Sector Cards
```html
<div class="sector-card">
    <h3>Titolo Settore</h3>
    <p>Subtitle</p>
    <ul>
        <li><strong>Item:</strong> Descrizione</li>
    </ul>
    <img src="..." alt="..." />
</div>
```
**Style**: Half content, half image, responsive stack

### 4. Checklist Card
```html
<div class="checklist-card">
    <h3>Cosa Controlliamo</h3>
    <ul>
        <li><icon /> Item</li>
        <!-- etc -->
    </ul>
</div>
```
**Style**: Centered, checklist with icons

### 5. Testimonial Cards
```html
<div class="testimonial-card">
    <img src="..." alt="Foto" />
    <h4>Nome</h4>
    <p>Ruolo</p>
    <p>Location</p>
    <p>"Quote..."</p>
    <p>Data</p>
</div>
```
**Style**: Photo top, content below, professional styling

### 6. Resource Cards
```html
<div class="resource-card">
    <icon />
    <h4>Titolo Guida</h4>
    <p>Descrizione</p>
</div>
```
**Style**: Simple, download icon

---

## 6. Spaziatura e Margini

### Section Spacing
```css
Section padding: 80px 0 (desktop), 40px 0 (mobile)
Gap between elements: 24px
Card margin: 16px
Text line height: 1.6
```

### Component Spacing
```css
Service cards: gap-8
Info cards: gap-6
Testimonials: gap-6
```

---

## 7. Animazioni e Interazioni

### Identificate
- **Hover effects sulle card**: Slight lift (translateY(-4px))
- **Button hover**: Color change + lift
- **Image hover**: Slight zoom
- **Smooth scroll**: Navigazione fluida

### Raccomandazioni per TechPlanner
```css
/* Hover effects */
.service-card {
    transition: all 0.3s ease;
}
.service-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 40px rgba(0,0,0,0.1);
}

/* Button effects */
.btn-primary {
    transition: all 0.2s ease;
}
.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}
```

---

## 8. Responsive Design

### Breakpoints
```css
Mobile: < 768px (single column)
Tablet: 768px - 1024px (2 columns)
Desktop: > 1024px (3-4 columns)
```

### Responsive Behavior
- **Service cards**: 3 → 2 → 1 column
- **Info cards**: 4 → 2 → 1 column
- **Testimonials**: 4 → 2 → 1 column
- **Sector columns**: Side by side → stacked

---

## 9. Content Strategy

### Copywriting Style
- **Professional e autorevole**
- **Focus su trust e competenza**
- **Benefit-oriented**
- **Specifico e dettagliato**
- **Social proof prominente**

### Structure per TechPlanner
1. **Hero**: Value proposition + 3 servizi principali
2. **Why Critical**: Pain points + solutions
3. **Sectors**: Specializzazioni + use cases
4. **What We Do**: Detailed service breakdown
5. **Testimonials**: Social proof (4+ testimonials)
6. **Resources**: Downloadable guides
7. **CTA**: Newsletter/Contact

---

## 10. SEO Elements

### Identificati
- **H1 principale**: "Radioprotezione e Sicurezza Radiologica..."
- **H2 sections**: "Controllo Radioprotezione", "Perché è Critico", etc.
- **Meta description**: Probabilmente ottimizzato
- **Alt text**: Presente sulle immagini
- **Internal links**: "Scopri di più" links
- **Schema markup**: Probabilmente presente (FAQ, Organization)

### Raccomandazioni per TechPlanner
```html
<!-- H1 -->
<h1>TechPlanner - Sistema di Gestione Tecnica Aziendale</h1>

<!-- H2 -->
<h2>Servizi Principali</h2>
<h2>Perché Scegliere TechPlanner</h2>
<h2>Settori di Specializzazione</h2>
<h2>Cosa Facciamo</h2>
<h2>Cosa Dicono i Nostri Clienti</h2>
<h2>Risorse Gratuite</h2>

<!-- Schema markup -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "TechPlanner",
  "description": "...",
  "aggregateRating": {...}
}
</script>
```

---

## 11. Call-to-Action Placement

### CTAs Identificati
1. **Hero services**: "Scopri di più →" (secondary CTAs)
2. **Resource cards**: Download icons
3. **Newsletter**: "Iscriviti" (primary CTA)

### Raccomandazioni per TechPlanner
- **Primary CTA**: "Inizia la Prova Gratuita" (hero)
- **Secondary CTAs**: "Scopri di più" su servizi
- **Trust CTA**: "Guarda le testimonianze"
- **Lead capture**: "Rimani aggiornato" (newsletter)

---

## 12. Elementi da Replicare per TechPlanner

### ✅ Devo Implementare

#### 1. Nuovo Hero Layout
- 3 card di servizio orizzontali
- Ogni card con icona, titolo, descrizione, CTA
- Layout più focalizzato su servizi

#### 2. "Perché TechPlanner" Section
- 4 card con pain points
- Focus su: Rischi competitivi, Efficienza, Conformità, Scalabilità

#### 3. Settori di Specializzazione
- 2 colonne: Manufacturing | Services
- Lista use cases specifici
- Immagini contestuali

#### 4. "Cosa Facciamo" Checklist
- Card centrale con checklist
- Icone + testo
- Design focalizzato

#### 5. Testimonials Section
- Grid 4 colonne
- Foto, nome, ruolo, location, quote, data
- Professional design

#### 6. Resources Section
- 2-3 card con guide PDF
- Download icons

#### 7. Newsletter Section
- Email capture form
- CTA "Iscriviti"

---

## 13. Piano di Implementazione per TechPlanner

### Phase 1: Aggiornamento Color Scheme
```css
/* tailwind.config.js */
colors: {
  primary: {
    light: '#60A5FA',
    DEFAULT: '#3B82F6',
    dark: '#2563EB',
  },
  secondary: {
    light: '#34D399',
    DEFAULT: '#10B981',
    dark: '#059669',
  },
  accent: {
    light: '#FBBF24',
    DEFAULT: '#F59E0B',
    dark: '#D97706',
  },
}
```

### Phase 2: Nuovi Blocchi da Creare

#### Blocco 1: Services Grid (Hero)
```json
{
  "type": "services-grid",
  "slug": "hero-services",
  "data": {
    "view": "pub_theme::components.blocks.services.grid",
    "title": "TechPlanner - Sistema di Gestione Tecnica",
    "subtitle": "Ottimizza processi, automatizza pianificazione e potenzia produttività",
    "services": [
      {
        "icon": "heroicon-o-users",
        "title": "Smart HR Management",
        "description": "Gestione intelligente del personale con algoritmi avanzati e valutazione performance in tempo reale.",
        "cta": "Scopri di più →",
        "url": "/employee/admin"
      },
      {
        "icon": "heroicon-o-chart-bar",
        "title": "Predictive Analytics",
        "description": "Prevedi trend, analizza dati e prendi decisioni basate su reportistica real-time.",
        "cta": "Scopri di più →",
        "url": "/chart/admin"
      },
      {
        "icon": "heroicon-o-sparkles",
        "title": "Automazione Totale",
        "description": "Elimina task ripetitivi e focalizzati su strategie che generano valore.",
        "cta": "Scopri di più →",
        "url": "/admin/settings"
      }
    ]
  }
}
```

#### Blocco 2: Why Critical
```json
{
  "type": "why-critical",
  "slug": "why-techplanner",
  "data": {
    "view": "pub_theme::components.blocks.why-critical.grid",
    "title": "Perché TechPlanner è Essenziale?",
    "subtitle": "Non è solo software: è la base per operare con efficienza e competitività",
    "points": [
      {
        "icon": "heroicon-o-exclamation-triangle",
        "title": "Rischi Operativi",
        "description": "Gestione manuale inefficiente porta a errori costosi e perdite di produttività."
      },
      {
        "icon": "heroicon-o-shield-check",
        "title": "Conformità Normativa",
        "description": "Rispetto degli standard aziendali e normative di settore per gestione compliant."
      },
      {
        "icon": "heroicon-o-light-bulb",
        "title": "Efficienza Operativa",
        "description": "Automazione intelligente che riduce tempi e aumenta la qualità del lavoro."
      },
      {
        "icon": "heroicon-o-chart-line-up",
        "title": "Scalabilità",
        "description": "Cresci senza problemi: sistema progettato per scalare con la tua azienda."
      }
    ]
  }
}
```

#### Blocco 3: Sectors
```json
{
  "type": "sectors",
  "slug": "industry-sectors",
  "data": {
    "view": "pub_theme::components.blocks.sectors.split",
    "title": "Settori di Specializzazione",
    "subtitle": "Competenza verticale per esigenze specifiche",
    "sectors": [
      {
        "title": "Manufacturing",
        "description": "Ottimizzazione per aziende manifatturiere",
        "use_cases": [
          "Gestione Turni e Presenze",
          "Pianificazione Produzione",
          "Controllo Qualità",
          "Manutenzione Preventiva"
        ],
        "image": "https://images.unsplash.com/photo-1581091226825-a6a2a5aee158"
      },
      {
        "title": "Services",
        "description": "Soluzioni per servizi professionali",
        "use_cases": [
          "Gestione Progetti",
          "Resource Allocation",
          "Client Relationship",
          "Performance Tracking"
        ],
        "image": "https://images.unsplash.com/photo-1551434678-e076c223a692"
      }
    ]
  }
}
```

#### Blocco 4: What We Do
```json
{
  "type": "what-we-do",
  "slug": "our-approach",
  "data": {
    "view": "pub_theme::components.blocks.what-we-do.checklist",
    "title": "Cosa Facciamo",
    "subtitle": "Il nostro approccio copre ogni aspetto della gestione tecnica aziendale",
    "checklist": [
      {
        "icon": "heroicon-o-cpu-chip",
        "title": "Automazione Intelligente",
        "description": "Sostituiamo processi manuali con algoritmi efficienti e affidabili."
      },
      {
        "icon": "heroicon-o-chart-pie",
        "title": "Analytics Avanzati",
        "description": "Dashboard in tempo reale per decisioni basate sui dati."
      },
      {
        "icon": "heroicon-o-users-group",
        "title": "Gestione Team",
        "description": "Ottimizzazione risorse umane con performance tracking e scheduling."
      },
      {
        "icon": "heroicon-o-lock-closed",
        "title": "Sicurezza",
        "description": "Protezione dati e conformità GDPR per totale tranquillità."
      },
      {
        "icon": "heroicon-o-document-text",
        "title": "Reporting",
        "description": "Reportistica dettagliata e personalizzabile per stakeholder."
      },
      {
        "icon": "heroicon-arrows-pointing-out",
        "title": "Integrazione",
        "description": "Connessione con i tuoi sistemi esistenti senza interruzioni."
      }
    ]
  }
}
```

#### Blocco 5: Testimonials
```json
{
  "type": "testimonials",
  "slug": "customer-reviews",
  "data": {
    "view": "pub_theme::components.blocks.testimonials.grid",
    "title": "Cosa Dicono i Nostri Clienti",
    "subtitle": "La tranquillità di chi ha scelto TechPlanner",
    "testimonials": [
      {
        "name": "Mario Rossi",
        "role": "CEO",
        "company": "TechSolutions S.r.l.",
        "location": "Milano, MI",
        "avatar": "https://images.unsplash.com/photo-1472099645785-5658abf4ff4e",
        "rating": 5,
        "quote": "TechPlanner ha trasformato completamente il modo in cui gestiamo il nostro team. Produttività aumentata del 40% in soli 3 mesi.",
        "date": "15 gennaio 2026"
      },
      {
        "name": "Elena Bianchi",
        "role": "Operations Manager",
        "company": "Innovatech Spa",
        "location": "Roma, RM",
        "avatar": "https://images.unsplash.com/photo-1494790108377-be9c29b29330",
        "rating": 5,
        "quote": "L'automazione dei processi ha eliminato ore di lavoro manuale. Il team può finalmente focalizzarsi su strategia e innovazione.",
        "date": "20 novembre 2025"
      },
      {
        "name": "Paolo Verdi",
        "role": "IT Director",
        "company": "Digital Flow",
        "location": "Torino, TO",
        "avatar": "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d",
        "rating": 5,
        "quote": "Dashboard analytics in tempo reale ci permettono di prendere decisioni basate sui dati. ROI positivo dal primo mese.",
        "date": "5 dicembre 2025"
      },
      {
        "name": "Giulia Neri",
        "role": "HR Manager",
        "company": "Future Works",
        "location": "Bologna, BO",
        "avatar": "https://images.unsplash.com/photo-1438761681033-6461ffad8d80",
        "rating": 5,
        "quote": "Gestione dipendenti mai stata così semplice. Scheduling automatico e performance tracking hanno rivoluzionato il nostro workflow.",
        "date": "1 febbraio 2026"
      }
    ]
  }
}
```

#### Blocco 6: Resources
```json
{
  "type": "resources",
  "slug": "free-resources",
  "data": {
    "view": "pub_theme::components.blocks.resources.grid",
    "title": "Risorse Utili per la Tua Azienda",
    "subtitle": "Scarica le nostre guide gratuite per approfondire TechPlanner",
    "resources": [
      {
        "title": "Guida TechPlanner per PMI",
        "description": "Vademecum essenziale sull'implementazione di TechPlanner in piccole e medie imprese.",
        "icon": "heroicon-o-document-text",
        "download_url": "/downloads/guida-pmi.pdf"
      },
      {
        "title": "Guida Automazione Processi",
        "description": "Manuale operativo per eliminare task ripetitivi e ottimizzare il workflow.",
        "icon": "heroicon-o-cog-6-tooth",
        "download_url": "/downloads/guida-automazione.pdf"
      }
    ]
  }
}
```

#### Blocco 7: Newsletter
```json
{
  "type": "newsletter",
  "slug": "email-subscription",
  "data": {
    "view": "pub_theme::components.blocks.newsletter.form",
    "title": "Rimani Aggiornato",
    "description": "Iscriviti alla nostra newsletter per ricevere aggiornamenti, consigli pratici e offerte esclusive.",
    "cta_label": "Iscriviti",
    "privacy_note": "I tuoi dati sono al sicuro. Nello spam non ci crediamo."
  }
}
```

---

## 14. Nuova Struttura home.json

```json
{
  "content_blocks": {
    "it": [
      {
        "type": "services-grid",
        "slug": "hero-services",
        "data": { /* services grid data */ }
      },
      {
        "type": "why-critical",
        "slug": "why-techplanner",
        "data": { /* why critical data */ }
      },
      {
        "type": "sectors",
        "slug": "industry-sectors",
        "data": { /* sectors data */ }
      },
      {
        "type": "what-we-do",
        "slug": "our-approach",
        "data": { /* what we do data */ }
      },
      {
        "type": "testimonials",
        "slug": "customer-reviews",
        "data": { /* testimonials data */ }
      },
      {
        "type": "resources",
        "slug": "free-resources",
        "data": { /* resources data */ }
      },
      {
        "type": "newsletter",
        "slug": "email-subscription",
        "data": { /* newsletter data */ }
      }
    ]
  }
}
```

---

## 15. Prossimi Passi

### Immediati
1. ✅ Creare nuovo blocco `services-grid.blade.php`
2. ✅ Creare nuovo blocco `why-critical.blade.php`
3. ✅ Creare nuovo blocco `sectors.blade.php`
4. ✅ Creare nuovo blocco `what-we-do.blade.php`
5. ✅ Creare nuovo blocco `testimonials.blade.php`
6. ✅ Creare nuovo blocco `resources.blade.php`
7. ✅ Creare nuovo blocco `newsletter.blade.php`
8. ✅ Aggiornare `home.json` con nuovi blocchi
9. ✅ Aggiornare `tailwind.config.js` con nuovi colori
10. ✅ Testare e verificare il risultato

### SEO & Ottimizzazioni
- Aggiungere meta tags ottimizzati
- Implementare structured data
- Ottimizzare immagini
- Aggiungere alt text
- Implementare hreflang

---

## 16. Success Metrics

### Obiettivi
- ✅ Homepage più professionale e focalizzata
- ✅ Trust aumentato con testimonials
- ✅ Lead generation migliorata con newsletter
- ✅ SEO ottimizzato per conversioni
- ✅ Design coerente e moderno

### KPI
- Time on page: > 2 minuti
- Scroll depth: 80%+
- Email sign-ups: 100+/settimana
- CTA clicks: > 5%
- Lighthouse score: > 90

---

**Documento Versione**: 1.0
**Creato**: 6 Febbraio 2026
**Stato**: Pronto per Implementazione