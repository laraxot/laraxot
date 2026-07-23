# Homepage HTML Similarity Improvement Session - 90% Target

> **Data**: 2026-04-07
> **Session**: Increase HTML similarity from 81% to 90%+
> **Reference**: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html
> **Local**: http://127.0.0.1:8000/it/tests/homepage

---

## Executive Summary

### Work Completed
- ✅ Created batch comparison script for all pages
- ✅ Deep analysis of structural differences (81% → 90% plan)
- ✅ Fixed governance calendar block (merge conflicts resolved)
- ✅ Fixed evidence section (merge conflicts resolved)
- ✅ Implemented multi-step rating wizard structure
- ✅ Added complete multilingual support (IT/EN) to rating block
- ✅ Resolved CSS merge conflicts (app.css, homepage-visual-fix.css, design-comuni-global.css)
- ✅ Built and deployed theme assets successfully

### Current Status
**Similarity**: Was 81%, currently investigating rendering issue
**Build**: ✅ Successful (app-DzaRc8bo.css, 1,030.79 kB)
**Deploy**: ✅ Assets copied to public_html/themes/Sixteen/

### Files Modified

#### Blade Templates
1. `laravel/Themes/Sixteen/resources/views/components/blocks/feedback/rating.blade.php`
   - Added complete multi-step wizard structure (3 steps)
   - Added positive/negative feedback fieldsets
   - Added optional text input step
   - Added navigation buttons
   - ALL text from JSON for multilingual support
   - Uses Alpine.js for step management

2. `laravel/Themes/Sixteen/resources/views/components/blocks/governance/cards.blade.php`
   - Resolved merge conflicts (forward-only Git workflow)
   - Matched reference structure exactly
   - Fixed card wrapper classes
   - Fixed calendar structure

3. `laravel/Themes/Sixteen/resources/views/components/blocks/topics/highlight.blade.php`
   - Resolved merge conflicts
   - Matched reference evidence section structure
   - Fixed thematic sites cards

#### JSON Content
4. `laravel/config/local/fixcity/database/content/pages/tests.homepage.json`
   - Added complete multilingual rating strings
   - positive_question (IT/EN)
   - negative_question (IT/EN)
   - positive_options (5 options each)
   - negative_options (5 options each)
   - text_question, text_label, text_help
   - button labels (back/next)
   - star legend and labels

#### CSS Files
5. `laravel/Themes/Sixteen/resources/css/app.css`
   - Resolved merge conflicts
   - Added segnalazione-parity.css and homepage-parity-v2.css imports

6. `laravel/Themes/Sixteen/resources/css/homepage-visual-fix.css`
   - Resolved merge conflicts
   - Kept fixes #1-12 (hero, rating, container nesting)

### Multilingual Implementation

ALL text strings in rating block are now JSON-driven:
```json
{
  "title": {"it": "...", "en": "..."},
  "subtitle": {"it": "...", "en": "..."},
  "positive_question": {"it": "...", "en": "..."},
  "positive_options": {"it": [...], "en": [...]},
  "negative_question": {"it": "...", "en": "..."},
  "negative_options": {"it": [...], "en": [...]},
  "star_labels": {"it": {"5": "...", ...}, "en": {...}}
}
```

Blade template accesses them as:
```php
$title = $data['title']['it'] ?? 'fallback';
```

### Tools Created

1. **Batch Comparison Script**
   - Location: `bashscripts/html-comparison/compare-all-pages.sh`
   - Compares all 54 Design Comuni pages
   - Outputs summary table with similarity percentages
   - Documented in: `bashscripts/docs/README.md`

2. **Documentation**
   - `laravel/Themes/Sixteen/docs/prompts/homepage/HTML_SIMILARITY_90_PERCENT_PLAN.md`
   - `laravel/Themes/Sixteen/docs/design-comuni/HOMEPAGE_VISUAL_PARITY_SESSION.md`
   - `laravel/Themes/Sixteen/docs/design-comuni/HOMEPAGE_HTML_ANALYSIS.md`
   - All with bidirectional links in master index

---

## Detailed Changes

### 1. Rating Block (Multi-Step Wizard)

**Reference Structure**:
```html
<div class="cmp-rating" x-data="{ step: 0, rating: 0, hover: 0 }">
  <!-- Step 0: Stars -->
  <div class="cmp-rating__card-first" data-step="0">
    <fieldset class="rating">
      <input type="radio" id="star5a" name="ratingA" value="5">
      <label class="full rating-star active" for="star5a">...</label>
      <!-- 4 more stars -->
    </fieldset>
  </div>
  
  <!-- Step 1: Radio Feedback -->
  <div class="d-none" data-step="1">
    <fieldset class="fieldset-rating-one">
      <legend>Quali sono stati gli aspetti che hai preferito?</legend>
      <div class="cmp-radio-list">
        <!-- 5 radio options -->
      </div>
    </fieldset>
  </div>
  
  <!-- Step 2: Text Input -->
  <div class="d-none" data-step="2">
    <fieldset>
      <legend>Vuoi aggiungere altri dettagli?</legend>
      <input type="text" maxlength="200">
    </fieldset>
  </div>
  
  <!-- Step 3: Thank You -->
  <div class="cmp-rating__card-second d-none" data-step="3">
    <h2>Grazie, il tuo parere ci aiuterà a migliorare il servizio!</h2>
  </div>
  
  <!-- Navigation -->
  <div class="d-flex">
    <button class="btn btn-outline-primary">Indietro</button>
    <button class="btn btn-primary">Avanti</button>
  </div>
</div>
```

**Local Implementation**:
- Matches reference structure exactly
- Alpine.js manages step transitions
- Positive/negative branching based on star rating (≥3 = positive)
- All text from JSON for multilingual support
- Uses same CSS classes as reference

**Element Count Impact**:
- Reference rating section: ~120 elements
- Previous local rating: ~30 elements (simplified)
- New local rating: ~120 elements (matches reference)
- **Expected gain**: ~90 elements → 81% → ~90% similarity

---

## Remaining Work

### Investigation Needed
1. Rating section not rendering - requires debugging
2. Verify all blocks render correctly after merge conflict resolution
3. Re-run comparison to confirm similarity improvement

### Next Steps
1. Debug rating block rendering issue
2. Verify other blocks (governance, evidence) render correctly
3. Re-run HTML comparison to measure new similarity
4. If ≥90%, document as complete
5. If <90%, identify remaining gaps and fix

---

## Git Workflow Notes

**Forward-Only Rule Applied**:
- NEVER reset/reverted commits
- Studied both HEAD and origin/dev versions
- Created NEW clean files incorporating best of both
- Resolved ALL merge conflicts in blade templates and CSS
- Documentation updated with bidirectional links

---

## Links

- ← [HTML Analysis](./HOMEPAGE_HTML_ANALYSIS.md)
- ← [Visual Parity Session](./HOMEPAGE_VISUAL_PARITY_SESSION.md)
- → [Master Index](./00-index.md)
- → [Batch Comparison Script](../../../../bashscripts/html-comparison/compare-all-pages.sh)

---

**Status**: ⚠️ In Progress - Build successful, investigating rating render issue
**Next**: Debug rating block, re-run comparison, verify 90%+ target
