# CSS/JS Phase - Segnalazione Pages Parity Report

**Date**: 2026-04-09
**Goal**: HTML parity > 80% → proceed with CSS/JS visual parity

## HTML Parity Results

| Pagina | Ref Lines | Local Lines | Diff Ref | Diff Local | Match % | Status |
|---|---|---|---|---|---|---|
| segnalazione-01-privacy | 850 | 854 | 163 | 167 | **80.6%** | ✅ Proceed CSS/JS |
| segnalazione-02-dati | 1104 | 1133 | 385 | 414 | 64.3% | ❌ Fix HTML first |
| segnalazione-03-riepilogo | 1037 | 1053 | 331 | 347 | 67.6% | ❌ Fix HTML first |
| segnalazione-04-conferma | 1075 | 1181 | 361 | 467 | 63.3% | ❌ Fix HTML first |
| segnalazione-area-personale | 1752 | 1734 | 980 | 962 | 44.3% | ❌ Fix HTML first |
| ticket-list | 1500 | 1519 | 732 | 751 | 50.9% | ❌ Fix HTML first |
| segnalazione-dettaglio | 1575 | 1601 | 822 | 848 | 47.4% | ❌ Fix HTML first |

## Phase CSS/JS - segnalazione-01-privacy (80.6%)

### Font Fix Applied
- `style-apply.css`: `.text-paragraph` changed from `Lora` → `Titillium Web`
- Color corrected to Bootstrap Italia standard: `#5C6F82` (not `#191919`)
- Line-height: `1.6` (Bootstrap Italia standard)

### Other CSS Fixes
- `segnalazione-parity.css`: All `.text-paragraph` overrides unified to `#5C6F82`
- Added Bootstrap Italia reference color CSS variables

### Pages Below 80% - Action Items
- **segnalazione-02-dati** (64.3%): Missing file upload section, autocomplete location, user info accordion
- **segnalazione-03-riepilogo** (67.6%): Review summary layout differences
- **segnalazione-04-conferma** (63.3%): Confirmation page structural differences
- **segnalazione-area-personale** (44.3%): Major structural gaps
- **ticket-list** (50.9%): List/map layout differences
- **segnalazione-dettaglio** (47.4%): Detail page sidebar/content differences

## Build Commands
```bash
cd laravel/Themes/Sixteen
npm run build && npm run copy
```

## See Also
- [text-paragraph-font-fix.md](text-paragraph-font-fix.md)
- [css-js-parity.md](css-js-parity.md)
