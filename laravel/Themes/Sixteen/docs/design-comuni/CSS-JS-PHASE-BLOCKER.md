# CSS/JS Phase - BLOCKER Report

**Date**: 2026-04-09  
**Page**: segnalazione-01-privacy (e tutte le altre pagine tests)  
**Status**: 🔴 **BLOCKED** - Database missing

---

## Critical Blocker

### Issue: Database SQLite Non Esiste

**Error**: `Database file at path [/var/www/_bases/base_fixcity_fila5/laravel/database/fixcity_data.sqlite] does not exist`

**Impact**: 
- TUTTE le pagine tests restituiscono HTTP 500
- Nessun elemento HTML viene renderizzato
- Impossibile verificare visual parity o fare screenshot meaningful
- CSS/JS fixes non possono essere testati

### Root Cause Analysis

1. **Database file missing**: `fixcity_data.sqlite` non esiste nella directory `laravel/database/`
2. **Migration failure**: Anche creando il file, le migrazioni falliscono con:
   ```
   Declaration of Illuminate\Database\Eloquent\Model::getKey() 
   must be compatible with Modules\Xot\Contracts\ModelContract::getKey(): mixed
   ```
3. **CMS Page loading**: Il modulo Cms cerca le pagine nel database, non nel JSON config

### JSON Config Exists ✅

Il file `laravel/config/local/fixcity/database/content/pages/tests.segnalazione-01-privacy.json` esiste ed è valido:
```json
{
  "id": "tests.segnalazione-01-privacy",
  "slug": "tests.segnalazione-01-privacy",
  "content_blocks": {
    "it": [{
      "type": "segnalazione-01-privacy",
      "data": {
        "view": "pub_theme::components.blocks.tests.segnalazione-01-privacy",
        "title": "Segnalazione disservizio",
        "current_step": 1,
        "total_steps": 3
      }
    }]
  }
}
```

### Blade Template Exists ✅

`laravel/Themes/Sixteen/resources/views/components/blocks/tests/segnalazione-01-privacy.blade.php` esiste

---

## CSS Fixes Already Applied (Waiting for DB fix)

### Font Fixes
| Page | Expected | CSS Applied | Status |
|------|----------|-------------|--------|
| segnalazione-01-privacy | 16px/24px #191919 | ✅ In segnalazione-parity.css | Ready |
| segnalazione-02-dati | 16px/24px #191919 | ✅ In segnalazione-parity.css | Ready |
| segnalazione-area-personale | 16px/24px #191919 | ✅ In segnalazione-parity.css | Ready |
| segnalazione-dettaglio | 16px/24px #191919 (Lora serif) | ✅ In segnalazione-parity.css | Ready |
| segnalazione-03-riepilogo | 18px/27px #30475f | ✅ In segnalazione-parity.css | Ready |

### Upload Placeholder
- ✅ Added to segnalazione-02-dati.blade.php
- ✅ Image copied to public_html/themes/Sixteen/design-comuni/assets/images/

### Console Errors
- ✅ Alpine multiple instances - Fixed in app.js
- ✅ Layout conditional loading - Fixed in main.blade.php

---

## Resolution Required

### Option 1: Fix Database (Recommended)
1. Resolve Xot ModelContract compatibility issue
2. Run migrations to create cms_pages table
3. Seed pages from JSON config files
4. Verify pages load with HTTP 200

### Option 2: Bypass Database
1. Modify Cms module to read from JSON config directly
2. Skip database lookup for tests pages
3. This would allow immediate CSS/JS verification

### Option 3: Use Existing Working Setup
1. Find a working database backup
2. Restore fixcity_data.sqlite
3. Verify pages load

---

## HTML Parity Scores (From Script, Before DB Issue)

| Page | Score | Status |
|------|-------|--------|
| segnalazione-01-privacy | 93.3% | ✅ |
| segnalazione-03-riepilogo | 92.75% | ✅ |
| segnalazione-02-dati | 88.96% | ✅ |
| segnalazione-area-personale | 88.43% | ✅ |
| segnalazione-dettaglio | 83.72% | ✅ |
| ticket-list | 80.19% | ✅ |
| segnalazione-04-conferma | 77.4% | ❌ |

---

## Next Steps After DB Fix

1. ✅ Verify pages load (HTTP 200)
2. ✅ Take screenshots at 1440px and 375px
3. ✅ Compare computed styles (font, color, spacing)
4. ✅ Fix any CSS mismatches
5. ✅ npm run build && npm run copy
6. ✅ Post-fix screenshots
7. ✅ Update this report

---

*Report created: 2026-04-09*  
*Requires: Database/Infrastructure fix before CSS/JS phase can continue*
