---
title: "Notify Module — Doctrine"
type: doctrine
tags: [notification, messaging, module-doctrine]
created: 2026-09-05
updated: 2026-09-05
qmd: "Notify module doctrine BMAD analysis purpose religion philosophy policy why zen gap enhancements split merge"
related:
  - "../../Xot/docs/module.md"
  - "../../Email/docs/module.md"
---

# Notify Module — Doctrine

## Scope (Scopo)

Notify orchestra le notifiche multi-canale: email, SMS, WhatsApp, Telegram, FCM push. Gestisce template personalizzabili, preferenze utente, tracciabilità delle consegne. È il servizio di messaggeria affidabile che sceglie il canale migliore.

## Religion (Religione)

**"Una promessa, una consegna."** La convinzione non negoziabile è che ogni notifica promessa deve essere consegnata attraverso il canale giusto, al momento giusto, rispettando le preferenze dell'utente.

## Philosophy (Filosofia)

- **Multi-channel orchestration**: canali multipli, un'interfaccia
- **Template separation**: contenuto separato dal meccanismo
- **Channel abstraction**: canali intercambiabili
- **Preference respect**: preferenze utente come first-class citizen
- **Delivery tracking**: success/failure per ogni canale

## Policy (Politica)

- Ogni notifica specifica almeno un canale
- Template modificabili senza deploy
- Preferenze utente rispettate per canale e tipo
- Feedback consegna per ogni canale
- Credenziali servizi esterni in config sicura

## Why (Perché)

Multi-canale con protocolli, formati, e requisiti diversi giustifica un modulo dedicato. Orchestrare canali multipli inline sarebbe ingestibile.

## Zen

*"Il messaggio giusto, nel canale giusto, al momento giusto."*

## Gap

- Test integrazione servizi esterni limitati
- Policies assenti
- Convenzioni template non documentate
- Logiche di configurazione disperse
- Retry e dead letter queue mancanti

## Add

- Policies per template e configurazioni
- Test con mock per canali
- Retry con exponential backoff
- Dashboard monitoring consegne
- Preferenze granulari per tipo e frequenza

## Split/Merge

**Mantenere come-is.** Il focus è sull'orchestrazione multi-canale, distinta da Email (creazione contenuto) e WhatsApp (canale specifico).

## Future Enhancements

1. **Smart channel selection**: ML per scegliere canale migliore
2. **Notification scheduler**: invio programmato ottimale
3. **Notification analytics**: engagement per canale
4. **User preference AI**: suggerimenti preferenze basati su engagement
5. **Transactional vs marketing**: separazione执法 per compliance
6. **Notification inbox**: inbox unificata per tutte le notifiche
