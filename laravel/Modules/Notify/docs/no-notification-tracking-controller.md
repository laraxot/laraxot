---
title: "Notify: no NotificationTrackingController"
type: concept
tags: [notification, tracking, controller]
created: 2026-07-14
updated: 2026-07-14
qmd: "no-notification-tracking-controller notify: no notificationtrackingcontroller"
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

# Notify: no NotificationTrackingController

## Regola

`Modules/Notify/app/Http/Controllers/NotificationTrackingController.php` non deve stare nel modulo.

## Perche'

- mescola transport HTTP, tracking, mutazione stato e redirect in un punto unico;
- sposta nel boundary web una responsabilita' che deve restare nel dominio `Notify`;
- rende il tracking meno riusabile, meno testabile e piu' facile da duplicare nei temi.

## Approccio corretto

- action dedicate per open/click tracking;
- route sottili, se davvero necessarie, che delegano subito al dominio;
- niente controller monolitici o orfani per tracking notifiche;
- nessuna logica di tracking nel tema.

## Nota di governance

La sua ricomparsa va trattata come regressione architetturale, non come semplice refactor incompleto.
