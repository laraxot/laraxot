# Multi-Agent Team Coordination - Design Comuni Replication

> **Status**: Phase 1 Complete ✅ | Phase 2 Starting 🚀  
> **All Pages**: HTML structure 99% identical ✅  
> **Next**: CSS + Alpine.js implementation (Option C: Hybrid batch approach)

## Team Structure

### 👨‍💼 Coordinator (Main Agent)
- Maintains this coordination doc
- Tracks phase progress
- Resolves blockers
- Merges findings

### 🎨 CSS/Tailwind Team (1-2 agents)
**Focus**: Bootstrap Italia → Tailwind mapping + component-level styling

**Responsibilities**:
- Create `CSS_MAPPING.md` document
- Update component templates with Tailwind classes
- Create `faq-replication.css` with @apply rules
- Test responsive breakpoints
- Document CSS approach

**Output**:
- Modified: `laravel/Themes/Sixteen/resources/css/`
- Modified: `laravel/Themes/Sixteen/resources/views/components/blocks/`
- Created: `laravel/Themes/Sixteen/docs/implementation/CSS_MAPPING.md`

### ⚙️ Alpine.js Team (1-2 agents)
**Focus**: Interactions, animations, form handling

**Responsibilities**:
- Identify interaction patterns needed
- Create Alpine components
- Implement accordion, search, forms
- Test keyboard navigation
- Document Alpine approach

**Output**:
- Modified: `laravel/Themes/Sixteen/resources/js/`
- Created: Alpine component guides
- Created: `laravel/Themes/Sixteen/docs/implementation/ALPINE_PATTERNS.md`

### 🧪 Testing/QA Team (1 agent)
**Focus**: Validation, screenshots, comparisons

**Responsibilities**:
- Run visual regression tests
- Capture screenshots (desktop, tablet, mobile)
- Compare to reference design
- Document discrepancies
- Verify WCAG compliance

**Output**:
- Screenshots in `laravel/Themes/Sixteen/docs/implementation/SCREENSHOTS/`
- Test report: `laravel/Themes/Sixteen/docs/implementation/TEST_REPORT.md`
- Visual diff analysis per page

### 📚 Documentation Team (1 agent)
**Focus**: Analysis, insights, implementation guides

**Responsibilities**:
- Deep-dive component analysis
- Create per-page implementation guides
- Update coordination docs
- Maintain index files
- Create troubleshooting guides

**Output**:
- Analysis docs in `laravel/Themes/Sixteen/docs/implementation/`
- Updated: `laravel/Themes/Sixteen/docs/00-INDEX.md`
- Per-component guides

---

## Parallel Workflow

### Phase 2A: CSS Mapping (Days 1-2)
**CSS Team** creates detailed mapping:
- Bootstrap Italia classes → Tailwind equivalents
- Per-component styling plan
- Responsive utility mappings
- Color/typography system

**Produces**: `CSS_MAPPING.md`

### Phase 2B: Component Updates (Days 2-3)
**CSS Team** updates templates:
- Breadcrumb component
- Hero component
- Search component
- Accordion component
- Grid/Card components
- Form components
- Footer component

**After each component**: 
- CSS Team commits changes
- QA Team captures screenshots
- Coordinator updates COMPONENT_CHECKLIST.md

### Phase 3: Alpine.js Implementation (Days 3-4)
**Alpine.js Team** implements interactions:
- Accordion toggle + animations
- Search/filter functionality
- Form validation
- Modal/dialog patterns
- Responsive menu toggle

**Parallel**: QA Team tests each interaction

### Phase 4: Testing & Polish (Days 4-5)
**QA Team** comprehensive testing:
- Responsive breakpoints (320px, 768px, 1440px)
- Browser compatibility (Chrome, Firefox, Safari)
- Accessibility (WCAG AA)
- Performance (Lighthouse)
- Visual regression (all 32 pages)

**Documentation Team** finalizes docs:
- Consolidates findings
- Creates implementation guide
- Updates index files
- Documents best practices

### Phase 5: Build & Deploy (Day 5)
**Coordinator**:
- Merges all branches
- Runs final build: `npm run build && npm run copy`
- Verifies no errors
- Tests all 32 pages
- Tags release

---

## Communication Protocol

### Daily Standup (Every 15-20 min)
Each team posts update in GitHub issue comment:

```markdown
**[Team Name] - Status**
- ✅ Completed: [items]
- 🔄 In Progress: [items]
- ⏸️ Blocked: [blocker]
- 📌 Next: [next step]
- 🔗 Related: [issue/PR links]
```

### Progress Tracking
Update status in SQL every hour:
```bash
sqlite3 ~/.copilot/session-state/.../session.db \
  "UPDATE todos SET status = 'done' WHERE id = 'breadcrumb-css';"
```

