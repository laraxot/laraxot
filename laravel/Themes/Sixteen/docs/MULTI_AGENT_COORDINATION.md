# Multi-Agent Coordination: FAQ Visual Replication

## Task Context
Replicate https://italia.github.io/design-comuni-pagine-statiche/sito/domande-frequenti.html locally using Tailwind CSS + Alpine.js (no Bootstrap Italia).

## Parallel Work Areas

### 🎨 Agent 1: CSS/Styling Lead
**Focus**: Tailwind CSS implementation, responsive design, visual polish

**Responsibilities**:
- Analyze reference Bootstrap Italia CSS
- Map to Tailwind equivalents
- Implement hero, accordion, search, contacts styling
- Test responsive breakpoints
- Document CSS changes in `FAQ_REPLICATION_STATUS.md`

**Outputs**:
- Modified files in `laravel/Themes/Sixteen/resources/css/`
- Tailwind utility classes applied
- Screenshots showing visual matching

**Progress Tracking**:
- Update `laravel/Themes/Sixteen/docs/analysis/visual-diff-*.md`
- Mark CSS todos as complete in SQL

### ⚙️ Agent 2: Alpine.js/Interactions Lead
**Focus**: JavaScript interactions, animations, user experience

**Responsibilities**:
- Implement accordion toggle logic
- Add keyboard navigation
- Implement search filtering (if needed)
- Smooth expand/collapse animations
- Feedback component interactions

**Outputs**:
- Modified files in `laravel/Themes/Sixteen/resources/js/`
- Alpine.js components with proper state management
- Demo of all interactions working

**Progress Tracking**:
- Update interaction status in `FAQ_REPLICATION_STATUS.md`
- Mark Alpine todos as complete in SQL

### 🧪 Agent 3: Testing/Validation Lead
**Focus**: Quality assurance, accessibility, cross-browser testing

**Responsibilities**:
- Create visual regression tests
- Test all interactions across browsers
- Verify accessibility (WCAG compliance)
- Performance profiling
- Document testing results

**Outputs**:
- Test cases in `laravel/Themes/Sixteen/tests/`
- Browser compatibility report
- Accessibility audit report

**Progress Tracking**:
- Update `FAQ_REPLICATION_STATUS.md` with test results
- Link to test artifacts

### 📚 Agent 4: Documentation/Analysis Lead
**Focus**: Understanding, documentation, analysis

**Responsibilities**:
- Deep analysis of reference design
- Screenshot capture and annotation
- Document component structure
- Create implementation guide
- Maintain coordination in this file

**Outputs**:
- Visual diff analysis (`laravel/Themes/Sixteen/docs/analysis/`)
- Screenshots with annotations
- Implementation guide
- Component mapping document

**Progress Tracking**:
- Update visual analysis status
- Update implementation guide
- Keep this file synchronized

---

## Synchronization Points

### Daily Standup (Every 15-20 minutes)
Each agent updates their status comment in the shared issue/discussion:
```markdown
**[Agent Name] - Status Update**
- ✅ Completed: [item 1, item 2]
- 🔄 In Progress: [item 3]
- ⏸️ Blocked By: [dependency]
- 📌 Next: [what's next]
```

### Merge/Integration Schedule
- **CSS Styling**: Merge when responsive design verified
- **Alpine.js**: Merge when all interactions tested
- **Testing**: Continuous integration
- **Docs**: Merge after each phase completes

### Conflict Resolution
If two agents modify the same file:
1. **First complete**: Commits changes
2. **Second agent**: Fetches latest, rebases, resolves conflicts
3. **Communication**: Update status comment with resolution

---

## Shared Artifacts

### Configuration
- Base: `laravel/config/local/fixcity/database/content/pages/tests.domande-frequenti.json`
- Do not modify (read-only for all agents)

### Content Blocks
- Breadcrumb, Hero, Search, Accordion, Feedback, Contacts
- All HTML generated from JSON config
- Component templates: `laravel/Themes/Sixteen/resources/views/components/blocks/`

