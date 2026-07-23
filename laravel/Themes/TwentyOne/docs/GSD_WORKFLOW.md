# GSD & BMAD Workflow — Tema TwentyOne

> Workflow spec-driven per lo sviluppo del tema TwentyOne usando GSD e BMAD.
>
> **Ultimo aggiornamento**: 2026-03-18  
> **Stato**: ✅ Attivo e verificato  
> **Versione**: 2.0 (GSD v1.26.0 + BMAD v6)

## Contesto

Tema frontend per il frontoffice:
- **Stack**: Tailwind v3, Blade components, Folio routing
- **Theme**: Theme pubblico per prediction markets
- **Routing**: CMS blocks via JSON config
- **Architecture**: Theme-first, module-agnostic

## Perché GSD + BMAD?

**Problema**: Context rot — degradazione qualità AI in sessioni lunghe.

**Soluzione**: 
- **GSD** per esecuzione spec-driven (fresh context, atomic commits)
- **BMAD** per design e pianificazione (agenti specializzati)

## Documentazione

| Risorsa | Link |
|---------|------|
| **Workflow Completo** | [../../../docs/project/gsd-and-bmad-workflow.md](../../../docs/project/gsd-and-bmad-workflow.md) |
| **Guida Completa** | [../../../.agents/docs/gsd-bmad-comprehensive-guide.md](../../../.agents/docs/gsd-bmad-comprehensive-guide.md) |
| **Coordinamento** | [../../../docs/project/gsd-agent-coordination.md](../../../docs/project/gsd-agent-coordination.md) |

## Workflow GSD per il Tema

### 1. Prima di Iniziare

```bash
# Verifica sempre lo stato corrente
/gsd-progress

# Leggi lo stato
cat .planning/STATE.md

# Pull latest
git pull origin dev
```

### 2. Per Modifiche UI/UX

```bash
# 1. Discuss fase (cattura decisioni)
/gsd-discuss-phase {N}

# 2. Plan fase (crea piani eseguibili)
/gsd-plan-phase {N}

# 3. Execute (implementa con atomic commits)
/gsd-execute-phase {N}

# 4. Verify (testa con UAT)
/gsd-verify-work {N}
```

### 3. Per Task Rapidi (UI Fix)

```bash
# Usa quick mode per fix UI
/gsd-quick "Fix button alignment in homepage"

# O debug mode
/gsd-debug "Homepage filters not working on mobile"
```

### 4. Per Task Complessi (Multi-Agent)

```
Agent 1 (UI Researcher):
  # Studia competitors (Polymarket, Kalshi)
  # Analizza best practices UX
  # Crea .planning/research/ui-{feature}.md
  # Commit: "docs: research ui {feature}"

Agent 2 (UX Designer):
  # Legge research
  # Crea UX design
  # Aggiorna STATE.md
  # Commit: "docs: ux design {feature}"

Agent 3 (Planner):
  # Legge UX design
  # Crea phase-N-PLAN.md
  # Commit: "docs: complete phase N planning"

Agent 4+ (Executors):
  # Wave execution parallela
  # Implementa componenti Blade
  # Commit per task atomico

Agent N (Verifier):
  /gsd:verify-work N
  # Testa UI/UX
  # Commit: "test: verify phase N"
```

## Regole Specifiche Tema

### Architecture Rules

| Regola | Descrizione |
|--------|-------------|
| **Theme-first** | Pagine servite dal tema, non dai moduli |
| **Generic blade purity** | `[container0]/index.blade.php` rimane generico |
| **Module-agnostic** | Tema non dipende da moduli specifici |
| **Blade minimal logic** | Logica in View Components, Actions |

### Generic Blade Purity

```php
// ❌ Il generic blade NON deve contenere logica modulo-specifica
// resources/views/pages/[container0]/[slug0]/index.blade.php

// ❌ MAI fare questo
@if($container0 === 'predicts')
    @php
        $markets = Predict::where('status', 'open')->get();
        // Logica specifica Predict
    @endphp
@endif

// ✅ Deve solo fare routing a blade specifici
@if($container0 === 'predicts')
    @include('predict::pages.predict-detail')
@elseif($container0 === 'events')
    @include('events::pages.event-detail')
@else
    {{-- Generic CMS rendering --}}
@endif
```

### Componenti Blade

**SEMPRE**:
- Minimizzare logica nelle Blade
- Spostare query/calcoli in View Components PHP
- Usare traduzione `__()` con fallback robusti

```blade
{{-- ❌ SBAGLIATO: Logica complessa in Blade --}}
@php
    $markets = DB::table('predicts')
        ->where('status', 'open')
        ->orderBy('created_at', 'desc')
        ->limit(10)
        ->get();
@endphp

{{-- ✅ CORRETTO: View Component --}}
<x-predict::markets-list :limit="10" />
```

### Quality Gate Rules

