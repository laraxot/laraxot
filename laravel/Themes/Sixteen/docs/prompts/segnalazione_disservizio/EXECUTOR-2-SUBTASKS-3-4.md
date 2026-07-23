# EXECUTOR #2 - SUBTASK 3 & 4 COORDINATION
## Blade Template Fixes + JSON Verification

**Subtask 3**: Fix blade template (`[slug].blade.php`)  
**Subtask 4**: Verify JSON content completeness  
**Owner**: Executor Agent #2  
**Status**: ⏳ AWAITING Subtask 1 results + Researcher analysis  
**Timeline**: ~40-60 minutes total (can run in parallel)

---

## 📋 PREPARATION CHECKLIST

Before you start, ensure:

- [ ] You have read `PHASE-1-STRATEGY.md` (understand design reference)
- [ ] You have read `GSD-PHASE-1-EXECUTION.md` (understand all 6 subtasks)
- [ ] Subtask 1 (comparison script) is COMPLETE
- [ ] You have read `PHASE-1-FINDINGS.md` (gap analysis from Researcher)
- [ ] You understand the translation pattern: `fixcity::segnalazione.fields.title.label`
- [ ] You know NOT to create separate `ticket-list.blade.php` (use `[slug].blade.php`)

---

## SUBTASK 3: FIX BLADE TEMPLATE

### File Location
```
laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php
```

### Key Principles

✅ DO:
- Add missing semantic `<section>` elements
- Use Bootstrap grid classes (col-lg-3, col-lg-9, etc.)
- Use `.nav.nav-tabs` for tab navigation
- Use `.card.card-report` for card pattern
- Use `.form-check-input`/`.form-check-label` for filters
- Use `trans('fixcity::...')` for ALL user-visible text
- Add ARIA attributes (role, aria-label, aria-selected, etc.)
- Keep blade DRY (no duplicated HTML)

