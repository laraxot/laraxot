---
title: "Module Architecture"
type: concept
tags: [module, architecture]
created: 2026-07-14
updated: 2026-07-14
qmd: "module-architecture module architecture"
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

# Module Architecture

> Architettura modulare Laravel per PTVX.

## 🏗️ Struttura Base

```
laravel/
├── Modules/
│   ├── Xot/           # Core framework Laraxot
│   ├── User/          # Gestione utenti
│   ├── UI/            # Componenti UI condivisi
│   ├── Ptv/           # Modulo principale PTVX
│   └── Performance/   # Gestione performance
└── Themes/One/        # Tema frontend
```

## 📦 Struttura Modulo

```
Modules/{ModuleName}/
├── app/
│   ├── Actions/           # Spatie QueueableActions
│   ├── Data/              # Spatie Laravel Data
│   ├── Filament/          # Resources, Pages, Widgets
│   ├── Models/            # Eloquent models
│   └── ...
├── database/
│   ├── migrations/
│   └── factories/
├── docs/
├── resources/
│   └── lang/
└── routes/
```

## 🔧 Estensioni Obbligatorie

```php
// ✅ CORRETTO
class UserServiceProvider extends XotBaseMigration
class User extends BaseModel
class UserResource extends XotBaseResource

// ❌ ERRATO
class User extends Illuminate\Database\Eloquent\Model
```

## 📋 Checklist Nuovo Modulo

- [ ] Struttura directory corretta
- [ ] Namespace senza segmento 'app'
- [ ] Service Provider estende XotBaseServiceProvider
- [ ] Modelli estendono BaseModel del modulo
- [ ] Repository pattern implementato
- [ ] Actions per logica di business
- [ ] Traduzioni complete (it/en/de)

## 🔗 Link

**Precedente:** [Project Context](project-context.md) | **Successivo:** [Code Style](code-style.md)

**Di ritorno:**
- [claude.md - Architecture Section](../../claude.md)
- [AGENTS.md - Module Architecture Section](../../AGENTS.md)
