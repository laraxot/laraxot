---
title: "Xot Module Architecture"
type: architecture
tags: [module, architecture, framework]
created: 2026-07-28
updated: 2026-07-28
---

# Xot Module — Architecture

## Purpose
Foundation framework module providing base models, traits, utilities, and Filament builders for all other modules.

## Core Components
- `BaseModel` → XotBaseModel (UUID PKs, timestamps)
- `XotBaseMigration` for consistent migrations
- Filament builders (TableBuilder, FormBuilder, etc.)
- Utility classes and helpers

## Quality Gates
✅ PHPStan L10: Executed (2026-07-28)
✅ LFS Issue: Resolved via git filter-repo (38 image files)
✅ Merge Markers: Fixed (4 files cleaned)
