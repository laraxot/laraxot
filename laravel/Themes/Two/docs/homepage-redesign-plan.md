# Homepage Redesign Plan - Theme Two

> Target: https://lightseagreen-dogfish-560272.hostingersite.com/
> Nostro: http://127.0.0.1:8000/it
> Data analisi: Febbraio 2026

## Analisi Target (7 sezioni identificate dagli screenshot)

### 1. HERO (full-width, immagine di sfondo)
- Header sticky: logo sx, nav center (Home, Chi Siamo, Servizi, Blog, FAQ, Contatti), CTA "Richiedi Consulenza" dx (verde)
- Hero full-screen con immagine di sfondo scura (tecnico al lavoro)
- Titolo H1 grande bianco bold
- Sottotitolo/descrizione grigio chiaro
- 2 CTA: primario verde "Richiedi Controllo Radioprotezione" + secondario outline "Scopri i Servizi"
- Barra statistiche in basso: "100% Conformita'", "Certificato Esperti Qualificati", "Rapido Intervento 24/48h"

### 2. SERVIZI (3 card bianche con icona verde, titolo, descrizione, link)
- Sfondo bianco/grigio chiaro
- 3 card: Controllo Radioprotezione, Controllo Elettromedicali, Documentazione e Conformita'
- Ogni card: icona cerchio verde, titolo bold, descrizione, link "Scopri di piu' ->"

### 3. PERCHE' CRITICA (sezione informativa con 4 card)
- Pretitolo verde uppercase "SICUREZZA E COMPLIANCE"
- Titolo H2 centrato "Perche' la Radioprotezione e' Critica?"
- Sottotitolo grigio
- 4 card con icona colorata: Rischi e Sanzioni, Obblighi Normativi, Sicurezza Persone, Responsabilita' Legale

### 4. SETTORI SPECIALIZZAZIONE (2 colonne con lista)
- Titolo centrato "Settori di Specializzazione"
- 2 card affiancate:
  - Odontoiatria (sfondo scuro con testo bianco in alto, lista sotto)
  - Medicina Veterinaria (immagine sfondo in alto, lista sotto)
- Ogni voce: icona cerchio verde + titolo + descrizione

### 5. COSA CONTROLLIAMO (layout 2 colonne: testo sx, lista dx)
- Sfondo scuro (slate/navy)
- Colonna sx: titolo "Cosa Controlliamo?", descrizione, box evidenziato "Perche' e' fondamentale?"
- Colonna dx: 5 voci con icona (Dosimetria, Schermature, Apparecchiature, Sistemi Protezione, Documentazione)

### 6. TESTIMONIALS (griglia 2x2)
- Titolo "Dicono di Noi" + sottotitolo
- 4 card testimonial: avatar, nome, ruolo, citta', 5 stelle, citazione, data
- Icona quote in alto a dx di ogni card

### 7. RISORSE + NEWSLETTER + FOOTER
- Sezione "Risorse Utili" (sfondo blu/verde): 2 card con download PDF
- Newsletter "Rimani Aggiornato" (sfondo gradiente blu-verde): input email + bottone
- Footer 4 colonne: Brand, Normative, Servizi, Contatti + social icons + copyright

## Analisi Nostro Sito Attuale

### Problemi identificati
1. **Layout con sidebar** — il target e' full-width, noi abbiamo sidebar dx con "Accesso Rapido" e "Info Sistema" (da rimuovere dalla homepage pubblica)
2. **Hero troppo semplice** — manca immagine di sfondo, manca barra statistiche
3. **Mancano sezioni** — no "Perche' critica", no "Settori", no "Cosa controlliamo", no risorse PDF, no newsletter
4. **Testimonials basiche** — mancano avatar, date, quote icon
5. **Footer minimale** — manca footer strutturato a 4 colonne
6. **Header generico** — dice "Laravel", manca nav strutturata e CTA

### Blocchi esistenti da migliorare
- `hero/main.blade.php` — aggiungere immagine sfondo + barra stats
- `features/grid.blade.php` — ok ma migliorare stile card
- `stats/overview.blade.php` — integrare nella hero come barra
- `testimonials/carousel.blade.php` — aggiungere avatar, date, quote icon, layout 2x2
- `cta/banner.blade.php` — ok

### Nuovi blocchi da creare
- `services/cards.blade.php` — card servizi con icona, titolo, desc, link
- `why-critical/grid.blade.php` — sezione "perche' critica" con pretitolo + 4 card
- `specializations/two-columns.blade.php` — 2 colonne con lista
- `checklist/split.blade.php` — layout 2 colonne sfondo scuro
- `resources/download-cards.blade.php` — card download PDF
- `newsletter/signup.blade.php` — form iscrizione newsletter
- `footer/structured.blade.php` — footer 4 colonne

## Migliorie rispetto al target (il nostro deve essere PIU' BELLO)

### Design
- Animazioni subtle su scroll (fade-in, slide-up)
- Gradiente piu' sofisticato nell'hero (non solo scuro)
- Card con hover effect piu' elegante (scale + shadow)
- Tipografia piu' curata (font-display per titoli)
- Spacing piu' generoso tra sezioni

### SEO Ready
- Structured data JSON-LD per Organization, LocalBusiness, FAQPage
- Meta tags OG e Twitter Card
- Canonical URL per ogni lingua
- Sitemap XML multilingua
- H1 unico per pagina, gerarchia H corretta

### Multilingua
- Tutti i testi dal JSON (gia' strutturato per lingua)
- hreflang tags nel head
- URL con prefisso lingua (/it/, /en/)
- Traduzioni complete per ogni blocco

### Inbound Marketing Ready
- CTA strategiche in ogni sezione
- Lead magnet (download PDF)
- Newsletter signup con incentivo
- Social proof (testimonials con date recenti)
- Trust signals (certificazioni, numeri)

### AdSense Ready
- Spazi predefiniti per banner (header, tra sezioni, sidebar articoli)
- Layout che non interferisce con ads
- Contenuto sufficiente per approvazione AdSense

## Ordine di implementazione

1. Rimuovere sidebar dalla homepage (layout full-width)
2. Migliorare hero con immagine sfondo + stats bar
3. Creare blocco services/cards
4. Creare blocco why-critical/grid
5. Creare blocco specializations/two-columns
6. Creare blocco checklist/split
7. Migliorare testimonials (2x2, avatar, date, quote)
8. Creare blocco resources/download-cards
9. Creare blocco newsletter/signup
10. Migliorare footer strutturato
11. Aggiornare home.json con tutti i nuovi blocchi
12. SEO: structured data, meta tags, hreflang

## Screenshots salvati in docs/
- target-homepage-top.png (hero + nav)
- target-homepage-section2.png (servizi + perche' critica)
- target-homepage-section3.png (settori specializzazione)
- target-homepage-section4.png (cosa controlliamo + testimonials)
- target-homepage-section5.png (testimonials dettaglio)
- target-homepage-section6.png (risorse + newsletter)
- target-homepage-section7.png (newsletter + footer)
- our-homepage-top.png (stato attuale nostro sito)
