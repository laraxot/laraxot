---
title: "Intervention Module — Doctrine"
type: doctrine
tags: [intervention, manutenzione, module-doctrine]
created: 2026-09-05
updated: 2026-09-05
qmd: "Intervention module doctrine BMAD analysis purpose religion philosophy policy why zen gap enhancements split merge"
related:
  - "../../Xot/docs/module.md"
  - "../../WorkOrder/docs/module.md"
  - "../../Production/docs/module.md"
---

# Intervention Module — Doctrine

## Scope (Scopo)

Intervention gestisce le attività di manutenzione, riparazione e intervento operativo su asset. Traccia ore-lavoro, materiali consumati, costi, e genera report per la manutenzione predittiva. Collega le commesse (WorkOrder) alle attività operative sul campo.

## Religion (Religione)

**"Ogni minuto contato, ogni pezzo tracciato."** La convinzione non negoziabile è che ogni attività di manutenzione deve avere ore registrate, materiali consumati, e costo allocato con precisione chirurgica. Niente approssimazioni, niente stime a occhio.

## Philosophy (Filosofia)

- **Time-tracking rigoroso**: ogni ora è tracciata, mai approssimata
- **Parts consumption tracking**: consumabili vs ricambi, tracciati separatamente
- **Cost allocation**: ogni intervento ha un costo totale calcolato
- **Supervisor approval**: ore e materiali approvati da supervisore
- **Linked to WorkOrder**: ogni intervento collegato a una commessa

## Policy (Politica)

- Ore lavoro obbligatorie con approvazione supervisore
- Materiali tracciati (consumabili vs ricambi)
- Cap ore e regole overtime automatiche
- Integrazione con manutenzione predittiva
- Audit trail completo su ogni modifica

## Why (Perché)

Intervention esiste perché la manutenzione è un dominio complesso con regole specifiche di tracciamento, allocazione costi, e approvazione. Non può essere gestita inline in WorkOrder perché ha proprie regole, workflow, e requisiti di reportistica.

## Zen

*"Traccia ogni azione, conta ogni minuto, alloca ogni centesimo."*

## Gap

- 26 Actions (dispersi su funzionalità)
- Nessun reporting dashboard trend manutenzione
- Integrazione Production limitata
- Test coverage scarso
- Mobile-friendly interface assente

## Add

- Dashboard manutenzione predittiva
- Barcode scanning per parti
- Integrazione con IoT sensori per manutenzione predittiva
- Mobile-first UI per operatori sul campo
- Calendario manutenzione programmata
- Analisi costi per tipo intervento

## Split/Merge

**Considerare merge con Production se manutenzione-heavy operations dominano.** Interventi e produzione condividono tracking ore/materiali. Se la manutenzione diventa la maggior parte delle operazioni, i due moduli convergono. Attualmente separati per distintività di dominio.

## Future Enhancements

1. **IoT integration**: sensori per manutenzione predittiva automatica
2. **Predictive maintenance AI**: ML per predire guasti prima che accadano
3. **AR assisted maintenance**: overlay istruzioni su dispositivi mobili
4. **Parts forecasting**: previsione ricambi necessari basata su storico
5. **Technician skill matching**: assegnazione automatica basata su competenze
6. **Warranty tracking**: monitoraggio scadenze garanzia
