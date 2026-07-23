# Piano di Implementazione Pagine Mancanti

## Priorità 1: Sistema Header
- [x] Analizzato header duplicato
- [ ] Rimuovere header nero "Laravel"
- [ ] Correggere contenuti header.json con "Consulenza Sicurezza"

## Priorità 2: Creare Pagine Base

### 1. Pagina Chi Siamo (/chi-siamo)
**File da creare:**
- `laravel/Themes/Two/resources/views/pages/about.blade.php`
- `laravel/config/local/techplanner/database/content/pages/about.json`

**Blocchi necessari:**
- Hero section con storia aziendale
- Team section con esperti qualificati
- Mission/Vision section
- Certificazioni section
- Testimonianze section

### 2. Pagina Servizi (/servizi)
**File da creare:**
- `laravel/Themes/Two/resources/views/pages/services.blade.php`
- `laravel/config/local/techplanner/database/content/pages/services.json`

**Blocchi necessari:**
- Hero servizi
- Cards per 3 servizi principali
- Dettagli per odontoiatria
- Dettagli per veterinaria
- CTA consulenza

### 3. Pagina Blog (/blog)
**File da creare:**
- `laravel/Themes/Two/resources/views/pages/blog.blade.php`
- `laravel/config/local/techplanner/database/content/pages/blog.json`

**Blocchi necessari:**
- Hero blog
- Download guides section
- Articoli recenti
- Categories sidebar
- Newsletter signup

### 4. Pagina FAQ (/faq)
**File da creare:**
- `laravel/Themes/Two/resources/views/pages/faq.blade.php`
- `laravel/config/local/techplanner/database/content/pages/faq.json`

**Blocchi necessari:**
- Hero FAQ
- Categories FAQ
- Search FAQ
- Contact support CTA

### 5. Pagina Contatti (/contatti)
**File da creare:**
- `laravel/Themes/Two/resources/views/pages/contacts.blade.php`
- `laravel/config/local/techplanner/database/content/pages/contacts.json`

**Blocchi necessari:**
- Hero contatti
- Contact form
- Mappa interattiva
- Info aziendali
- Social links

## Priorità 3: Blocchi Riutilizzabili

### Blocchi Comuni da Creare
1. **Hero Section** (`components/blocks/hero/`)
2. **Service Cards** (`components/blocks/services/`)
3. **Testimonials** (`components/blocks/testimonials/`)
4. **Contact Form** (`components/blocks/forms/`)
5. **Info Cards** (`components/blocks/info/`)

## Priorità 4: GDPR e Privacy

### Moduli da Attivare
- Modulo GDPR esistente
- Cookie consent banner
- Privacy policy page
- Terms & conditions page

### File Privacy
- `laravel/Themes/Two/resources/views/pages/privacy.blade.php`
- `laravel/Themes/Two/resources/views/pages/terms.blade.php`

## Priorità 5: Ottimizzazioni SEO

### Meta Tag per Ogni Pagina
- Title ottimizzati
- Description uniche
- Open Graph tags
- Twitter Cards
- Structured data

### Schema Markup
- Organization schema
- Service schema
- Article schema (blog)
- FAQ schema

## Priorità 6: Assets e Immagini

### Immagini da Scaricare/Creare
1. `hero-bg.jpg` - Immagine hero principale
2. `medical-equipment.jpg` - Attrezzature mediche
3. `veterinary-radiology.jpg` - Radiologia veterinaria
4. `testimonial-1.jpg` a `testimonial-4.jpg` - Clienti
5. Icone servizi personalizzate

### SVG Icons da Creare
- `linkedin.svg` in `Modules/TechPlanner/resources/svg/`
- Icone personalizzate per servizi
- Icone social media

## Priorità 7: Navigazione e Routing

### Aggiornamenti Navigazione
- Link attivi corretti
- Breadcrumb navigation
- Sitemap XML
- Internal linking strategy

### Routing Laravel
- Definire routes per tutte le pagine
- Middleware multilingua
- Redirects SEO-friendly

## Note Tecniche
- Usare sempre Filament Forms per i contenuti
- Seguire pattern XotBase per componenti
- Mantenere struttura JSON per contenuti
- Testare responsive design su tutti i dispositivi