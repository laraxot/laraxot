# Section Structure Differences

## Discovery: Governance Carousel Not in Reference Design

### Local Structure (5 sections)
```
[0] Hero section (454px)
[1] Featured Topics/Argomenti in Evidenza (832px) ← 🎨 Blue→Emerald gradient
[2] Governance Carousel (448px) ← ⚠️ NOT IN REFERENCE
[3] Evidence Section (1125px)
[4] Useful Links Section (539px)
```

### Reference Structure (4 sections)
```
[0] Hero section (471px)
[1] Featured Topics/Argomenti in Evidenza (860px) ← Same gradient
[2] Evidence Section (1102px)  ← No carousel in between!
[3] Useful Links Section (489px)
```

## Content Management Analysis

### tests.homepage.json Structure
The JSON contains 3+ blocks:
- `block-hero` (weight: 10) ✅ In both
- `block-calendario` (weight: 20) ← This is the governance carousel **ACTIVE but NOT in reference**
- `block-ricerca` (search section)
- ...other blocks

### Critical Finding
The `block-calendario` (governance carousel) is marked as `active: true` in the JSON but:
- **LOCAL**: Renders as visible section (448px height)
- **REFERENCE**: Does NOT appear in the design at all

## Decision Options

### Option A: Hide the carousel in CSS (Recommended)
- Keep HTML structure intact (no changes to blade templates)
- Use CSS to hide: `.it-carousel-wrapper.it-carousel-landscape-abstract-four-cols { display: none; }`
- Pros: Maintains data integrity, reversible, CSS-only
- Cons: Section still in DOM, slightly wasteful

### Option B: Conditionally disable block render (Data-driven)
- Modify tests.homepage.json: change `"active": true` to `"active": false` for block-calendario
- Let blade template respect active flag
- Pros: Cleaner HTML, no unused DOM nodes
- Cons: Requires JSON content change (not pure CSS/JS fix)

### Option C: Alpine.js visibility toggle
- Use Alpine to show/hide based on data attribute
- Pros: Dynamic, reversible
- Cons: Unnecessary complexity

## Recommendation: Option A (CSS Hide)
- **Why**: Matches the requirement of "CSS and JS only" fixes
- **How**: Add CSS rule targeting governance carousel wrapper
- **Verification**: After CSS hide, section count should be 4 (matching reference)

## Expected Results After Fix
- Local sections will reorder:
  ```
  [0] Hero (471px)
  [1] Featured (860px)
  [2] Evidence (1102px)  ← Now directly after Featured
  [3] Useful Links (489px)
  ```
- Heights will adjust slightly due to no gap from hidden carousel
- Page structure will match reference

## Related Findings
- Hero section: 12px height difference (will reflow after carousel hide)
- Header padding differences may resolve after section reflow
- Overall page composition will be 90%+ identical to reference

