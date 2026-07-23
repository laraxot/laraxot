# CSS/JS Phase - Status Report

**Date**: 2026-04-09
**Page**: segnalazione-01-privacy

## HTML Parity Status

| Metric | Value |
|---|---|
| Reference structure lines | 850 |
| Local structure lines | 852 |
| Lines only in reference | 161 |
| Lines only in local | 163 |
| **Match %** | **~81%** ✅ |

## Missing Classes (Reference has, Local doesn't)

None - all reference classes are present in local HTML.

## Extra Classes (Local has, Reference doesn't)

- `cms-view-wrapper`
- `content`
- `page-content`
- `segnalazione-privacy-page`

These are wrapper classes added by our CMS system and don't affect visual parity.

## CSS Fixes Applied

### 1. Font Issues
- ✅ `.text-paragraph`: Fixed from Lora → Titillium Web
- ✅ Color fixed to `#5C6F82` (Bootstrap Italia standard)
- ✅ Line-height fixed to `1.6`

### 2. Steppers (Updated 2026-04-09 12:30)
- ✅ `.steppers-header`: Added `padding: 0 24px; height: 64px; background: #fff; box-shadow: 0 8px 20px rgba(0,0,0,.1)`
- ✅ `.steppers-header ul li`: `font-size: 1.125rem; font-weight: 600; color: #5b6f82`
- ✅ `.steppers-header li.active/.confirmed`: `color: #06c` (blue, matching Bootstrap Italia)
- ✅ Icons: `fill: #5b6f82` inactive, `fill: #06c` active/confirmed, `width: 24px; height: 24px`
- ✅ `.steppers-index`: `margin-left: auto; font-weight: 600; flex-shrink: 0`

### 3. Button (Updated 2026-04-09 13:45)
- ✅ `.btn-primary`: Changed from green `#007a52` → blue `#06c` (Bootstrap Italia standard)
- ✅ Hover: `#0052a3`, Active: `#004780`

### 4. Checkbox/Form-check (Updated 2026-04-09 13:45)
- ✅ Hidden input: `position: absolute; opacity: 0`
- ✅ Label: `padding-left: 2rem; font-weight: 600`
- ✅ Checked color: `#06c` (blue)

### 5. Contact List (Updated 2026-04-09 13:45)
- ✅ Link color: `#06c` (blue), hover: `#0052a3`
- ✅ Icon fill: `#06c`

### 6. Color Standard Discovery
**IMPORTANT**: Bootstrap Italia uses **blue #06c** (hsl(210,100%,40%)) for ALL primary interactive elements, NOT green #007a52. This was a systematic error across the segnalazione CSS that has been corrected.

## Build Commands

```bash
cd laravel/Themes/Sixteen
npm run build && npm run copy
```

## Cache Clearing (CRITICAL)

```bash
cd laravel
rm -rf storage/framework/views/*.php
php artisan optimize:clear
```

## Next Steps

1. Fix remaining components: breadcrumbs, contacts card, form-check
2. Verify on multiple screen sizes (mobile, tablet, desktop)
3. Check other segnalazione pages for HTML structural fixes
