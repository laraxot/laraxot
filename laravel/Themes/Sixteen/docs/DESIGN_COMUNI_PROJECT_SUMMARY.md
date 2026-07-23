# Design Comuni Replication - Project Summary

**Date**: April 1, 2026  
**Status**: 🟡 Phase 1 In Progress  
**Timeline**: 12 weeks (April 1 - June 30, 2026)

---

## Executive Summary

This document summarizes the comprehensive planning and preparation for replicating **38 Design Comuni static pages** using **Tailwind CSS + Alpine.js** with **JSON-driven content blocks**.

### Key Achievements (Phase 1 - Week 1)
✅ **Research Complete**: 674-line analysis of all 38 pages  
✅ **Roadmap Created**: 12-week plan with 6 phases  
✅ **GitHub Templates**: Issue and discussion templates  
✅ **Documentation**: Team guide and architecture docs  
✅ **Bug Fixes**: Fixed 9 files with Livewire multiple root elements error

---

## Project Scope

### What We're Building
- **38 Pages**: Complete replication of Design Comuni template
- **47 Components**: Universal, reusable components (NOT page-specific)
- **JSON Blocks**: Content separated from presentation
- **Tailwind CSS**: No Bootstrap Italia CDN - replicate with @apply
- **WCAG 2.1 AA**: Full accessibility compliance

### What We're NOT Building
- ❌ Page-specific components (DRY principle)
- ❌ Hardcoded HTML in Blade (JSON-driven)
- ❌ Multiple route files (single `[slug].blade.php`)
- ❌ Bootstrap Italia CDN imports (Tailwind @apply)

---

## Architecture Overview

### Core Principles

#### 1. DRY (Don't Repeat Yourself)
**Good**: Universal `cmp-hero` used by 15 pages  
**Bad**: Page-specific `cmp-homepage-hero` (violates DRY)

#### 2. KISS (Keep It Simple, Stupid)
**Good**: Simple JSON with blocks  
**Bad**: Complex nested PHP logic in Blade

#### 3. Single Dynamic Route
**Good**: `tests/[slug].blade.php` handles all pages  
**Bad**: Creating `homepage.blade.php`, `amministrazione.blade.php`

#### 4. Tailwind @apply
**Good**: Replicate Bootstrap classes with @apply  
**Bad**: Import Bootstrap Italia CSS from CDN

---

## Technical Stack

| Component | Technology | Why |
|-----------|-----------|-----|
| **Framework** | Laravel 12 | Latest LTS, best-in-class |
| **Routing** | Laravel Folio | File-based routing |
| **Components** | Livewire Volt | Single-file components |
| **CSS** | Tailwind CSS v4 | Utility-first, customizable |
| **JS** | Alpine.js | Lightweight, reactive |
| **UI Components** | DaisyUI | Pre-built components |
| **Design Tokens** | Bootstrap Italia | Official Design Comuni colors |
| **Testing** | Pest PHP | Modern testing framework |
| **Static Analysis** | PHPStan Level 10 | Type safety |
| **Code Style** | Laravel Pint | PSR-12 compliance |

---

## Page Inventory (38 Pages)

### Category 1: General Pages (9 pages)
1. Homepage ✅ IN PROGRESS
2. Argomenti (Topics)
3. Amministrazione
4. Documenti e Dati
5. Novità (News)
6. Novità Dettaglio
7. Servizi
8. Servizio Dettaglio
9. FAQ

### Category 2: Amministrazione (2 pages)
10. Amministrazione (duplicate - for testing)
11. Documenti e Dati (duplicate - for testing)

### Category 3: Novità (2 pages)
12. Novità (duplicate - for testing)
13. Novità Dettaglio (duplicate - for testing)

### Category 4: Servizi (3 pages)
14. Servizi (duplicate - for testing)
15. Servizio Categoria
16. Servizio Dettaglio (duplicate - for testing)

### Category 5: Vivere il Comune (2 pages)
17. Eventi
18. Evento Dettaglio

### Category 6: Prenotazione Appuntamento (8 pages)
19. Appuntamento 01 - Ufficio
20. Appuntamento 01 - Luogo
21. Appuntamento 02 - Data e Ora
22. Appuntamento 03 - Dettagli
23. Appuntamento 04 - Richiedente
24. Appuntamento 04 - Autenticato
25. Appuntamento 05 - Riepilogo
26. Appuntamento 06 - Conferma

