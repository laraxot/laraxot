---
title: "Cms — Content Management System"
description: "Content Management System con Laravel Folio e Livewire Volt per gestione pagine e contenuti dinamici"
module: "Cms"
alias: "cms"
version: "1.0.0"
priority: 3
active: true
status: "content-management"
author: "Team Laraxot"
license: "Proprietary"
php_version: "^8.1"
core_version: "10.0"
dependencies: ["Xot"]
extends: []
extended_by: 0
documentation_date: "2026-05-27"
---

# Cms — Content Management System

## Scopo

Cms è il modulo che gestisce pagine e contenuti dinamici tramite Laravel Folio e Livewire Volt. È il sistema di gestione contenuti dell'ecosistema: pagine, articoli, blocchi, footer, header, navigazione, configurazione di tema.

## Religione

- **"Folio e Volt per le pagine pubbliche"**: ogni pagina pubblica è in `resources/views/pages/`
- **"Blocco come primitiva"**: ogni contenuto è un blocco riusabile con configurazione JSON
- **"Configurazione runtime"**: footer, header, navigazione sono configurati a runtime tramite `SaveFooterConfigAction`, `SaveHeadernavConfigAction`
- **"XotBase come fondamento"**: ogni componente UI estende i widget Xot
- **"Traduzioni dai file di lingua"**: mai hardcoded labels

## Filosofia

Cms crede che **il contenuto sia un asset, non un codice**. Ogni pagina è una composizione di blocchi, ogni blocco è configurabile senza toccare il codice. Il sistema è progettato per **content team** che devono poter modificare pagine senza dipendere dagli sviluppatori.

## Politica

- **Pages via Folio**: `resources/views/pages/` contiene pagine pubbliche, file-based routing
- **Volt components**: ogni componente interattivo è un Volt component
- **Block schema**: ogni blocco ha uno schema JSON per la configurazione
- **Translation keys**: `cms::cms.field` per ogni label
- **XotBaseServiceProvider boot**: auto-discovery di views, translations, components

## Zen

> **"La pagina è un blocco. Il blocco è un pattern. Il pattern è la conoscenza."**

Lo Zen di Cms è la **componibilità**. Ogni contenuto è composto da blocchi, ogni blocco è composto da pattern, ogni pattern è conoscenza condivisibile.

## Perché esiste

Le applicazioni web moderne hanno bisogno di pagine dinamiche, gestione contenuti, configurazione di temi. Cms esiste per **centralizzare questa responsabilità** in un modulo riusabile.

## Cosa Mancherebbe (Gap Analysis)

| Gap | Severità | Suggerimento |
|-----|----------|--------------|
| Manca editor visuale per blocchi | Alta | Aggiungere block editor WYSIWYG |
| Nessun sistema di versioning contenuti | Alta | Aggiungere content versioning con rollback |
| Manca sistema di scheduling pubblicazione | Media | Aggiungere `PublishScheduleAction` |
| Nessun modulo di SEO analysis | Media | Integrare con `Seo` per ottimizzazione |
| Manca A/B testing per pagine | Bassa | Aggiungere `AbTesting` per varianti |
| Nessun sistema di cache contenuti | Bassa | Implementare content cache intelligente |

---

*Documento generato secondo le convenzioni del progetto — modulo `Cms` — data 2026-05-27*