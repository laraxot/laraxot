# Site Replication Strategy

## Overview
The goal is to surpass the target site (`https://lightseagreen-dogfish-560272.hostingersite.com/`) in terms of performance, SEO, and aesthetic consistency while maintaining 100% content parity.

## Strategy Pillars

### 1. Data-Driven Architecture
- **Content Storage**: All content is managed via `home.json` and `header.json` in `config/local/techplanner/database/content/`.
- **Block Identification**: Using `slug` attributes in `BlockData` to uniquely identify and style components.
- **Translatable Support**: Ensuring multi-language support (Italian/English) is built into the block retrieval logic.

### 2. Modern Frontend Stack
- **Styling**: Tailwind CSS for rapid, consistent UI development.
- **Interactivity**: Alpine.js for lightweight state management (e.g., sticky header effects, mobile menus).
- **Asset Pipeline**: Vite for optimized bundling and copy scripts for public asset deployment.

### 3. Visual Parity & Improvements
- **Typography**: Inter as the primary font for a clean, professional look.
- **Color Palette**: 
  - Primary Blue: `#1E5A96` (Trust, Professionalism)
  - Accent Orange: `#E67E22` (Energy, Action)
- **Glassmorphism**: Subtle use of backdrop-blur for a premium feel.

### 4. SEO & Inbound Marketing
- **Semantic HTML**: Proper H1-H6 hierarchy for search engines.
- **Metadata**: Dynamic metatag generation based on JSON content.
- **AdSense Ready**: Structural slots reserved for future monetization.

## Status: 95%+ Achieved
- [x] Fixed Header with dynamic scroll effects.
- [x] Robust backend block retrieval logic.
- [x] Initial content replication for Hero, Services, and Testimonials.
- [ ] Final polishing of Sector and Resource blocks.