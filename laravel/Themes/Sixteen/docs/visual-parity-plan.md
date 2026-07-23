# Piano Miglioramento Visual Parity - Segnalazione Creazione

## Stato Attuale vs Design Comuni Reference

### 1. Header Navigation
**Problemi:**
- Hamburger menu non visibile su mobile
- Navbar overlay non funziona correttamente
- Header ha elementi sovrapposti

**Soluzioni:**
- [x] Aggiunto CSS mobile per hamburger menu visibile
- [x] Fix per navbar overlay e collapsible menu
- [ ] Testare su dispositivi reali

### 2. Stepper Component
**Problemi:**
- Stepper non è responsive
- "Informazioni richieste" non nascosto su mobile
- Layout non si adatta a schermi piccoli

**Soluzioni da implementare:**
- [ ] Aggiungere classe CSS `.steppers-mobile` per nascondere titolo su mobile
- [ ] Ridurre dimensioni stepper su mobile
- [ ] Impostare stepper orizzontale su desktop, verticale su mobile

### 3. Map Section
**Problemi:**
- Troppo spazio bianco sotto "Indica il luogo del disservizio"
- Manca pulsante fullscreen
- Input latitudine/longitudine non si aggiornano al movimento del marker

**Soluzioni:**
- [x] Aggiunto pulsante fullscreen
- [x] Aggiornato coordinate in tempo reale durante drag
- [ ] Rimuovere excess spacing sotto la mappa
- [ ] Aggiungere map controls better positioned

### 4. Mobile Responsiveness
**Problemi:**
- Map ha margini grandi su tablet/mobile
- Container non è mobile-first
- Spaziature non ottimizzate

**Soluzioni:**
- [x] Aggiunto CSS mobile-first per map
- [ ] Ottimizzare padding/margin per schermi piccoli
- [ ] Testare su tablet (768px) e mobile (< 768px)

### 5. CSS Issues
**Problemi:**
- Uso selettori specifici `.page-content[data-slug="tests.segnalazione-crea"]`
- CSS non generici
- Classi Bootstrap Italia non convertite correttamente

**Soluzioni:**
- [x] Creato CSS generici senza selettori specifici
- [x] Aggiunti fixes mobile-first
- [ ] Verificare tutte le classi Bootstrap Italia

## Piano di Azione

### Fase 1: CSS Fixes Urgenti (1 ora)
1. [ ] Aggiungere CSS stepper mobile hide
2. [ ] Rimuovere excess spacing dalla mappa
3. [ ] Ottimizzare container padding per mobile

### Fase 2: Componenti UI (1 ora)
1. [ ] Completare implementazione map fullscreen
2. [ ] Aggiungere zoom controls alla mappa
3. [ ] Fix stepper responsive

### Fase 3: Testing e Ottimizzazioni (30 min)
1. [ ] Test su desktop, tablet, mobile
2. [ ] Verificare accessibilità
3. [ ] Performance test

## File Modificati

### Nuovi File Creati:
- `/resources/css/mobile-header-fix.css` - Header mobile fixes
- `/resources/css/mobile-map-fix.css` - Map mobile fixes
- `/docs/visual-parity-plan.md` - Questo piano

### File Modificati:
- `/resources/views/components/layouts/main.blade.php` - Aggiunto CSS fixes
- `/Modules/Geo/resources/views/filament/forms/components/latitude-longitude-input.blade.php` - Map fixes

## Prossimi Passi

1. **Immediato**: Completare Fase 1 - CSS fixes
2. **Breve termine**: Fase 2 - Componenti UI
3. **Medio termine**: Testing su diversi dispositivi

## Checklist Pre-Deploy

- [ ] Header funziona su mobile (hamburger visibile)
- [ ] Stepper responsive (nascondi titolo mobile)
- [ ] Mappa con fullscreen e aggiornamento coordinate
- [ ] Nessun selettore CSS specifico per pagina
- [ ] Mobile-first design
- [ ] Performance ottimale