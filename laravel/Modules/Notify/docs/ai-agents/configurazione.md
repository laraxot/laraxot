---
title: "Configurazione e Setup"
type: concept
tags: [configurazione]
created: 2026-07-14
updated: 2026-07-14
qmd: "configurazione configurazione e setup"
issues: ["https://github.com/provtv/base_ptv_fila5/issues/124"]
discussions: ["https://github.com/provtv/base_ptv_fila5/discussions/1"]
related:
  - "./00-index.md"
  - "./01-gsd-workflow.md"
  - "./02-bmad-workflow.md"
  - "./03-architecture-zen.md"
  - "./04-filament-philosophy.md"
  - "./05-front-office-audit.md"
  - "./06-cinematic-effects.md"
  - "./07-mcp-tailwind-ui.md"
---

# Configurazione e Setup

## Requisiti
- PHP 8.2+
- Laravel 12.x
- Database MySQL/PostgreSQL
- Composer

## Installazione
```bash
# Installazione dipendenze
composer install

# Configurazione ambiente
cp .env.example .env
php artisan key:generate

# Esecuzione migrazioni
php artisan migrate

# Abilitazione moduli
php artisan module:enable User Performance Gdpr Activity
```

## Ambiente
Il sistema supporta connessioni multiple per ottimizzazione performance:
- `mysql` - Database principale
- `performance` - Database valutazioni performance
- `user` - Database dati sensibili (GDPR)

## Approfondimenti
- Come configurare le Actions: [Regole Critiche Laraxot](./regole-critiche.md#actions-vs-services)
- Gestione della configurazione dei moduli: [Moduli Principali](./moduli-principali.md)