❌ DON'T:
- Hardcode any text (Italian, English, or any language)
- Create separate page-specific blade files
- Use incorrect translation pattern
- Add CSS styling (that's Phase 2)
- Add JavaScript (that's Phase 3)
- Remove existing structure that matches reference

### Expected Changes

Based on `PHASE-1-FINDINGS.md`, you will likely need to:

1. **Add Hero Section** (if missing)
   ```blade
   <section id="head-section" class="container-fluid py-5">
     {{-- Featured item from $data['featured_item'] --}}
   </section>
   ```

2. **Add Tab Navigation** (if missing)
   ```blade
   <section id="map-and-list">
     <ul class="nav nav-tabs" role="tablist">
       <li class="nav-item" role="presentation">
         <button class="nav-link active" 
                 id="tab-list" 
                 data-bs-toggle="tab" 
                 data-bs-target="#list-pane"
                 type="button" 
                 role="tab" 
                 aria-controls="list-pane" 
                 aria-selected="true">
           {{ trans('fixcity::segnalazione.tabs.list.label') }}
         </button>
       </li>
     </ul>
     <div class="tab-content">
       {{-- Tab panes --}}
     </div>
   </section>
   ```

3. **Fix Layout Structure** (if needed)
   ```blade
   <section id="filter-and-cards">
     <div class="container-fluid">
       <div class="row">
         <aside class="col-lg-3">
           {{-- Filters from $data['filters'] --}}
         </aside>
         <article class="col-lg-9">
           {{-- Cards from $data['items'] --}}
         </article>
       </div>
     </div>
   </section>
   ```

4. **Fix Filter Checkboxes** (if using wrong pattern)
   ```blade
   @foreach($filters as $category)
     <div class="form-check">
       <input class="form-check-input" 
              type="checkbox" 
              id="filter-{{ $category['id'] }}"
              name="category"
              value="{{ $category['id'] }}">
       <label class="form-check-label" for="filter-{{ $category['id'] }}">
         {{ trans('fixcity::segnalazione.filters.' . $category['key'] . '.label') }}
       </label>
     </div>
   @endforeach
   ```

5. **Fix Card Pattern** (if using wrong HTML structure)
   ```blade
   @foreach($items as $item)
     <div class="card card-report">
       <div class="card-body">
         <h5 class="card-title">{{ trans_choice(...) }}</h5>
         <p class="card-text">{{ $item['description'] ?? '' }}</p>
       </div>
     </div>
   @endforeach
   ```

6. **Replace All Hardcoded Text**
   ```blade
   ❌ Wrong:  <h1>Segnalazioni - Elenco</h1>
   ✅ Correct: <h1>{{ trans('fixcity::segnalazione.fields.title.label') }}</h1>
   ```

### Testing During Development

After each change:

1. **Visual Check**
   ```bash
   curl -s http://127.0.0.1:8000/it/tests/ticket-list | head -100
   ```

2. **Element Count Check** (manually inspect HTML)
   - Count `<section>` tags (should match reference)
   - Count `.nav-link` elements (should match reference)
   - Count `.card.card-report` elements (should match reference)

3. **No Errors in Browser Console** (when you view page)

---

## SUBTASK 4: VERIFY JSON CONTENT

### File Location
```
laravel/config/local/fixcity/database/content/pages/tests.ticket-list.json
```

### Expected Structure

The JSON must contain ALL these sections (populate from reference if missing):

```json
{
  "slug": "ticket-list",
  "featured_item": {
    "id": "...",
    "title": "...",
    "description": "...",
    "category": "...",
    "date": "..."
  },
  "tabs": [
    {
      "id": "list",
      "label_key": "tabs.list.label",
      "icon": "icon-list"
    },
    {
      "id": "map",
      "label_key": "tabs.map.label",
      "icon": "icon-map"
    }
  ],
  "filters": [
    {
      "id": "category_1",
      "key": "categoria_service",
      "label_key": "filters.categoria_service.label",
      "items": [
        {
          "id": "cat_1",
          "name": "name",
          "count": 0
        }
      ]
    }
  ],
  "items": [
    {
      "id": "item_1",
      "title": "Item Title",
      "description": "Description",
      "category": "Category",
      "date": "2026-04-08",
      "status": "open",
      "link": "/it/segnalazioni/item_1"
    }
  ],
  "cta": {
    "title_key": "cta.title.label",
    "button_text_key": "cta.button.label",
    "link": "/it/segnalazioni/new"
  },
  "contacts": [
    {
      "title_key": "contacts.title.label",
      "items": [
        {
          "label": "Email",
          "value": "info@comune.it",
          "link": "mailto:info@comune.it"
        }
      ]
    }
  ]
}
```

### Verification Checklist

- [ ] JSON is valid (no syntax errors)
  ```bash
  php -r "json_decode(file_get_contents('path/to/file.json'), true) ?: exit('Invalid JSON');"
  ```

- [ ] All required sections present:
  - [ ] `featured_item`
  - [ ] `tabs`
  - [ ] `filters`
  - [ ] `items`
  - [ ] `cta`
  - [ ] `contacts`

- [ ] All `*_key` fields reference valid translation keys:
  - [ ] Keys follow pattern: `<contesto>.<collezione>.<chiave>.<tipo>`
  - [ ] Namespace would be: `fixcity::` (prepended by blade)

- [ ] Data is realistic (not all empty/null):
  - [ ] `items` array has at least 3-5 example items
  - [ ] `filters` has proper hierarchy
  - [ ] `tabs` matches reference page tabs

- [ ] No hardcoded language text in JSON
  - [ ] Use `*_key` fields pointing to translation files
  - [ ] Keep data agnostic (supports multi-language)

### Example Entry Check

```php
// Verify in blade context
$data = json_decode(file_get_contents('...tests.ticket-list.json'), true);

// Should not throw errors
foreach ($data['items'] as $item) {
    echo trans('fixcity::' . $item['title_key']); // ← Must exist
}
```

---

## 📋 CHECKLIST BEFORE SUBMITTING

### Blade Template Fixes
- [ ] All hardcoded text replaced with `trans('fixcity::...')`
- [ ] All semantic `<section>` elements present
- [ ] All Bootstrap classes match reference
- [ ] All ARIA attributes present
- [ ] Tab navigation works (data-bs-toggle, aria-selected)
- [ ] Filter checkboxes use `.form-check-input`/`.form-check-label`
- [ ] Cards use `.card.card-report` pattern
- [ ] No CSS styling added (that's Phase 2)
- [ ] No JavaScript added (that's Phase 3)
- [ ] Blade renders without errors (check console)

### JSON Verification
- [ ] JSON validates (no syntax errors)
- [ ] All required sections present
- [ ] All `*_key` fields use correct translation pattern
- [ ] No empty/null sections
- [ ] Data is realistic and sufficient
- [ ] File path correct: `laravel/config/local/fixcity/database/content/pages/tests.ticket-list.json`

---

## 🔄 HANDOFF: WHAT HAPPENS NEXT

After you complete Subtasks 3 & 4:

1. **Executor #1 (Subtask 5)** will re-run comparison script
   ```bash
   ./bashscripts/html/html-structure-compare.sh ticket-list
   ```

2. **Expected Result**: Parity score should be ≥ 90%

3. If score < 90%:
   - Researcher will analyze remaining gaps
   - You will apply additional fixes
   - Executor #1 will re-verify again

4. Once ≥ 90% achieved:
   - Researcher creates PHASE-1-COMPLETION-REPORT.md
   - Phase 1 is COMPLETE ✅
   - Phase 2 (CSS alignment) begins

---

## 📞 HELP & REFERENCES

**Translation Pattern Questions**:
- Read: `PHASE-1-STRATEGY.md` § Translation Patterns
- File: `laravel/lang/it/fixcity.php`
- Pattern: `fixcity::segnalazione.fields.title.label`

**HTML Reference**:
- https://italia.github.io/design-comuni-pagine-statiche/sito/ticket-list.html
- Reference analysis in: `PHASE-1-STRATEGY.md` § ticket-list Analysis

**Bootstrap Grid Reference**:
- Reference structure uses: `col-lg-3` (sidebar), `col-lg-9` (main)
- Bootstrap docs: https://getbootstrap.com/docs/5.3/layout/grid/

**Blade/Laravel Docs**:
- Blade syntax: https://laravel.com/docs/blade
- Translations: https://laravel.com/docs/localization

---

*This document is your guide for Subtasks 3 & 4. Read it fully before starting.*

**Status**: ⏳ Awaiting your start (after Subtask 1 & 2 complete)
