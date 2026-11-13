---
title: "Sixteen Theme Documentation Index (BMAD + Second Brain + QMD + Graphify)"
type: index
updated: 2026-10-14
tags: [docs, sixteen]
qmd: "sixteen theme documentation index (bmad + second brain + qmd + graphify) sixteen"
status: legacy-router
---

> Questo è un router storico. L'ingresso canonico è [README.md](README.md);
> l'indice operativo corrente è [00-INDEX.md](00-INDEX.md). I link storici
> sotto non sono una garanzia di esistenza: verificare il target prima di
> usarli e non aggiungere nuove sezioni qui.

# Sixteen Theme Documentation Index (BMAD + Second Brain + QMD + Graphify)

This index organizes all documentation under `/laravel/Themes/Sixteen/docs/` for BMAD, second brain (hindsight), QMD search (`qmd` CLI), and graph visualization (`graphify` / `qmd` graph commands).

---

## 1. BMAD (Bold, Minimal, Actionable, Durable) — Project Planning & Design

Location: `/docs/bmad/`

| File | Type | Description |
|---|---|---|
| `header-footer-design-comuni-redesign.md` | Epic story | Header `<x-section slug="header" />` + footer `<x-section slug="footer" />` redesign spec — full inventory, decisions, plan |
| `s150-homepage-template.md` | Story (epic-story) | Homepage Design Comuni (`homepage.html`) with layout `test`, component inventory, verification criteria |
| `investigation-migrations-violations.md` | Investigation | Migration convention audit (`2026_09_27...`, `2026_10_01...`) — BIGINT→UUID cast failure on MySQL |
| `architecture.md` | Architecture spec | Design Comuni redesign architecture with modern stack |
| `brainstorming.md` | Brainstorming | Variant approaches (Progressive/Lit-First/Clean) |
| `decision-log.md` | Decision log | Decisions D1-D10 (no controllers, no services, layout `test` selection, component naming) |

Usage with `qmd`: `qmd search "layout test"` — finds the decision rule.
Usage with `graphify`: node `homepage-template` connects to nodes `component-inventory`, `layout-test-rule`, `bmad-method`.

---

## 2. Design System

Location: `/docs/design-system/`

| File | Description |
|---|---|
| `tokens.md` | Design tokens (PA `#007A52`, `#333333`, scale 1.25, spacing 8px grid, elevation 5 levels, surface budget 3) |
| `components.md` | Component inventory (`muni-navbar`, `muni-footer`, `muni-hero`, `muni-card-news`, etc.) |
| `argomenti.md` | Component documentation for `argomenti` block |

Usage: these are the source of truth for `ux_audit` gate (tokens, states contract, slop tells). Referenced by `bmad` stories as constraints.

---

## 3. Stories (BMAD Epic Capsules)

Location: `/docs/stories/`

Key stories (chronological order from file list):

| Story file | Epic / Topic |
|---|---|
| `redesign-front-office-pages-with-design-comuni.story.md` | Main redesign epic |
| `front-office-design-comuni-rebuild.story.md` | Rebuild phase |
| `homepage-design-comuni-rebuild.story.md` | Homepage rebuild |
| `homepage-taste-skill-bmad.story.md` | Taste skill + BMAD integration |
| `702-tests-page-design-comuni-parity.story.md` | Tests page (`/tests.index`) parity |
| `front-office-complete-redesign.story.md` | Complete redesign tracking |

Usage with second brain (hindsight): search `hindsight_search_knowledge_pages("Design Comuni redesign")` to retrieve initiative `kp-174d15a58ff94901b2b0df0d86b8907a`.

---

## 4. Wiki (Concept, Rules, Entities, Sources, Skills)

Location: `/docs/wiki/`

