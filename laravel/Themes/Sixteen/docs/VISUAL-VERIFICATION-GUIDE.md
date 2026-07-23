# Visual Verification Guide

**Purpose**: Manual verification of CSS fixes and visual parity  
**Date**: 2026-04-02  
**Status**: Ready for testing

---

## 🎯 Verification Checklist

### Before Testing
- [ ] Browser cache cleared (Ctrl+Shift+Del or Cmd+Shift+Delete)
- [ ] Hard refresh done (Ctrl+F5 or Cmd+Shift+R)
- [ ] Private/incognito window open
- [ ] Local server running: http://127.0.0.1:8000/
- [ ] Reference page accessible: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html

---

## 🔍 Visual Fix Verification

### Fix 1: Link Colors → Design Comuni Green (#007A52)

**Expected**: All links should be **green** (#007A52)

**To Test**:
1. Open http://127.0.0.1:8000/it/tests/homepage
2. Look at any link in the page (header, navigation, body, footer)
3. Links should be **green**, not white

**Verification Points**:
- [ ] Header links are green
- [ ] Navigation links are green
- [ ] Body content links are green
- [ ] Footer links are green
- [ ] Link hover changes to darker green (#005841)

**Screenshot**: Capture link color area for documentation

---

### Fix 2: Footer Background Styling

**Expected**: Footer should have proper Design Comuni styling

**To Test**:
1. Scroll to the bottom of page (footer area)
2. Check footer background color
3. Should match reference footer appearance
4. Compare with: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html

**Verification Points**:
- [ ] Footer background color matches reference
- [ ] Footer padding looks correct
- [ ] Footer text is readable
- [ ] Footer links are visible and styled

**Screenshot**: Capture footer area for comparison

---

### Fix 3: Header Height Adjustment

**Expected**: Header should be taller (222px vs 204px before)

**To Test**:
1. Look at the top of the page (header area)
2. Check if header appears slightly taller than before
3. Use browser DevTools to measure (F12 → Inspector → measure header)

**Verification Points**:
- [ ] Header appears proportionally correct
- [ ] Logo/branding has proper spacing
- [ ] Navigation items have adequate height
- [ ] No overlap or crowding

**Screenshot**: Full header area for comparison

---

### Fix 4: H1 Text Color

**Expected**: H1 title should be exact dark gray (#191919)

**To Test**:
1. Find the main H1 title on page
2. Color should be dark gray (nearly black)
3. Should match reference appearance

**Verification Points**:
- [ ] H1 color is dark gray (not black, not lighter)
- [ ] Text is readable
- [ ] Color matches reference

**Screenshot**: Capture H1 element for color comparison

---

### Fix 5: Footer Height & Spacing

**Expected**: Footer should be taller (775px vs 756px before)

**To Test**:
1. Scroll to footer area
2. Check vertical spacing of footer columns
3. Footer should appear less cramped
4. Use DevTools to measure (F12 → Inspector → measure footer)

**Verification Points**:
- [ ] Footer appears spacious
- [ ] Column spacing is adequate
- [ ] Content doesn't feel crowded
- [ ] Visual balance with footer content

**Screenshot**: Full footer area for comparison

---

## 🔄 Comparison Process

### Side-by-Side Comparison
1. Open two browser windows
2. Left: http://127.0.0.1:8000/it/tests/homepage (Local)
3. Right: https://italia.github.io/design-comuni-pagine-statiche/sito/homepage.html (Reference)
4. Compare corresponding areas

### Key Areas to Compare
- Header height and proportions
- Link colors (must be green)
- Footer background and styling
- Footer height and spacing
- Overall color consistency
- Typography and text colors

---

## 🛠️ DevTools Inspection

### Measuring Header Height
```
1. Right-click on header → Inspect
2. Look for <header> element
3. Note computed height in Computed styles
4. Should be close to 222px
```

### Checking Link Colors
```
1. Right-click on any link → Inspect
2. Look for computed color in Styles tab
3. Should show rgb(0, 122, 82) or #007A52
4. Hover state should show rgb(0, 88, 65) or #005841
```

### Verifying CSS Import
```
1. Open DevTools (F12)
2. Go to Sources tab
3. Find app-CfOBDP80.css
4. Search for "007A52" or "007a52"
5. Should find green color definitions
```

---

## 📊 Documented Findings

### ✅ Confirmed Fixes
Document which fixes are visually confirmed working:

- [ ] Fix 1 - Link color green: **Status**: Working / Not Working
- [ ] Fix 2 - Footer styling: **Status**: Working / Not Working
- [ ] Fix 3 - Header height: **Status**: Working / Not Working
- [ ] Fix 4 - H1 color: **Status**: Working / Not Working
- [ ] Fix 5 - Footer height: **Status**: Working / Not Working

### ⚠️ Issues Found
If any visual differences remain:

**Issue 1**: [Description]
- Location: [Where on page]
- Expected: [What it should look like]
- Actual: [What it looks like]
- Impact: [High/Medium/Low]
- Fix: [Possible solution]

**Issue 2**: [Description]
- ...

---

## 📸 Screenshot Documentation

### Required Screenshots

1. **Local Homepage - Full Page**
   - File: `local-full-page.png`
   - Viewport: 1920x1080
   - Save to: `/tmp/screenshots/`

2. **Reference Homepage - Full Page**
   - File: `reference-full-page.png`
   - Viewport: 1920x1080
   - Save to: `/tmp/screenshots/`

3. **Header Comparison**
   - File: `header-comparison.png`
   - Capture both pages' headers side-by-side
   - Annotations on differences

4. **Footer Comparison**
   - File: `footer-comparison.png`
   - Capture both pages' footers side-by-side
   - Annotations on differences

5. **Links Color Check**
   - File: `links-color-sample.png`
   - Capture several links showing green color
   - Use color picker to verify #007A52

---

## 🚀 Browser Testing

### Chrome / Chromium
- Hard refresh: `Ctrl+Shift+R`
- Clear cache: `Ctrl+Shift+Delete`
- DevTools: `F12`

### Firefox
- Hard refresh: `Ctrl+Shift+R`
- Clear cache: `Ctrl+Shift+Delete`
- Developer Tools: `F12`

### Safari (Mac)
- Hard refresh: `Cmd+Shift+R` or `Cmd+Option+R`
- Clear cache: Preferences → Privacy
- Web Inspector: `Cmd+Option+I`

---

## ✅ Sign-Off Checklist

When all verification complete, confirm:

- [ ] All 5 CSS fixes visually verified
- [ ] Links are green (#007A52)
- [ ] Footer background correct
- [ ] Header height appropriate
- [ ] H1 color correct
- [ ] Footer spacing adequate
- [ ] No visual regressions introduced
- [ ] All screenshots captured
- [ ] Browser cache properly cleared
- [ ] No build errors remain

---

## 🔗 Related Documentation

- [CSS-FIX-STRATEGY.md](./html-structure-analysis/CSS-FIX-STRATEGY.md)
- [CSS-FIX-IMPLEMENTATION-REPORT.md](./html-structure-analysis/CSS-FIX-IMPLEMENTATION-REPORT.md)
- [STRUCTURE-COMPARISON.md](./html-structure-analysis/STRUCTURE-COMPARISON.md)

---

## 📝 Verification Report Template

```
# Visual Verification Report

**Date**: [Date]
**Tested By**: [Your name]
**Browser**: [Chrome/Firefox/Safari] v[version]

## Fixes Verified

### Fix 1: Link Colors
- Status: ✅ Working / ❌ Not Working / ⚠️ Partial
- Notes: [Details]

### Fix 2: Footer Background
- Status: ✅ Working / ❌ Not Working / ⚠️ Partial
- Notes: [Details]

### Fix 3: Header Height
- Status: ✅ Working / ❌ Not Working / ⚠️ Partial
- Notes: [Details]

### Fix 4: H1 Color
- Status: ✅ Working / ❌ Not Working / ⚠️ Partial
- Notes: [Details]

### Fix 5: Footer Height
- Status: ✅ Working / ❌ Not Working / ⚠️ Partial
- Notes: [Details]

## Overall Assessment
- HTML structure: ✅ Matches reference
- CSS styling: [Overall assessment]
- Visual parity: [Estimated %]
- Issues found: [Count]
- Recommendation: [Proceed / Revise]

## Screenshots Attached
- [ ] local-full-page.png
- [ ] reference-full-page.png
- [ ] header-comparison.png
- [ ] footer-comparison.png
- [ ] links-color-sample.png
```

---

**Status**: Ready for manual visual testing  
**Next Step**: Open http://127.0.0.1:8000/it/tests/homepage and begin verification