### File Organization
```
laravel/Themes/Sixteen/docs/implementation/
├── STRATEGY.md                          (master plan)
├── PHASE_PROGRESS.md                    (daily updates)
├── CSS_MAPPING.md                       (CSS team output)
├── ALPINE_PATTERNS.md                   (Alpine team output)
├── COMPONENT_CHECKLIST.md               (per-component status)
├── TEST_REPORT.md                       (QA team output)
├── SCREENSHOTS/
│   ├── reference-*-*.png
│   └── local-*-*.png
└── PER_COMPONENT/
    ├── breadcrumb-guide.md
    ├── hero-guide.md
    ├── search-guide.md
    └── [etc]
```

---

## Dependency Management

### Strict Ordering
1. CSS Mapping MUST complete before component updates
2. Component updates MUST complete before Alpine implementation
3. Alpine implementation MUST complete before comprehensive testing

### Parallel Allowed
- CSS Team can work on multiple components simultaneously
- QA Team can start screenshots as components complete
- Documentation Team can start analysis immediately

### Conflict Resolution
If two agents modify same file:
1. **First team** commits & pushes
2. **Second team** pulls & rebases
3. **All teams** coordinate in issue comment
4. **Coordinator** verifies merge is correct

---

## Key Metrics & Success

### For CSS Team
- [ ] CSS_MAPPING.md complete (100% Bootstrap → Tailwind)
- [ ] All 7 core components updated with Tailwind classes
- [ ] No Bootstrap Italia classes in templates
- [ ] Responsive tested at 3 breakpoints
- [ ] Build succeeds: `npm run build`

### For Alpine Team
- [ ] All interaction patterns documented
- [ ] Accordion toggle working smoothly
- [ ] Search/filter functionality complete
- [ ] Form validation implemented
- [ ] Keyboard navigation working
- [ ] No console errors

### For QA Team
- [ ] Visual regression tests passing
- [ ] All 32 pages screenshot comparisons done
- [ ] < 5% visual differences from reference
- [ ] WCAG AA compliance verified
- [ ] Lighthouse score > 85

### For Documentation Team
- [ ] Implementation guides for all 7 components
- [ ] Troubleshooting guide created
- [ ] Best practices documented
- [ ] Index files updated
- [ ] Related links verified

---

## Escalation Path

### Blocker: CSS Not Matching Reference
1. CSS Team documents issue in `CSS_MAPPING.md`
2. QA Team creates screenshot showing diff
3. Coordinator reviews with team
4. Consider reference CSS more carefully
5. Iterate on Tailwind approach

### Blocker: Alpine Interactions Broken
1. Alpine Team debugs in browser console
2. Creates minimal test case
3. Documents issue + attempted solutions
4. Coordinator helps troubleshoot
5. Document solution for future reference

### Blocker: Build Fails
1. Coordinator checks build log
2. Coordinates with CSS and Alpine teams
3. Identify conflicting changes
4. Resolve and rebuild
5. Document prevention strategy

---

## Daily Checklist (Coordinator)

- [ ] Check all team status comments
- [ ] Update SQL todo status
- [ ] Verify builds running cleanly
- [ ] Check for merge conflicts
- [ ] Update PHASE_PROGRESS.md
- [ ] Identify and unblock any issues
- [ ] Prepare screenshots for comparison
- [ ] Update index/coordination docs

---

## Expected Timeline

| Phase | Duration | Start | End | Status |
|-------|----------|-------|-----|--------|
| Phase 1: Analysis | 2 hours | Day 0 | Day 0 | ✅ Done |
| Phase 2A: CSS Map | 4 hours | Day 0 | Day 1 | 🔄 Today |
| Phase 2B: Components | 6 hours | Day 1 | Day 2 | ⏳ Tomorrow |
| Phase 3: Alpine | 4 hours | Day 2 | Day 3 | ⏳ Day 3 |
| Phase 4: Testing | 4 hours | Day 3 | Day 4 | ⏳ Day 4 |
| Phase 5: Deploy | 1 hour | Day 4 | Day 4 | ⏳ Day 4 |

**Total Effort**: ~21 hours (parallelized to ~4-5 days with full team)

---

## Resources

- **Reference Design**: https://italia.github.io/design-comuni-pagine-statiche/
- **Local Implementation**: http://127.0.0.1:8000/it/tests/
- **Tailwind Docs**: https://tailwindcss.com/docs
- **Alpine Docs**: https://alpinejs.dev/
- **WCAG Guidelines**: https://www.w3.org/WAI/WCAG21/quickref/

---

**Created**: 2026-04-03 | **Coordinator**: Copilot CLI  
**Team Size**: 4-5 agents | **Duration**: 4-5 days with full parallelization
