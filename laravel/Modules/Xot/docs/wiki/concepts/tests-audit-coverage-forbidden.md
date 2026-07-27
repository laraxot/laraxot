---
title: tests AuditCoverage forbidden
type: concept
module: Xot
tags: [tests, audit-coverage, hygiene, gitignore, claude-audit]
created: 2026-07-27
updated: 2026-07-27
qmd: "tests AuditCoverage forbidden gitignore AI scaffold modulo tema mai committare"
related:
  - ./claude-audit-static-all-modules.md
  - ../rules/module-root-cleanup-rules.md
  - ../../../../../docs/wiki/memories/module-root-hygiene.md
---

# `tests/AuditCoverage/` — vietata in ogni modulo/tema

## Regola

La cartella `tests/AuditCoverage/` **non deve esistere** nel tree di nessun modulo né tema.
È scaffold temporaneo di tool AI (padding coverage / claude-audit) — **mai committare**.

| Azione | Dettaglio |
|--------|-----------|
| Se esiste | `rm -rf tests/AuditCoverage` |
| `.gitignore` | Aggiungere sempre `tests/AuditCoverage/` |
| Bridge canonico | `audit-coverage/tests/` (fuori da `tests/`, vedi claude-audit-static) |

**Non confondere** con `audit-coverage/` alla root modulo (altro bridge claude-audit, policy separata in UI/Xot wiki).

## Comandi

```bash
bash bashscripts/tools/ensure-audit-coverage-gitignore.sh   # fix
bash bashscripts/tools/audit-no-audit-coverage-dir.sh       # verifica
bash bashscripts/tools/audit-module-theme-root-hygiene.sh   # include AuditCoverage
```

## Blocco `.gitignore` standard

```gitignore
# Laraxot — tests/AuditCoverage forbidden (AI scaffold, never commit)
tests/AuditCoverage/
```

## Perché

- Inquina il ratio test reale e PHPStan/Pest
- Non fa parte del dominio nwidart
- Già rimossa in passato (es. Lang changelog) e riappare con agenti AI
