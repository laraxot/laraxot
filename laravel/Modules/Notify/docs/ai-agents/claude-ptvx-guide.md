---
title: "Utilizzo di Claude con il Progetto PTVX"
type: guide
tags: [claude, ptvx, guide]
created: 2026-07-14
updated: 2026-07-14
qmd: "claude-ptvx-guide utilizzo di claude con il progetto ptvx"
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

# Utilizzo di Claude con il Progetto PTVX

## Best Practices Specifiche

### Come formulare le richieste a Claude
- Fornisci sempre contesto specifico sul progetto PTVX
- Indica chiaramente il modulo o la funzionalità coinvolta
- Richiedi esplicitamente di rispettare le regole Laraxot

### Analisi del codice
Quando chiedi a Claude di analizzare il codice:
- Specifica le cartelle o file specifici da esaminare
- Richiedi di verificare il rispetto delle regole Laraxot
- Chiedi di controllare l'uso corretto delle classi XotBase

### Suggerimenti per la scrittura di richieste efficaci
- "Analizza il modulo User e verifica che tutte le risorse estendano XotBaseResource"
- "Controlla che le traduzioni siano gestite correttamente nel modulo Performance"
- "Verifica che non ci siano estensioni dirette di classi Filament"

## Pattern Specifici da Riconoscere

### Estensioni corrette
- `XotBaseModel`, `XotBaseUser`, `XotBaseResource`
- `XotBaseCreateRecord`, `XotBaseEditRecord`, `XotBaseListRecords`

### Pattern da evitare
- Estensioni dirette di classi Filament
- Metodi hardcoded come `->label('testo')`
- Services tradizionali invece di Actions

## Esempi di richieste efficaci

### Richiesta di analisi architetturale
```
Analizza il modulo IndennitaResponsabilita e verifica che rispetti tutti i principi Laraxot,
in particolare l'uso corretto delle classi XotBase e la gestione delle traduzioni.
```

### Richiesta di correzione di codice
```
Correggi questa risorsa Filament in modo che rispetti le regole Laraxot:

class MiaRisorsa extends Filament\Resources\Resource {
    // codice attuale
}

La risorsa dovrebbe estendere XotBaseResource invece che Filament\Resources\Resource.
```

## Risoluzione dei Problemi Comuni

Quando Claude fornisce soluzioni che non rispettano i pattern Laraxot:
- Ricorda che tutte le classi devono estendere le corrispondenti XotBase
- Le traduzioni devono essere gestite automaticamente
- Le Actions devono essere usate al posto dei Services