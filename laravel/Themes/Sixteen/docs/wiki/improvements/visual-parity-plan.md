# Piano Miglioramento Visual Parity - Segnalazione

## Analisi Differenze con Design Comuni

### 1. Header Mobile
**Problemi attuali:**
- Hamburger menu non visibile
- Elementi sovrapposti
- Layout non mobile-first

**Soluzione:** 
- Rimuovere selettori `.page-content[data-slug="..."]` da app.css
- Implementare grid overlay per mobile header
- Assicurarsi che il navbar-wrapper abbia z-index corretto

### 2. Stepper Responsive
**Problemi attuali:**
- Stepper non responsive
- Testo "informazioni richieste" visibile su mobile
- Layout fisso non adattivo

**Soluzione:**
- Nascondere testo "informazioni richieste" su mobile (< 768px)
- Renderlo visibile solo su desktop
- Aggiungere media query specifiche

### 3. Spaziatura Mappa
**Problemi attuali:**
- Troppi margini a destra e sinistra su tablet/mobile
- Troppo spazio bianco sotto "Indica il luogo"

**Soluzione:**
- Ridurre margini della mappa su mobile/tablet
- Ottimizzare spaziatura tra elementi
- Mobile-first approach

### 4. Funzionalità Mappa
**Aggiungere:**
- Pulsante fullscreen per la mappa
- Aggiornamento automatico latitudine/longitudine al muovere il marker
- Diverse viste (satellite, street, etc.)

### 5. Multilingua
**Implementare:**
- Sistema multilingua completo
- Traduzioni per tutti i testi UI
- Selector linguaggio funzionante

## Priorità di Implementazione

1. **URGENT** - Fix header mobile (hamburger visibile)
2. **URGENT** - Rimuovere selettori pagina-specifici
3. High - Stepper responsive
4. High - Ottimizzazione spaziatura mappa
5. Medium - Funzionalità mappa avanzate
6. Low - Sistema multilingua completo

## File da Modificare

### CSS
- `/resources/css/app.css` - Rimuovere selettori specifici
- `/resources/css/segnalazione-parity.css` - Migliorare mobile header
- Nuovo file `/resources/css/mobile-fixes.css` - Fix responsive specifici

### JavaScript
- `/resources/js/map.js` - Aggiungere funzionalità mappa
- `/resources/js/mobile-nav.js` - Ottimizzare nav mobile

### Blade
- `/resources/views/components/sections/header/v1.blade.php` - Aggiungere multilingua

## Test da Fare

1. Desktop: 1440px, 1024px
2. Tablet: 768px, 992px
3. Mobile: 375px, 414px
4. Verificare su browser diversi (Chrome, Firefox, Safari)

## Metriche di Successo

- [ ] Header mobile identico a Design Comuni
- [ ] Stepper responsive con testo nascosto su mobile
- [ ] Mappa senza margini eccessivi
- [ ] Funzionalità fullscreen mappa
- [ ] Input lat/lon aggiornati al muovere marker
- [ ] Sistema multilingua funzionante