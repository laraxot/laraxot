---
title: "Customer Module — Doctrine"
type: doctrine
tags: [customer, anagrafica, module-doctrine]
created: 2026-09-05
updated: 2026-09-05
qmd: "Customer module doctrine BMAD analysis purpose religion philosophy policy why zen gap enhancements split merge"
related:
  - "../../Xot/docs/module.md"
  - "../../Billing/docs/module.md"
  - "./gestionale-commesse-port.md"
---

# Customer Module — Doctrine

## Scope (Scopo)

Customer gestisce l'anagrafica clienti: dati anagrafici, sedi, referenti, ruoli referente, rivenditori, contatti. È la fonte unica di verità per chiunque interagisca col sistema. Tutti gli altri moduli (Billing, Quotation, WorkOrder) leggono i dati cliente da qui, mai da copie locali.

## Religion (Religione)

**"Un cliente, un record, nessun duplicato."** La convinzione non negoziabile è che i dati anagrafici sono sacri: nessun duplicato, nessuno stallo, nessuna modifica invisibile. Ogni cliente è una riga, ogni modifica è tracciata, ogni accesso è loggato.

## Philosophy (Filosofia)

- **Single source of truth**: Customer è l'unica tabella per i clienti
- **Entity-relation focus**: relazioni rigorose con FK e indici
- **Soft delete per storico**: mai hard delete, mai perdita di dati
- **Ruoli referente**: un cliente può avere più referenti, ciascuno con un ruolo
- **Sedi multiple**: un cliente può avere N sedi, ciascuna con il proprio referente

## Policy (Politica)

- Email/telefono unici per cliente (vincolo DB)
- Campi obbligatori per cliente attivo
- Soft delete obbligatorio
- Accesso ai dati sensibili basato su ruolo (RBAC)
- Mai duplicare dati cliente in altri moduli

## Why (Perché)

Customer esiste perché senza un'anagrafica centralizzata, ogni modulo (Billing, Quotation, WorkOrder) avrebbe i propri dati cliente con conseguente disallineamento, duplicazione, e impossibilità di aggiornamento consistente. Customer è la base su cui si costruiscono tutte le relazioni commerciali.

## Zen

*"Una sola anagrafica, infinite interazioni. Il cliente è uno, il record è uno."*

## Gap

- Solo 8 Actions (pochi per un modulo così critico)
- Nessuna integrazione automatica con Billing/Quotation
- Nessun bulk import per migrazione da altri sistemi
- Test coverage basso
- Ricerca/filter limitati

## Add

- Bulk import/export CSV per migrazione massiva
- Sincronizzazione automatica con Billing/Quotation
- Sistema di segmentazione e tagging
- Ricerca full-text avanzata con filtri
- Integrazione con servizi di validazione P.IVA / CF
- Audit trail per ogni modifica dati sensibili

## Split/Merge

**Mantenere separato.** Customer è fondamentale ma distinto dai workflow operativi. Potrebbe in futuro fondersi parzialmente con Billing se emergono operazioni finanziarie centrate sul cliente, ma attualmente la separazione è netta. È un candidato naturale per essere la radice di un eventuale "Customer 360" se in futuro emergesse bisogno di unificare Customer + Address + Contact.

## Future Enhancements

1. **AI-assisted data enrichment**: completamento automatico dati da fonti esterne
2. **Customer scoring**: punteggio di affidabilità basato su storico pagamenti
3. **GDPR compliance integration**: richieste di rettifica/cancellazione automatiche
4. **Multi-channel contact history**: timeline unificata email/chat/call
5. **Customer journey mapping**: visualizzazione del percorso cliente nel sistema
6. **Duplicate detection AI**: identificazione probabilistica di record duplicati
