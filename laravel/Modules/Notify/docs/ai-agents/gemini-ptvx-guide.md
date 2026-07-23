---
title: "Utilizzo di Gemini con il Progetto LaravelPizza"
type: guide
tags: [gemini, ptvx, guide]
created: 2026-07-14
updated: 2026-07-14
qmd: "gemini-ptvx-guide utilizzo di gemini con il progetto laravelpizza"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index.md"
  - "./01-gsd-workflow.md"
  - "./02-bmad-workflow.md"
  - "./03-architecture-zen.md"
  - "./04-filament-philosophy.md"
  - "./05-front-office-audit.md"
  - "./06-cinematic-effects.md"
  - "./07-mcp-tailwind-ui.md"
---

# Utilizzo di Gemini con il Progetto LaravelPizza

## Best Practices Specifiche

### Come formulare le richieste a Gemini
- Fornisci sempre contesto specifico sul progetto LaravelPizza
- Indica chiaramente il modulo o la funzionalità coinvolta
- Richiedi esplicitamente di rispettare le regole Laraxot
- Ricorda: LaravelPizza è una piattaforma per meetup sviluppatori, NON un e-commerce pizza

### Analisi del codice
Quando chiedi a Gemini di analizzare il codice:
- Specifica le cartelle o file specifici da esaminare
- Richiedi di verificare il rispetto delle regole Laraxot
- Chiedi di controllare l'uso corretto delle classi XotBase
- Verifica che il frontend usi Folio + Volt + CMS-driven pages (NO controllers)

### Suggerimenti per la scrittura di richieste efficaci
- "Analizza il modulo Meetup e verifica che tutte le risorse estendano XotBaseResource"
- "Controlla che le Actions usino il pattern `app(Action::class)->execute()`"
- "Verifica che non ci siano estensioni dirette di classi Filament"
- "Controlla che le pagine usino JSON CMS-driven e non controller tradizionali"

## Pattern Specifici da Riconoscere

### Estensioni corrette
- `XotBaseModel`, `XotBaseUser`, `XotBaseResource`
- `XotBaseCreateRecord`, `XotBaseEditRecord`, `XotBaseListRecords`
- `XotBaseServiceProvider`, `XotBaseEventServiceProvider`

### Pattern da evitare
- Estensioni dirette di classi Filament
- Metodi hardcoded come `->label('testo')`, `->placeholder('testo')`
- Services tradizionali invece di Actions
- `belongsToMany()` invece di `belongsToManyX()`
- Controller e routes per frontend (usare Folio + Volt)
- Pacchetti nel root composer.json (usare module composer.json)

## Esempi di richieste efficaci

### Richiesta di analisi architetturale
```
Analizza il modulo Meetup e verifica che rispetti tutti i principi Laraxot,
in particolare l'uso corretto delle classi XotBase, le Actions con execute(),
e le pagine CMS-driven.
```

### Richiesta di creazione pagina pubblica
```
Crea una nuova pagina "speakers" per LaravelPizza:
1. File JSON in config/local/laravelpizza/database/content/pages/speakers.json
2. Block components in Themes/Meetup/resources/views/components/blocks/speakers/
3. NON creare controller o route in web.php
```

## Risoluzione dei Problemi Comuni

Quando Gemini fornisce soluzioni che non rispettano i pattern Laraxot:
- Ricorda che tutte le classi devono estendere le corrispondenti XotBase
- Le traduzioni devono essere gestite automaticamente (AutoLabelAction)
- Le Actions devono usare `app(Action::class)->execute()`, mai metodi custom
- Il frontend deve usare Folio + Volt + CMS-driven JSON pages
- I pacchetti vanno nel composer.json del modulo, poi `composer go`
