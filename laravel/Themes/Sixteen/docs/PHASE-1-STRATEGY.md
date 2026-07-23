# 🎯 PHASE 1 - HTML STRUCTURE PARITY STRATEGY

**Document**: `laravel/Themes/Sixteen/docs/PHASE-1-STRATEGY.md`  
**Status**: 📋 Strategy & Research Complete  
**Target**: Achieve 90% HTML structural parity with Design Comuni reference  
**Scope**: ticket-list (pilot page) → scaling to all pages  
**Timeline**: Concurrent execution by multiple AI agents  

---

## 📊 EXECUTIVE SUMMARY

### Objective
Align FixCity's Sixteen theme HTML structure with official Italian Design Comuni templates to achieve:
- ✅ Identical DOM hierarchy
- ✅ Matching CSS classes and attributes
- ✅ Same semantic elements
- ✅ 90%+ structural parity score

### Why This Matters
1. **Standards Compliance**: Design Comuni represents AGID-approved PA design
2. **User Familiarity**: Citizens recognize consistent structure across Italian municipalities
3. **Accessibility**: Official templates are WCAG 2.1 AA validated
4. **Maintenance**: Easier to follow official patterns than reinvent

### NOT in This Phase
- 🚫 CSS styling (Phase 2)
- 🚫 JavaScript behavior (Phase 3)
- 🚫 Content/translations (separate task)
- 🚫 Performance optimization (Phase 4)

---

## 🏗️ DESIGN COMUNI ARCHITECTURE (Reference)

### Technology Stack
| Component | Technology | Version |
|-----------|-----------|---------|
| **Templating** | Handlebars | 4.7.6 |
| **Build Tool** | Webpack | 4.46.0 |
| **CSS Framework** | Bootstrap Italia | 2.9.2 |
| **Styling** | SCSS | 1.51.0 |
| **JS Libraries** | Splide (carousel), Just-Validate (forms) | 4.0.1, 3.8.1 |

### Component Organization (75+ Components)
```
Reference Components → FixCity Blade Components
┌─────────────────────────────────────────────────┐
│ cmp-breadcrumbs    → x-breadcrumbs              │
│ cmp-header         → x-header                   │
│ cmp-footer         → x-footer                   │
│ cmp-button         → x-button                   │
│ cmp-card*          → x-card-* (5+ variants)     │
│ cmp-accordion      → x-accordion                │
│ cmp-modal          → x-modal                    │
│ cmp-form-*         → x-forms-* (8 types)        │
│ cmp-nav-*          → x-nav-* (4 types)          │
│ cmp-alert-*        → x-alert-* (4 types)        │
│ cmp-icon-*         → x-icon-* (5 types)         │
│ cmp-info-*         → x-info-* (6 types)         │
│ cmp-list-*         → x-list-* (3 types)         │
│ ... and 40+ more                                │
└─────────────────────────────────────────────────┘
```

### Data Flow (Reference)
```
Handlebars JSON (Mock Data)
        ↓
Page Template (.hbs)
        ↓
Component Partials (cmp-*)
        ↓
Webpack Compilation
        ↓
Static HTML Output
```

### Key Design Tokens

#### Color Palette (HSL-based)
```
Primary (PA Blue):   hsl(160, 100%, 48%)  = #007a52
Hover State:         hsl(160, 100%, 15%)  = Darker teal
Active State:        hsl(160, 100%, 14%)  = Even darker
Light Background:    hsl(160, 40%, 92%)   = Very light teal

Functional:
- Header:           #00402b (Dark teal)
- Footer:           #202a2e (Dark gray)
- Card Dark:        #2c2c2c (Near black)
```

#### Typography
- **Font Family**: Roboto (from Bootstrap Italia), fallback to system fonts
- **Scale**: Bootstrap 5 responsive headings (h1-h6)
- **Line Height**: 1.5+ for accessibility

#### Spacing Grid
- **Base Unit**: 1rem (16px)
- **Scale**: 0.25rem, 0.5rem, 1rem, 1.5rem, 3rem, 5rem
- **Applied via**: Bootstrap utilities (.mt-3, .p-2, etc.)

