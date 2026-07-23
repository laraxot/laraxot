---
title: "No Orphan Http Controllers"
type: concept
tags: [orphan, http, controllers]
created: 2026-07-14
updated: 2026-07-14
qmd: "no-orphan-http-controllers no orphan http controllers"
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

# No Orphan Http Controllers

## Regola

Nel modulo `Notify` non devono esistere controller HTTP orfani, cioe' file sotto `app/Http/Controllers` senza route effettive o senza un ruolo architetturale chiaro nel boundary web.

## Caso concreto

`NotificationTrackingController.php` non deve stare nel modulo:

- non risultano route collegate;
- incapsula tracking open/click come controller legacy;
- il tracking delle notifiche e' dominio applicativo e va gestito tramite action/service dedicati oppure introdotto solo quando esiste davvero un boundary HTTP dichiarato.

## Regola operativa

1. se un comportamento non ha route vive, il controller non va tenuto nel modulo;
2. se il tracking serve davvero, prima si definisce il contratto architetturale in docs;
3. poi si implementa nel punto corretto, evitando file HTTP morti o non integrati.
