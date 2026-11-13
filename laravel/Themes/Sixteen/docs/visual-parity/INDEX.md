---
title: "Visual Parity Index"
type: note
tags: [documentation, parity, design-comuni]
created: 2026-09-26
updated: 2026-10-13
qmd: "visual parity index"
issues: []
discussions: []
related:
  - ../index.md
  - ../wiki/concepts/design-comuni-parity-method.md
status: draft
---

# Visual Parity Index

**Theme**: Sixteen
**Reference**: https://italia.github.io/design-comuni-pagine-statiche/
**Local**: http://192.168.1.40:8000/it/tests/

## Folder structure (canonical, 2026-10-13)

```
docs/visual-parity/
├── INDEX.md                  # This file
├── reports/                  # Analysis reports (2026-09-26 batch)
│   ├── complete-report.md
│   ├── fix-action-plan.md
│   ├── lista-categorie-analysis.md
│   ├── progress.md
│   └── visual-inspection.md
├── screenshots/
│   ├── pages/<page>/         # Per-page evidence: local-*, ref-*, comparison.md
│   └── legacy-2026-04/       # Historical evidence (2026-04, read-only)
└── scripts/                  # Python analyzers (nested-path bug fixed 2026-10-13)
    ├── capture_screenshots.py
    ├── html_structure_analyzer.py
    ├── html_structure_analyzer.nested.old.py
    ├── quick_analyzer.py
    └── visual_comparison.py
```

## How to use

1. Capture evidence: `node docs/visual-parity/scripts/capture_screenshots.py` (or Playwright
   via `bashscripts/tools/visual/`).
2. Per-page verdicts live in `screenshots/pages/<page>/comparison.md`.
3. Release gate: controlled visual parity (images stubbed) + body HTML parity, see
   `docs/prompts/homepage/index.md` and the BMAD stories in `docs/bmad/stories/`.

## Related

- [Theme docs index](../index.md)
- [Parity method wiki](../wiki/concepts/design-comuni-parity-method.md)
- [Design Comuni prompt](../prompts/homepage/index.md)