### Accessibility Standards (WCAG 2.1 AA)
✅ Semantic HTML elements  
✅ ARIA landmarks and labels  
✅ Keyboard navigation support  
✅ Color contrast ratios (4.5:1 minimum)  
✅ Skip links for screen readers  
✅ Form labels and error messaging  

---

## 🎯 PILOT PAGE: ticket-list

### Why This Page?
- **Representative**: Contains multiple patterns (list, filters, tabs, forms, CTAs)
- **Medium Complexity**: Not too simple, not too complex
- **High Value**: Core feature of municipality websites
- **Reusable Patterns**: Solutions applicable to other pages

### Reference vs FixCity Current State

#### Reference Structure (Design Comuni)
```html
<body>
  <header>               <!-- PA header with nav -->
  <main id="main-container">
    <section id="hero">  <!-- Featured news/hero section -->
    <section id="filters">
      <aside>            <!-- Left sidebar with category filters -->
      <article>          <!-- Main content area -->
        <div class="tabs">
          <button>Mappa</button>   <!-- Tab 1 -->
          <button>Elenco</button>  <!-- Tab 2 -->
        </div>
        <div class="tab-content">
          <!-- Tab content for map or list -->
        </div>
        <div class="cta">         <!-- Call to action button -->
    <section id="contacts">       <!-- Contact info footer section -->
  <footer>                         <!-- PA footer -->
</body>
```

#### FixCity Current State (Sixteen Theme)
```php
// [slug].blade.php uses generic <x-page> component
// Which loads content from JSON

{
  "content_blocks": [
    {
      "type": "segnalazioni-layout",
      "data": { /* all config here */ }
    },
    {
      "type": "contacts",
      "data": { /* contacts config */ }
    }
  ]
}
```

### Expected Differences

#### What WILL Be Different (OK)
- Blade template syntax vs Handlebars
- Laravel Folio routing vs static file generation
- JSON-driven content vs Handlebars JSON
- Tailwind CSS classes vs Bootstrap Italia

#### What MUST Match (90% Target)
- ✅ Element hierarchy (nesting)
- ✅ Semantic tags (<section>, <article>, <aside>, <nav>)
- ✅ Class names and attributes (MUST be identical)
- ✅ ARIA labels and roles
- ✅ Form input structure
- ✅ Button and link patterns

---

## 🔍 ANALYSIS: ticket-list.html

### HTML Body Structure

#### Section 1: Hero/Featured Section
```html
<section id="head-section" class="section-hero">
  <div class="container">
    <div class="row">
      <div class="col-12">
        <!-- Featured card with image, category, date -->
      </div>
    </div>
  </div>
</section>
```

**Key Points**:
- `id="head-section"` is critical marker
- Container + row + col-12 (Bootstrap grid)
- Image with alt text
- Category badge (green background)
- Date display
- Title and description

#### Section 2: Tabs Navigation
```html
<section id="map-and-list">
  <div class="container">
    <ul class="nav nav-tabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="tab-map" 
                data-bs-toggle="tab" 
                aria-selected="true" 
                role="tab">
          Mappa
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-list" 
                data-bs-toggle="tab" 
                aria-selected="false" 
                role="tab">
          Elenco
        </button>
      </li>
    </ul>
    
    <div class="tab-content">
      <div class="tab-pane fade show active" 
           id="pane-map" 
           role="tabpanel">
        <!-- Map content -->
      </div>
      <div class="tab-pane fade" 
           id="pane-list" 
           role="tabpanel">
        <!-- List content -->
      </div>
    </div>
  </div>
</section>
```

**Key Points**:
- `.nav.nav-tabs` for tab navigation (Bootstrap)
- `.nav-link.active` for active tab styling
- `data-bs-toggle="tab"` for Bootstrap JS interaction
- `role="tab"` and `role="tabpanel"` for accessibility
- `aria-selected` to indicate active tab