### Documentation Hub
```
laravel/Themes/Sixteen/docs/
├── FAQ_REPLICATION_STATUS.md          (← UPDATE THIS)
├── analysis/
│   ├── visual-diff-desktop.md
│   ├── visual-diff-tablet.md
│   └── visual-diff-mobile.md
└── screenshots/
    ├── reference-*.png
    └── local-*.png
```

---

## Git Workflow

### Branch Names
```
faq-css-styling
faq-alpine-interactions
faq-testing
faq-docs-analysis
```

### Commit Message Format
```
[FAQ] [Component] Brief description

- Detailed changes
- Links to related findings
- References to todo items

Co-authored-by: Copilot <...>
```

### Pull Request Template
```markdown
## [FAQ] [Component] - [Brief Title]

### Changes
- [ ] CSS changes
- [ ] Alpine.js changes
- [ ] Tests added
- [ ] Docs updated

### Related Issues/Todos
- Closes #123
- Relates to: [todo-id]

### Visual Changes
[Screenshots before/after]

### Testing
- [ ] Desktop (1440px)
- [ ] Tablet (768px)
- [ ] Mobile (375px)
```

---

## Dependency Graph

```
Phase 1: HTML Verification ✅
  └─ Extract & compare structures
  └─ Document block types

Phase 2: Visual Analysis 🔄
  ├─ Screenshot capture (Agent 4)
  ├─ CSS analysis (Agent 1)
  └─ Interaction review (Agent 2)

Phase 3: Implementation (Parallel)
  ├─ Agent 1: Tailwind CSS styling
  ├─ Agent 2: Alpine.js interactions
  └─ Agent 4: Docs update

Phase 4: Testing & Validation
  └─ Agent 3: QA & accessibility
  └─ All: Integration testing

Phase 5: Documentation
  └─ Agent 4: Final docs
  └─ All: PR reviews
```

---

## Communication Channels

1. **GitHub Issues**: Main coordination
   - Issue: `FAQ: Visual Parity for domande-frequenti`
   - Format: Status comments with tags `@agent-1`, `@agent-2`, etc.

2. **Shared Docs**:
   - `FAQ_REPLICATION_STATUS.md` - Main reference
   - `laravel/Themes/Sixteen/docs/analysis/` - Findings

3. **Code Comments**:
   - Mark sections with `// @agent-1` if needing specific attention
   - Use `// TODO:` for next steps

---

## Error Handling

### If CSS Changes Break Styling
1. **Agent 1** creates new branch for fix
2. **Agent 3** validates fix doesn't break interactions
3. **Agent 4** documents workaround in status
4. All continue with parallel work

### If Alpine.js Interactions Fail
1. **Agent 2** creates minimal test case
2. **Agent 3** debugs in browser
3. **Agent 1** checks for CSS conflicts
4. Document findings in `FAQ_REPLICATION_STATUS.md`

### If Dependency Issues
1. Agent tags blocking issue in status update
2. Determine which agent can unblock
3. Prioritize unblocking work
4. Update status with new timeline

---

## Success Metrics

- ✅ HTML structure matches (90%+)
- ✅ Visual appearance identical at all breakpoints
- ✅ All interactions smooth and responsive
- ✅ No Bootstrap Italia classes present
- ✅ WCAG AA accessibility compliance
- ✅ Tests passing (visual regression)
- ✅ Documentation complete
- ✅ Build succeeds (`npm run build`)

---

## Escalation Path

If task becomes blocked:
1. **First**: Check shared docs/issue for other agents' findings
2. **Second**: Document blocker in status update
3. **Third**: Tag relevant agent or ask in issue
4. **Final**: Escalate to project coordinator

---

**Document Status**: Active  
**Last Updated**: 2026-04-03  
**Coordination Model**: Parallel agents with async updates
