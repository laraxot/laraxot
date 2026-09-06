---
title: "User — BMAD Method Integration"
description: "BMAD workflow documentation per il modulo User"
module: "User"
alias: "user"
documentation_date: "2026-05-27"
bmad_version: "6.2.0"
bmad_track: "core-identity"
---

# User — BMAD Method Integration

## Scopo BMAD per User

User è il **modulo identità** che garantisce autenticazione, autorizzazione e multi-tenancy. In BMAD, questo modulo rappresenta il **core della sicurezza** su cui tutti gli altri moduli fanno affidamento.

## Religione BMAD per User

User segue i principi BMAD di:

1. **`project-context.md`** è costituzione — non sviluppare senza comprenderlo
2. **Separazione database** è politica — connessione `user` per identità, connessione applicativa per business
3. **Actions, non Services** — tutta la logica utente passa per Actions
4. **PHPStan Level 10** — sicurezza tipografica non negoziabile
5. **Ruoli per team** — permessi contestuali al team
6. **Token scade, log resta** — persistente identità

## Workflow BMAD Consigliati per User

### Phase 1: Analysis (Essenziale per User)
```bash
bmad-domain-research      # Studio dominio identità, autenticazione
bmad-technical-research   # Valutazione architettura multi-tenant
```

### Phase 2: Planning
```bash
bmad-create-prd           # PRD modulo User: provider auth, provider social
bmad-create-architecture  # Architettura database separate (user vs app)
```

### Phase 3: Solutioning
```bash
bmad-create-epics-and-stories  # Epic: User management, OAuth2, SSO
bmad-check-implementation-readiness  # Quality gate prima dello sprint
```

### Phase 4: Implementation
```bash
bmad-sprint-planning      # Sprint iniziale per feature utente
bmad-create-story         # Story: login, registro, profilo
bmad-dev-story            # Implementazione
bmad-code-review          # Review con focus su sicurezza
```

## Quick Flow per User

Per task rapidi su User:
```bash
bmad-quick-dev "Aggiungi social provider X"
bmad-quick-spec "Specifica per OTP 2FA"
```

## Agenti Specializzati per User

| Agente | Ruolo | Quando Usare |
|--------|-------|--------------|
| Mary 📊 | Analyst | Ricerca provider auth, analisi dominio |
| John 📋 | PM | PRD auth, roadmap social |
| Winston 🏗️ | Architect | Architettura database separati, multi-tenant |
| Amelia 💻 | Developer | Implementazione login, OTP, social |
| Quinn 🧪 | QA | Test sicurezza, impersonation, OAuth2 |

## Configurazione

```bash
# Verifica config User
php artisan config:show user

# Test provider
php artisan user:passport:install

# Esegui seeding
php artisan db:seed --class=RoleSeeder

# Verifica PHPStan
./vendor/bin/phpstan analyse Modules/User --memory-limit=-1
```

## Struttura Output BMAD

```
_bmad-output/
├── planning-artifacts/
│   ├── PRD.md              # Requisiti modulo User
│   ├── architecture.md     # Architettura DB separata
│   └── epics/
│       ├── epic-001-user-auth.md
│       ├── epic-002-oauth2.md
│       └── epic-003-ss.md
└── implementation-artifacts/
    ├── sprint-status.yaml
    └── story-001-login-implementation.md
```

## Vedi Anche

- [quick-reference](quick-reference.md)
- [setup-guide](setup-guide.md)
- [BMAD Workflow Catalog](../bmad-workflow-catalog.md)

---

*User · BMAD Method · data 2026-05-27*