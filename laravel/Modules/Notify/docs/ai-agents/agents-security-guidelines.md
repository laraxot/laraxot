---
title: "AGENTS Security Guidelines"
type: guide
tags: [agents, security, guidelines]
created: 2026-07-14
updated: 2026-07-14
qmd: "agents-security-guidelines agents security guidelines"
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

# AGENTS Security Guidelines

Linee guida per la sicurezza.

## Authentication & Authorization

- Use Laravel's built-in authentication
- Implement **Policies** for authorization
- Use **Gates** for simple access control
- Validate all input through Form Requests

---

## Data Protection

- Never commit credentials (use .env)
- Encrypt sensitive data with Laravel's encryption
- Use prepared statements (Eloquent) against SQL injection

---

## Security Best Practices

### Never Do

- ❌ Hardcode secrets in source code
- ❌ Commit `.env` files
- ❌ Use `eval()` or similar dangerous functions
- ❌ Skip input validation

### Always Do

- ✅ Use environment variables for secrets
- ✅ Hash passwords with bcrypt
- ✅ Use parameterized queries
- ✅ Implement CSRF protection
- ✅ Use HTTPS in production

---

## 🔗 Link

- [Indice AGENTS](./agents-split-index.md)
- [laravel-security-audit skill available](../../.opencode/skills/laravel-security-audit/SKILL.md)
- [AGENTS.md originale](../../AGENTS.md)
- [Index principale](./index.md)