#### Section 3: Sidebar + Main Content
```html
<section id="filter-and-cards">
  <div class="container">
    <div class="row">
      <aside class="col-lg-3">
        <div class="filter-group">
          <h3>Filtra per categoria</h3>
          <ul class="list-unstyled">
            <li>
              <label class="form-check-label">
                <input type="checkbox" class="form-check-input">
                Acqua, allagamenti, problemi fognari (21)
              </label>
            </li>
            <!-- More filter items -->
          </ul>
        </div>
      </aside>
      
      <article class="col-lg-9">
        <div class="cards-grid">
          <div class="card card-report">
            <!-- Report/segnalazione card -->
          </div>
          <!-- More cards -->
        </div>
        <nav class="pagination">
          <!-- Pagination controls -->
        </nav>
      </article>
    </div>
  </div>
</section>
```

**Key Points**:
- Bootstrap grid: `col-lg-3` (sidebar), `col-lg-9` (main)
- `<aside>` semantic element for filters
- `<article>` semantic element for main content
- Form checkboxes with `.form-check-input`, `.form-check-label`
- Card components with consistent class naming
- Pagination with accessibility markup

#### Section 4: Call-to-Action
```html
<section id="cta-section" class="bg-light">
  <div class="container">
    <div class="cta-content">
      <h2>Fai una segnalazione</h2>
      <p>Se vuoi aggiungere una segnalazione, puoi farlo dopo 
         esserti autenticato con le tue credenziali SPID o CIE.</p>
      <a href="/it/tests/segnalazioni" 
         class="btn btn-primary">
        Segnala disservizio
      </a>
    </div>
  </div>
</section>
```

**Key Points**:
- `.bg-light` for background color
- `btn btn-primary` for styling (no Tailwind here!)
- Link wrapping pattern
- Clear call-to-action structure

#### Section 5: Contact Information
```html
<section id="info-contacts">
  <div class="container">
    <h2>Contatta il comune</h2>
    <ul class="links-list">
      <li>
        <a href="...">Richiedi assistenza</a>
      </li>
      <!-- More contact links -->
    </ul>
  </div>
</section>
```

---

## 🗂️ FixCity Current Architecture

### Blade Template: [slug].blade.php
```php
<?php
declare(strict_types=1);
use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;

name('tests.view');
middleware(PageSlugMiddleware::class);
?>

@php
    $pageSlug = 'tests.'.$slug;
    $data = ['slug' => $slug];
@endphp

<x-layouts.app>
    <div id="main-container">
        <x-page side="content" :slug="$pageSlug" :data="$data" />
    </div>
</x-layouts.app>
```

### JSON Content: tests.ticket-list.json
```json
{
  "id": "tests.ticket-list",
  "content_blocks": {
    "it": [
      {
        "type": "segnalazioni-layout",
        "data": {
          "view": "pub_theme::components.blocks.segnalazioni.layout",
          "results_count": 645,
          "tabs": [...],
          "cta": {...},
          "items": [...],
          "filters": {...}
        }
      }
    ]
  }
}
```

### Block Component: components/blocks/segnalazioni/layout.blade.php
- Currently renders content from JSON
- Uses Tailwind classes (to be migrated)
- May be missing semantic structure

---

## ❌ IDENTIFIED GAPS

Based on initial analysis, likely gaps in current implementation:

### Gap 1: Hero/Featured Section
- ❌ Missing `<section id="head-section">`
- ❌ Missing featured card structure
- ❌ Missing Bootstrap grid classes

### Gap 2: Tab Navigation
- ❌ Missing `.nav.nav-tabs` Bootstrap structure
- ❌ May have custom Tailwind tabs instead
- ❌ Missing `data-bs-toggle="tab"` attribute
- ❌ Missing ARIA attributes for accessibility

### Gap 3: Sidebar Layout
- ❌ Missing `<aside>` semantic element
- ❌ Missing `<article>` semantic element
- ❌ Missing Bootstrap grid (`col-lg-3`, `col-lg-9`)
- ❌ May use Tailwind flexbox instead

### Gap 4: Filter Form
- ❌ Missing `.form-check-input` / `.form-check-label` classes
- ❌ Checkbox structure may differ

### Gap 5: Cards Grid
- ❌ Missing `.card.card-report` Bootstrap structure
- ❌ Grid layout may use Tailwind instead of Bootstrap

### Gap 6: Color Classes
- ❌ May have `.bg-light` instead of semantic class names
- ❌ `.btn-primary` vs custom Tailwind button classes

