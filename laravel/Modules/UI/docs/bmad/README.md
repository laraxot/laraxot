---
title: "UI — BMAD Method Integration"
description: "BMAD workflow documentation per il modulo UI"
module: "UI"
alias: "ui"
documentation_date: "2026-05-27"
bmad_version: "6.2.0"
bmad_track: "design-system"
---

# UI — BMAD Method Integration

## Scopo BMAD per UI

UI è il **design system** che garantisce coerenza visiva in tutto l'ecosistema. In BMAD, questo modulo rappresenta l'**esperienza utente** su cui tutti gli altri moduli fanno affidamento.

## Workflow BMAD Consigliati per UI

### Phase 1: Analysis
```bash
bmad-domain-research      # Studio design system, componenti
bmad-technical-research   # Valutazione Tailwind, Filament v5
```

### Phase 2: Planning
```bash
bmad-create-prd           # PRD design system: tokens, componenti, temi
bmad-create-ux-design     # UX per componenti UI
```

### Phase 3: Solutioning
```bash
bmad-create-architecture  # Architettura design system
bmad-create-epics-and-stories  # Epic: componenti, temi, tokens
```

### Phase 4: Implementation
```bash
bmad-sprint-planning      # Sprint planning
bmad-dev-story           # Implementazione componenti
bmad-code-review         # Review con focus su accessibilità
```

## Agenti Specializzati per UI

| Agente | Ruolo | Quando Usare |
|--------|-------|--------------|
| Sally 🎨 | UX Designer | Design system, componenti |
| Amelia 💻 | Developer | Implementazione componenti |
| Quinn 🧪 | QA | Test accessibilità |

## Vedi Anche

- [quick-reference](quick-reference.md)
- [setup-guide](setup-guide.md)

---

*UI · BMAD Method · data 2026-05-27*