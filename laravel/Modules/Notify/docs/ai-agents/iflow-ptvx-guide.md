---
title: "Utilizzo di iFlow con il Progetto PTVX"
type: guide
tags: [iflow, ptvx, guide]
created: 2026-07-14
updated: 2026-07-14
qmd: "iflow-ptvx-guide utilizzo di iflow con il progetto ptvx"
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

# Utilizzo di iFlow con il Progetto PTVX

## Best Practices Specifiche

### Come utilizzare iFlow CLI con PTVX
- Assicurati sempre che iFlow rispetti le regole architetturali Laraxot
- Usa iFlow per analizzare e modificare il codice rispettando i pattern esistenti
- Verifica che tutte le modifiche rispettino le convenzioni del progetto

### Operazioni comuni con iFlow
- Analisi del codice per rilevare violazioni Laraxot
- Modifica di file rispettando le regole di estensione
- Creazione di nuovi componenti secondo i pattern XotBase

### Suggerimenti per un utilizzo efficace
- Usa iFlow per verificare che le nuove classi estendano le corrette XotBase
- Richiedi a iFlow di controllare l'uso corretto delle traduzioni
- Verifica con iFlow che non ci siano pattern vietati nel codice

## Pattern Specifici da Riconoscere

### Estensioni corrette
- `XotBaseModel`, `XotBaseUser`, `XotBaseResource`
- `XotBaseCreateRecord`, `XotBaseEditRecord`, `XotBaseListRecords`

### Pattern da evitare
- Estensioni dirette di classi Filament
- Metodi hardcoded come `->label('testo')`
- Services tradizionali invece di Actions

## Esempi di operazioni efficaci

### Verifica dell'architettura
```
Controlla che tutte le risorse nel modulo IndennitaResponsabilita estendano XotBaseResource
e che rispettino le regole Laraxot.
```

### Rimozione di pattern non corretti
```
Sostituisci tutte le estensioni dirette di classi Filament con le corrispondenti XotBase
nel modulo Performance.
```

## Risoluzione dei Problemi Comuni

Quando iFlow esegue operazioni che potrebbero violare i pattern Laraxot:
- Verifica sempre che le classi estendano le corrette XotBase
- Controlla che le traduzioni siano gestite automaticamente
- Assicurati che le Actions siano usate al posto dei Services

## Integrazione con lo sviluppo
- Usa iFlow per automatizzare la correzione di violazioni Laraxot
- Implementa controlli automatici durante le modifiche del codice
- Sfrutta iFlow per mantenere la coerenza architetturale del progetto