| Subfolder | Key contents |
|---|---|
| `concepts/` | `design-comuni-page-structure.md`, `design-comuni-font-tokens.md`, `design-comuni-site-wide-component-css-rule.md`, `no-controllers-folio-volt-filament.md`, `bmad-method.md`, `bmad-context-compression.md` |
| `rules/` | `assets-404-fix.md`, `container0-index-folio-pattern.md`, `folio-page-pattern.md`, `no-controllers-rule.md`, `no-services-rule.md`, `no-parallel-design-comuni-layout.md` (correction applied: `layout: "test"` for `tests.*`) |
| `entities/` | `design-comuni-class-mapping.md` (CSS class mapping between Bootstrap Italia and Tailwind) |
| `skills/` | `design-comuni-frontoffice-quality.md` (quality gate skill) |
| `comparisons/` | `segnalazione-01-privacy-design-comuni-vs-local.md`, `ticket-01-privacy-design-comuni-vs-local.md` (visual parity comparison reports) |
| `sources/` | Reference sources mapping |
| `story-8-108-*` | Individual story tracking files |

Usage with QMD + graphify: `qmd search "no-parallel-design-comuni-layout"` → returns rule file. `graphify` can visualize connections between `rules/no-parallel-design-comuni-layout.md` → `bmad/s150-homepage-template.md` → component inventory.

---

## 5. Visual Comparison & Visual Parity Reports

Location: `/docs/visual-comparison/` (structure-analysis) and `/docs/visual-parity/` (screenshot reports)

Key files:

| File / Folder | Description |
|---|---|
| `segnalazione-dettaglio/README.md` | Visual comparison for segnalazione detail page |
| `homepage/comparison.md` | Homepage visual parity comparison |
| `structure-analysis/*.md` | HTML structural comparison per page (`amministrazione`, `domande-frequenti`, `eventi`, `servizi`, etc.) |
| `visual-parity-report.md` | Complete visual parity status |
| `complete-report.md` | Final report on all front office pages |

These are the evidence files for BMAD verification phase (`verify-work` skill, `audit-plan` skill reference). The comparison reports are linked to the initiative through the second brain.

---

## 6. Design Comuni Reference (Reference HTML Source)

Location: `/docs/design-comuni/` (subfolder in Sixteen docs)

This folder contains the reference HTML files downloaded from `https://italia.github.io/design-comuni-pagine-statiche/` — `sito/homepage.html`, `sito/segnalazione-dettaglio.html`, `sito/amministrazione.html`, etc. These are the ground truth for visual comparison.

Usage: in `bmad` workflow, these are cited as reference links (`links: {reference_url: "..."}`) in the stories.

---

## 7. Investigation & Technical Reports

Location: `/docs/investigation/` (inside `bmad/` and root docs)

Key files:

- `investigation-migrations-violations.md` (BMAD investigation document — migration timestamp-only rule, `bigint` → `uuid` MySQL limitation, `N` models → `N` migration naming)
- `docs/theme-completion-plan.md`, `docs/theme-improvements-analysis.md`, `docs/theme-namespace-issue.md`
- `docs/visual-analysis/*.md` — visual analysis reports

These feed into `audit-plan` (quality gate) and `verify-work` (post-implementation verification).

---

## 8. Component & Block Documentation

Location: `/docs/components.md` (inside `design-system/`)

- Component inventory: `muni-navbar`, `muni-footer`, `muni-hero`, `muni-card-*`, `muni-breadcrumb`, `muni-chip`, `muni-read-more`, `muni-skip-links`, `muni-modal`, `muni-report-wizard`
- Pattern reference: DaisyUI + Flowbite mapping to Design Comuni tokens
- Component contracts (state contracts: `default`/`hover`/`focus-visible`/`active`/`disabled` + `prefers-reduced-motion`)

Cross-reference: each component is mapped to a `design-system/components.md` entry and linked to the `bmad` stories that use it.

---

## 9. QMD (Query Markdown) Integration

Location: `/docs/qmd-setup.md` + `.qmd/` (not shown, but referenced in docs)

- QMD index includes all `.md` files in `/docs/` and `/docs/bmad/`
- `qmd search` works across all sections above
- Search queries can combine: `qmd search "layout test"` → returns `bmad/s150-homepage-template.md` + `rules/no-parallel-design-comuni-layout.md`