---

## 🎬 EXECUTION STRATEGY

### Phase 1a: Analysis (COMPLETED ✅)
- [x] Study Design Comuni architecture
- [x] Extract reference HTML structure
- [x] Identify component patterns
- [x] Document design tokens
- [x] Create this strategy document

### Phase 1b: Comparison (PENDING ⏳)
**Executor Agent Task**: Run HTML comparison script

```bash
./bashscripts/html/html-structure-compare.sh ticket-list
```

**Output Location**:
```
laravel/Themes/Sixteen/docs/body-structure-comparison/ticket-list/
├── comparison-report.json          # Full technical report
├── parity-summary.txt              # Human-readable summary
├── reference-body.html             # Reference structure (extracted)
└── local-body.html                 # FixCity structure (extracted)
```

**Success Criteria**:
- ✅ Script runs without errors
- ✅ Both reference and local HTML extracted
- ✅ Parity score calculated and displayed

### Phase 1c: Gap Analysis (PENDING ⏳)
**Researcher Task**: Analyze comparison report

**What to Review**:
1. Read `comparison-report.json` (machine-readable)
2. Read `parity-summary.txt` (human-readable)
3. Compare against "IDENTIFIED GAPS" section above
4. Document findings in `PHASE-1-FINDINGS.md`

**Deliverable**: Detailed list of:
- Which gaps are real
- Magnitude of each gap
- Estimated complexity to fix

### Phase 1d: Blade Template Fix (PENDING ⏳)
**Executor Agent Task**: Apply HTML fixes

**Work On**: `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php`

**NOT to Touch**: Don't create separate `ticket-list.blade.php`

**Do This**:
1. Ensure blade calls a generic block component system
2. Component loads data from JSON
3. Renders with correct HTML structure (matching reference)
4. All text comes from translations (via `trans()` or `__()`)
5. Translation keys follow: `fixcity::segnalazione.fields.title.label`

**Do NOT Do**:
- ❌ Hardcode Italian text in blade
- ❌ Invent custom HTML structure
- ❌ Use Tailwind classes (yet)
- ❌ Create page-specific blade (use [slug].blade.php)

### Phase 1e: JSON Content Structure (PENDING ⏳)
**Executor Task**: Verify JSON content structure

**Review**: `laravel/config/local/fixcity/database/content/pages/tests.ticket-list.json`

**Ensure**:
- ✅ All content is in JSON (not hardcoded in blade)
- ✅ Structure supports all reference sections:
  - Featured section data
  - Tab definitions
  - Filter categories
  - Card items
  - CTA information
  - Contact information

### Phase 1f: Verification (PENDING ⏳)
**Executor Task**: Re-run comparison and verify 90% parity

```bash
./bashscripts/html/html-structure-compare.sh ticket-list
```

**Check Results**:
- Parity score ≥ 90% ✅
- Missing elements count ≤ 5% ⚠️
- Extra elements ≤ 5% ⚠️

**If Not Achieved**:
1. Analyze remaining gaps
2. Apply additional fixes
3. Re-test

---

## 📋 TRANSLATION KEY PATTERNS

### Correct Pattern (MUST USE)
```
fixcity::segnalazione.<field>.<section>.<type>
```

Where:
- `fixcity` = namespace (use this)
- `segnalazione` = entity (subject area)
- `<field>` = specific field (title, description, etc.)
- `<section>` = UI section (optional, can omit if obvious)
- `<type>` = .label, .help, .placeholder, .error

### Examples (CORRECT)
```blade
{{-- Title label --}}
{{ trans('fixcity::segnalazione.fields.title.label') }}

{{-- Filter placeholder --}}
{{ trans('fixcity::segnalazione.filters.category.placeholder') }}

{{-- Help text --}}
{{ trans('fixcity::segnalazione.fields.description.help') }}

{{-- Error message --}}
{{ trans('fixcity::segnalazione.fields.title.error') }}

{{-- Button text --}}
{{ trans('fixcity::segnalazione.actions.submit.label') }}
```

