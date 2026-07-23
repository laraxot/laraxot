# HYBRID EXECUTION PLAN
## Phase 1 Blocker Fixes + Phase 2 Research in Parallel

**Decision**: OPTION C (HYBRID)  
**Status**: 🟢 APPROVED - Ready to Execute  
**Timeline**: ~60 minutes (all parallel)  
**Execution Model**: Parallel multi-agent work

---

## 🎯 EXECUTION MODEL

### Track A: Phase 1 HTML Blocker Fixes
**Owner**: Executor #2  
**Scope**: Fix 12 blockers + 14 flags identified by Subtask 1  
**Duration**: ~40-50 minutes  
**Deliverable**: Clean HTML with no BLOCKED verdict  
**Files Modified**: [slug].blade.php, tests.ticket-list.json

### Track B: Phase 2 Research & Strategy
**Owners**: Researcher + 3 Research Agents  
**Scope**: Extract design tokens, CSS architecture, best practices  
**Duration**: ~15-30 minutes (parallel with Track A)  
**Deliverable**: Phase 2 strategy document ready for execution  
**Research Agents Running**: 3 in background

### Track C: Monitoring & Coordination (Researcher)
**Owner**: Researcher  
**Scope**: Coordinate both tracks, synthesize findings, prepare Phase 2 execution  
**Duration**: Continuous (parallel with A & B)  

---

## 📋 TRACK A: HTML BLOCKER REMEDIATION

### Executor #2 Workflow

**Step 1: Prepare** (5 min)
- Read PHASE-1-DECISION-BLOCKERS.md
- Review report.md (12 blockers + 14 flags)
- Understand each issue and fix approach
- Plan blade modifications

**Step 2: Fix Header Issues** (5 min)
- Issue 1-2: Add missing `<span>` children to button in header slim zone
- Modification: laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php

**Step 3: Fix Navigation Issues** (10 min)
- Issue 3: Add `<div class="it-search-wrapper">` to nav
- Issue 4-5: Add `<div id="nav4">` wrapper
- Issue 7: Fix navbar toggle (button vs div)
- Multiple fixes to nav section structure

**Step 4: Fix Main Element Issues** (15 min)
- Issue 6-9: Restructure main content
- Ensure main has 4 children (not 1)
- Fix children count for rows and columns
- Multiple structural changes

**Step 5: Add Missing Bottom Sections** (10 min)
- Issue 10: Add `<div class="bg-primary">` section (CTA)
- Issue 11: Add `<div class="bg-grey-card shadow-contacts">` section (Contacts)
- Issue 12: Add `<div class="it-example-modal">` section (Modal)
- These are CRITICAL - page is incomplete without them

**Step 6: Build & Test** (5 min)
```bash
cd laravel/Themes/Sixteen
npm run build
npm run copy
# Test locally at: http://127.0.0.1:8000/it/tests/ticket-list
```

**Step 7: Verify** (5 min)
- Manually check page structure
- Verify no console errors
- Ensure all sections visible
- Ready for Subtask 5 re-verification

---

## 📊 TRACK B: PHASE 2 RESEARCH & STRATEGY

### Research Agents Running in Parallel

**Agent 1: Design Comuni CSS/JS Architecture**
- Extract design tokens (colors, typography, spacing)
- Analyze component-specific styling
- Understand JavaScript behavior patterns
- Document responsive design approach

**Agent 2: FixCity Sixteen Current Setup**
- Map CSS/SCSS directory structure
- Document JavaScript setup
- Review current design tokens (if any)
- Analyze build process

**Agent 3: CSS/JS Best Practices**
- Research modern CSS techniques
- Bootstrap customization patterns
- Form styling approaches
- Performance optimization strategies

### Researcher Workflow (Parallel with Track A)

**While waiting for research agents**:

1. Monitor Track A progress
2. Prepare Phase 2 strategy framework (already created: PHASE-2-STRATEGY-FRAMEWORK.md)
3. Ready template documents for Phase 2 execution
4. Prepare coordination for when research agents complete

**When research agents complete** (should be ~15-20 min):
1. Read all 3 research outputs
2. Synthesize into Phase 2 Strategy Document
3. Create design tokens specification
4. Outline CSS/JS execution plan

---

## 🔄 SYNCHRONIZATION POINTS

### Timeline

**T+0-10 min**: Start Track A & Track B simultaneously
- Executor #2 starts HTML blocker remediation
- Research agents continue research
- Researcher monitors and prepares

**T+10-20 min**: Track B progresses
- Research agents likely completing
- Executor #2 working on main content fixes
- Researcher beginning to compile Phase 2 strategy

**T+20-40 min**: Final touches
- Executor #2 finalizing HTML fixes
- Phase 2 strategy document being written
- Researcher synthesizing all inputs

**T+40-50 min**: Track A completion expected
- Executor #2 finishes HTML blocker fixes
- Build & test passing
- Ready for Subtask 5 re-verification

**T+50-60 min**: Both tracks converge
- HTML fixes complete ✅
- Phase 2 strategy ready ✅
- Next phase execution ready to begin

---

## 📤 DELIVERABLES