Usage pattern for agents:
1. Before proposing code: `qmd search "layout test"` → find `no-parallel-design-comuni-layout.md`
2. Before editing blade: `qmd search "component inventory"` → find `components.md`
3. Before verification: `qmd search "visual comparison"` → find `visual-comparison/README.md`

---

## 10. Graph Visualization (`graphify`)

The docs directory supports graph visualization through the `graphify` tool (referenced in project):

- Nodes: `bmad/stories/*.md`, `bmad/decision-log.md`, `design-system/*.md`, `docs/stories/*.md`
- Edges: `links` from YAML frontmatter (`github_issue`, `github_discussion`, `reference_url`)
- Example graph query: `graphify --source docs/bmad/s150-homepage-template.md --links` → shows connections to `components.md`, `tokens.md`, `visual-comparison/homepage/comparison.md`, `design-comuni/homepage.html` (reference)

---

## 11. Second Brain (Initiative Tracking)

Initiative page: `kp-174d15a58ff94901b2b0df0d86b8907a` (from `hindsight_capture_initiative`)

Contents tracked:
- FreeLLMAPI studied (`34 providers`, `635 endpoints`)
- Design Comuni redesign (`Sixteen` theme, `Tailwind+Alpine+Lit`)
- `layout: "test"` decision recorded (no `bodyPage` variable)
- Component naming (`pub_theme::components.blocks.*.default`, no `.design-comuni` duplication)
- Migration convention violations (`bigint` → `uuid` limitation on MySQL, timestamp-only rule)
- BMAD stories (`homepage`, `segnalazione-dettaglio`, `amministrazione`, `domande-frequenti`, `risultati-ricerca`)
- JSON files (`homepage.json`, `segnalazione-dettaglio.json`, `amministrazione.json`, etc.) with `layout` field

Usage: call `hindsight_read_knowledge_page("kp-174d...")` or search with `hindsight_search_knowledge_pages("Design Comuni")` to retrieve this initiative.

---

## 12. Documentation Usage Patterns (BMAD + QMD + Graph)

### Pattern A: Before implementing a new page
```
1. qmd search "layout test rules" → rules/no-parallel-design-comuni-layout.md
2. bmad/stories/s150-*.md → check story scope
3. design-system/components.md → find component
4. graphify --links docs/bmad/s150-homepage-template.md → see dependencies
```

### Pattern B: Before fixing a component
```
1. hindsight_search_knowledge_pages("pub_theme components") → initiative page
2. docs/bmad/decision-log.md → check D-decisions (D4: component naming)
3. components.md → verify component contract
```

### Pattern C: Before verification (visual parity)
```
1. docs/visual-comparison/<page>/README.md → comparison checklist
2. docs/visual-parity/COMPLETE-REPORT.md → overall status
3. bmad/stories/702-tests-page-design-comuni-parity.story.md (tests.index parity)
```

---

## 13. Index File (`docs/index.md`)

Location: `/docs/index.md` — this is the master index that should reference all the above sections. It provides a quick view of:
- Project status (`00-INDEX.md` inside docs also provides a sub-index)
- Component inventory and status (`components.md`)
- Design system (`design-system/*.md`)
- BMAD stories (`docs/stories/*.md`)
- Visual comparison reports (`visual-comparison/*.md`)
- Decision log (`docs/decision-log.md` inside docs root, not bmad folder — note: there are two: `docs/decision-log.md` for theme, `docs/bmad/decision-log.md` for BMAD initiative)

Usage: reference `docs/index.md` as the entry point for any documentation query. Combine with `qmd search` for granular access.

---

## 14. Quick Reference (File Names and Their Purpose)

