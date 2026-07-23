---
title: "IFLOW Tech Stack"
type: concept
tags: [iflow, stack]
created: 2026-07-14
updated: 2026-07-14
qmd: "iflow-stack iflow tech stack"
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

# IFLOW Tech Stack

Stack tecnologico del progetto.

---

## Backend

| Tecnologia | Descrizione |
|-----------|-------------|
| PHP 8.2+ | Strict types obbligatorio |
| Laravel 11+ | Con architettura modulare Laraxot |
| Filament v4 | Per il backoffice |
| PHPStan Level 10 | Per qualità codice |
| Pest PHP | Per test unitari |

---

## Frontend

| Tecnologia | Descrizione |
|-----------|-------------|
| Tailwind CSS 4.x | Con Vite 7 |
| Alpine.js 3.x | Per interattività |
| DaisyUI | Per componenti UI |
| Folio + Volt | Per pagine dinamiche |
| TypeScript | Per type safety |

---

## Architettura

| Componente | Descrizione |
|-----------|-------------|
| Moduli indipendenti | Dependency injection |
| Multi-tenancy | Tenant module |
| Queue system | Job asincroni |
| Media library | Gestione file |
| SEO optimization | Integrata |

---

## 🔗 Link

- [Indice IFLOW](./iflow-split-index.md)
- [tech-stack.md](./tech-stack.md)
- [IFLOW.md originale](../../IFLOW.md)
- [Index principale](./index.md)
