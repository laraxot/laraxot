---
title: "WorkOrder Module — Doctrine"
type: doctrine
tags: [workorder, commesse, module-doctrine]
created: 2026-09-05
updated: 2026-09-05
qmd: "WorkOrder module doctrine BMAD analysis purpose religion philosophy policy why zen gap enhancements split merge"
related:
  - "../../Xot/docs/module.md"
  - "../../Customer/docs/module.md"
  - "../../Intervention/docs/module.md"
  - "./gestionale-commesse-port.md"
---

# WorkOrder Module — Doctrine

## Scope (Scopo)

WorkOrder gestisce il ciclo di vita end-to-end delle commesse (lavori): dalla creazione iniziale, all'assegnazione, esecuzione, tracking, chiusura. È il cuore operativo del sistema: ogni ordine cliente passa per WorkOrder, che orchestra team, scadenze, status, e genera i dati per fatturazione e produzione.

## Religion (Religione)

**"Ogni commessa ha un ciclo, ogni transizione è sorvegliata, ogni stato è sacro."** La convinzione non negoziabile è che le transizioni di stato non sono mai libere: New → InProgress → Completed/Closed è uno state machine rigoroso, ogni transizione è tracciata, ogni invalid transition è bloccata.

## Philosophy (Filosofia)

- **CQRS-inspired**: comandi (create/update) separati da query (dashboard/reports)
- **Event-driven state changes**: ogni cambio stato emette un evento per audit
- **Actions, not Services**: ogni operazione è una Queueable Action con `execute()`
- **Strict state machine**: New → Assigned → InProgress → Completed → Closed
- **Time-bound SLAs**: ogni stato ha una scadenza associata

## Policy (Politica)

- Transizioni di stato solo tramite `ChangeWorkOrderStatusAction`
- Status history immutabile (append-only)
- Assegnazione obbligatoria prima di `InProgress`
- Soft delete per storico, mai hard delete
- Audit trail completo su ogni transizione

## Why (Perché)

WorkOrder esiste perché senza un modulo dedicato, le commesse sarebbero disperse tra billing, production, customer. WorkOrder è la fonte unica di verità per lo stato di un lavoro, e tutti gli altri moduli (Billing, Production, Inventory) leggono da qui.

## Zen

*"Una commessa, un ciclo, una storia. Orchestriamo dall'apertura alla chiusura."*

## Gap

- Solo 17 Actions (vs. 20+ in moduli simili)
- Test suite insufficiente
- Reporting avanzato mancante (aging, SLA compliance)
- Nessun bulk import/export per migrazione dati
- Status transition history non completamente loggato

## Add

- Bulk import/export CSV/XML per migrazione commesse
- Dashboard SLA compliance con alert
- Hook di integrazione per ERP di terze parti
- Advanced reporting (trend, analisi scadenze)
- Event Sourcing completo per state machine (opzionale)

## Split/Merge

**Mantenere separato.** WorkOrder governa il *processo* (assegnazione, esecuzione, chiusura), mentre Billing gestisce gli *esiti* economici e Production gli *esiti* produttivi. Condividono dati ma hanno preoccupazioni primarie diverse. Non ha senso fondere WorkOrder con Billing o Production.

## Future Enhancements

1. **Predictive SLA**: ML per predire quali commesse sforeranno le scadenze
2. **Auto-assignment**: algoritmo che assegna le commesse al team più adatto basato su skill e workload
3. **WorkOrder templates**: template di commesse ricorrenti con pre-popolazione
4. **Mobile-first UI**: interfaccia mobile per operatori sul campo
5. **Customer portal**: dashboard pubblica per il cliente per vedere lo stato della propria commessa
6. **Resource forecasting**: previsione del fabbisogno di risorse future basata su commesse pianificate