### From Track A (Executor #2)
- ✅ Updated [slug].blade.php (with blocker fixes)
- ✅ Updated tests.ticket-list.json (if needed)
- ✅ Compiled CSS/JS (npm run build + npm run copy)
- ✅ Verification: Page loads correctly, no console errors
- ✅ Ready for Subtask 5: Re-run comparison script

### From Track B (Research Agents + Researcher)
- ✅ Design Comuni CSS/JS Architecture Analysis
- ✅ FixCity Sixteen Current Setup Analysis
- ✅ CSS/JS Best Practices Guide
- ✅ **PHASE-2-STRATEGY.md** - Complete Phase 2 strategy
- ✅ **PHASE-2-DESIGN-TOKENS.md** - Design tokens specification
- ✅ **PHASE-2-CSS-ARCHITECTURE.md** - CSS/SCSS structure guide
- ✅ Execution plan ready to hand off to implementation agents

---

## 👥 TEAM ASSIGNMENTS

### Executor #2 (Blocker Fixes)
- Reads blockers analysis
- Modifies blade template
- Fixes all 12 + 14 issues
- Builds and tests
- Status: Ready for next phase

### Researcher (Coordination)
- Monitors both tracks
- Compiles research into strategy
- Prepares Phase 2 execution plans
- Coordinates handoff

### Research Agents (Background)
- Agent 1: Design Comuni analysis → Document
- Agent 2: FixCity setup analysis → Document
- Agent 3: Best practices → Document
- (Running in background, no active handoff needed)

---

## ✅ SUCCESS CRITERIA

### Track A Success
- [ ] All 12 blocker issues fixed
- [ ] All 14 flag issues addressed
- [ ] HTML builds without errors
- [ ] Page loads locally without console errors
- [ ] All bottom 3 sections visible (bg-primary, grey-card, modal)
- [ ] Subtask 5 ready: comparison script can re-run

### Track B Success
- [ ] Design Comuni architecture documented
- [ ] FixCity current state documented
- [ ] Best practices captured
- [ ] Phase 2 strategy document complete
- [ ] Design tokens extracted and specified
- [ ] CSS architecture planned
- [ ] Execution plan ready

### Hybrid Success
- [ ] Both tracks complete by T+60 min
- [ ] HTML clean (ready for Subtask 5)
- [ ] Phase 2 strategy ready (can begin immediately after)
- [ ] No waiting time between phases
- [ ] Maximum efficiency achieved

---

## 🔍 WHAT HAPPENS AFTER

### Immediately After Hybrid Execution

**T+60 min: Convergence Point**

1. **Executor #1** (waiting): Ready to run Subtask 5
   - Re-run comparison script
   - Expected: ≥95% with NO BLOCKED verdict
   - Verify clean pass

2. **Phase 2 Strategy Ready**: Can begin immediately
   - CSS/JS architecture planned
   - Design tokens specified
   - Implementation team ready

3. **Two Parallel Options**:
   - **Option 1**: Run Subtask 5 first, then Phase 2
   - **Option 2**: Parallelize Subtask 5 re-verification with Phase 2 execution

---

## 📁 FILES TO MONITOR

### Track A Progress
- `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php` (being modified)
- `laravel/config/local/fixcity/database/content/pages/tests.ticket-list.json` (verify)
- Build output (npm run build succeeds)

### Track B Progress
- Research agent outputs (documents created in background)
- PHASE-2-STRATEGY.md (being written)
- PHASE-2-DESIGN-TOKENS.md (being written)
- PHASE-2-CSS-ARCHITECTURE.md (being written)

---

## ⏱️ TIMELINE BREAKDOWN

| Component | Duration | Start | End | Status |
|-----------|----------|-------|-----|--------|
| Track A: Header fixes | 5 min | T+0 | T+5 | 🟠 In Progress |
| Track A: Nav fixes | 10 min | T+5 | T+15 | 🟠 Queued |
| Track A: Main fixes | 15 min | T+15 | T+30 | 🟠 Queued |
| Track A: Bottom sections | 10 min | T+30 | T+40 | 🟠 Queued |
| Track A: Build & verify | 10 min | T+40 | T+50 | 🟠 Queued |
| Track B: Research | 15-30 min | T+0 | T+15-30 | 🟠 In Progress |
| Track B: Strategy synthesis | 15-20 min | T+15-30 | T+40-50 | 🟠 Queued |
| Track C: Coordination | Continuous | T+0 | T+60 | 🟠 In Progress |
| **CONVERGENCE** | — | — | **T+60** | 🟠 Target |

---

## 🎯 GOAL

By **T+60 minutes**:
- ✅ HTML blockers fixed (clean pass ready)
- ✅ Phase 2 strategy complete (ready to execute)
- ✅ No idle time (everything parallelized)
- ✅ Maximum efficiency
- ✅ Ready to tackle Phase 2 with solid foundation

---

**Status**: 🟢 HYBRID PLAN APPROVED & READY TO EXECUTE

Execution start: NOW
Timeline: ~60 minutes
All tracks: PARALLEL

---

*Hybrid execution plan created*  
*Ready for parallel multi-agent execution*
