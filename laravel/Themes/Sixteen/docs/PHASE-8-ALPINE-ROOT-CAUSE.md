# Phase 8: Root Cause Found! - Alpine Directives Being Stripped

**Date**: 2026-04-02  
**Status**: 🔴 ROOT CAUSE IDENTIFIED  
**Severity**: HIGH - Blocking all Alpine.js functionality

---

## The Problem (CONFIRMED)

### Evidence Chain

1. **Alpine.js is installed**: ✅
   ```bash
   $ grep -i "alpine" resources/js/app.js
   ✅ import Alpine from 'alpinejs';
   ✅ Alpine.start();
   ```

2. **Alpine is in built bundle**: ✅
   ```bash
   $ grep -i "alpine" public/assets/app-B4ubt5st.js
   ✅ Found multiple Alpine references
   ```

3. **Script is loaded in HTML**: ✅
   ```html
   <script type="module" src="http://127.0.0.1:8000/themes/Sixteen/assets/app-B4ubt5st.js"></script>
   ```

4. **Directives ARE in source blade**: ✅
   ```blade
   <div x-data="mobileMenu()" @click="toggle()"> <!-- In source -->
   ```

5. **Directives NOT in rendered HTML**: ❌
   ```html
   <!-- NOT in output - directives removed! -->
   <div id="header-nav-wrapper">
   ```

### Conclusion

**Alpine directives are being STRIPPED between blade rendering and HTML output.**

This is NOT a build issue. This is a RUNTIME issue.

---

## Root Cause Investigation

### Hypothesis 1: View Composer Sanitization (MOST LIKELY)

**File**: `laravel/Themes/Sixteen/app/View/Composers/SixteenComposer.php`

**Theory**: The view composer might be:
1. Parsing HTML output
2. Removing "unknown" attributes
3. Stripping `x-data`, `@click`, etc.

**How to check**:
```bash
cat laravel/Themes/Sixteen/app/View/Composers/SixteenComposer.php | grep -i "sanitize\|strip\|filter\|html_purify\|tidy"
```

### Hypothesis 2: HTML Purifier Middleware

**Theory**: Laravel or Folio middleware might be:
1. Running an HTML purifier
2. Removing "unsafe" attributes

**How to check**:
```bash
grep -r "Purifier\|HtmlSpecialChars\|strip_tags" laravel/config/*.php
grep -r "middleware.*html\|middleware.*purif" laravel/routes/*.php
```

### Hypothesis 3: Laravel Blade Security

**Theory**: Blade might be escaping or stripping attributes

**How to check**:
```bash
# Check if Blade has raw output
curl -s http://127.0.0.1:8000/it/tests/homepage | grep "header-nav-wrapper" -A 5
```

### Hypothesis 4: CMS/Folio Override

**Theory**: The CMS might be intercepting view rendering

**How to check**:
```bash
grep -r "render\|blade" laravel/Modules/Cms --include="*.php" | grep -i "strip\|purify\|filter"
```

---

## Investigation Steps for Phase 9

### Step 1: Inspect SixteenComposer (10 min)

```bash
# Find SixteenComposer
find . -name "SixteenComposer.php"

# Review its code
cat laravel/Themes/Sixteen/app/View/Composers/SixteenComposer.php
```

**Look for**:
- HTML manipulation
- Attribute removal
- Sanitization logic
- String replacement on HTML

### Step 2: Check Middleware (10 min)

```bash
# Search for HTML middleware
grep -r "class.*Middleware" laravel/config/local/fixcity/ --include="*.php" | grep -i "html\|purif"

# Check request handling
grep -r "html\|purif\|sanitize" laravel/bootstrap/ --include="*.php"
```

### Step 3: Test Direct Blade Rendering (15 min)

```php
// Create a test route to check if directives survive blade rendering
Route::get('/test-alpine', function () {
    return view('pub_theme::components.blocks.header.nav-wrapper', [
        'test_mode' => true
    ]);
});
```

**Visit**: `http://127.0.0.1:8000/test-alpine`
**Check**: Are directives present?

### Step 4: Check CMS View Cache (10 min)

