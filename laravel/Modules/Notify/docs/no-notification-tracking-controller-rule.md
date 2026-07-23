---
title: "Notify Rule: No NotificationTrackingController"
type: rule
tags: [notification, tracking, controller, rule]
created: 2026-07-14
updated: 2026-07-14
qmd: "no-notification-tracking-controller-rule notify rule: no notificationtrackingcontroller"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index-1.md"
  - "./00-index-2.md"
  - "./00-index.md"
  - "./absolute-completion-100.md"
  - "./acronym-naming-conventions-1.md"
  - "./acronym-naming-conventions-2.md"
  - "./acronym-naming-conventions.md"
  - "./action-plan-immediate.md"
---

# Notify Rule: No NotificationTrackingController

Nel modulo Notify non deve esistere `app/Http/Controllers/NotificationTrackingController.php`.

Motivazione:
- il tracking notifiche va gestito tramite Actions/Channels dedicati,
- evitare controller legacy non allineati alla struttura corrente,
- ridurre superfici HTTP non governate dal flusso modulo.

Conseguenza operativa:
- rimuovere il file controller dal runtime,
- mantenere eventuale tracking dentro action class testabili e servizi di canale,
- non spostare questa responsabilita' nel tema o nei file Folio/Blade.
