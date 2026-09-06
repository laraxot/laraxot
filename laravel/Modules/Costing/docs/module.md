---
title: "Costing Module — Doctrine"
type: doctrine
tags: [costing, costi, module-doctrine]
created: 2026-09-05
updated: 2026-09-05
qmd: "Costing module doctrine BMAD analysis purpose religion philosophy policy why zen gap enhancements split merge"
related:
  - "../../Xot/docs/module.md"
  - "../../WorkOrder/docs/module.md"
  - "../../Production/docs/module.md"
  - "../../Billing/docs/module.md"
---

# Costing Module — Doctrine

## Scope (Scopo)

Costing calcola e gestisce i costi: centri di lavoro, ricarichi condivisi tra moduli di preventivazione, allocazione costi di produzione. Fornisce la verità economica di ciò che costa ogni commessa, ogni prodotto, ogni intervento.

## Religion (Religione)

**"Nessuna stima senza dato."** La convinzione non negoziabile è che ogni costo deve avere una fonte documentata. Stime non sono ammesse senza supporting data. Ogni costo è tracciato, allocato, e verificabile.

## Philosophy (Filosofia)

- **Rigorous cost allocation**: center-of-cost methodology
- **Transparent allocation rules**: ogni regola di allocazione documentata
- **Cost driver documentation**: obbligatoria per ogni stima
- **Periodic variance analysis**: confronto costo reale vs budget
- **Integration con Production**: costi di produzione allocati per ruolo/turno/dipartimento

## Policy (Politica)

- Nessuna stima senza supporting data
- Documentazione obbligatoria per ogni cost driver
- Variance analysis periodica (mensile)
- Costi di produzione allocati con regole trasparenti
- Audit trail su ogni allocazione

## Why (Perché)

Costing esiste perché la calcolazione dei costi ha regole complesse (center-of-cost, ricarichi, allocazioni) che non possono essere gestite inline in Billing o WorkOrder. Costing è il garante che ogni numero economico sia verificabile e tracciabile.

## Zen

*"Ogni centesimo ha una storia. Ogni costo è documentato."*

## Gap

- **Quasi vuoto**: 0 Actions (priorità massima di sviluppo)
- Nessun cost calculation engine implementato
- Nessuna integrazione con Production o Billing
- Test coverage scarso
- Nessun reporting capability

## Add

- Center-of-cost calculation engine
- Labor cost allocation (per ruolo, turno, dipartimento)
- Material cost tracking con supplier pricing
- Profitability analysis dashboard
- Integration con Production e Billing
- Cost variance analysis reports
- Budget vs actual tracking

## Split/Merge

**Alta priorità di sviluppo.** Costing è la base di precisione finanziaria. Deve essere sviluppato indipendentemente ma integrato precocemente con Production e Billing per chiudere il ciclo costi→prodotto→fattura.

## Future Enhancements

1. **Activity-based costing (ABC)**: allocazione costi per attività, non per centro
2. **Standard vs actual cost**: confronto automatico
3. **Cost simulation**: "what-if" analysis per nuove produzioni
4. **Supplier cost benchmarking**: confronto prezzi fornitori nel tempo
5. **Multi-currency cost tracking**: costi in valute multiple con conversione storica
6. **Cost allocation AI**: suggerimenti automatici di allocazione basati su storico
