---
title: "Production Module — Doctrine"
type: doctrine
tags: [production, manifattura, module-doctrine]
created: 2026-09-05
updated: 2026-09-05
qmd: "Production module doctrine BMAD analysis purpose religion philosophy policy why zen gap enhancements split merge"
related:
  - "../../Xot/docs/module.md"
  - "../../WorkOrder/docs/module.md"
  - "../../Intervention/docs/module.md"
---

# Production Module — Doctrine

## Scope (Scopo)

Production pianifica ed esegue la produzione: scheduling delle risorse, gestione ordini di lavoro, tracciamento stato produzione, report di efficienza. Trasforma le commesse (WorkOrder) in prodotti finiti attraverso una pianificazione ottimale delle risorse.

## Religion (Religione)

**"La produzione deve bilanciare workload, risorse e priorità."** La convinzione non negoziabile è che la pianificazione produttiva deve essere ottimale: nessun sovraccarico di risorse, nessun conflitto di scheduling, nessun ordine fuori sequenza. Il sistema è il cervello della fabbrica.

## Philosophy (Filosofia)

- **Process orchestration**: pianificazione basata su capacità e priorità
- **Capacity constraints**: ogni risorsa ha limiti configurabili
- **Priority-based scheduling**: critico > standard > low-priority
- **Real-time status**: aggiornamenti in tempo reale durante esecuzione
- **Conflict detection**: rilevamento e risoluzione automatica conflitti

## Policy (Politica)

- Capacity constraints per ogni risorsa
- Priorità obbligatoria (critica/standard/low)
- Conflitti di scheduling rilevati e risolti automaticamente
- Stato produzione aggiornato in tempo reale
- Report di efficienza OEE (Overall Equipment Effectiveness)

## Why (Perché)

Production esiste perché la pianificazione produttiva è un dominio complesso che richiede algoritmi di scheduling, gestione capacità, e risoluzione conflitti. Non può essere gestita in WorkOrder (che è un workflow cliente, non una linea di produzione).

## Zen

*"Pianifica con precisione, esegui con efficienza, migliora con dati."*

## Gap

- Solo 2 Actions (quasi vuoto)
- Nessun scheduling engine implementato
- Nessun rilevamento conflitti risorse
- Test coverage scarso
- Reporting limitato

## Add

- Production scheduling engine con risorse
- Bottleneck identification e alerting
- WorkOrder-to-Production mapping
- Real-time production dashboard
- Integrazione con Billing per cost attribution
- Integrazione con IoT per monitoraggio macchine
- MES (Manufacturing Execution System) lightweight

## Split/Merge

**Considerare merge con Intervention se manutenzione-heavy operations dominano.** Produzione e interventi condividono tracking ore/materiali e allocazione risorse. Se la manutenzione diventa il 60%+ delle operazioni, i due moduli convergono in un "Operations" module. Attualmente separati per distintività di dominio.

## Future Enhancements

1. **Advanced scheduling AI**: algoritmi ML per ottimizzazione scheduling
2. **Digital twin**: replica virtuale della linea produttiva
3. **OEE analytics**: analisi efficienza con benchmark settoriali
4. **Shop floor MES**: gestione operazioni in tempo reale
5. **Material requirements planning (MRP)**: calcolo automatico materiali necessari
6. **Quality control integration**: tracciamento qualità per lotto di produzione