### Category 7: Richiesta Assistenza (2 pages)
27. Assistenza 01 - Dati
28. Assistenza 02 - Conferma

### Category 8: Segnalazione Disservizio (7 pages)
29. Segnalazione Dettaglio
30. Segnalazione 01 - Privacy
31. Segnalazione 02 - Dati
32. Segnalazione 03 - Riepilogo
33. Segnalazione 04 - Conferma
34. Segnalazione Area Personale
35. Segnalazioni Elenco

### Category 9: Additional Pages (3 pages)
36. Mappa del Sito
37. Login
38. 404 Error

---

## Component Inventory (47 Components)

### Tier 1: Critical (7 components) - Implement First
1. **cmp-base** - Base wrapper (100% usage)
2. **cmp-breadcrumbs** - Breadcrumbs (97% usage)
3. **cmp-contacts** - Contacts block (95% usage)
4. **cmp-rating** - Rating component (87% usage)
5. **cmp-hero** - Hero section (79% usage)
6. **cmp-card** - Generic card (92% usage)
7. **cmp-button** - Button (85% usage)

### Tier 2: High Priority (12 components)
8. **cmp-navscroll** - Sticky navigation
9. **cmp-steps-progress** - Multi-step progress
10. **cmp-form-input** - Input field
11. **cmp-form-select** - Select dropdown
12. **cmp-form-checkbox** - Checkbox
13. **cmp-form-radio** - Radio button
14. **cmp-accordion** - Accordion
15. **cmp-tabs** - Tabs
16. **cmp-modal** - Modal dialog
17. **cmp-summary-box** - Summary box
18. **cmp-status-badge** - Status badge
19. **cmp-personal-area** - User dashboard

### Tier 3: Medium Priority (15 components)
20. **cmp-topics-grid** - Topics grid
21. **cmp-news-list** - News list
22. **cmp-event-list** - Event list
23. **cmp-service-list** - Service list
24. **cmp-admin-list** - Admin list
25. **cmp-doc-list** - Document list
26. **cmp-faq-accordion** - FAQ accordion
27. **cmp-sitemap-tree** - Sitemap tree
28. **cmp-detail-hero** - Detail hero
29. **cmp-related-content** - Related content
30. **cmp-tag-list** - Tag list
31. **cmp-share-buttons** - Share buttons
32. **cmp-timeline** - Timeline
33. **cmp-calendar-view** - Calendar
34. **cmp-map-view** - Map

### Tier 4: Low Priority (8 components)
35. **cmp-search-bar** - Search bar
36. **cmp-filter-panel** - Filter panel
37. **cmp-feedback-form** - Feedback form
38. **cmp-appointment-form** - Appointment form
39. **cmp-contact-form** - Contact form
40. **cmp-data-table** - Data table
41. **cmp-info-list** - Info list
42. **cmp-file-upload** - File upload

### Tier 5: Specialized (5 components)
43. **cmp-carousel** - Carousel
44. **cmp-gallery** - Gallery
45. **cmp-video-player** - Video player
46. **cmp-chart** - Chart
47. **cmp-infographic** - Infographic

---

## Implementation Timeline

### Phase 1: Foundation & Homepage (Weeks 1-2) 🟡 IN PROGRESS
**Dates**: April 1-14, 2026  
**Goal**: 100% HTML and visual parity for homepage

**Tasks**:
- ✅ Fix Livewire multiple root elements (9 files)
- ✅ Create research document
- ✅ Create roadmap
- ✅ Create GitHub templates
- ✅ Create team guide
- ⏳ Implement header component
- ⏳ Implement footer component
- ⏳ Implement hero component
- ⏳ Create tests.homepage.json
- ⏳ Achieve HTML parity
- ⏳ Achieve visual parity
- ⏳ Accessibility audit
- ⏳ Performance optimization

**Deliverables**:
- Header component (universal)
- Footer component (universal)
- Hero component (universal)
- Homepage JSON file
- Screenshot comparison analysis
- Accessibility audit report

---

### Phase 2: List Pages (Weeks 3-4)
**Dates**: April 15-28, 2026  
**Goal**: Implement 8 list-type pages