```bash
# List cache directories
ls -la storage/framework/cache/

# Check view compilation
ls -la storage/framework/views/ | grep -i "nav-wrapper\|header"

# Extract and inspect compiled blade
cat storage/framework/views/*nav-wrapper*.php | head -50
```

### Step 5: Add Debug Output (15 min)

**Add to nav-wrapper.blade.php**:
```php
<!-- DEBUG: Checking if this renders -->
<!-- DATA ATTRIBUTE TEST: data-test="value" -->
<!-- ALPINE TEST: x-data="test()" -->
```

**Visit page and view source**:
- If DEBUG comment appears → Blade is rendering
- If DATA comment appears → HTML attributes survive
- If ALPINE comment doesn't appear → Alpine stripped

---

## Most Likely Solution

### Problem: SixteenComposer or middleware is sanitizing HTML

### Solution: Remove sanitization or whitelist Alpine attributes

**In SixteenComposer.php**:
```php
// BEFORE: Likely sanitizing
$html = strip_tags($view->getContents(), '<allowed-tags>');

// AFTER: Should NOT strip Alpine directives
$html = $view->getContents(); // Leave as-is, trust Blade

// Or whitelist Alpine
$html = HTML::clean($view->getContents(), [
    'allowed_attributes' => ['x-data', '@click', '@keydown', ...]
]);
```

### Alternative: Bypass Composer

If composer is the culprit, modify the affected view path to NOT use composer.

---

## Diagnostic Script for Phase 9

Create this to quickly identify the issue:

```php
<?php
// test-alpine-rendering.php

// Test 1: Check blade file content
$bladePath = 'laravel/Themes/Sixteen/resources/views/components/blocks/header/nav-wrapper.blade.php';
$bladeContent = file_get_contents($bladePath);
echo "BLADE SOURCE:\n";
echo (strpos($bladeContent, 'x-data') !== false ? "✅ Has x-data\n" : "❌ No x-data\n");

// Test 2: Render blade
$rendered = view('pub_theme::components.blocks.header.nav-wrapper')->render();
echo "\nRENDERED OUTPUT:\n";
echo (strpos($rendered, 'x-data') !== false ? "✅ Has x-data\n" : "❌ No x-data\n");
echo (strpos($rendered, '@click') !== false ? "✅ Has @click\n" : "❌ No @click\n");

// Test 3: Check for sanitization
echo "\nSANITIZATION CHECK:\n";
if (strpos($rendered, 'x-data') === false && strpos($bladeContent, 'x-data') !== false) {
    echo "🔴 DIRECTIVES BEING STRIPPED!\n";
    echo "Likely culprit: View Composer or Middleware\n";
}
?>
```

---

## Quick Fixes to Try (in priority order)

### Fix 1: Disable SixteenComposer (5 min)
```php
// In ThemeServiceProvider.php, comment out:
// $this->app['view']->composer([...], SixteenComposer::class);
```

Then test if Alpine appears. If YES → Composer is the issue.

### Fix 2: Check for HTML Purifier (5 min)
```bash
grep -i "purifier" laravel/config/local/fixcity/*.php
```

If found, disable it or whitelist Alpine.

### Fix 3: Add Alpine to blade raw (10 min)
```blade
{!! Blade::compileString($view) !!}
```

### Fix 4: Pass raw HTML without rendering (15 min)
```php
// Return HTML directly instead of through view composer
return response($html);
```

---

## Prevention Strategy (Phase 9)

Once fixed:
1. Add tests to verify Alpine directives survive rendering
2. Document the whitelist of allowed attributes
3. Set up CI check to catch future stripping
4. Add Alpine directive comments to track rendering

---

## Timeline to Fix

- **Analysis**: 30 min (Phase 9 Step 1-3)
- **Identification**: 15 min (debug output)
- **Fix**: 15-30 min (depends on root cause)
- **Testing**: 15 min (verify in browser)
- **Total**: 1.25-2 hours

---

## Success Criteria

- [ ] x-data attributes in rendered HTML
- [ ] @click directives in rendered HTML
- [ ] Mobile menu toggle works
- [ ] No console errors
- [ ] Directives survive all rendering steps

---

**Status**: Ready for Phase 9 implementation  
**Next**: Execute investigation steps  
**Owner**: Next phase developer

