---
title: "Front-office readiness and Cms Head brainstorming"
type: brainstorming-report
status: synthesized
theme: Sixteen
tags: [bmad, ecc, brainstorming, front-office, head, readiness]
qmd: "sixteen front office readiness guest authenticated cms head brainstorming"
related:
  - ../stories/front-office-production-readiness-audit.story.md
  - ../../../../Modules/Cms/docs/stories/head-sixteen-layout-migration.story.md
  - ../../../../Modules/Cms/docs/stories/head-sixteen-parity-closeout.story.md
  - front-office-production-readiness-plan.md
---

# Front-office readiness and Cms Head brainstorming

## Guiding question

How can Sixteen use the Cms Head contract without changing the user-visible experience, while proving production readiness for both guests and authenticated users?

## Session decisions

- The scope combines the existing Head migration and full front-office readiness work.
- The PO selected all eight BMAD techniques below.
- Existing in-progress stories are the work records; this report does not create duplicate stories or change their status.
- The owner docs, not root `bmad-output/`, hold this report and its plan.
- GitHub coordination: [Head SEO decision discussion](https://github.com/laraxot/theme_sixteen_fila5/discussions/93).
- Reported fixes and test results in linked stories are leads, not proof revalidated by this brainstorming.

## 5 Whys

1. **Why combine Head adoption and front-office readiness?** Metadata must remain consistent while both public and signed-in journeys are assessed.
2. **Why use a shared Head contract?** Separate theme and CMS renderers can diverge in page title, locale, indexing policy, and social metadata.
3. **Why is divergence material?** Users, search engines, assistive technology, and operators rely on stable page identity and predictable navigation.
4. **Why audit more than metadata and screenshots?** Production behavior also depends on authorization, localized content, assets, responsive states, and accessibility.
5. **Why document acceptance before implementation or closure?** The worktree contains overlapping in-progress work and historical claims; only current, scoped evidence can support a readiness decision.

**Insight:** Treat the initiative as a contract-and-evidence effort. Define owners, states, and measurable gates before claiming parity or readiness.

## SCAMPER

| Lens | Ideas | Impact / feasibility |
|---|---|---|
| Substitute | Replace theme-owned SEO output with Cms Head; replace implicit metadata assumptions with a documented input/fallback contract. | H / M |
| Combine | Join metadata, locale, theme defaults, and structured data in one Head contract; join guest/auth checks with responsive and accessibility checks in one matrix. | H / M |
| Adapt | Express public/private metadata policy through owner configuration; provide locale-aware fallbacks for missing optional data. | H / M |
| Modify | Expand parity from title/description to canonical, hreflang, social metadata, structured data, theme color, and asset/style order; test auth expiry and return navigation. | H / M |
| Put to another use | Reuse the route/state inventory as a recurring smoke checklist; reuse structured data for public previews while keeping private pages non-indexable. | H / M |
| Eliminate | Remove duplicate Head generation only after evidence; eliminate success claims without linked, reproducible proof. | H / H |
| Reverse / rearrange | Define the Cms Head contract first, then supply Sixteen context; run guest and authenticated checks separately before checking shared routes. | H / H |

## Mind map

```text
Front-office production readiness
├── Guest journeys
│   ├── Home, navigation, search, CMS pages
│   ├── Service/ticket lists and details
│   ├── Login, registration, recovery, consent, errors
│   └── Localized links and public SEO metadata
├── Authenticated journeys
│   ├── Session entry, expiry, return path, sign-out
│   ├── Ticket creation, confirmation, tracking
│   ├── Personal area, followed items, settings, notifications
│   └── Private data and authorization boundaries
├── Head integration
│   ├── Cms owns metadata generation and policy
│   ├── Sixteen owns layout, theme behavior, and visual evidence
│   └── Fixcity/User own domain and identity decisions
└── Evidence
    ├── Route × locale × auth-state inventory
    ├── Head DOM diff and browser/network checks
    ├── Responsive and WCAG evidence
    └── Owner-scoped Pest/build/release gates
```

## Reverse brainstorming

| Failure to create | Preventive control |
|---|---|
| Lose, duplicate, or contradict title, description, robots, canonical, or social tags. | Maintain a field-by-field migration map and compare rendered output by route and locale. |
| Expose user-specific data or private metadata to guests. | Define expected access and redirects per route; test guest, authenticated, expired-session, and error states. |
| Verify only a desktop happy path. | Check key journeys at 375, 768, and 1440 px, keyboard navigation, focus, contrast, zoom/reflow, and automated accessibility. |
| Break language switching or fallbacks. | Inventory localized content and links; test supported locales and missing-translation behavior. |
| Pass locally while deploy assets are missing. | Verify built manifest, public asset paths, and production webroot rather than only the development server. |
| Contaminate results with shared browser or storage state. | Isolate browser contexts and fixtures; record locale, viewport, origin, and auth setup for each run. |
| Treat an agent launch or stale status as completion. | Record owner, file scope, handoff, and evidence; distinguish reported from independently verified work. |
| Call skipped tests green or reuse historical results. | Record exact command, result, environment, and artifacts; label blocked or unrun checks unverified. |
| Break missing/null metadata behavior. | Specify precedence, nullable guards, escaping, and safe defaults; test empty, partial, localized, and populated data. |
| Ship inconsistent shared and page-specific Head output. | Test shared layouts and page overrides together; reject duplicate/conflicting tags. |

## Six Thinking Hats

- **White — evidence:** Route count, live baseline, current API behavior, and guest/auth coverage must be measured; historical story data is not current proof.
- **Red — user confidence:** Familiar appearance and predictable signed-in access matter; explain visible or metadata differences rather than treating them as incidental.
- **Black — risks:** Duplicate tags, stale canonical/robots behavior, script/style ordering changes, auth leaks, locale gaps, and incomplete coverage can block release.
- **Yellow — benefits:** One metadata pipeline can reduce drift if parity and both access states are demonstrated.
- **Green — options:** Use route/state matrices, representative DOM comparisons, explicit fallbacks, and separate owner-level evidence.
- **Blue — direction:** Keep Head logic in Cms, presentation and browser evidence in Sixteen, and domain/auth decisions with their actual owner. Close decisions before declaring parity.

## Starbursting

| Dimension | Questions and evidence |
|---|---|
| Who | Which guest and authenticated roles/journeys are in scope? Who owns Head, identity, ticket behavior, and final sign-off? |
| What | Which metadata, layouts, routes, errors, empty states, and shared components define “full”? Which prior behavior must be preserved? |
| When | What baseline is captured before a change? Which gates must pass before parity or production sign-off? What changes trigger revalidation? |
| Where | Which routes, locales, tenants, origins, and viewports are covered? Are assets resolved from the actual public webroot? |
| Why | Which user or operator risk does each criterion prevent? Which differences are deliberate and who approved them? |
| How | What is metadata precedence/fallback? How are auth fixtures isolated? How are browser, Pest, accessibility, and deploy results recorded? |

## SWOT

| Strengths | Weaknesses |
|---|---|
| Approved direction and existing owner stories provide a starting point. | Multiple in-progress files make ownership and current status unclear; test claims have not been independently rechecked here. |
| Cms and Sixteen already have distinct ownership boundaries. | Full route/state coverage and current production baseline still need a canonical evidence matrix. |

| Opportunities | Threats |
|---|---|
| Reconcile existing work, clarify coverage, and make sign-off evidence repeatable. | Duplicate edits, stale claims, or unresolved product decisions can lose work or mask regressions. |
| Make accessibility, privacy, localization, and asset readiness release criteria. | A dev-only green result may not represent the deployment environment. |

**Mitigations:** inventory in-progress work before edits; link evidence rather than rewriting concurrent stories; gate readiness on explicit guest/auth coverage and environment-specific results.

## Brainwriting ideas

1. Build a route-to-metadata migration map. **H / M**
2. Keep an evidence ledger separating verified facts, historical reports, assumptions, and open questions. **H / H**
3. Inventory guest/auth access, redirects, and outcomes per route. **H / H**
4. Specify missing, null, and partial Head data fallbacks. **H / M**
5. Define Head preflight/postflight checks for required fields and representative renders. **H / M**
6. Track localized route and link destinations, including query context. **H / M**
7. Cover sign-in, expiry, sign-out, and return-navigation transitions. **H / M**
8. Use a responsive/accessibility checklist at 375, 768, and 1440 px. **H / M**
9. Record deploy prerequisites, failure signals, and safe forward recovery steps. **H / M**
10. Link every story to its owner and evidence without treating status as proof. **M / H**

## Actionable insights

1. **Head parity is a contract, not a visual-only check.** Compare required fields and theme boot behavior across representative paths and locales.
2. **The audit must be state-aware.** A guest crawl cannot prove authenticated behavior or privacy boundaries.
3. **Owners remain separate.** Cms owns Head generation; Sixteen owns presentation; domain and identity behavior stays with their modules.
4. **Evidence needs provenance.** Keep exact command, environment, route, state, viewport, result, and artifact.
5. **Open product decisions block closure.** In particular, D-7 about canonical/hreflang on `noindex` routes remains unresolved in the linked Cms story.

## Next BMAD steps

- Follow the [Sixteen production-readiness plan](./front-office-production-readiness-plan.md).
- Follow the [Cms Head adoption plan](../../../../Modules/Cms/docs/bmad/head-component-adoption-plan.md).
- Continue the existing [Sixteen audit story](../stories/front-office-production-readiness-audit.story.md) and [Cms Head story](../../../../Modules/Cms/docs/stories/head-sixteen-layout-migration.story.md); do not create duplicates.
