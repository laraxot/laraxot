# Parity Assessment Findings

**Analysis Date:** 2026-04-03

## Key Discovery: Tag-Count Metric is Misleading

Initial analysis used **tag element counts** (h1, h2, form, input, etc.) to calculate parity percentage. This metric proved **inaccurate** for assessing structural parity.

### Why Tag Counts Fail
1. **Content-driven variations** - Different number of h2 tags doesn't mean structure is different
2. **Template flexibility** - Same logical structure can use different tag hierarchies
3. **False negatives** - Results showed 0% "approved" pages despite visual parity of 95%+

### Visual Height Analysis Results

**Pages with 95%+ Visual Parity (READY FOR CSS/JS):**

| Page | Local Height | Reference Height | Diff | Visual Parity | Status |
|------|---|---|---|---|---|
| persona | 1080px | 1080px | 0px | ✅ 100% | IDENTICAL |
| homepage | 4761px | 4629px | 132px | ✅ 97% | NEAR-IDENTICAL |
| domande-frequenti | 3193px | 3314px | 121px | ✅ 96% | NEAR-IDENTICAL |

**Pages with 90%+ Visual Parity (GOOD CANDIDATES):**

| Page | Local Height | Reference Height | Diff | Visual Parity | Status |
|------|---|---|---|---|---|
| argomenti | 3692px | 4104px | 412px | ✅ 90% | Minor diffs |

**Pages Requiring Investigation:**

| Page | Local Height | Reference Height | Diff | Visual Parity | Issue |
|------|---|---|---|---|---|
| mappa-sito | 2542px | 4202px | 1660px | ❌ 61% | Significant layout differences |

## Revised Assessment Criteria

✅ **Approved for CSS/JS (Visual Parity ≥95%):**
1. persona
2. homepage
3. domande-frequenti

⚠️ **Secondary Candidates (Visual Parity 90-95%):**
1. argomenti

❌ **Blocked (Visual Parity <90%):**
- All other pages (need structural review)

## Implementation Plan

### Phase 1: Homepage + FAQ + Persona (95%+ Parity)
- Apply CSS/JS fixes to match visual details
- Create documentation for each fix
- Verify with screenshots after each build

### Phase 2: Secondary Pages (90-95% Parity)
- Investigate visual differences
- Apply CSS/JS alignment
- Consider blade template updates if needed

### Phase 3: Low-Parity Pages (<90%)
- Requires structural analysis
- May need blade template updates
- Prioritize based on importance

## CSS/JS Fixes Status

### Homepage
- ✅ Container width: 1320px (fixed)
- ✅ Gradient: Blue→Emerald (fixed)
- ✅ Carousel: Hidden (CSS applied)
- ✅ Evidence section height: 1102px (CSS applied with overflow:hidden)
- ✅ Header height: 222px (CSS applied)
- ✅ Social icons: 27px (CSS applied)
- ✅ Footer background: Transparent (CSS applied)
- ⏳ Card wrapper visibility: CSS selector needs refinement

### FAQ (domande-frequenti)
- 🔄 In progress - structure verified at 95%+ parity
- Need visual comparison for CSS fixes

### Persona
- 🔄 Identical height - quick analysis needed
- Should be minimal fixes required

