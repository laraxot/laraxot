# roadmap - Tema TwentyOne

## Obiettivi
- **Allineamento Filament v4**: stili, componenti, autenticazione.
- **Accessibilità**: conformità AGID/WCAG, test contrasto, focus states.
- **Localizzazione**: pieno supporto i18n, chiavi consistenti.
- **Performance**: ottimizzazione assets, immagini responsive, lazy loading.

## Piano di Lavoro
### Fase 1 – Fondamenta ✅ COMPLETATA
- [x] Verifica integrazione Filament v4 (stili, layout) – vedi `filament-laravel-ui-integration.md`
- [x] Inventario componenti UI e mapping a use-case (forms, nav, cards)
- [x] Definizione design tokens (colori, spaziature, tipografia)

### Fase 2 – Accessibilità 🔄 IN CORSO
- [x] Audit ARIA e focus management per componenti principali
- [ ] Test contrasto e varianti dark/light
- [ ] Linee guida componenti accessibili (docs)

### Fase 3 – Localizzazione e Testi 🔄 IN CORSO
- [x] Rimozione testi hardcoded, sostituzione con chiavi
- [ ] Revisione traduzioni e fallback

### Fase 4 – Performance 🔄 IN CORSO
- [ ] Ottimizzazione bundle Vite, code splitting dove necessario
- [ ] Strategia immagini responsive per pagine con media
- [ ] Cache headers e preloading critico

## Collegamenti
- roadmap principale: `/docs/roadmap.md`
- Modulo CMS: `../../../Modules/Cms/docs/`
- Modulo User: `../../../Modules/User/docs/`
