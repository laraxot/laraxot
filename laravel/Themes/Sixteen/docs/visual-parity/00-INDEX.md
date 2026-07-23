# Visual Parity Documentation Index

**Theme**: Sixteen  
**Focus**: HTML structure and visual comparison with Italia Design Comuni reference

## Overview

This folder contains analysis, scripts, and documentation for visual parity work between:
- **Reference**: https://italia.github.io/design-comuni-pagine-statiche/sito/
- **Local**: http://127.0.0.1:8000/it/tests/

## Screenshots Captured

| Page | Local | Reference | Size |
|------|-------|-----------|------|
| homepage | ✓ | ✓ | 676KB / 881KB |
| lista-categorie | ✓ | ✓ | 122KB / 658KB |
| amministrazione | ✓ | - | 44KB |
| novita | ✓ | - | 121KB |
| servizi | ✓ | - | 121KB |
| contatti | ✓ | - | 34KB |

## Pages Analyzed

| Page | Status | Last Analyzed |
|------|--------|---------------|
| lista-categorie | ✅ Complete | 2026-04-04 |
| homepage | ✅ Screenshots | 2026-04-04 |
| amministrazione | ⏳ In Progress | 2026-04-04 |
| novita | ⏳ In Progress | 2026-04-04 |
| servizi | ⏳ In Progress | 2026-04-04 |
| contatti | ⏳ In Progress | 2026-04-04 |

## Folder Structure

```
docs/visual-parity/
├── 00-INDEX.md                    # This file
├── LISTA-CATEGORIE-ANALYSIS.md    # Detailed analysis
├── FIX-ACTION-PLAN.md             # Action plan for fixes
├── COMPLETE-REPORT.md             # Complete analysis report
├── PROGRESS.md                    # Progress tracker
├── scripts/
│   ├── html_structure_analyzer.py
│   ├── visual_comparison.py
│   └── capture_screenshots.py
├── temp/
│   ├── reference-*.html           # Downloaded HTML from reference
│   └── local-*.html               # Downloaded HTML from local
└── screenshots/
    ├── local-*.png                # Local page screenshots
    ├── reference-*.png            # Reference page screenshots
    └── ANALYSIS.md                # Screenshot analysis
```

## How to Use

1. **Analyze a page**:
   ```bash
   cd laravel/Themes/Sixteen/docs/visual-parity
   python3 scripts/visual_comparison.py
   ```

2. **Build theme**:
   ```bash
   cd laravel/Themes/Sixteen
   npm run build && npm run copy
   ```

3. **Compare visually**:
   - Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/{page}.html
   - Local: http://127.0.0.1:8000/it/tests/{page}

## Related Documentation

- [00-index.md](../00-index.md) - Theme docs index
- [design-comuni/00-index.md](../design-comuni/00-index.md) - Active parity workspace