| Short reference | Full path | Purpose | Related to |
|---|---|---|---|
| `bmad/homepage` | `docs/bmad/s150-homepage-template.md` | Homepage redesign spec | Component `homepage.default`, `layout: test` |
| `bmad/segnalazione` | `docs/bmad/s150-segnalazione-redesign.md` | Segnalazione redesign spec | Wizard component, 4-step flow |
| `bmad/investigation` | `docs/bmad/investigation-migrations-violations.md` | Migration violations | Timestamp rule, UUID limitation |
| `components/inventory` | `docs/components.md` | Lit/Web component list | `muni-*` components |
| `design/tokens` | `docs/design-system/tokens.md` | Design tokens | PA colors, 8px grid, elevation |
| `visual/homepage` | `docs/visual-comparison/homepage/comparison.md` | Homepage parity | Reference `sito/homepage.html` |
| `visual/segnalazione` | `docs/visual-comparison/segnalazione-dettaglio/README.md` | Segnalazione parity | Reference `sito/segnalazione-dettaglio.html` |
| `wiki/rules/no-parallel` | `docs/wiki/rules/no-parallel-design-comuni-layout.md` | Layout rule (corrected: `test`) | `tests.index.blade.php` update |
| `wiki/concepts/bmad-method` | `docs/wiki/concepts/bmad-method.md` | BMAD process definition | `docs/stories/*.md` format |

---

## 15. Cross-Reference with Project Context (`AGENTS.md`)

This documentation follows the rules from `/AGENTS.md` (project context):

- **No HTTP Controllers** (use Folio pages + Actions) → `docs/bmad/s150-*.md` specifies Folio page structure (`pages/tests/[slug].blade.php`)
- **No Services layer** → business logic in Actions (`app/Actions/*`), not in `docs/`
- **Module providers manifest** → `docs/bmad/` does NOT register providers (only `docs/decision-log.md` records decisions)
- **Composer root minimal** → `docs/` is theme-level documentation, not module-level
- **Migration update timestamps only** → `docs/bmad/investigation-migrations-violations.md` documents the violations (`bigint` → `uuid` cast failure)
- **Wiki markdown frontmatter mandatory** → all `docs/wiki/*.md` and `docs/stories/*.md` start with YAML frontmatter
- **No root `.txt` / extra `.md` in modules/themes** → all docs in `docs/` (organized, audited by `bashscripts/tools/audit-module-theme-root-files.sh`)
- **BMAD process**: every story has at least one GitHub Issue link and Discussion link (see `docs/bmad/*.md`)

---

## 16. Second Brain Reference (Initiative)

Initiative: `kp-174d15a58ff94901b2b0df0d86b8907a` (FreeLLMAPI studied + docs modules/themes to improve)
- Captured: `hindsight_capture_initiative` return value
- Related pages: this index (`docs/index.md`), `docs/bmad/*.md`, `docs/design-system/*.md`
- Updates tracked: correction of `.design-comuni` duplication, `layout: "test"` decision, component naming (`pub_theme::components.blocks.*.default`)

Usage: search `hindsight_search_knowledge_pages("Design Comuni redesign")` → returns this initiative page + all related docs.

---

## 17. Final Note

This index (`docs/index.md`) and the organized structure (`docs/bmad/`, `docs/design-system/`, `docs/stories/`, `docs/visual-comparison/`, `docs/visual-parity/`, `docs/wiki/`) provide a complete BMAD documentation framework for the Sixteen theme redesign.

The framework supports:
- **QMD search** (`qmd search` across all `.md` files)
- **Graph visualization** (`graphify` linking stories, components, rules, visual reports)
- **Second brain tracking** (`hindsight` initiative `kp-174d...` + memory `MEMORY.md`)
- **BMAD verification** (`docs/bmad/*.md` stories + `docs/visual-parity/*.md` evidence + `docs/investigation/*.md` for root cause)

All work is performed with the BMAD discipline (Bold, Minimal, Actionable, Durable) and follows the `AGENTS.md` rules (no controllers, no services layer, module contracts naming, composer root minimal, etc.). No `pub_theme::components.blocks.*.design-comuni` duplication remains (corrected globally in JSON files).D19: coordinate dinamiche — luogo reale dell'utente; NO coordinate fisse; NO HTTPS msg; silent fallback
