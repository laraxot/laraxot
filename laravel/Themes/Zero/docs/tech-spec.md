---
title: "Technical Specification - Zero Theme"
type: technical_spec
tags: [tech spec, zero, themes]
created: 2026-08-04
updated: 2026-08-04
---
# Technical Specification - Zero Theme

## Overview
Frontend theme implementation using Blade, Tailwind CSS, and Vite.

## Assets Pipeline

### Build Commands
```bash
npm install          # Install dependencies
npm run dev          # Development server with HMR
npm run build        # Production build
npm run lint         # Tailwind linting
```

### Entry Points
- `resources/css/app.css` — Main stylesheet
- `resources/js/app.js` — Main JavaScript
- `resources/views/components/` — Blade components

## Key Files

### Configuration
- `tailwind.config.js` — Tailwind setup
- `vite.config.js` — Build configuration
- `.gitignore` — Exclude built assets

### Views
- `resources/views/layouts/` — Base templates
- `resources/views/components/` — Reusable components

## Testing Strategy
- Visual regression testing (optional)
- Build verification
- Component rendering tests

## Quality Gates
1. Build: npm run build passes
2. Lint: npm run lint passes
3. Preview: Theme renders in browser
4. Performance: Lighthouse score >90
