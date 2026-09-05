# Improve All Prompts — Scopo, Utilità, Perché, Filosofia, Politica, Religione, Zen

**Status:** ✅ PARZIALMENTE COMPLETATO
- 65 prompt in `bashscripts/docs/prompts/` hanno ricevuto la sezione Zen
- 20+ prompt già avevano sezioni simili (24-boy-scout.md, 50-trigger-operativi.md, 00-master-prompt.md, 03-quality-gates.md, 11-phpstan.md, 16-git-forward-only.md)

**Status:** ❌ IN CORSO - moduli rimanenti
- `User`, `Cms`, `Lang`, `Geo`, `Gdpr` con modifiche README/docs ma non ancora sincronizzati
- PHPStan 0 errori (già verificato)
- PHPMD / PHPInsights / Pest da eseguire su moduli toccati

## Nuovi Task per Swarm Paralleli

### Task 1: PHPStan Fix + Pest Coverage — User Module
**Owner:** swarm-agent-1
**Files:** `laravel/Modules/User/`
**Scope:** Verifica zero errori, incrementa coverage (XotBaseTestCase)
**Gate:** phpstan → phpmd → phpinsights → pest
**Git:** dopo completamento

### Task 2: PHPStan Fix + Pest Coverage — Cms Module  
**Owner:** swarm-agent-2
**Files:** `laravel/Modules/Cms/`
**Scope:** Verifica zero errori, incrementa coverage
**Gate:** phpstan → phpmd → phpinsights → pest
**Git:** dopo completamento

### Task 3: PHPStan Fix + Pest Coverage — Lang Module
**Owner:** swarm-agent-3
**Files:** `laravel/Modules/Lang/`
**Scope:** Verifica zero errori, incrementa coverage
**Gate:** phpstan → phpmd → phpinsights → pest
**Git:** dopo completamento

### Task 4: PHPStan Fix + Pest Coverage — Geo Module
**Owner:** swarm-agent-4
**Files:** `laravel/Modules/Geo/`, `laravel/Themes/Sixteen/`
**Scope:** Verifica zero errori, incrementa coverage su Geo, docs auth per Sixteen
**Gate:** phpstan → phpmd → phpinsights → pest
**Git:** dopo completamento

### Task 5: Gdpr Module Sync
**Owner:** swarm-agent-5
**Files:** `laravel/Modules/Gdpr/`
**Scope:** Sync README.md, docs/, git fetch/merge
**Gate:** phpstan → phpmd → phpinsights → pest
**Git:** dopo completamento

### Task 6: Clean Root Repo
**Owner:** swarm-agent-6
**Files:** Root `base_workorder_fila5/`
**Scope:** Clean detached files, sync docs/stories, push dev
**Gate:** git status, git add, commit, push
**Note:** Only cleaned up dangling changes (already done mostly)

## Coordination (docs/chat/INDEX.md)
- Lock files checked: OK per tutti i moduli
- Story numbers assigned: 1-6 (increment from last)
- Each agent update handoff in docs/chat/ con status
- Post-completion: update this MAIN story con risultati