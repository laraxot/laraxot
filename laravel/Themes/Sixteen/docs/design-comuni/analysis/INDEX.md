# Design Comuni Pages - HTML Structure Analysis Index

## Overview

Analizzate tutte le pagine di test confrontando:
- Reference: `https://italia.github.io/design-comuni-pagine-statiche/sito/<pagina>.html`
- Local: `http://127.0.0.1:8000/it/tests/<pagina>`

## Pages Status Summary

### Working Pages (with differences)

| Page | Match % | Status | Priority |
|------|---------|--------|----------|
| homepage | ~92% | Working | Medium |
| domande-frequenti | ~75% | Working | Medium |
| risultati-ricerca | ~70% | Working | High |
| argomenti | ~80% | Working | Done |
| argomento | ~45% | Working | High |
| lista-risorse | ~85% | Working | Medium |
| mappa-sito | ~40% | Working | Low |
| amministrazione | ~55% | Working | High |

### Error Pages (500/404)

| Page | Error | Action Required |
|------|-------|----------------|
| lista-categorie | 500 | Fix components |
| lista-risorse-categorie | 500 | Fix components |
| contatti | 500 | Fix components |
| organo | 500 | Fix components |
| persona | 500 | Fix components |
| documenti-dati | 500 | Fix components |
| servizi | 500 | Fix components |
| servizio-dettaglio | 500 | Fix components |
| eventi | 500 | Fix components |
| luoghi | 500 | Fix components |
| luogo-dettaglio | 500 | Fix components |
| novita | 500 | Fix components |
| novita-dettaglio | 500 | Fix components |
| prenotazione-appuntamento | 500 | Fix components |
| appuntamento-06-conferma | 500 | Fix components |
| richiesta-assistenza | 500 | Fix components |
| aree-amministrative | 404 | Create blocks |
| area-amministrativa-dettaglio | 404 | Create blocks |
| servizi-categoria | Partial | ~25% - placeholder |
| evento-dettaglio | Partial | ~20% - placeholder |
| appuntamento-01-ufficio | Partial | ~15% - placeholder |
| appuntamento-05-riepilogo | Partial | ~10% - placeholder |

## Analysis Files

- [PAGES_GROUP_1.md](PAGES_GROUP_1.md) - homepage, domande-frequenti, risultati-ricerca, argomenti, argomento
- [PAGES_GROUP_2.md](PAGES_GROUP_2.md) - lista-risorse, lista-categorie, lista-risorse-categorie, mappa-sito, contatti
- [PAGES_GROUP_3.md](PAGES_GROUP_3.md) - amministrazione, aree-amministrative, area-amministrativa-dettaglio, organo, persona
- [PAGES_GROUP_4.md](PAGES_GROUP_4.md) - documenti-dati, servizi, servizi-categoria, servizio-dettaglio, evento
- [PAGES_GROUP_5.md](PAGES_GROUP_5.md) - eventi, evento-dettaglio, luoghi, luogo-dettaglio, novita
- [PAGES_GROUP_6.md](PAGES_GROUP_6.md) - Richieste: prenotazione-appuntamento, appuntamento-01 to appuntamento-06, richiesta-assistenza
- [PAGES_GROUP_7.md](PAGES_GROUP_7.md) - Servizi Pagamento: pagamento, pagamento-dettaglio

## Key Structural Differences Found

### Homepage
- Additional search form in head-section
- Different rating implementation (Alpine.js vs vanilla JS)

### Domande Frequenti
- Reference has 17 FAQ items vs local has 11
- Alpine.js accordion vs Bootstrap collapse

### Risultati Ricerca
- Missing Argomenti filter section (50+ topics)
- Simplified filters

### Argomenti (FIXED ✅)
- Contacts section added
- Rating background fixed

### Argomento
- Major simplification
- Missing novita, amministrazione, servizi, documenti sections

### Mappa Sito
- Completely different structure

### Amministrazione
- Missing "In evidenza" section
- Simplified "Organi di governo"

## Next Steps

1. **Fix 500 errors first** - lista-categorie (FIXED ✅), contatti, organo, persona
2. **Fix structural gaps** - argomento, amministrazione
3. **CSS/JS parity** - Make visually identical

### FIXES APPLIED

- ✅ lista-categorie - Created categories/grid.blade.php component
- ✅ argomenti - Fixed hero, contacts, rating styling

## Tech Stack

- NO Bootstrap Italia
- Tailwind CSS + Alpine.js only
- Build: `npm run build` from laravel/Themes/Sixteen

## Extra Reports

- [live-parity-2026-04-04.md](../live-parity-2026-04-04.md) - live screenshot findings for lista-risorse, amministrazione, ticket-list
- [LIVE_BODY_PARITY_REPORT.md](../LIVE_BODY_PARITY_REPORT.md) - current live body audit from the running app