**SEMPRE** prima di commit:

```bash
# 1. PHPStan (se applicabile)
composer phpstan

# 2. Laravel Pint (format)
vendor/bin/pint

# 3. Test (se applicabile)
php artisan test

# 4. Verify UI
/gsd-verify-work {N}

# 5. Test manuale
# - Desktop (Chrome, Firefox, Safari)
# - Mobile (iOS Safari, Chrome Android)
# - Tablet
```

### I18n Rules

**SEMPRE** traduzioni complete:

```blade
✅ __('theme::user.fields.first_name.label')
❌ __('theme::fields.key')  // Missing type!
```

**SEMPRE** fallback in blade:

```blade
{{ $title ?? __('theme::labels.untitled') }}
```

## File Chiave Tema TwentyOne

| File | Scopo |
|------|-------|
| `docs/blade-generic-architecture.md` | Architettura blade generico |
| `docs/README.md` | README del tema |
| `docs/theme-workflow.md` | Workflow tema |
| `docs/filament-philosophy.md` | Filosofia Filament |
| `prd.json` | Product Requirements |
| `product-roadmap.md` | Roadmap tema |

## Best Practices

### Documentation First

**MAI** scrivere codice prima di:
1. Leggere gsd-and-bmad-workflow.md
2. Verificare `.planning/STATE.md`
3. Capire UX design decisions
4. Avere piano chiaro

### Atomic Commits

**SEMPRE** un commit per task:

```bash
# ✅ CORRETTO
abc123f docs: complete homepage UX research
def456g feat: add hero section component
hij789k feat: add filters sidebar

# ❌ SBAGLIATO
xyz7890 feat: updated homepage with all improvements
```

### Multi-Agent Coordination

**SEMPRE**:
1. Leggi coordination docs prima di iniziare
2. Aggiorna `STATE.md` con tuo progress
3. Non duplicare lavoro di altri agenti
4. Usa Git per comunicare (commit messages chiari)

### UI/UX Excellence

**SEMPRE**:
- Mobile-first responsive design
- Accessibility (WCAG 2.2 AA)
- Performance (Lighthouse > 90)
- Cross-browser testing

## Comandi GSD Rapidi

```bash
# Core
/gsd-help
/gsd-new-project
/gsd-plan-phase 1
/gsd-execute-phase 1
/gsd-verify-work 1

# Navigation
/gsd-progress
/gsd-settings
/gsd-set-profile balanced

# Session
/gsd-pause-work   # Handoff
/gsd-resume-work  # Riprendi

# Utils
/gsd-quick "task"
/gsd-debug "desc"
/gsd-add-todo "idea"
```

## Esempio: Implementare Homepage Hero Section

```
# Fase 1: Research + UX Design
Agent 1: Studia competitors (Polymarket, Kalshi)
Agent 2: Analizza best practices UX
Agent 3: Crea UX design
Commit: "docs: homepage hero UX design"

# Fase 2: Planning
Agent 4: /gsd:plan-phase 2
Agent 5: Verifica piani
Commit: "docs: complete phase 2 planning"

# Fase 3: Execution (Wave)
Wave 1: Create components (hero, badges, stats)
Wave 2: Integrate components
Wave 3: Responsive + accessibility
Commit per task: "feat: add {component}"

# Fase 4: Verification
Agent N: /gsd:verify-work 2
# Test: Desktop, Mobile, Accessibility
Commit: "test: verify phase 2 complete"
```

## Riferimenti

### Documentazione Progetto

- [Workflow Completo](../../../docs/project/gsd-and-bmad-workflow.md)
- [Guida Completa](../../../.agents/docs/gsd-bmad-comprehensive-guide.md)
- [Coordinamento](../../../docs/project/gsd-agent-coordination.md)
- [AGENTS.md](../../../AGENTS.md)

### Documentazione Tema

- [Blade Generic Architecture](./blade-generic-architecture.md)
- [Theme Workflow](./theme-workflow.md)
- [Filament Philosophy](./filament-philosophy.md)
- [PRD](./prd.json)
- [Product Roadmap](./product-roadmap.md)

### Documentazione Progetto (Container Blade)

- [Container Blade Correct Architecture](../../../docs/project/CONTAINER_BLADE_CORRECT_ARCHITECTURE.md)
- [Container Blade Pollution Error](../../../docs/project/CONTAINER_BLADE_POLLUTION_ERROR_FIX.md)

### Risorse Esterne

- **GSD GitHub**: https://github.com/gsd-build/get-shit-done
- **GSD Docs**: https://gsd-build-get-shit-done.mintlify.app/
- **BMAD GitHub**: https://github.com/bmad-code-org/BMAD-METHOD
- **BMAD Docs**: https://docs.bmad-method.org/

---

**Mantenuto da**: AI Agents Team  
**Versione**: 2.0  
**Ultimo aggiornamento**: 2026-03-18