### Examples (WRONG ❌ - DO NOT USE)
```blade
❌ 'SEGNALAZIONE::SEGNALAZIONE.ELENCO.TITLE'  (invents namespace)
❌ 'segnalazioni.list.title'                  (missing type)
❌ 'pages::pages.tickets.title'          (wrong namespace)
```

### Language File Structure
```php
// laravel/lang/it/fixcity.php
return [
    'segnalazione' => [
        'fields' => [
            'title' => [
                'label'       => 'Titolo segnalazione',
                'placeholder' => 'Scrivi il titolo...',
                'help'        => 'Massimo 100 caratteri',
                'error'       => 'Il titolo è obbligatorio',
            ],
            'description' => [
                'label'       => 'Descrizione',
                'placeholder' => 'Descrivi il problema...',
                'help'        => 'Fornisci dettagli utili',
                'error'       => 'La descrizione è obbligatoria',
            ],
        ],
        'filters' => [
            'category' => [
                'label'       => 'Filtra per categoria',
                'placeholder' => 'Scegli una categoria...',
            ],
        ],
        'actions' => [
            'submit' => [
                'label' => 'Segnala disservizio',
                'help'  => 'Invia la tua segnalazione',
            ],
        ],
    ],
];

// laravel/lang/en/fixcity.php (same structure, English translations)
```

---

## 🚀 EXECUTION FLOW (Multi-Agent Coordination)

```
Phase 1a: RESEARCH (Researcher Agent)
├── ✅ COMPLETED: Study Design Comuni
├── ✅ COMPLETED: Document architecture
├── ✅ COMPLETED: Create strategy (this document)
└── Output: PHASE-1-STRATEGY.md

Phase 1b: COMPARE (Executor Agent #1)
├── Run: ./bashscripts/html/html-structure-compare.sh ticket-list
├── Fetch reference HTML
├── Fetch local HTML
├── Extract BODY elements
├── Compare structures
└── Output: comparison-report.json + parity-summary.txt

Phase 1c: ANALYZE (Researcher Agent)
├── Input: comparison-report.json
├── Map findings to strategy gaps
├── Create detailed gap analysis
└── Output: PHASE-1-FINDINGS.md + todo list

Phase 1d: FIX BLADE (Executor Agent #2)
├── Input: Gap analysis
├── Apply fixes to [slug].blade.php
├── Create/update block components
├── Ensure translation keys correct
└── Output: Updated blade template

Phase 1e: FIX JSON (Executor Agent #2)
├── Input: Gap analysis
├── Ensure JSON structure complete
├── Add missing sections/fields
└── Output: Updated JSON

Phase 1f: VERIFY (Executor Agent #1)
├── Re-run: ./bashscripts/html/html-structure-compare.sh
├── Check parity score ≥ 90%
└── Output: Final parity report

Phase 1g: DOCUMENT (Researcher Agent)
├── Create: PHASE-1-COMPLETION-REPORT.md
├── Update: docs/INDEX.md with new findings
├── Update: bashscripts/docs/html/ with lessons learned
└── Prepare: Phase 2 strategy for CSS alignment
```

---

## 📚 DOCUMENTATION TO UPDATE

As work progresses, update these docs (Researcher/Documentation responsibility):

### Theme Documentation
- [ ] `laravel/Themes/Sixteen/docs/PHASE-1-STRATEGY.md` ← This document
- [ ] `laravel/Themes/Sixteen/docs/PHASE-1-FINDINGS.md` ← Findings (to create)
- [ ] `laravel/Themes/Sixteen/docs/PHASE-1-COMPLETION-REPORT.md` ← Results (to create)
- [ ] `laravel/Themes/Sixteen/docs/body-structure-comparison/ticket-list/` ← Comparison output
- [ ] `laravel/Themes/Sixteen/docs/00-INDEX.md` ← Update with Phase 1 progress

### Bashscripts Documentation
- [ ] `bashscripts/docs/html/INDEX.md` ← Already created
- [ ] `bashscripts/docs/html/html-structure-compare.md` ← Detailed technical guide (to create)

### Blade/Component Documentation
- [ ] `laravel/Themes/Sixteen/docs/blocks/` ← Component patterns used
- [ ] `laravel/Themes/Sixteen/docs/design-comuni/pages/ticket-list-structure.md` ← Page-specific reference