**Pages**:
- Argomenti
- Amministrazione
- Documenti e Dati
- Novità
- Servizi
- Eventi
- FAQ
- Mappa del Sito

**Components**:
- topics-grid
- news-list
- event-list
- service-list
- admin-list
- doc-list
- faq-accordion
- sitemap-tree

---

### Phase 3: Detail Pages (Weeks 5-6)
**Dates**: April 29 - May 12, 2026  
**Goal**: Implement 4 detail-type pages

**Pages**:
- Novità Dettaglio
- Servizio Dettaglio
- Evento Dettaglio
- Documento Dettaglio

**Components**:
- navscroll
- detail-hero
- related-content
- tag-list
- share-buttons
- rating-widget
- timeline

---

### Phase 4: Multi-Step Forms - Part 1 (Weeks 7-8)
**Dates**: May 13-26, 2026  
**Goal**: Implement appointment booking flow (8 pages)

**Flow**: 6-step appointment booking + confirmation

**Components**:
- steps-progress
- form-input
- form-select
- form-autocomplete
- form-radio-group
- form-checkbox
- form-date-picker
- form-time-picker
- summary-box
- confirmation-card

---

### Phase 5: Multi-Step Forms - Part 2 (Weeks 9-10)
**Dates**: May 27 - June 9, 2026  
**Goal**: Implement assistance and disruption flows (11 pages)

**Flows**:
- Assistance request (2 steps)
- Service disruption report (7 steps + personal area + list)

**Components**:
- file-upload
- privacy-consent
- captcha-widget
- personal-area
- submission-list
- status-badge

---

### Phase 6: Polish & Documentation (Weeks 11-12)
**Dates**: June 10-30, 2026  
**Goal**: Final polish, audits, complete documentation

**Tasks**:
- Accessibility audit (WCAG 2.1 AA)
- Performance optimization (Lighthouse >90)
- Documentation completion
- Testing (Pest + Browser)
- Code review
- Stakeholder approval

**Deliverables**:
- COMPONENT_CATALOG.md (47 components)
- BLOCK_TYPES.md (all block types)
- JSON_STRUCTURE.md (JSON schema)
- ACCESSIBILITY_AUDIT.md (WCAG compliance)
- PERFORMANCE_REPORT.md (Lighthouse scores)
- DEVELOPER_GUIDE.md (onboarding)

---

## Success Metrics

### Code Quality Metrics
- ✅ PHPStan Level 10: 0 errors
- ✅ Pint: PSR-12 compliance
- ✅ Test coverage: >80%
- ✅ DRY: No duplicate code
- ✅ KISS: Simple, readable code

### Design Quality Metrics
- ✅ HTML parity: 100% (inside `<body>`, excluding scripts)
- ✅ Visual parity: 100% (colors, spacing, typography)
- ✅ Responsive: Mobile (320px), Tablet (768px), Desktop (1200px)
- ✅ Accessibility: WCAG 2.1 AA compliance
- ✅ Lighthouse: Performance >90, Accessibility >90, Best Practices >90, SEO >90

### Documentation Quality Metrics
- ✅ Component catalog: 47 components documented
- ✅ Block types: All types documented
- ✅ JSON structure: Schema and examples
- ✅ Bidirectional links: All docs cross-referenced
- ✅ Master index: Complete and up-to-date

### Performance Metrics
- ✅ Page load time: <2s
- ✅ Time to Interactive: <3s
- ✅ Total bundle size: <500KB
- ✅ Unused CSS: <5%
- ✅ Image optimization: WebP, lazy loading

---

## Risk Management

### Technical Risks

