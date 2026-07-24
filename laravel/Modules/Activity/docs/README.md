---
title: "Activity Module Documentation"
type: documentation
tags: [module, documentation]
created: 2026-06-05
updated: 2026-06-05
---

# Modulo Activity

## Overview

Il modulo **Activity** fa parte dell'ecosistema Laraxot PTVX.

## Scopo

Fornisce audit trail e activity logging basato su `spatie/laravel-activitylog` ed `spatie/laravel-event-sourcing`. Espone `LogActivityAction` (`app/Actions/LogActivityAction.php`) come entrypoint per registrare eventi (type, causer, subject, properties) e risorse Filament per consultare/analizzare i log.

## Struttura

```
Activity/
├── app/
│   ├── Models/
│   ├── Filament/
│   └── ...
├── docs/
├── lang/
└── resources/
```

## Dipendenze

- [Xot Base](../Xot/docs/)
- [User Module](../User/docs/) (se usa autenticazione)
- [Tenant Module](../Tenant/docs/) (se multi-tenant)

## Collegamenti

- [Documentazione Root](../../../docs/ACTIVITY_MODULE.md)
- [Regole Architecture](../Xot/docs/architecture/)

## Backlinks

- [Indice Moduli](../README.md)

## TODO

- [ ] Completare descrizione funzionalità
- [ ] Documentare modelli principali
- [ ] Documentare risorse Filament
- [ ] Aggiungere esempi codice

## AI Workflows
- [AI Methodologies](./ai-methodologies.md)