---

## 🎯 SUCCESS CRITERIA

### Parity Score
- ✅ **Primary Goal**: ≥ 90% HTML structural parity
- ⚠️ **Acceptable**: 85-89% with documented exceptions
- ❌ **Retry**: < 85% (significant work needed)

### Structural Requirements
- ✅ All semantic elements present (section, article, aside, nav, header, footer)
- ✅ Bootstrap grid classes match (col-lg-3, col-lg-9, etc.)
- ✅ Component class names identical (btn-primary, nav-tabs, form-check-input)
- ✅ ARIA attributes present and correct (role, aria-label, aria-selected)
- ✅ Form input structure matches (labels, checkboxes, etc.)

### Code Quality
- ✅ No hardcoded text in blade (all translations)
- ✅ Translation keys follow `fixcity::*.*.*.<type>` pattern
- ✅ JSON structure complete and organized
- ✅ Single [slug].blade.php (no page-specific blades)
- ✅ All configuration in JSON, all rendering in blade

### Documentation
- ✅ Findings documented in PHASE-1-FINDINGS.md
- ✅ Index updated with Phase 1 progress
- ✅ Lessons learned captured for Phase 2
- ✅ Comparison reports preserved in theme docs

---

## 📞 MULTI-AGENT COORDINATION

### Agent Roles

#### Researcher (Primary in Phase 1a)
- **Responsibility**: Study, analyze, document
- **Output**: Strategy documents, gap analysis, findings
- **Does NOT**: Execute code changes, run scripts

#### Executor #1 (Comparison & Verification)
- **Responsibility**: Run comparison scripts, verify results
- **Work**: Phase 1b (compare) and Phase 1f (verify)
- **Command**: `./bashscripts/html/html-structure-compare.sh ticket-list`

#### Executor #2 (Code Changes)
- **Responsibility**: Fix blade templates and JSON content
- **Work**: Phase 1d (blade fixes) and Phase 1e (JSON fixes)
- **Files**: `[slug].blade.php`, `tests.ticket-list.json`, block components

#### Coordinator (Overall)
- **Responsibility**: Track progress, unblock issues
- **Coordination**: GitHub issues, todo list, status updates

### Communication Points
1. **After Phase 1b** (Executor #1): Share comparison report with Executor #2
2. **During Phase 1d-1e** (Executor #2): Report blockers or unexpected issues
3. **After Phase 1f** (Executor #1): Confirm 90% parity achievement
4. **Throughout**: Update GitHub issues and todo list

### Conflict Prevention
- ✅ Each agent has clear scope (no overlap)
- ✅ Work happens sequentially (Phase 1b → 1c → 1d → 1e → 1f)
- ✅ Single source of truth: comparison report
- ✅ Documentation keeps everyone aligned

---

## 🔗 RELATED DOCUMENTATION

- 📖 [bashscripts/docs/html/INDEX.md](../../../bashscripts/docs/html/INDEX.md) - HTML comparison tools
- 📋 [bashscripts/html/html-structure-compare.sh](../../../bashscripts/html/html-structure-compare.sh) - Main script
- 🎯 [Design Comuni Reference](https://italia.github.io/design-comuni-pagine-statiche/sito/ticket-list.html) - Live reference
- 📚 [Bootstrap Italia Docs](https://italia.github.io/bootstrap-italia/) - Official component library

---

## 📝 CHECKLIST: RESEARCHER HANDOFF

Before handing off to executors, confirm:

- [x] Design Comuni architecture studied
- [x] Component patterns documented
- [x] Design tokens captured
- [x] WCAG compliance requirements understood
- [x] Expected gaps identified
- [x] Execution strategy defined
- [x] Translation patterns documented
- [x] Multi-agent coordination planned
- [x] Success criteria clear
- [x] Documentation structure ready
- [ ] **Next**: Share strategy with executor agents, ready comparison script

---

**Document Status**: 📋 **STRATEGY COMPLETE**  
**Next Phase**: Executor agents apply fixes → Researcher analyzes findings → Phase 2 CSS alignment  
**Created**: 2026-04-08  
**Updated By**: Researcher Agent (BMAD Mode)
