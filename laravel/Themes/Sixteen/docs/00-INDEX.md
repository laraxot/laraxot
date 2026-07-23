# 📚 Sixteen Theme Documentation Index

**Theme**: Sixteen (Bootstrap Italia)  
**Version**: 2.3.0  
**Status**: Phase 1 - HTML Structure Alignment  
**Last Updated**: 2026-04-08  

---

## 🎯 QUICK NAVIGATION

### 🟢 Current Phase: Phase 1 - HTML Structure

**Status**: Strategy Complete → Execution Ready  
**Start**: 2026-04-08  
**Target**: 90% HTML structural parity (ticket-list pilot)  

**Key Documents**:
1. [PHASE-1-STRATEGY.md](PHASE-1-STRATEGY.md) - **START HERE** - Complete strategy with architecture analysis
2. [GSD-PHASE-1-EXECUTION.md](GSD-PHASE-1-EXECUTION.md) - Execution plan with subtasks
3. [PHASE-1-FINDINGS.md](PHASE-1-FINDINGS.md) - ⏳ Gap analysis (created after comparison)
4. [PHASE-1-COMPLETION-REPORT.md](PHASE-1-COMPLETION-REPORT.md) - ⏳ Results (created after verification)
5. [Directory Structure Rules](../../docs/directory-structure-rules.md) - vietati `lang/lang/` e `_docs/` nei temi
6. [Merge Conflict Marker Cleanup](merge-conflict-marker-cleanup.md) - regola batch sparsi per cleanup `<<<<<<< HEAD`

---

## 📖 DOCUMENTATION BY TOPIC

### 🏗️ Architecture & Design

**Understanding the Theme**:
- [PHASE-1-STRATEGY.md](PHASE-1-STRATEGY.md) - Design Comuni architecture, component patterns, design tokens
- [Theme Overview](README.md) - General theme description

**Component System**:
- [/blocks/](blocks/) - Block component documentation
- [/components/](components/) - Blade component reference
- [architecture/CSS-SCOPING-RULE.md](architecture/CSS-SCOPING-RULE.md) - Regola canonica: body plain, scoping solo su `page-content[data-slug][data-side]`, mai su `tests-view-wrapper`
- [WEB-COMPONENTS-AND-BUILD-SYSTEM.md](WEB-COMPONENTS-AND-BUILD-SYSTEM.md) - Web Components (Lit.dev), build architecture, module vs theme assets, why module components must NOT be globally imported

**Design System**:
- Color palette & tokens → See PHASE-1-STRATEGY.md § "Design Tokens"
- Typography system → See PHASE-1-STRATEGY.md § "Typography"
- Spacing scale → See PHASE-1-STRATEGY.md § "Spacing Grid"

---

### 🔍 Phase 1: HTML Structure Alignment

**Strategy & Planning**:
- [PHASE-1-STRATEGY.md](PHASE-1-STRATEGY.md) - Deep dive strategy (22,000+ words)
  - Design Comuni architecture analysis
  - ticket-list structure breakdown
  - FixCity current state assessment
  - Identified gaps list
  - Execution strategy with multi-agent coordination
  - Translation patterns & examples
  - Success criteria & checklists

**Execution Plan**:
- [GSD-PHASE-1-EXECUTION.md](GSD-PHASE-1-EXECUTION.md) - Detailed execution plan
  - 6 subtasks with clear owners
  - Step-by-step instructions per subtask
  - Expected inputs/outputs
  - Success criteria per subtask
  - Dependency graph
  - Timeline estimates

**Analysis & Findings** (during execution):
- [PHASE-1-FINDINGS.md](PHASE-1-FINDINGS.md) - ⏳ Created after comparison
  - Detailed gap inventory
  - Categorized by type (semantic, classes, ARIA)
  - Prioritized fix list
  - Code examples & suggestions
  - Time estimates per gap

