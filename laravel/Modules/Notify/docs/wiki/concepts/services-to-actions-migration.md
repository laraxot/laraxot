---
title: "Services → QueueableAction — modulo Notify"
type: concept
tags: [notify, push, actions, migration]
created: 2026-07-13
updated: 2026-07-13
qmd: "Notify PushNotificationService removed SendPushNotificationAction scheduled job"
issues:
discussions:
related:
  - "./claude-audit-static.md"
  - "./code-redundancy-notify.md"
  - "./composer-root-minimal-nwidart.md"
  - "./context-overflow-prevention.md"
  - "./enum-standards.md"
  - "./llm-wiki-governance.md"
  - "./method-name-homonyms.md"
  - "./module-root-uppercase-folders-archive.md"
---

# Notify — `PushNotificationService` eliminato

`app/Services/PushNotificationService.php` rimosso. Logica migrata in `Actions/Push/`,
un'azione per responsabilità (una sola `execute()` pubblica ciascuna, come da
`queueable-action-trait-mandatory`):

- `Push/SendPushToDeviceAction` — invio a un singolo token multi-piattaforma
- `Push/SendPushToDevicesAction` — invio a più token, raggruppati per piattaforma
- `Push/SendPushToPlatformAction` — delivery su una piattaforma specifica (FCM reale via HTTP,
  APNS/WebPush simulati — limite già presente nel Service originale, non introdotto dalla
  migrazione)
- `Push/SendPushToTopicAction` — invio a un topic
- `Push/SendPushToAllUsersAction` — invio a tutti i token attivi
- `Push/SendPushWithTemplateAction` — invio da template
- `Push/SendPushWithTargetingAction` — invio con criteri di targeting
- `Push/SchedulePushNotificationAction` — schedulazione via cache + job

`Jobs/SendScheduledPushNotification::handle()` inietta `Push\SendPushToDevicesAction` e chiama:

```php
$pushService->execute($tokens, $notification, $data);
```

Due tentativi intermedi di migrazione — `Actions/PushNotificationAction` (duplicato 1:1 del
vecchio Service, multi-metodo pubblico) e `Actions/SendPushNotificationAction` +
`Actions/PushNotificationPlatformDelivery` (wrapper multi-metodo attorno alla stessa logica) —
sono stati rimossi il 2026-07-13 perché non referenziati altrove e superati dallo split in
`Actions/Push/`.
