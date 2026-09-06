---
name: phase2-pest-l10-decision
description: "Phase 2 decision gate — Pest L10 method.internalClass blocker"
owner: marco.sottana@gmail.com
module: AiAssistant
tags: [bmad, decision, blocker, phpstan, pest, phase2]
created: 2026-09-06
status: pending_user_decision
---

# Phase 2 Decision Gate — Pest L10 method.internalClass Blocker

## Status
**BLOCKED** — Awaiting user decision on Pest version + PHPStan strategy.

## Problem Statement
PHPStan Level 10 analysis on 3 test-heavy modules shows 613 errors:
- 538/613 (87%) are `method.internalClass` (Pest internal API access)
- Root cause: Pest 4.7.8 lacks official PHPStan plugin support
- Official fix: Pest 5.0+ with `pestphp/pest-plugin-phpstan` auto-loaded

## Technical Constraints
1. **User policy** (CLAUDE.md): Never modify `phpstan.neon`, no `@phpstan-ignore` decorators
2. **Pest status**: Locked to v4.7.8 (composer.lock)
3. **Plugin requirement**: `pestphp/pest-plugin-phpstan ^5.2` → requires Pest ^5.0.0
4. **Dependency changes**: Require user approval (CLAUDE.md: "Do not change dependencies without approval")

## Options

| Option | Approach | Pros | Cons | Blocker |
|--------|----------|------|------|---------|
| **A** | Upgrade Pest 4.7.8→5.1+ | Plugin auto-fixes L10 errors; no code changes | Composer change (needs approval) | User approval |
| **B** | Bulk `@phpstan-ignore` on test files | Fast; preserves Pest 4 | Violates "no ignores" policy | Policy violation |
| **C** | Refactor tests to avoid internal Pest APIs | Permanent fix; respects policy | 500+ errors; time-intensive | Scope/time |
| **D** | Document Pest L10 as "known limitation" | Honest; unblocks non-test modules | Leaves 538 errors unresolved | Incomplete |

## Recommendation
**Option A + D hybrid**: 
1. User approves Pest 5.0+ upgrade
2. Plugin installed → method.internalClass vanishes automatically
3. Remaining non-test modules (15 modules, ~360 errors) fixed via BMAD stories
4. Test files clean by plugin, not by decorator or refactor

## Blocking Decision
**User must choose:**
- [ ] Approve Pest 5.0+ upgrade (recommend)
- [ ] Approve bulk `@phpstan-ignore` on test files only (policy exception)
- [ ] Defer Pest L10 (document as known limitation, fix non-test modules only)

## Next Steps
1. User decision
2. If Pest 5: `composer update pestphp/pest -W && composer require --dev pestphp/pest-plugin-phpstan:^5.2`
3. If decorators: spawn Intervention/Customer/Cms bulk-decorator agents
4. If defer: shift focus to 15 remaining modules (HR 54, Compliance 18, Gdpr 16, Email 13, Document 11, + 10 others)

---
**BMAD Phase**: 2 (Planning)  
**Owner module**: AiAssistant  
**Story ID**: XOT-PHPSTAN-PHASE2-01  