**Results & Metrics** (after completion):
- [PHASE-1-COMPLETION-REPORT.md](PHASE-1-COMPLETION-REPORT.md) - ⏳ Created after verification
  - Final parity score
  - What was fixed
  - Files modified
  - Metrics & statistics
  - Lessons learned
  - Phase 2 preparation

**Comparison Reports**:
- [/body-structure-comparison/ticket-list/](body-structure-comparison/ticket-list/) - Comparison output
  - `comparison-report.json` - Machine-readable full report
  - `parity-summary.txt` - Human-readable summary
  - `reference-body.html` - Reference structure extracted
  - `local-body.html` - FixCity structure extracted

---

### 🚀 Tools & Utilities

**HTML Structure Comparison Tools**:
- [bashscripts/docs/html/INDEX.md](../../bashscripts/docs/html/INDEX.md) - HTML comparison tools documentation
- [Project MCP Servers](../../../docs/project/mcp-overview.md) - Memory and development MCP servers
- [.qwen/mcp-servers/README.md](../../../.qwen/mcp-servers/README.md) - MCP servers detailed docs
- [bashscripts/html/html-structure-compare.sh](../../bashscripts/html/html-structure-compare.sh) - Main comparison script
- [bashscripts/html/extract-body-html.py](../../bashscripts/html/extract-body-html.py) - Body extraction helper
- [bashscripts/html/compare-html-body.py](../../bashscripts/html/compare-html-body.py) - Structure comparison helper

**Usage**:
```bash
cd /var/www/_bases/base_fixcity_fila5
./bashscripts/html/html-structure-compare.sh ticket-list
```

Output → `laravel/Themes/Sixteen/docs/body-structure-comparison/ticket-list/`

---

### 📝 Blade Templates

**Main Template**:
- [resources/views/pages/tests/[slug].blade.php](resources/views/pages/tests/[slug].blade.php)
  - Dynamic routing based on slug parameter
  - **Do NOT create**: ticket-list.blade.php (use [slug] pattern)
  - Loads content from JSON via PageSlugMiddleware
  - Calls <x-page> component

**Block Components**:
- [resources/views/components/blocks/segnalazioni/](resources/views/components/blocks/segnalazioni/)
  - layout.blade.php - Main layout & orchestration
  - featured.blade.php - Hero/featured section (may need to create)
  - filters.blade.php - Sidebar filters (may need to create)
  - cards.blade.php - Segnalazione cards list (may need to create)

**Base Components**:
- [resources/views/components/](resources/views/components/) - Shared blade components

---

### 📋 Content & Configuration

**JSON Content Structure**:
- [laravel/config/local/fixcity/database/content/pages/tests.ticket-list.json](../../../laravel/config/local/fixcity/database/content/pages/tests.ticket-list.json)
  - Pilot page content configuration
  - Single source of truth for content
  - Structured by content_blocks type

**Translations**:
- Pattern: `fixcity::segnalazione.<field>.<section>.<type>`
- Files: `laravel/lang/{locale}/fixcity.php`
- **Important**: All user-visible text must use `trans()` helper
- Examples in [PHASE-1-STRATEGY.md](PHASE-1-STRATEGY.md) § "Translation Key Patterns"

---

## 📊 PHASE PROGRESSION

### ✅ Phase 1: HTML Structure Alignment
**Status**: 🟢 Strategy Complete → Execution In Progress  
**Goal**: 90% HTML structural parity  
**Pilot Page**: ticket-list  
**Timeline**: ~2 hours concurrent execution  

**Deliverables**:
- ✅ PHASE-1-STRATEGY.md (complete)
- ✅ GSD-PHASE-1-EXECUTION.md (complete)
- ⏳ PHASE-1-FINDINGS.md (awaits comparison)
- ⏳ PHASE-1-COMPLETION-REPORT.md (awaits completion)
- ⏳ Comparison reports (awaits execution)

**Next**: Executor agents begin Subtask 1 (Comparison)

### ⏳ Phase 2: CSS Styling Alignment
**Status**: 🔵 Not Started  
**Goal**: Achieve visual parity via CSS  
**Approach**: Map Bootstrap Italia → Tailwind equivalents  
**Start**: After Phase 1 ✅ Complete  

