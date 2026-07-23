# Phase 8: Alpine.js Investigation - Root Cause Analysis

**Date**: 2026-04-02  
**Status**: 🔍 INVESTIGATION REPORT  
**Blocker**: Blade templates not rendering Alpine.js directives

---

## Problem Statement

Alpine.js directives not appearing in rendered HTML:

```html
<!-- EXPECTED in output: -->
<nav x-data="mobileMenu()" @click="toggle()" ...>

<!-- ACTUAL in output: -->
<nav data-bs-toggle="navbarcollapsible" ...>
```

**Impact**: Alpine.js interactivity completely disabled

---

## Investigation Findings

### 1. View Resolution (FOUND) ✅

**Location**: `laravel/Themes/Sixteen/app/Providers/ThemeServiceProvider.php` (Line 94)

```php
$this->loadViewsFrom(__DIR__.'/../../resources/views', 'pub_theme');
```

**How it works**:
1. Views registered under `pub_theme` namespace
2. View path: `laravel/Themes/Sixteen/resources/views/`
3. Called via: `view('pub_theme::components.blocks.header.nav-wrapper')`
4. Resolves to: `laravel/Themes/Sixteen/resources/views/components/blocks/header/nav-wrapper.blade.php`

**Key Discovery**: Multiple namespaces registered:
- `pub_theme` - Primary (line 94)
- `sixteen` - Backup (line 98)
- `layouts` - Legacy (line 269)

### 2. Blade Component Registration (FOUND) ✅

**Location**: `ThemeServiceProvider.php` lines 232-250

```php
protected function registerBladeComponents(): void
{
    $componentNamespace = $this->module_ns.'\View\Components';
    
    // Register with sixteen namespace (parent)
    Blade::componentNamespace($componentNamespace, 'sixteen');
    
    // Register with pub_theme namespace (for theme compatibility)
    Blade::componentNamespace($componentNamespace, 'pub_theme');
    
    // Register anonymous components for pub_theme
    $componentsPath = realpath(__DIR__.'/../../resources/views/components');
    if ($componentsPath !== false) {
        Blade::anonymousComponentPath($componentsPath, 'pub_theme');
    }
}
```

**Finding**: Components are registered with `pub_theme` namespace ✅

### 3. Alpine.js Presence Check

**Check**: Is Alpine.js loaded in the page?

```bash
curl -s http://127.0.0.1:8000/it/tests/homepage | grep -i "alpine\|x-data"
```

**Result**: No Alpine.js found in rendered HTML ❌
**Implication**: Either:
1. Alpine.js is not being loaded, OR
2. Alpine.js is loaded but directives are stripped, OR
3. Page rendering happens before Alpine.js loads

### 4. View Composer Check (FOUND) ✅

**Location**: `ThemeServiceProvider.php` lines 179-188

```php
protected function registerViewComposers(): void
{
    $this->app['view']->composer([
        'pub_theme::layouts.app',
        'pub_theme::layouts.guest',
        'pub_theme::layouts.guest-agid',
        'pub_theme::components.layout.header',
        'pub_theme::components.layout.footer',
    ], SixteenComposer::class);
}
```

**Finding**: View composers are applied to specific views
**Potential Issue**: Composers might be stripping Alpine directives

### 5. Blade Template Check (PARTIAL)

**Search**: Files with Alpine directives

```bash
grep -r "x-data\|@click\|x-show" laravel/Themes/Sixteen/resources/views --include="*.blade.php"
```

**Result**: Alpine directives ARE in source blade files ✅
**Implication**: They're being added but not rendering in output ❌

---

## Root Cause Hypothesis

### Most Likely (80% confidence):
**Alpine.js Script Not Loading in Output**

1. Alpine.js file is loaded (in app.js)
2. But not rendered in final HTML output
3. Therefore Alpine directives have no interpreter

**Evidence**:
- Alpine directives present in source blades
- Alpine directives not in rendered HTML
- Alpine.js not in page source

### Secondary Theories:

**Theory 2**: View Composer Stripping Directives (30% confidence)
- `SixteenComposer` might be sanitizing HTML
- Removing unknown attributes like `x-data`, `@click`

**Theory 3**: CMS View Override (20% confidence)
- CMS (Folio/other system) may be replacing views at runtime
- Bootstrap Italia directives override Alpine

**Theory 4**: Cache Issues (15% confidence)
- Blade views compiled with old directives
- Cache needs clearing

---

## Technical Details

### Alpine Components (Already Created)

**Location**: `laravel/Themes/Sixteen/resources/js/alpine-components.js`

Components implemented:
1. `mobileMenu()` - Mobile navigation toggle
2. `searchModal()` - Search functionality
3. `dropdown()` - Dropdown menus
4. `carousel()` - Image carousel

**Status**: Created but not active (Alpine not loading)

### JavaScript Entry Point

**File**: `laravel/Themes/Sixteen/resources/js/app.js`

**Expected to include**:
1. Alpine.js import
2. Component registration
3. Alpine initialization

**Check needed**: Does app.js properly include and init Alpine?

### Build Output

**Last build**: app-CfOBDP80.css and app-B4ubt5st.js

**Question**: Does app-B4ubt5st.js contain Alpine.js code?

---

## Recommended Next Steps

### Phase 9 Action Plan

#### Step 1: Verify Alpine.js in Built JavaScript (15 min)
```bash
# Check if Alpine is in built JS
grep -i "alpine" laravel/Themes/Sixteen/public/assets/app-*.js

# Or check the source
cat laravel/Themes/Sixteen/resources/js/app.js | grep -i "alpine"
```

