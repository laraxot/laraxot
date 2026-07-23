# MAUVE++ Accessibility Validation

## Cos'è MAUVE++

MAUVE++ è un validatore di accessibilità sviluppato dal CNR (ISTI - HIIS Lab) che verifica la conformità WCAG 2.1 dei siti web.

**Sito**: https://mauve.isti.cnr.it/

## Caratteristiche

- Validazione WCAG 2.1 (livelli A, AA, AAA)
- Supporto per pagine web e PDF
- Report dettagliati con suggerimenti di correzione
- Possibilità di creare progetti di audit

## Come Validare

### 1. Registrazione

1. Vai su https://mauve.isti.cnr.it/
2. Clicca "Register"
3. Compila il form con i tuoi dati

### 2. Validazione Singola Pagina

1. Dopo il login, vai su "Single page evaluation"
2. Inserisci l'URL della pagina da validare
3. Scegli le linee guida (WCAG 2.1)
4. Scegli il livello di conformità (A, AA, AAA)
5. Clicca "Validate"

### 3. Validazione Intero Sito

1. Crea un nuovo progetto
2. Inserisci l'URL del sito
3. Il crawler analizza automaticamente le pagine
4. Scarica il report in CSV

## Pagine da Validare (Sottana Service)

- **Production**: https://sottana.net/it
- **Contatti**: https://sottana.net/it/contatti
- **Servizi**: https://sottana.net/it/servizi
- **Blog**: https://sottana.net/it/blog

## Riferimenti

- **Piano WCAG Completo**: [wcag-compliance-plan.md](./wcag-compliance-plan.md)
- **Strumenti CLI**: 
  ```bash
  lighthouse https://sottana.net/it --view
  npx @axe-core/cli https://sottana.net/it
  ```

## Regole di Accessibilità (WCAG 2.1)

### Priorità Alta (A)
- Alternative testuali per contenuti non testuali
- Contenuti informativi non presentati solo con colore
- Uso corretto del linguaggio HTML
- Navigazione da tastiera

### Priorità Media (AA)
- Contrasto minimo 4.5:1 per testo
- Ridimensionamento testo fino al 200%
- Navigazione consistente
- Nomi e ruoli chiari

## Note per Sviluppatori

- Usare attributi `alt` per tutte le immagini
- Garantire contrasto colori conforme
- Form labels per tutti gli input
- Heading strutturati correttamente (h1, h2, h3...)
- Links con testi descriptivi

## Status Validazione

- [x] Sito deployato su https://sottana.net
- [x] axe-core eseguito (5 issue accessibilità trovate)
- [x] Performance base misurata (curl)
- [x] W3C HTML Validator eseguito (72-75 errori rilevati)
- [ ] PageSpeed Insights (da fare manualmente)
- [ ] MAUVE++ (da fare manualmente)

## Problemi Rilevati

### 1. Errori W3C HTML Validator

Il sito ha **72-75 errori HTML** rilevati dal W3C Validator:

**Principali categorie:**
- Attributi Blade non compilati (`:class`, `x-data`, `@click`, `@scroll.window`)
- Attributi duplicati `class`
- Direttive Livewire/Blade non processate

**Causa**: Le viste Blade non vengono compilate in produzione. Questo può accadere se:
- `php artisan view:cache` non è stato eseguito
- C'è un problema con la configurazione di produzione

**Soluzione**: Eseguire `php artisan view:cache` sul server di produzione

### 2. Errori Accessibilità (axe-core)

- 2 errori contrasto colori (`text-brand-orange`)
- 3 errori link senza testo visibile

## Performance Base (curl)

| Pagina | Tempo | Dimensione | Status |
|--------|-------|------------|--------|
| Home | 0.35s | 76KB | 200 |
| Contatti | 0.35s | 77KB | 200 |
| Servizi | 0.35s | 53KB | 200 |
| Blog | 0.35s | 85KB | 200 |

Note:
- Tutte le pagine caricano in < 400ms
- HTTPS attivo
- Laravel session attive

## PageSpeed Insights

**Sito**: https://pagespeed.web.dev/

PageSpeed Insights richiede un account Google e API key per uso programmatico. Per validazione manuale:

1. Vai su https://pagespeed.web.dev/
2. Inserisci l'URL della pagina
3. Clicca "Analyze"

### Pagine da testare:
- https://pagespeed.web.dev/report?url=https://sottana.net/it
- https://pagespeed.web.dev/report?url=https://sottana.net/it/contatti
- https://pagespeed.web.dev/report?url=https://sottana.net/it/servizi

## Note Tecniche

MAUVE richiede autenticazione e JavaScript per funzionare correttamente. Per validazione automatizzata, usare:

```bash
# axe-core (accessibilità)
npx @axe-core/cli https://sottana.net/it --tags wcag2a,wcag2aa

# Lighthouse (performance + accessibilità)
lighthouse https://sottana.net/it --view
```
