---
id: phpstan-other-modules-fix
slug: phpstan-other-modules
scope:
  - project:base_workorder_fila5
  - modules:All excluding Xot,Intervention,Timber
status: Pending
epic: PHPStan Quality Gates
priority: High
created: 2026-09-06
---

## Problema

PHPStan su moduli rimanenti restituisce **~700+ errori**.

## Moduli da Analizzare

| Modulo | Errori Stimati | Status |
|--------|----------------|--------|
| User | ~50 | Pending |
| Activity | ~30 | Pending |
| AI | ~20 | Pending |
| AiAssistant | ~100 | Pending |
| Billing | ~50 | Pending |
| Customer | ~40 | Pending |
| Job | ~30 | Pending |
| Media | ~40 | Pending |
| Notify | ~30 | Pending |
| Tenant | ~30 | Pending |
| WorkOrder | ~50 | Pending |
| Altri 30 moduli | ~200 | Pending |

## Solution Overview

1. Analisi per modulo
2. BMAD stories specifiche per modulo
3. Fix pattern-by-pattern
4. Git sync dopo ogni modulo

## Acceptance Criteria

- [ ] 0 PHPStan errors in all modules
- [ ] PHPMD passes per tutti
- [ ] PHPInsights > 90% per tutti
- [ ] Pest coverage incrementato per tutti