**Planned Documents**:
- PHASE-2-STRATEGY.md
- PHASE-2-FINDINGS.md
- PHASE-2-COMPLETION-REPORT.md

### ⏳ Phase 3: JavaScript Behavior
**Status**: 🔵 Not Started  
**Goal**: Match interactive behavior (tabs, modals, forms)  
**Approach**: Alpine.js for client-side interactivity  
**Start**: After Phase 2 ✅ Complete  

### ⏳ Phase 4: Scaling & Polish
**Status**: 🔵 Not Started  
**Goal**: Replicate methodology across all 49 test pages  
**Approach**: Automate comparison, batch fixes, parallel execution  
**Start**: After Phase 1 methodology proven  

---

## 🔄 MULTI-AGENT COORDINATION

### Phase 1 Team Assignments

**Researcher Agent** (Current):
- ✅ Strategy research & documentation
- ⏳ Gap analysis & findings documentation
- ⏳ Completion reporting
- ⏳ Index maintenance

**Executor Agent #1** (HTML Comparison):
- ⏳ Run comparison script
- ⏳ Re-verify parity after fixes
- Subtasks: 1, 5

**Executor Agent #2** (Code Implementation):
- ⏳ Apply blade template fixes
- ⏳ Update JSON content structure
- ⏳ Verify build succeeds
- Subtasks: 3, 4

**Coordinator**:
- Track progress
- Unblock issues
- Update GitHub issues
- Maintain this index

### Communication Flow
```
Researcher (Strategy)
    ↓
Executor #1 (Comparison)
    ↓ reports findings
Researcher (Analysis)
    ↓ recommends fixes
Executor #2 (Implementation)
    ↓ applies fixes
Executor #1 (Verification)
    ↓ re-tests
Researcher (Documentation)
    ↓ reports completion
```

---

## 📍 FILE LOCATIONS REFERENCE

### Key Directories
```
laravel/Themes/Sixteen/
├── docs/                                (← Documentation YOU ARE HERE)
│   ├── 00-INDEX.md                     (this file)
│   ├── PHASE-1-STRATEGY.md             (strategy complete)
│   ├── GSD-PHASE-1-EXECUTION.md        (execution plan)
│   ├── PHASE-1-FINDINGS.md             (⏳ pending)
│   ├── PHASE-1-COMPLETION-REPORT.md    (⏳ pending)
│   ├── body-structure-comparison/      (comparison outputs)
│   │   └── ticket-list/
│   │       ├── comparison-report.json
│   │       ├── parity-summary.txt
│   │       ├── reference-body.html
│   │       └── local-body.html
│   ├── blocks/                         (block component docs)
│   ├── components/                     (blade component docs)
│   └── [other docs]
│
├── resources/views/
│   ├── pages/tests/
│   │   ├── [slug].blade.php           (← MAIN: Use this)
│   │   ├── index.blade.php
│   │   └── design-comuni-[slug].blade.php.old
│   └── components/
│       └── blocks/segnalazioni/       (block components)
│
├── config/
│   └── (theme config files)
│
└── public/
    └── (compiled assets)

bashscripts/
├── body/
│   └── html-structure-compare.sh      (← MAIN SCRIPT)
├── html/
│   ├── extract-body-html.py
│   └── compare-html-body.py
└── docs/
    └── html/
        ├── INDEX.md                    (tool documentation)
        └── html-structure-compare.md

laravel/config/local/fixcity/database/content/pages/
└── tests.ticket-list.json     (← JSON CONTENT)
```

### Do NOT Modify
```
❌ Create laravel/Themes/Sixteen/resources/views/pages/tests/ticket-list.blade.php
   Use: laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php

❌ Move bashscripts/html-structure-compare.sh
   Use: bashscripts/html/html-structure-compare.sh

❌ Move bashscripts/compare-html-body.py
   Use: bashscripts/html/compare-html-body.py
```

---

