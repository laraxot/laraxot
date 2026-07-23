---
title: "Indice Documentazione Notifiche"
type: concept
tags: [index]
created: 2026-07-14
updated: 2026-07-14
qmd: "index indice documentazione notifiche"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./errori-comuni-da-evitare-1.md"
  - "./errori-comuni-da-evitare.md"
  - "./multi-channel-notifications-1.md"
  - "./multi-channel-notifications-2.md"
  - "./multi-channel-notifications.md"
  - "./netfun-sms-implementation-1.md"
  - "./netfun-sms-implementation.md"
  - "./notifications-implementation-guide-1.md"
---

# Indice Documentazione Notifiche

## Collegamenti Correlati
- [Indice Documentazione Notify](../index.md)
- [README Modulo Notify](../readme.md)
- [Documentazione Generale Quaeris](../../../../../../docs/readme.md)
- [Collegamenti Documentazione](../../../../../../docs/collegamenti-documentazione.md)

## Guida Implementazione
- [Guida Implementazione Notifiche](./notifications-implementation-guide-1.md) - Guida generale all'implementazione delle notifiche
- [Notifiche Multi-Canale](./multi-channel-notifications-2.md) - Implementazione di notifiche su più canali
- [Errori Comuni da Evitare](./errori-comuni-da-evitare-1.md) - Problemi comuni e come evitarli

## Canali di Notifica

### SMS
- [Implementazione SMS Dettagliata](./sms-implementation-details-2.md) - Dettagli implementativi per il canale SMS
- [Configurazione Provider SMS](./sms-provider-configuration-2.md) - Configurazione dei provider SMS
- [Implementazione Netfun SMS](./netfun-sms-implementation-1.md) - Implementazione specifica per il provider Netfun

### Telegram
- [Guida Notifiche Telegram](./telegram-notifications-guide-1.md) - Implementazione delle notifiche Telegram

## Architettura e Pattern
- [Factory Pattern per Provider](../factory-pattern-analysis-1.md) - Analisi del pattern Factory per i provider
- [Provider vs DTO](../channel-vs-dto-provider-selection-1.md) - Selezione tra provider e DTO
- [Architettura Provider](../provider-actions-architecture-2.md) - Architettura delle azioni provider

## Documentazione Correlata
- [Implementazione SMS](../sms-implementation-1.md) - Panoramica dell'implementazione SMS
- [Canale WhatsApp](../whatsapp-channel-2.md) - Documentazione del canale WhatsApp
- [Canale Telegram](../telegram-channel-2.md) - Documentazione del canale Telegram

## Note Importanti
- Quaeris utilizza il pattern Factory per la creazione delle azioni di invio messaggi
- Il sistema si basa su Queueable Actions (spatie/laravel-queueable-action) e non su Service Pattern
- Le azioni specifiche per provider devono implementare l'interfaccia comune corrispondente
- I DTO standardizzati vengono utilizzati come ponte tra il sistema e i provider specifici

## Regole di Implementazione

1. Per ogni provider configurato deve esistere una corrispondente azione
2. Tutte le azioni devono implementare l'interfaccia comune
3. I canali devono utilizzare le factory per la creazione delle azioni
4. Le factory devono gestire la selezione del driver predefinito

