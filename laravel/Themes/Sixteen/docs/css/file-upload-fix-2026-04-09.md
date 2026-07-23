# File Upload Fix - segnalazione-02-dati

**Date**: 2026-04-09  
**Page**: `segnalazione-02-dati` (Step 2: Dati di segnalazione)  
**Issue**: File upload button was static (hardcoded placeholder image) — no real upload functionality

## Changes Made

### File: `segnalazione-02-dati.blade.php`

**Before**: Static upload section with hardcoded image name "6yhakandsahm413d8.jpg"
**After**: Interactive Alpine.js file upload with:

1. **Hidden `<input type="file">`** with `multiple` and `accept="image/*"`
2. **Alpine.js `x-data`** state management for uploaded files
3. **Dynamic preview list** — shows thumbnail for images, file icon for others
4. **Remove button** per file with proper aria-label
5. **File validation** — max 5 files, max 5MB each
6. **Memory cleanup** — `URL.revokeObjectURL()` on file removal

### Translation Fix

Changed `__('fixcity::segnalazione.buttons.upload.*')` → `__('fixcity::segnalazione.actions.upload.*')` to match actual translation file structure.

## HTML Parity

| Metric | Value |
|--------|-------|
| Structure lines (ref) | 1,104 |
| Structure lines (local) | 1,133 |
| Match % | **64.3%** |

Differences are primarily in attribute values (href paths, SVG use hrefs), not structure.

## Files Modified

| File | Change |
|------|--------|
| `segnalazione-02-dati.blade.php` | Added Alpine.js file upload with preview |
