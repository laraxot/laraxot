---
title: Activity Module - Documentation Index
type: index
tags: [activity, audit-log, event-sourcing, phpstan]
created: 2025-12-13
updated: 2026-07-23
---

# 📚 Indice Documentazione Modulo Activity

**Status**: PHPStan Level 9 (verificato in `phpstan.neon`; `LogActivityAction` presente in `app/Actions/LogActivityAction.php`)

## 🎯 Lettura Essenziale
1. [README.md](./README.md) - Panoramica del modulo.
2. [philosophy.md](./philosophy.md) - Principi dell'Audit Trail e dell'Event Sourcing.

## 🏗️ Architettura e Pattern
- [Core Structure](./structure.md) - Organizzazione interna del modulo.
- [Event Sourcing](./event-sourcing.md) - Implementazione degli eventi di dominio.

## 📊 Filament & UI
- [Filament Resources](./filament-resources.md) - Gestione Log e Analytics nell'Admin Panel.
- [Analytics Widgets](./dual-label-chart-widget-implementation.md) - Grafici e statistiche.
- [Nested Resources](./filament-5-nested-resources-complete-guide.md) - Risorse nidificate in Filament v5.

## 🧪 Qualità e Testing
- [PHPStan Compliance](./phpstan-analysis.md) - Report sulla compliance (livello configurato: 9, cfr. phpstan.neon).
- [Testing Strategy](./testing-strategy-implementation.md) - Approccio Pest/PHPUnit.
- [PHPMD Fixes](./phpmd-fixes.md) - Risoluzione dei problemi di complessità ciclomatica.
- PSR-4 Test Helpers: preferire Pest e classi anonime nei test; evitare classi helper top-level non allineate al filepath.

## 🔧 Actions Pattern (verificato in codice)

```php
use Modules\Activity\Actions\LogActivityAction;

app(LogActivityAction::class)->execute(
    type: 'user.login',
    user: $user,
    subject: $record,
    properties: ['ip' => request()->ip()],
    description: 'User logged in successfully'
);
```

## 📦 Pacchetti Composer
- [Riferimento composer packages](../../../../bashscripts/ai/wiki/memories/composer-packages-reference.md)
- `spatie/laravel-activitylog` - Audit trail
- `spatie/laravel-event-sourcing` - Event sourcing, CQRS

## 🔗 Moduli Correlati
- [Xot](../../Xot/docs/README.md) - Core framework.
- [User](../../User/docs/README.md) - Autenticazione e causer activity.

## Dependency Intelligence
- [Dependency intelligence](dependency-intelligence.md)

## Regola Operativa Obbligatoria
- Prima di modificare codice: ragionare, studiare i docs del modulo/tema, aggiornare docs/rules/memory/skills.
- Riferimento globale: [Pre-Edit Docs-First Rule](../../../../docs/rules/pre-edit-docs-first-rule.md)
- Memory: [Pre-Edit Docs-First Memory](../../../../docs/memory/pre-edit-docs-first-memory.md)
- Skill: [Pre-Edit Docs-First Skill](../../../../docs/skills/pre-edit-docs-first-skill.md)

---
*Documentazione conforme agli standard Laraxot - DRY + KISS + SOLID*
</content>
