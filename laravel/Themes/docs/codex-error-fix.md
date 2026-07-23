---
module: theme
topic: codex-error-fix
canonical: shared-components/codex-error-fix.md
---

See canonical documentation: shared-components/codex-error-fix.md

## 2026-07-06 - PHPStan module sweep notes

The current PHPStan sweep touched module tests only; no theme runtime files were changed. For future theme-side fixes, keep the same guardrail used for modules: check for a sibling `.lock`, create it before editing, validate the touched file or theme scope, then remove the lock. Do not add local PHPStan configuration in a theme; use the root Laravel `phpstan.neon` only.

## 2026-07-06 - Second brain as Knowledge OS

Il second brain della repo non e' una singola directory: e' un Knowledge OS composto da `docs/wiki`, docs dei moduli, docs dei temi, `docs/chat` per coordinamento multi-agente e indice QMD. Le regole strutturali dei moduli vanno replicate nei docs di dominio e ricercate via QMD/rg prima delle modifiche.
