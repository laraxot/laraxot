---
title: "Email Module — Doctrine"
type: doctrine
tags: [email, messaging, module-doctrine]
created: 2026-09-05
updated: 2026-09-05
qmd: "Email module doctrine BMAD analysis purpose religion philosophy policy why zen gap enhancements split merge"
related:
  - "../../Xot/docs/module.md"
  - "../../Notify/docs/module.md"
---

# Email Module — Doctrine

## Scope (Scopo)

Email gestisce la posta in entrata e uscita con supporto IMAP/POP3, webhook di classificazione AI, threading conversazioni, e integrazione con WorkOrder e Intervento. Trasforma email da comunicazione in dati azionabili collegati alle operazioni.

## Religion (Religione)

**"Ogni email è un'entità, non un testo."** La convinzione non negoziabile è che le email sono cittadini di prima classe: tracciate, classificabili, collegabili alle entità di business. Il sistema deve trasformare email in azioni.

## Philosophy (Filosofia)

- **Entity pattern**: Conversazione, Messaggio, Contatto come entità
- **AI classification strategy**: iniettato via service provider
- **CQRS-lite**: comandi (invio/elaborazione) separati da query
- **Threading**: conversazioni raggruppate per subject/thread

## Policy (Politica)

- Ogni messaggio appartiene a una conversazione
- Direzione (in/out) tracciata esplicitamente
- Inbound processato via webhook o polling IMAP
- Classificazione AI automatica
- Collegamento a entità opzionale

## Why (Perché)

Email implica logiche complesse di protocolli (IMAP, SMTP), classificazione AI, e integrazione business. Un modulo dedicato garantisce coerenza.

## Zen

*"Posta intelligente. Da comunicazione a azione, automaticamente."*

## Gap

- Test integrazione IMAP/POP3 limitati
- Policies assenti
- Dipendenze dirette da servizi esterni (OpenAI)
- Documentazione webhook mancante
- Gestione errori rete migliorabile

## Add

- Policies per messaggi e conversazioni
- Test con mock per IMAP e AI
- Fallback per classificazione AI
- Rate limiting e circuit breaker
- Strumenti diagnostici salute connessioni

## Split/Merge

**Mantenere come-is.** Le preoccupazioni (protocolli, AI, integrazione) sono interconnesse. Separare ricezione da invio aggiungerebbe complessità.

## Future Enhancements

1. **Smart inbox**: prioritizzazione email con ML
2. **Email automation**: regole automatiche personalizzabili
3. **CRM integration**: sync bidirezionale con CRM
4. **Email analytics**: open rate, click rate, engagement
5. **Spam detection AI**: filtro antispam avanzato
6. **Email templates**: editor WYSIWYG per template
