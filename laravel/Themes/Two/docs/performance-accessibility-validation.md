# Validazione Performance e Accessibilità

## PageSpeed Insights

**URL**: https://pagespeed.web.dev/

Per validare le pagine:
1. Vai su https://pagespeed.web.dev/
2. Inserisci l'URL del sito (es. https://sottana.net/it)
3. Clicca "Analyze"

### Pagine da validare
- Home: https://sottana.net/it
- Chi Siamo: https://sottana.net/it/about
- Servizi: https://sottana.net/it/services
- Contatti: https://sottana.net/it/contatti
- Blog: https://sottana.net/it/blog

## Axe Accessibility Checker

Tool CLI per validazione accessibilità:
```bash
npx @axe-core/cli https://sottana.net/it
```

## Risultati Home Page (axe)

### Problemi Rilevati (24 issue)

1. **color-contrast** (2 occorrenze)
   - Contraste colori insufficienti su elementi .text-brand-orange

2. **heading-order** (1 occorrenza)
   - Ordine heading non corretto

3. **link-name** (3 occorrenze)
   - Link senza testo visibile

4. **region** (18 occorrenze)
   - Contenuto non contenuto in landmarks

### Note
- Rilevato automaticamente solo 20-50% dei problemi
- Test manuale sempre richiesto

## WCAG 2.1 - Regole Base

### Contrasto Colori
- Testo normale: 4.5:1 minimo
- Testo grande (18pt+): 3:1 minimo

### Struttura
- heading order (h1 → h2 → h3)
- landmarks (main, nav, footer)
- form labels

### Link
- Testo descrittivo
- Nome accessibile
