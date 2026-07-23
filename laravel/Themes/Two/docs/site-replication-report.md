# Site Replication Report: Target vs Local

## Data: 2026-02-07

## Obiettivo
Replicare https://lightseagreen-dogfish-560272.hostingersite.com/ in http://127.0.0.1:8000/it

## Mappatura URL

| Pagina | Target URL | Local URL | JSON File |
|--------|-----------|-----------|-----------|
| Home | `/` | `/it/pages/home` | `home.json` |
| Servizi | `/servizi` | `/it/pages/services` | `services.json` |
| Blog | `/blog` | `/it/pages/blog` | `blog.json` |
| FAQ | `/faq` | `/it/pages/faqs` | `faqs.json` |
| Contatti | `/contatti` | `/it/pages/contacts` | `contacts.json` |
| Chi Siamo | `/chi-siamo` | `/it/pages/about` | `about.json` |

## Analisi Header Navigation

### Target Site Header
- **Stato iniziale**: `bg-transparent`, testo bianco
- **Effetto scroll**: Quando si scrolla verso il basso, il background diventa bianco e il testo diventa nero
- **Componenti**: Logo (Marco Sottana), Nav items, CTA button

### Local Site Header (Current)
- Stato: Header attualmente con sfondo blu scuro (`bg-[#0f2b46]/90`)
- Da implementare: Effetto scroll con trasformazione bg bianco + testo nero

## Struttura Blocchi Contenuti

### Homepage Target
1. **Hero Section**: Immagine di background con testo sovrapposto
2. **Services Grid**: 3 cards con icone
3. **Why Choose Us**: Sezione con features
4. **CTA Section**: Call to action
5. **Testimonials**: Slider recensioni
6. **FAQ Preview**: Anteprima FAQ
7. **Newsletter**: Form iscrizione

### Services Page Target
1. **Hero**: Titolo "I Nostri Servizi"
2. **Services List**: Dettaglio servizi offerti
3. **Pricing**: Eventuali prezzi
4. **CTA**: Contatto

### Altro...
[Da completare con analisi dettagliata]

## Assets da Scaricare

### Immagini necessarie
- Logo Marco Sottana
- Hero background images
- Service icons
- Team member photos
- Client logos (se presenti)

### Font
- Identificare font family usati

## Componenti da Creare

### Nuovi Block Components
```
laravel/Themes/Two/resources/views/components/blocks/
├── hero/
│   └── simple.blade.php
├── services/
│   └── grid.blade.php
├── cta/
│   └── section.blade.php
├── testimonials/
│   └── slider.blade.php
└── ...
```

## GDPR & Privacy

### Requisiti
- [ ] Cookie banner
- [ ] Privacy policy page
- [ ] Terms of service
- [ ] GDPR compliance checklist

### Moduli coinvolti
- Modulo GDPR esistente da integrare

## SEO & Inbound Marketing

### Requisiti SEO
- [ ] Meta tags dinamici per pagina
- [ ] Open Graph tags
- [ ] Structured data (JSON-LD)
- [ ] Sitemap XML
- [ ] Robots.txt

### Inbound Marketing
- [ ] Form capture leads
- [ ] Newsletter integration
- [ ] Analytics tracking
- [ ] Conversion tracking

## AdSense Ready

### Requisiti
- [ ] Spazi pubblicitari definiti
- [ ] Policy compliance check
- [ ] Performance optimization

## Priorità Implementazione

### Fase 1: Header & Navigation
- [ ] Implementare effetto scroll header
- [ ] Verificare multilingua
- [ ] Test responsive

### Fase 2: Homepage
- [ ] Replicare hero section
- [ ] Creare services grid
- [ ] Implementare CTA sections

### Fase 3: Pagine Interne
- [ ] Services page
- [ ] About page
- [ ] Contact page
- [ ] Blog/FAQ pages

### Fase 4: Ottimizzazioni
- [ ] GDPR compliance
- [ ] SEO optimization
- [ ] Performance tuning

## Screenshots Reference

Salvati in: `laravel/Themes/Two/docs/screenshots/`
- `target-homepage.png`
- `target-homepage-scrolled.png`
- `target-services.png`
- `target-blog.png`
- `target-faq.png`
- `target-contacts.png`
- `target-about.png`

## Note Tecniche

### Sistema Folio + JSON
- Non creare file blade per pagine singole
- Usare `[slug].blade.php` esistente
- Definire contenuto in JSON files
- Componenti riutilizzabili in `components/blocks/`

### Multilingua
- Supporto IT/EN nativo
- Slug tecnici in inglese nei JSON
- URL localizzate con prefisso `/it/`, `/en/`

### Errori Comuni da Evitare
- Non usare controller tradizionali
- Non creare `PagesController`
- Usare Folio + Volt + Laraxot architecture