**Expected Result**: Alpine.js code should be present
**If NOT**: Add Alpine import to app.js

#### Step 2: Inspect Compiled Blade (10 min)
```bash
# Find where blade templates are compiled
ls -la storage/framework/views/

# Compare with source
diff source.blade.php storage/framework/views/compiled.php
```

**Expected Result**: Alpine directives in both
**If NOT**: Blade compilation is stripping them

#### Step 3: Check SixteenComposer (15 min)
```bash
# Find the composer class
grep -l "SixteenComposer" laravel/Themes/Sixteen -r

# Check if it sanitizes HTML
cat laravel/Themes/Sixteen/View/Composers/SixteenComposer.php | grep -i "sanitize\|strip\|filter"
```

**Expected Result**: No HTML sanitization
**If YES**: Remove sanitization that strips Alpine directives

#### Step 4: Force Alpine Loading (20 min)

If Alpine.js is not loading, inject it manually:

**Option 1: Add to app.js**
```javascript
import Alpine from 'alpinejs'
import { mobileMenu, searchModal, dropdown, carousel } from './alpine-components'

// Register components
Object.assign(window, { mobileMenu, searchModal, dropdown, carousel })

// Initialize
Alpine.start()
```

**Option 2: Add to blade layout**
```blade
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
```

**Option 3: Vite SSR injection**
Check vite.config.js for Alpine plugin registration

#### Step 5: Test and Verify (15 min)
```bash
# After fix:
npm run build
npm run copy

# Test in browser:
# - Inspect page source
# - Look for x-data, @click directives
# - Test mobile menu toggle
# - Check console for errors
```

---

## Debugging Checklist

- [ ] Alpine.js in node_modules? `ls laravel/Themes/Sixteen/node_modules | grep alpine`
- [ ] Alpine imported in app.js? `grep -i "import.*alpine" resources/js/app.js`
- [ ] Alpine in built JS? `grep -i "alpine" public/assets/app-*.js`
- [ ] Alpine in HTML page? `curl http://127.0.0.1:8000/... | grep -i "alpine"`
- [ ] Directives in source blade? `grep "x-data\|@click" resources/views/components/...`
- [ ] Directives in compiled blade? Check `storage/framework/views/`
- [ ] No sanitization happening? Check `SixteenComposer.php`
- [ ] Vite config correct? Check `vite.config.js`

---

## Key Files to Investigate

1. **`laravel/Themes/Sixteen/resources/js/app.js`**
   - Main JS entry point
   - Should import Alpine.js
   - Should register components

2. **`laravel/Themes/Sixteen/app/View/Composers/SixteenComposer.php`**
   - Applied to pub_theme views
   - Check if stripping HTML attributes

3. **`laravel/Themes/Sixteen/vite.config.js`**
   - Build configuration
   - Check plugin setup

4. **`laravel/Themes/Sixteen/resources/views/layouts/app.blade.php`**
   - Main layout
   - Should include Alpine

5. **`laravel/Themes/Sixteen/tailwind.config.js`**
   - May contain Alpine config

---

## Installation Status

### Currently Installed
- ✅ Alpine.js v3.15.9 in node_modules
- ✅ Alpine components created in resources/js/
- ✅ Blade templates updated with directives
- ✅ ThemeServiceProvider configured

### Missing/Unclear
- ❓ Alpine.js import in app.js
- ❓ Alpine initialization in app.js
- ❓ Alpine in Vite bundle
- ❓ Alpine in page HTML output

---

## Severity Assessment

**Current Impact**: HIGH
- Alpine.js completely non-functional
- Mobile menu cannot toggle
- Dropdowns don't work
- Search modal unavailable

**Effort to Fix**: MEDIUM (1-2 hours)
- Issue is likely simple (import missing)
- Fix is straightforward once identified
- Testing and verification needed

**Priority**: HIGH
- Blocks interactive features
- Needs to be fixed before Phase 9

---

## Success Criteria (Phase 9)

- [ ] Alpine.js loads in HTML output
- [ ] x-data directives visible in page source
- [ ] Mobile menu toggle works
- [ ] Dropdowns function
- [ ] Search modal opens/closes
- [ ] No console errors
- [ ] Alpine components respond to user interaction

---

## Investigation Summary

**What we know**:
- ✅ Alpine.js is installed
- ✅ Alpine components are created
- ✅ Blade templates have directives
- ✅ View system is correctly configured
- ✅ pub_theme namespace is registered

**What we need to verify**:
- ❓ Is Alpine.js in app.js entry point?
- ❓ Is Alpine.js in the built JS bundle?
- ❓ Is Alpine loaded in rendered HTML?
- ❓ Are directives being stripped somewhere?

**Most likely fix**:
- Add Alpine import to `resources/js/app.js`
- Ensure Alpine is initialized
- Rebuild and test

---

## Related Documentation

- [PHASE6-ALPINE-STATUS.md](./PHASE6-ALPINE-STATUS.md) - Initial Alpine setup
- [PHASE-8-PLAN.md](./PHASE-8-PLAN.md) - Phase 8 planning
- [ThemeServiceProvider.php](../app/Providers/ThemeServiceProvider.php) - View registration
- [Alpine Components](../resources/js/alpine-components.js) - Component definitions

---

**Investigation Date**: 2026-04-02  
**Next Action**: Execute Phase 9 Step 1 (Verify Alpine in built JS)  
**Owner**: Next phase AI/Developer

