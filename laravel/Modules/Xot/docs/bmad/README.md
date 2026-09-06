---
title: "Xot — BMAD Method Integration"
description: "BMAD workflow documentation per il modulo Xot"
module: "Xot"
alias: "xot"
version: "1.0.0"
priority: 2
active: true
status: "core-foundation"
author: "Team Laraxot"
license: "Proprietary"
php_version: "^8.1"
core_version: "10.0"
dependencies: ["User", "Tenant"]
extends: []
extended_by: 46
documentation_date: "2026-05-27"
bmad_version: "6.2.0"
bmad_track: "enterprise"
---

# Xot — BMAD Method Integration

## Scopo BMAD per Xot

Xot è il **modulo fondamentale** che rende possibile l'intero ecosistema Laraxot. In BMAD, questo modulo rappresenta l'**architettura di base** che deve essere stabilita prima che qualsiasi altro modulo possa esistere.

## Religione BMAD per Xot

Xot incarna i principi di base del sistema BMAD di sviluppo:

1. **`project-context.md`** è la costituzione - non puoi svilupparti senza comprenderla prima
2. **`docs/` è la fonte della verità** - leggi documentazione prima di scrivere codice
3. **`phpstan.neon` è sacro** - nessun ignoreErrors, correggi nel codice
4. **Actions, non Services** - logica di business solo in Actions
5. **Never Filament directly** - tutte le classi Filament passano per XotBase
6. **Folio + Volt per pubblico** - Filament per admin

## Workflow BMAD Consigliati per Xot

### Phase 1: Analysis (Fondamentale per Xot)
```bash
bmad-domain-research      # Studio del dominio Xot (classi base, convenzioni)
bmad-technical-research   # Fattibilità tecnica per le classi base
bmad-create-product-brief # Breve su cosa dovrebbe fare Xot
```

### Phase 2: Planning (Fondamentale per Xot)
```bash
bmad-create-prd           # PRD modulo Xot: classi base, convenzioni, regole
bmad-create-architecture  # Architettura delle classi base
bmad-create-ux-design     # UX per admin tramite Filament
```

### Phase 3: Solutioning (Fondamentale per Xot)
```bash
bmad-create-epics-and-stories  # Epic: XotCore + XotFilament
bmad-check-implementation-readiness  # Validate all PRD→UX→Arch allineamenti
```

### Phase 4: Implementation
```bash
bmad-sprint-planning      # Piano sprint per modulare Xot
bmad-create-story         # Story: XotBaseModel
bmad-dev-story            # Implementazione
bmad-code-review          # Review con focus su phpstan level 10
```

## Quick Flow per Xot

Per task rapidi su Xot:
```bash
bmad-quick-dev "Aggiungi XotBaseModel con tracciamento created_by"
bmad-quick-spec "Specifica tecnica per XotBaseResource"
```

## Agenti Specializzati per Xot

| Agente | Ruolo | Quando Usare |
|--------|-------|--------------|
| Mary 📊 | Analyst | Studio dominio Xot, ricerca pattern |
| John 📋 | PM | PRD per Xot, requisiti specifici |
| Winston 🏗️ | Architect | Architettura delle classi base, regole del modulo |
| Amelia 💻 | Developer | Implementazione Actions Xot, classi base |
| Quinn 🧪 | QA | Test Xot, PHPStan level 10, edge case |

## Configurazione

```bash
# Verifica che Xot sia abilitato
php artisan module:status Xot

# Esegui migration
php artisan migrate --path=modules/Xot/database/migrations

# Seed iniziale
php artisan db:seed --class=XotDatabaseSeeder

# Verifica PHPStan
./vendor/bin/phpstan analyse Modules/Xot --memory-limit=-1
```

## Struttura Output BMAD

```
_bmad-output/
├── planning-artifacts/
│   ├── PRD.md              # Requisiti modulo Xot
│   ├── architecture.md     # Architettura delle classi base
│   └── epics/
│       └── epic-001-xot-base.md
└── implementation-artifacts/
    ├── sprint-status.yaml
    └── story-001-xotbasemodel.md
```

## Vedi Anche

- [quick-reference](quick-reference.md)
- [setup-guide](setup-guide.md)
- [BMAD Workflow Catalog](../bmad-workflow-catalog.md)
- [merge proposals](MERGE_PROPOSAL_AI_VS_AIASSISTANT.md)

---

*Xot · BMAD Method · data 2026-05-27*