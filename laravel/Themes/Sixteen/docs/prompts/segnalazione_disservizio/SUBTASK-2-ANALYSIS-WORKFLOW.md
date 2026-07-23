# SUBTASK 2: ANALYSIS & FINDINGS
## Researcher Workflow Guide

**Agent**: Researcher  
**Subtask**: 2 (Analyze Findings)  
**Status**: ⏳ AWAITING Subtask 1 completion  
**Duration**: 20-30 minutes

---

## 🎯 YOUR MISSION

Once Executor #1 (Subtask 1) completes the comparison script:

1. **Retrieve** the comparison results
2. **Analyze** what's missing and what's different
3. **Categorize** gaps by priority (HIGH, MEDIUM, LOW)
4. **Create** PHASE-1-FINDINGS.md with detailed recommendations
5. **Coordinate** handoff to Executor #2

---

## 📥 INPUT FILES (You'll receive from Subtask 1)

Location: `laravel/Themes/Sixteen/docs/body-structure-comparison/ticket-list/`

Files to analyze:

1. **comparison-report.json** (Main data file)
   ```json
   {
     "parity_score": 75.5,
     "total_elements_reference": 142,
     "total_elements_local": 138,
     "identical_elements": 107,
     "missing_elements": [
       {
         "tag": "section",
         "id": "head-section",
         "context": "Main hero section missing"
       }
     ],
     "different_elements": [
       {
         "tag": "div",
         "reference_classes": "col-lg-3",
         "local_classes": "",
         "issue": "Bootstrap grid classes missing"
       }
     ]
   }
   ```

2. **parity-summary.txt** (Human-readable summary)
   ```
   Parity Score: 75.5%
   Total Elements: 142 (reference) vs 138 (local)
   Identical: 107 ✅
   Missing: 8 ❌
   Different: 27 ⚠️
   Status: NEEDS WORK
   ```

3. **reference-body.html** (Reference structure)
4. **local-body.html** (Current local structure)

---

## 🔍 ANALYSIS PROCESS

### Step 1: Extract & Organize Data

Read `comparison-report.json`:

```python
import json

with open('comparison-report.json', 'r') as f:
    report = json.load(f)

parity = report['parity_score']
missing = report['missing_elements']
different = report['different_elements']

print(f"Parity: {parity}%")
print(f"Missing elements: {len(missing)}")
print(f"Different elements: {len(different)}")
```

### Step 2: Categorize Gaps

**HIGH Priority** (blocking):
- Missing semantic sections (`<section>`, `<article>`, `<aside>`)
- Missing navigation structure (`.nav.nav-tabs`)
- Missing layout structure (grid classes col-lg-3, col-lg-9)
- Missing form structure (`.form-check-input`/`.form-check-label`)

**MEDIUM Priority** (important):
- Missing Bootstrap semantic classes (`.bg-light`, `.btn-primary`)
- Missing ARIA attributes
- Missing card pattern (`.card.card-report`)
- Missing IDs/data attributes

**LOW Priority** (cosmetic):
- Text differences (not structure)
- Order differences (if content is all present)
- Class name variations (if semantically equivalent)

### Step 3: Identify Root Causes

For each gap, ask:
- **Where is it?** (Which section/component?)
- **Why is it missing?** (Not in blade? Not in JSON?)
- **What needs to change?** (Blade or JSON or both?)
- **How to fix it?** (Code pattern)

Example:
```
Gap: Missing <section id="head-section">
Location: Top of page (hero section)
Why: Blade doesn't have hero component
Fix: Add section with featured_item from JSON
Files: [slug].blade.php
Effort: Low
```

### Step 4: Create Code Examples

For each major gap, prepare concrete code examples:

```blade
❌ Current (wrong):
<div>{{ $data['title'] }}</div>

✅ Should be (correct):
<section id="head-section" class="hero">
  <div class="container-fluid">
    <h1>{{ trans('fixcity::segnalazione.fields.title.label') }}</h1>
  </div>
</section>
```

---

## 📋 TEMPLATE: PHASE-1-FINDINGS.md

Use this template to organize your findings:

