---
title: "Billing Module — Doctrine"
type: doctrine
tags: [billing, fatturazione, module-doctrine]
created: 2026-09-05
updated: 2026-09-05
qmd: "Billing module doctrine BMAD analysis purpose religion philosophy policy why zen gap enhancements split merge"
related:
  - "../../Xot/docs/module.md"
  - "../../WorkOrder/docs/module.md"
  - "./gestionale-commesse-port.md"
---

# Billing Module — Doctrine

## Scope (Scopo)

Billing gestisce la fatturazione, l'incasso, la riconciliazione finanziaria. Crea fatture da commesse WorkOrder, gestisce pagamenti, crediti, debiti, e genera i dati per la contabilità. È il modulo della verità economica: ogni numero che esce da qui deve essere verificabile, immutabile, auditabile.

## Religion (Religione)

**"Un numero scritto, un numero sacro. Mai modificato, mai cancellato."** La convinzione non negoziabile è che i record finanziari sono immutabili: append-only, mai UPDATE su righe fattura, mai DELETE. Ogni modifica è una reversal entry con motivazione, mai una sovrascrittura.

## Philosophy (Filosofia)

- **Append-only ledger**: ogni fattura è scritta una volta, mai modificata
- **Audit trail obbligatorio**: ogni evento finanziario è tracciato
- **Dual-approval per fatture ad alto valore**: soglia configurabile
- **Reconciliation job nightly**: matching automatico con estratti conto
- **Multi-currency ready**: supporto EUR/USD/GBP con tassi di cambio storici

## Policy (Politica)

- Mai UPDATE su fattura emessa
- Solo reversal entries per correzioni
- Riconciliazione bancaria obbligatoria per chiusura
- Audit trail con hash chain per integrità
- Soft delete per fatture mai pagate, mai per quelle emesse

## Why (Perché)

Billing esiste perché la fatturazione ha requisiti di integrità e conformità che non possono essere gestiti inline nei moduli operativi. Billing è il garante dell'integrità finanziaria del sistema e supporta la compliance fiscale.

## Zen

*"Ogni numero è una promessa. Ogni promessa è mantenuta."*

## Gap

- 20 Actions (già buono, ma sbilanciato)
- Quasi zero test
- Reconciliation job automatica non implementata
- Solo template fattura base
- Nessuna integrazione con payment gateway
- Reporting finanziario basilare

## Add

- Reconciliation job automatica con riconciliazione estratti conto
- Integrazione Stripe/PayPal per pagamenti online
- Multi-currency support completo con tassi storici
- Invoice aging e overdue notifications
- Financial reporting dashboard
- Esportazione per commercialisti (CSV/XML standard)
- Fatturazione elettronica (SDI per Italia, SDI-equivalent per EU)

## Split/Merge

**Mantenere separato.** Billing è finanziariamente distinto dal processing operativo. Tuttavia, potrebbe parzialmente fondersi con Production se la fatturazione production-divenisse dominante. Attualmente la separazione è netta e non va toccata.

## Future Enhancements

1. **AI-powered cash flow forecasting**: previsione incassi basata su storico
2. **Smart dunning**: gestione automatica solleciti con escalation
3. **Cryptographic audit trail**: hash chain per ogni entry finanziaria
4. **Real-time reconciliation**: matching in tempo reale invece di nightly batch
5. **Multi-tax jurisdictions**: supporto per giurisdizioni fiscali multiple
6. **Financial AI assistant**: chat per analisi dati finanziari