#### Risk 1: Bootstrap Italia Dependency
**Risk**: Relying on CDN imports instead of Tailwind @apply  
**Probability**: Medium  
**Impact**: High  
**Mitigation**: 
- Enforce Tailwind @apply in code reviews
- Remove all CDN imports from layouts
- Document architecture decision (ADR #001)

#### Risk 2: Component Complexity
**Risk**: Components too complex, hard to maintain  
**Probability**: Medium  
**Impact**: Medium  
**Mitigation**:
- Start with Tier 1 components (simplest)
- Regular code reviews
- Refactor when complexity detected

#### Risk 3: Performance Degradation
**Risk**: Slow page loads, poor Lighthouse scores  
**Probability**: Low  
**Impact**: High  
**Mitigation**:
- Regular Lighthouse audits (weekly)
- Optimize images (WebP, lazy loading)
- Minimize CSS/JS bundle sizes
- Use Vite's code splitting

### Schedule Risks

#### Risk 1: Scope Creep
**Risk**: Adding features beyond 38 pages  
**Probability**: Medium  
**Impact**: High  
**Mitigation**:
- Strict adherence to roadmap
- Document "out of scope" items
- Create backlog for future phases

#### Risk 2: Component Reuse
**Risk**: Creating page-specific components instead of universal  
**Probability**: High  
**Impact**: Medium  
**Mitigation**:
- Code reviews enforce DRY
- Component catalog shows usage
- Refactor page-specific to universal

#### Risk 3: Testing Debt
**Risk**: Not writing tests, technical debt accumulates  
**Probability**: Medium  
**Impact**: High  
**Mitigation**:
- Test-driven development (write tests first)
- CI/CD requires tests passing
- Regular testing audits

---

## Documentation Structure

### Master Index Files
- `docs/MODULE_DOCS_INDEX.md` - Master index for all module docs
- `Themes/Sixteen/docs/THEME_INDEX.md` - Master index for theme docs
- `_bmad-output/DESIGN_COMUNI_INDEX.md` - BMad documentation index

### Architecture Docs
- `.planning/PROJECT.md` - Project overview and history
- `.planning/ROADMAP.md` - 12-week implementation plan
- `.planning/research/design-comuni-pages.md` - Page analysis (674 lines)
- `_bmad-output/design-comuni-architecture.md` - Architecture decisions
- `_bmad-output/design-comuni-block-analysis.md` - Block analysis (598 lines)

### Component Docs
- `Themes/Sixteen/docs/COMPONENT_CATALOG.md` - All 47 components
- `Themes/Sixteen/docs/BLOCK_TYPES.md` - All block types
- `Themes/Sixteen/docs/JSON_STRUCTURE.md` - JSON schema
- `Themes/Sixteen/docs/DESIGN_COMUNI_TEAM_GUIDE.md` - Team guide

### Quality Docs
- `Themes/Sixteen/docs/screenshots/` - Screenshot comparisons
- `Themes/Sixteen/docs/ACCESSIBILITY_AUDIT.md` - WCAG compliance
- `Themes/Sixteen/docs/PERFORMANCE_REPORT.md` - Lighthouse scores
- `Themes/Sixteen/docs/TESTING_STRATEGY.md` - Testing approach

### Developer Docs
- `Themes/Sixteen/docs/DEVELOPER_GUIDE.md` - Onboarding guide
- `Themes/Sixteen/docs/CONTRIBUTING.md` - Contribution guidelines
- `Themes/Sixteen/docs/CODE_CONVENTIONS.md` - Coding standards
- `.github/ISSUES_GUIDE.md` - GitHub issues guide

---

## GitHub Strategy

### Issues
**Templates Created**:
- `epic-*.md` - Epic issues (6 total)
- `component-implementation.md` - Component issues (47 total)
- `page-implementation.md` - Page issues (38 total)
- `adr-architecture-decision.md` - ADR issues

**Labels**:
- Type: `epic`, `component`, `page`, `adr`, `bug`, `documentation`
- Priority: `priority-high`, `priority-medium`, `priority-low`
- Status: `status-proposed`, `status-in-progress`, `status-review`, `status-done`
- Category: `design-comuni`, `frontend`, `backend`, `testing`, `accessibility`, `performance`

### Discussions
**Categories**:
- Architecture Decisions - RFC for major technical decisions
- Component Design - Proposals for new components
- Block Types - Discussion on JSON block structures
- Accessibility - WCAG compliance strategies
- Performance - Optimization techniques
- Q&A - General questions and troubleshooting

**First Discussion**:
- `adr-001-tailwind-apply.md` - Use Tailwind @apply instead of Bootstrap Italia CDN

---

## Current Status

### Week 1 Progress (April 1, 2026)

#### Completed ✅
1. **Research Document** (674 lines)
   - Analyzed all 38 pages
   - Identified 47 components
   - Mapped URLs (Design Comuni → FixCity)
   - Defined 5 architectural patterns

2. **Roadmap** (Comprehensive 12-week plan)
   - 6 phases defined
   - Success metrics established
   - Risk management documented
   - GitHub strategy defined

3. **GitHub Templates**
   - Epic issue template
   - Component issue template
   - Page issue template
   - ADR template
   - Issues guide

4. **Team Guide**
   - Quick start guide
   - Development workflow
   - Common tasks
   - Troubleshooting
   - Testing guide

5. **Bug Fixes**
   - Fixed 9 files with Livewire multiple root elements error
   - Files: `tests/[slug].blade.php`, `tests/design-comuni-[slug].blade.php`, `administration/*.blade.php`, `eventi/index.blade.php`, `news/[slug].blade.php`, `eventi/[slug].blade.php`

#### In Progress 🟡
1. **Header Component**
   - Blade component: 0% complete
   - Tailwind @apply: 0% complete
   - Alpine.js interactions: 0% complete
   - Tests: 0% complete
   - Documentation: 0% complete

2. **Footer Component**
   - Same status as header

3. **Homepage JSON**
   - Structure defined: 50% complete
   - Content blocks: 0% complete

#### Pending ⚪
1. Hero component
2. Topics grid component
3. News card component
4. Event card component
5. Service card component

---

## Next Steps

### Immediate (This Week)
1. **Complete Header Component** (2 days)
   - Create Blade component
   - Add Tailwind @apply styles
   - Add Alpine.js dropdown
   - Write tests
   - Document usage

2. **Complete Footer Component** (2 days)
   - Same as header

3. **Create Homepage JSON** (1 day)
   - Define blocks structure
   - Add content for all sections

4. **Achieve HTML Parity** (1 day)
   - Compare source vs target
   - Fix any differences
   - Document in screenshot analysis

5. **Achieve Visual Parity** (1 day)
   - Screenshot comparison
   - Fix colors, spacing, typography
   - Document fixes

### Week 2
1. **Accessibility Audit** (2 days)
   - Color contrast
   - Keyboard navigation
   - ARIA labels
   - Screen reader testing

2. **Performance Optimization** (2 days)
   - Lighthouse audit
   - Image optimization
   - CSS/JS minification
   - Lazy loading

3. **Documentation** (1 day)
   - Update component catalog
   - Update block types
   - Update master index

---

## Team

### Roles and Responsibilities

#### Project Lead
- Overall project management
- Stakeholder communication
- Risk management
- Decision making

#### Frontend Lead
- Component architecture
- Code reviews
- Quality assurance
- Mentoring junior developers

#### Backend Lead
- JSON structure
- CMS integration
- Database optimization
- API development

#### QA Lead
- Testing strategy
- Accessibility audits
- Performance testing
- Bug tracking

#### Documentation Lead
- Component catalog
- Block types documentation
- Master index maintenance
- Team guide updates

---

## Communication

### Daily
- Update `.planning/STATE.md` with current task
- Commit atomic changes with conventional commits
- Update GitHub issues with progress

### Weekly
- Team standup (Monday 10:00 CET)
- Sprint review (Friday 16:00 CET)
- Update roadmap if needed
- Create screenshot comparisons

### Per Phase
- Phase retrospective
- Update PROJECT.md with lessons learned
- Archive completed phase docs
- Celebrate milestones! 🎉

---

## Budget

### Development Time
- **12 weeks** × **40 hours/week** = **480 hours** total
- **6 phases** × **80 hours/phase** = **480 hours** total

### Infrastructure Costs
- **Development Server**: €100/month × 3 months = €300
- **Staging Server**: €50/month × 3 months = €150
- **Domain & SSL**: €50 one-time
- **Total**: €500

### Tools & Services
- **GitHub Pro**: €4/month × 3 months = €12
- **Lighthouse CI**: Free
- **Accessibility Tools**: Free
- **Total**: €12

### Grand Total: €512

---

## Conclusion

This project represents a significant investment in time and resources, but the payoff will be substantial:

### Benefits
1. **Reusable Components**: 47 universal components for future projects
2. **JSON-Driven CMS**: Flexible content management without code changes
3. **Tailwind Expertise**: Team becomes proficient in modern CSS
4. **Accessibility First**: WCAG 2.1 AA compliance built-in
5. **Performance Optimized**: Lighthouse scores >90
6. **Documentation Complete**: Comprehensive guides for future developers

### Long-Term Vision
This replication is not just about copying 38 pages. It's about:
- Building a **design system** for future municipal websites
- Creating a **CMS-driven architecture** that can be reused
- Establishing **best practices** for Laravel + Tailwind projects
- Demonstrating **accessibility-first development**
- Proving **performance optimization** is achievable

### Call to Action
Let's build something amazing together! 🚀

---

**Document Version**: 1.0  
**Last Updated**: April 1, 2026  
**Next Review**: April 8, 2026  
**Maintained By**: Project maintainers

---

## Appendix A: File Checklist

### Files Created (Phase 1 - Week 1)
- [x] `.planning/research/design-comuni-pages.md` (674 lines)
- [x] `.planning/ROADMAP.md` (Comprehensive 12-week plan)
- [x] `.github/ISSUE_TEMPLATE/epic-1-foundation-homepage.md`
- [x] `.github/ISSUE_TEMPLATE/adr-architecture-decision.md`
- [x] `.github/ISSUE_TEMPLATE/component-implementation.md`
- [x] `.github/ISSUE_TEMPLATE/page-implementation.md`
- [x] `.github/ISSUES_GUIDE.md`
- [x] `.github/DISCUSSIONS/adr-001-tailwind-apply.md`
- [x] `laravel/Themes/Sixteen/docs/DESIGN_COMUNI_TEAM_GUIDE.md`
- [x] `laravel/Themes/Sixteen/docs/DESIGN_COMUNI_PROJECT_SUMMARY.md` (this file)

### Files Modified (Phase 1 - Week 1)
- [x] `laravel/Themes/Sixteen/resources/views/pages/tests/[slug].blade.php` (Fixed Livewire error)
- [x] `laravel/Themes/Sixteen/resources/views/pages/tests/design-comuni-[slug].blade.php` (Fixed Livewire error)
- [x] `laravel/Themes/Sixteen/resources/views/pages/administration/aree.blade.php` (Fixed Livewire error)
- [x] `laravel/Themes/Sixteen/resources/views/pages/administration/organi.blade.php` (Fixed Livewire error)
- [x] `laravel/Themes/Sixteen/resources/views/pages/administration/uffici.blade.php` (Fixed Livewire error)
- [x] `laravel/Themes/Sixteen/resources/views/pages/eventi/index.blade.php` (Fixed Livewire error)
- [x] `laravel/Themes/Sixteen/resources/views/pages/news/[slug].blade.php` (Fixed Livewire error)
- [x] `laravel/Themes/Sixteen/resources/views/pages/eventi/[slug].blade.php` (Fixed Livewire error)

### Files To Create (Phase 1 - Week 2)
- [ ] `laravel/Themes/Sixteen/resources/views/components/blocks/header/main.blade.php`
- [ ] `laravel/Themes/Sixteen/resources/views/components/blocks/footer/full.blade.php`
- [ ] `laravel/Themes/Sixteen/resources/views/components/blocks/hero/default.blade.php`
- [ ] `laravel/config/local/fixcity/database/content/pages/tests.homepage.json`
- [ ] `laravel/Themes/Sixteen/docs/screenshots/homepage-header-comparison.png`
- [ ] `laravel/Themes/Sixteen/docs/screenshots/homepage-footer-comparison.png`
- [ ] `laravel/Themes/Sixteen/docs/screenshots/homepage-content-comparison.png`
- [ ] `laravel/Themes/Sixteen/docs/COMPONENT_CATALOG.md` (initial version)

---

## Appendix B: Quick Reference

### URL Mapping
```
Design Comuni: https://italia.github.io/design-comuni-pagine-statiche/sito/{page}.html
FixCity:       http://fixcity.local/it/tests/{page-slug}
```

### Component Naming
```
pub_theme::components.blocks.<type>.<variant>
Example: pub_theme::components.blocks.header.main
```

### JSON Structure
```json
{
  "slug": "tests.homepage",
  "title": "Homepage",
  "blocks": [
    {
      "type": "header",
      "view": "pub_theme::components.blocks.header.main",
      "data": { ... }
    }
  ]
}
```

### Build Commands
```bash
cd laravel/Themes/Sixteen
npm install
npm run build
npm run copy
```

### Clear Cache
```bash
php artisan view:clear
php artisan config:clear
php artisan cache:clear
```

---

**END OF DOCUMENT**