```markdown
# PHASE 1 FINDINGS & ANALYSIS
## ticket-list HTML Parity Report

**Parity Score**: XX%  
**Status**: [PASS ≥90% / NEEDS WORK 70-89% / CRITICAL <70%]

## 📊 METRICS
- Total elements reference: X
- Total elements local: Y
- Identical: Z
- Missing: A
- Different: B

## ⚠️ CRITICAL GAPS (HIGH PRIORITY)

### Gap 1: Missing Hero Section
**Tag**: section#head-section  
**Impact**: Featured item not displayed at top  
**Root Cause**: Blade doesn't have hero component  
**Fix**: 
  - File: [slug].blade.php
  - Add: @section with featured_item from JSON
  
**Code**:
\`\`\`blade
<section id="head-section" class="hero py-5">
  ...
</section>
\`\`\`

---

[Continue for each gap]

## 🔧 IMPLEMENTATION PLAN

| Gap | File | Change | Effort |
|-----|------|--------|--------|
| 1. Hero | blade | Add section | Low |
| 2. Tabs | blade | Add nav.nav-tabs | Medium |
| ... | ... | ... | ... |

## 📤 HANDOFF TO EXECUTOR #2

Share:
1. This document
2. List of files to modify
3. Code examples for each fix
4. Expected outcome after fixes
```

---

## 📤 DELIVERABLE

After analysis, create: **PHASE-1-FINDINGS.md**

Location: `laravel/Themes/Sixteen/docs/prompts/segnalazione_disservizio/PHASE-1-FINDINGS.md`

Content should include:
- ✅ Parity score and current status
- ✅ Categorized list of gaps (HIGH/MEDIUM/LOW)
- ✅ Root cause for each gap
- ✅ Concrete fix recommendations with code examples
- ✅ Clear file locations and change descriptions
- ✅ Effort estimate for each fix
- ✅ Success criteria for verification

---

## 🎯 QUALITY CHECKLIST

Before sharing with Executor #2, verify:

- [ ] Parity score clearly stated
- [ ] All gaps categorized by priority
- [ ] Each gap has: where, why, what to fix
- [ ] Code examples are copy-pasteable
- [ ] File locations are correct
- [ ] Translation patterns are correct
- [ ] No CSS/styling recommendations (that's Phase 2)
- [ ] No JavaScript recommendations (that's Phase 3)
- [ ] Effort estimates are realistic
- [ ] Document is clear and actionable

---

## 📞 COORDINATION WITH EXECUTOR #2

Once PHASE-1-FINDINGS.md is ready:

1. **Notify** Executor #2 that findings are available
2. **Share** the document
3. **Highlight** HIGH priority gaps (must fix)
4. **Explain** each gap's impact
5. **Answer** any clarifying questions
6. **Set** expectation: Executor #2 applies fixes → Subtasks 3 & 4
7. **Target**: 90% parity after fixes

---

## 🔗 REFERENCE DOCUMENTS

- **Design Comuni Analysis**: PHASE-1-STRATEGY.md § ticket-list Analysis
- **Execution Plan**: GSD-PHASE-1-EXECUTION.md § Subtask 2
- **Template for Findings**: PHASE-1-FINDINGS-TEMPLATE.md
- **Tools Documentation**: bashscripts/docs/html/INDEX.md

---

## ⏱️ TIMELINE

1. **Subtask 1 (Executor #1)**: 15-20 min → Comparison results
2. **YOU (Subtask 2)**: 20-30 min → Analyze & create findings
3. **Subtask 3 & 4 (Executor #2)**: 45-60 min → Apply fixes
4. **Subtask 5 (Executor #1)**: 15-20 min → Re-verify

**When to Start**: As soon as Subtask 1 completes  
**When to Finish**: Before Executor #2 starts Subtasks 3 & 4  
**Next Handoff**: To Executor #2 with PHASE-1-FINDINGS.md

---

**Status**: ⏳ READY TO BEGIN WHEN SUBTASK 1 COMPLETES

---

*This guide is your workflow for Subtask 2. Use it to analyze the comparison results and create actionable findings for the implementation team.*