## 🎓 LEARNING PATH

**New to this project?** Start here:

1. **5 min**: Read this INDEX.md (you are here)
2. **20 min**: Skim [PHASE-1-STRATEGY.md](PHASE-1-STRATEGY.md) § "Executive Summary" + § "Design Comuni Architecture"
3. **15 min**: Review [GSD-PHASE-1-EXECUTION.md](GSD-PHASE-1-EXECUTION.md) § "Phase Overview" + § "Subtasks"
4. **30 min**: Deep dive [PHASE-1-STRATEGY.md](PHASE-1-STRATEGY.md) full document for complete context
5. **10 min**: Run comparison script to see it in action
6. **20 min**: Study comparison reports and gap analysis

---

## ✅ TODO & TRACKING

**Phase 1 Progress**:
- [x] Strategy document created
- [x] Execution plan created
- [ ] Comparison executed (awaits Executor #1)
- [ ] Gap analysis documented (awaits comparison)
- [ ] Blade template fixes applied (awaits gap analysis)
- [ ] JSON structure verified (awaits gap analysis)
- [ ] Verification test run (awaits fixes)
- [ ] Completion report created (awaits verification)
- [ ] Index updated (ongoing)
- [ ] Phase 2 strategy outlined (awaits Phase 1 completion)

**Scaling Preparation**:
- [ ] Methodology validated on ticket-list
- [ ] Process documented for other pages
- [ ] Batch execution approach designed
- [ ] Parallel agent workflow optimized

---

## 🔗 RELATED RESOURCES

**Official References**:
- [Design Comuni Live](https://italia.github.io/design-comuni-pagine-statiche/sito/ticket-list.html) - Reference implementation
- [Design Comuni GitHub](https://github.com/italia/design-comuni-pagine-statiche) - Source code
- [Designers Italia](https://designers.italia.it/modelli/comuni/) - Official documentation
- [Bootstrap Italia](https://italia.github.io/bootstrap-italia/) - Component library

**FixCity Docs**:
- [FixCity README.md](../../../README.md) - Project overview
- [Laravel Custom Instructions](../../../laravel/CLAUDE.md) - Framework rules
- [Bashscripts Documentation](../../docs/html/INDEX.md) - Automation tools

---

## 🚀 GETTING STARTED

### For Executor Agents:

**Executor #1 (Comparison)**:
```bash
# Step 1: Start Laravel dev server
cd laravel && php artisan serve

# Step 2: Run comparison (in new terminal)
cd /var/www/_bases/base_fixcity_fila5
./bashscripts/html/html-structure-compare.sh ticket-list

# Step 3: Review output
cat laravel/Themes/Sixteen/docs/body-structure-comparison/ticket-list/parity-summary.txt
```

**Executor #2 (Code Fixes)**:
```bash
# Step 1: Read gap analysis
cat laravel/Themes/Sixteen/docs/PHASE-1-FINDINGS.md

# Step 2: Edit blade template
nano laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php

# Step 3: Edit JSON content
nano laravel/config/local/fixcity/database/content/pages/tests.ticket-list.json

# Step 4: Build & test
cd laravel/Themes/Sixteen && npm run build && npm run copy
php artisan optimize:clear
# Open: http://127.0.0.1:8000/it/tests/ticket-list
```

---

## 📞 QUESTIONS?

**About Phase 1 Strategy?** → See [PHASE-1-STRATEGY.md](PHASE-1-STRATEGY.md)  
**About Execution Plan?** → See [GSD-PHASE-1-EXECUTION.md](GSD-PHASE-1-EXECUTION.md)  
**About Tools?** → See [bashscripts/docs/html/INDEX.md](../../docs/html/INDEX.md)  
**About Blade/Components?** → See [README.md](README.md)  
**About Translations?** → See [PHASE-1-STRATEGY.md](PHASE-1-STRATEGY.md) § "Translation Key Patterns"  

---

**Document Status**: 📖 **ACTIVE REFERENCE**  
**Maintenance**: Updated as phases progress  
**Last Review**: 2026-04-08  
**Next Review**: After Phase 1 completion  

*This index is your source of truth for Phase 1. Bookmark it.*

---

## 🟠 PHASE 1 EXECUTION DOCUMENTS (Created During Execution)

*These documents are created as Subtasks progress*

### Execution Coordination
- **PHASE-1-EXECUTION-STATUS.md** - Real-time progress tracking (updated every subtask)
- **PHASE-1-FINDINGS-TEMPLATE.md** - Template for analysis results
- **SUBTASK-2-ANALYSIS-WORKFLOW.md** - Researcher workflow guide for findings
- **EXECUTOR-2-SUBTASKS-3-4.md** - Detailed instructions for blade fixes and JSON verification

### Output Locations
```
laravel/Themes/Sixteen/docs/
├─ body-structure-comparison/ticket-list/
│  ├─ comparison-report.json          (Subtask 1 output)
│  ├─ parity-summary.txt              (Subtask 1 output)
│  ├─ reference-body.html             (Subtask 1 output)
│  └─ local-body.html                 (Subtask 1 output)
└─ prompts/segnalazione_disservizio/
   ├─ PHASE-1-FINDINGS.md             (Subtask 2 output)
   └─ PHASE-1-COMPLETION-REPORT.md    (Subtask 6 output)
```

---

## �� MULTI-AGENT WORKFLOW

### Executor #1 (Comparison & Verification)
- **Subtask 1**: Run comparison script
  - Input: [web URLs]
  - Output: comparison-report.json, parity-summary.txt
  - Duration: 15-20 min
  - Status: 🟠 IN PROGRESS (Agent ID: executor-phase1-subtask1)

- **Subtask 5**: Re-verify after fixes
  - Input: Updated blade + JSON
  - Output: New comparison results with parity ≥ 90%
  - Duration: 15-20 min
  - Status: ⏳ PENDING

### Researcher (Analysis & Documentation)
- **Subtask 2**: Analyze findings
  - Input: comparison-report.json from Subtask 1
  - Output: PHASE-1-FINDINGS.md with gap analysis
  - Duration: 20-30 min
  - Status: ⏳ PENDING (awaits Subtask 1)
  - Workflow: See SUBTASK-2-ANALYSIS-WORKFLOW.md

- **Subtask 6**: Document completion
  - Input: Final comparison results, all changes
  - Output: PHASE-1-COMPLETION-REPORT.md
  - Duration: 20-25 min
  - Status: ⏳ PENDING (awaits Subtask 5)

### Executor #2 (Implementation)
- **Subtask 3**: Fix blade template
  - Input: PHASE-1-FINDINGS.md
  - Output: Updated [slug].blade.php
  - Duration: 30-40 min
  - Status: ⏳ PENDING (awaits Subtask 2)
  - Workflow: See EXECUTOR-2-SUBTASKS-3-4.md

- **Subtask 4**: Verify JSON content
  - Input: PHASE-1-FINDINGS.md
  - Output: Verified tests.ticket-list.json
  - Duration: 15-20 min
  - Status: ⏳ PENDING (awaits Subtask 2)
  - Can run in parallel with Subtask 3

---

## 📊 CURRENT STATUS

```
PHASE 1: HTML Structural Parity
├─ ✅ COMPLETE: Strategy & Planning (Phase 0)
├─ 🟠 IN PROGRESS: Subtask 1 - Comparison (Agent: executor-phase1-subtask1)
├─ ⏳ PENDING: Subtask 2 - Analysis
├─ ⏳ PENDING: Subtask 3 - Blade Fixes
├─ ⏳ PENDING: Subtask 4 - JSON Verify
├─ ⏳ PENDING: Subtask 5 - Re-Verify
└─ ⏳ PENDING: Subtask 6 - Documentation

Expected Parity: ≥ 90% ✅
Timeline: 90-150 minutes total (parallel execution)
```

For detailed status, see: **PHASE-1-EXECUTION-STATUS.md**