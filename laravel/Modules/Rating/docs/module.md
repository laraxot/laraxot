---
title: "Rating Module — Doctrine"
type: doctrine
tags: [rating, feedback, module-doctrine]
created: 2026-09-05
updated: 2026-09-05
qmd: "Rating module doctrine BMAD analysis purpose religion philosophy policy why zen gap enhancements split merge"
related:
  - "../../Xot/docs/module.md"
  - "../../Comment/docs/module.md"
---

# Rating Module — Doctrine

## Scope (Scopo)

Rating gestisce valutazioni con scale multiple (stellari, numeriche, like/dislike), votazioni, e attributi schemaless. Trasforma opinioni in dati quantitativi.

## Religion (Religione)

**"Ogni voto è un dato, non un'opinione."** Le valutazioni sono segnali quantificabili: raccolti strutturatamente, analizzabili, aggregabili.

## Philosophy (Filosofia)

- **Inheritance pattern**: BaseRating → Rating
- **Schemaless attributes**: flessibilità per attributi extra
- **Value object**: il rating come oggetto valore
- **Aggregation-ready**: media, somme efficienti
- **Multiple scales**: supporto per scale diverse

## Policy (Politica)

- Relazione morphTo o diretta obbligatoria
- Valore validato secondo regola
- Schemaless attributes gestiti
- Valutazioni multiple supportate
- Aggregazioni efficienti

## Why (Perché)

Rating ha gestione di scale multiple, attributi schemaless. Un modulo dedicato evita duplicazione e garantisce coerenza.

## Zen

*"Da opinione a dato. Da singolo voto a tendenza."*

## Gap

- Test per scale limitati
- Policies assenti
- Convenzioni enum non documentate
- Logiche negli accessor
- Eventi per umidità significative mancanti

## Add

- Policies per rating e reazioni
- Test per configurazioni diverse
- Servizi analisi (trend, distribuzione)
- Eventi per cambiamenti significativi
- Anomaly detection

## Split/Merge

**Mantenere come-is.** Il focus su valutazioni quantitative è distinto da Comment (feedback qualitativo). Separazione giustificata.

## Future Enhancements

1. **Multi-criteria rating**: più dimensioni
2. **Weighted averages**: pesi diversi per criteri
3. **Verified purchase only**: solo acquirenti
4. **Rating incentives**: gamification
5. **Aggregate analytics**: trend, distribuzione, correlazione
6. **Anomaly detection**: identificazione voti sospetti
