---
type: bmad-initiative
title: DesignComuni - Registrazione autenticazione e pagine statiche blog/eventi
status: open
created: 2026-10-17
updated: 2026-10-17
initiative_id: kp-designcomuni-auth-blog
tags: [design-comuni, auth, register, blog, events, bmad, second-brain, filament-blueprint]
related:
  - module: Fixcity
  - module: User
  - initiative_id: kp-servizi-design-comuni
summary: |
  Allineare le pagine di registrazione (/it/auth/register), recupero password (/it/auth/password/reset), blog (/it/blog) ed eventi (/it/events) al pattern DesignComuni (design-comuni-pagine-statiche: argomenti, argomento, lista-risorse, novità, eventi). Utilizzare BMAD + Second Brain + EEC + Filament Blueprint per tracciare le decisioni architetturali e mantenere la memoria nel second brain.
scope_in: [auth/register, auth/password/reset, auth/login, blog/index, events/index]
scope_out: [admin, backend-only controllers]
docs:
  design-comuni-patterns: https://italia.github.io/design-comuni-pagine-statiche/sito/
  reference-blog-1: https://www.afcformazione.it/blog/
  reference-blog-2: https://www.aferrari.it/articoli/
  reference-blog-3: https://www.assodellavendita.it/blog/
