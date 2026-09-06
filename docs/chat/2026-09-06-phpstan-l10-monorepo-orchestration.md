---
title: "PHPStan L10 monorepo orchestration — 3000+ errors, parallel batches"
type: coordination
tags: [phpstan, monorepo, multi-module, bmad, orchestration]
created: 2026-09-06T21:00:00Z
status: active
agent: claude-haiku — base_workorder_fila5 (this session)
---

# Claim: PHPStan Level 10 Monorepo Cleanup Orchestration

## Master Story

**XOT-18.2** — `laravel/Modules/Xot/docs/stories/18.2.phpstan-level-10-monorepo-cleanup.story.md`

**Goal:** 3-phase parallel cleanup of 3000+ errors across 30+ modules.

---

## Current Coordination Map

### AiAssistant (TAKEN — Claude Haiku, earlier session)

**Batch 1: 40+ cast/offset errors**
- Stories: XOT-5.62 to 5.65 (AiAssistant module)
- Status: READY FOR DEV
- Link: `docs/chat/2026-09-06-phpstan-aiassistant-cast-errors.md`

### Xot (AUDIT + ORCHESTRATION — This session)

**Phase 1:** Master audit + triage matrix (in progress)
- Blocker: PHPStan timeout on full Modules scan (3000+ errors, 1000 cap)
- Workaround: per-modulo batch scan + manual triage from earlier run baseline
- Deliverable: `Modules/Xot/docs/phpstan-l10-triage.md` (error count/type per module)

### Other modules (TBD)

Pending audit results; batch 2+ will be assigned as Phase 1 completes.

---

## Parallel Execution Model

**Phase 1 (Xot, sequential):** Audit all modules, identify high-error ones (100+)  
**Phase 2 (parallel by module):** Each 100+-error module gets dedicated agent  
**Phase 3 (Xot, sequential):** Re-gate, coverage verification, docs consolidation

---

## Coordination Rules

1. **No duplicate work:** Before creating BMAD stories for a module, check `docs/chat/` for existing claims
2. **Per-module git sync:** Each agent handling a module must:
   - `git fetch laraxot dev && git merge … --allow-unrelated-histories`
   - `git add -A && git commit`
   - `git push` to all remotes (`git remote -v`)
3. **Coverage baseline:** Before/after per-module Pest coverage in `docs/coverage.md`
4. **No neon edits:** `phpstan.neon` is user-only; agent must NOT modify

---

## Known Issues

- **Bootstrap syntax error (cleared):** Was due to session state; `composer dump-autoload -o` resolved
- **PHPStan timeout:** Full `Modules` scan hits 1000-error display cap; use `-c` per-module or JSON format for counts
- **Method.internalClass false positives:** 2700+ Pest API errors; not blockers for core logic

---

**Session:** Base Workorder Fila5  
**Coordinator:** Claude Haiku (haiku-4-5)  
**Last updated:** 2026-09-06T21:00Z  
**Status:** Phase 1 in progress; AiAssistant batch 1 ready for dev
