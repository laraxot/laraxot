---
epic: 4
slug: phpstan-modules-systematic-fix
title: PHPStan L10 Compliance Across All Modules
status: ready-for-scoping
created: 2026-09-06
audience: Modules owners + Agent orchestrator
---

# Epic 4 — PHPStan L10 Compliance Across All Modules

## Overview

Full-stack PHPStan Level 10 audit + fix for all Laravel Modules. Bootstrap now works (GetComponentsAction fix applied). Scan reveals **1000+ errors** requiring systematic module-by-module resolution.

## Scope

- Modules: All 13+ directories (Activity, AiAssistant, Cms, Gdpr, Notify, Ptv, Seo, Timber, Ui, Xot, etc.)
- Errors: 1000+ (truncated; breakdown by module TBD)
- Workflow: Create per-module stories → Spawn agents → Validate + fix → Git sync → Repeat
- Exclude: Themes (PHPStan config omits them explicitly)

## Current State

### Completed
- Story 3.0-3.3: Xot module PHPStan L10 fixes (57 errors resolved)
  - merge conflict resolution
  - @var type hints added
  - @phpstan-ignore comments applied (false positives documented)
  - type guard for clone.nonObject

### Blockers Resolved
- GetComponentsAction bootstrap error: Fixed (try-catch for JSON decode)
- XotBaseMainPanelProvider merge conflicts: ✅ Resolved in Story 3.0
- PHPStan bootstrap: ✅ Now succeeds

### Known Pre-existing Issues (Out of scope Epic 4)
- PHPMD: Version incompatibility (skipped; unblocking tool)
- PHPInsights: Removed due to Pest 5 incompatibility

## Acceptance Criteria

- [ ] All modules audited with PHPStan L10
- [ ] Per-module story created for each error category (generics.notGeneric, staticMethod.notFound, argument.type, etc.)
- [ ] Agent validation + fixes applied
- [ ] Coverage incremented in each module's docs/coverage.md
- [ ] Full git sync (fetch + merge + push) for each module
- [ ] Root repo synced (docs/ + commit history)
- [ ] Final run: PHPStan 0 errors across Modules (if feasible per constraints)

## Estimated Effort

| Phase | Tasks | Modules | Est. Duration |
|-------|-------|---------|---------------|
| **Phase 1: Scoping** | Categorize errors by module + type | All 13+ | 2-3h |
| **Phase 2: Story Creation** | BMAD stories per module | Per category | 1-2h per module |
| **Phase 3: Dev + Fix** | Agent fixes + validation | Parallel | 3-5h per module (varies) |
| **Phase 4: Git Sync** | Fetch + merge + push per module | All 13+ | 30min total |
| **Phase 5: Re-validate** | Final PHPStan pass | All | 2-3h |
| **Phase 6: Docs + Memory** | Update coverage, wiki, rules | All | 1-2h |

**Total: 10-20h (parallel + batch optimization)**

## Next Steps

1. **Run error breakdown** → Count errors per module + category
2. **Create Epic 4 scoping manifest** → List stories to spawn
3. **Spawn Phase 1 agents** → Audit + categorize (parallel)
4. **Wait for scoping → Spawn Phase 2 agents** → Story authorship (parallel)
5. **Wait for stories → Spawn Phase 3 agents** → Development (parallel)
6. **Sequential: Git sync + docs** → One module at a time
7. **Final: Repeat PHPStan** → Verify compliance

## Key Constraints

- **BMAD mandatory**: Every fix requires story + agent coordination (no ad-hoc changes)
- **Git protocol**: ALWAYS `git fetch` + `git merge` BEFORE status/commit (per [[git-sync-parallel-agents]])
- **No --force**: All pushes are normal (no `--force`, no `--force-with-lease`)
- **Config read-only**: `phpstan.neon` not editable (user-only)
- **Type safety**: Replace `mixed` with specific types; use `User` → `UserContract` pattern
- **Coverage boost**: Pest coverage must increase, not stagnate

## Related Memories

- [[git-sync-parallel-agents]] — Fetch+merge protocol for multi-agent work
- [[phpstan-neon-readonly]] — Never edit phpstan.neon
- [[modules-agnostic]] — Module docs must be module-specific, not host-project-specific

## Risk & Mitigation

| Risk | Mitigation |
|------|-----------|
| 1000+ errors too large for single pass | Break into per-module + per-category stories; parallelize |
| Merge conflicts during git sync | Use `--allow-unrelated-histories -s resolve`; coordinate timing |
| False positives overwhelming review | Document Filament/Laravel quirks in @phpstan-ignore; separate real bugs |
| Coverage stagnation | Enforce coverage boost in each story AC; measure baseline + delta |
| Memory/context exhaustion | Spawn focused subagents per module; archive decisions to memory |

---

**Status:** ready-for-scoping  
**Next**: Spawn Phase 1 audit agents to categorize errors by module + type  
**Owner**: Agent orchestrator + Module owners (per module)
