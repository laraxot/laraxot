---
title: "Sixteen front-office production readiness plan"
type: bmad-plan
status: active
theme: Sixteen
tags: [bmad, ecc, front-office, production, accessibility, guest, authenticated]
qmd: "sixteen front office production readiness plan guest authenticated audit"
related:
  - ../stories/front-office-production-readiness-audit.story.md
  - ../stories/auth-components-regression-restore.story.md
  - front-office-readiness-brainstorming.md
  - ../../../../Modules/Cms/docs/bmad/head-component-adoption-plan.md
  - ../../../../Modules/Cms/docs/stories/head-sixteen-layout-migration.story.md
  - ../../../../Modules/Cms/docs/stories/head-sixteen-parity-closeout.story.md
---

# Sixteen front-office production readiness plan

## Purpose

Establish repeatable evidence for the public FixCity front office in guest and authenticated contexts, and coordinate the Sixteen visual layer with the Cms Head migration. This plan does not certify the current worktree or production environment.

## Owner boundaries

| Area | Owner |
|---|---|
| Shared Head component, SEO metadata, robots/canonical/hreflang contract | Cms |
| Layout composition, theme assets, responsive behavior, accessibility and browser parity | Sixteen |
| Ticket/report workflow and user-visible domain outcomes | Fixcity |
| Login, identity, session, permissions and personal data access | User and the owning module |
| Cookie-consent policy and localized legal copy | Gdpr and the content owner |
| Map behavior and geographic data | Geo |

Filament Blueprint applicability: Sixteen has no Filament Resource ownership. Admin/content Builder changes remain with their Cms or domain owner and follow the [Cms Filament Blueprint](../../../../Modules/Cms/docs/bmad/filament-blueprint.md).

## Coverage model

Build and maintain a route matrix with one row per meaningful journey, not an assumed exhaustive URL count. Record locale, guest/auth state, expected access or redirect, page state, owner, viewport evidence, and result.

Include at least:

- Public home, navigation, search, CMS information pages, service/ticket listings and details.
- Account entry, registration, verification and password recovery.
- Ticket creation, validation, upload, confirmation and tracking.
- Personal-area practices, followed items, settings and notifications.
- Comments/ratings where present, consent, localized error pages, sitemap and public links.
- Missing, empty, loading, unauthorized, expired-session and not-found states where applicable.

Check all supported locales `it`, `en`, `de`, and `es` where the route is expected to exist. Do not equate a page response of 200 with correct content, access, localization, or usability.

## Delivery waves

| Wave | Work | Owner | Evidence |
|---|---|---|---|
| 0 — Reconcile | Preserve the existing audit and Head stories; identify concurrent changes, owners, locks, environment, and current evidence. | Sixteen lead + Cms | Inventory references the existing stories and labels each claim verified, historical, inferred, or open. |
| 1 — Define | Finalize route × locale × auth-state matrix, expected redirects and the visual/metadata contract. Resolve product decisions that block parity. | Sixteen + Cms + domain owners | Matrix and approved decisions linked from the stories and GitHub discussion. |
| 2 — Guest sweep | Check HTTP outcome, links, metadata, assets, locale, console/network, overflow, and key user journeys. | Sixteen | Reproducible browser report with route, locale, viewport, and artifacts. |
| 3 — Authenticated sweep | Use an isolated test account/fixture; cover login, protected content, ticket journeys, personal area, expiry, and logout. | Sixteen + User/Fixcity | Evidence proves expected access/redirect behavior and absence of cross-user/private-data exposure. |
| 4 — UX and release gates | Verify responsive, keyboard, accessibility, builds, owner tests and destination-environment configuration. | Sixteen + relevant owners | Each gate has exact command/result or is explicitly blocked/unverified. |
| 5 — Closeout | Fix only evidence-backed defects in their owner scope; rerun affected checks; update stories and this plan. | Owner of each defect | All critical criteria pass or the release remains blocked with named residual risks. |

## Acceptance gates

- **Routes:** Every in-scope route/state has an expected result; no unexplained 5xx, accidental soft-404, or broken internal destination.
- **Authorization:** Guest redirects and authenticated access match owner policy; no private content or account-specific metadata is exposed to another user.
- **Head/SEO:** One coherent document head, correct localized title/description/robots/canonical/hreflang/social/schema output, and only approved differences from baseline. Cms D-7 remains a blocker until resolved.
- **Assets:** Critical CSS, JavaScript, icons, fonts, and images resolve from the actual public webroot and deployment artifact, not only from the development server.
- **Responsive:** Critical journeys are checked at 375, 768, and 1440 px; no unintended horizontal overflow or clipped essential action.
- **Accessibility:** Automated axe plus keyboard/manual checks cover names, roles, focus, contrast, state, zoom/reflow, and reduced motion where applicable. A scan is evidence, not proof of full WCAG compliance.
- **Localization:** Visible copy, page language, labels, links, and fallbacks match the URL locale or are explicitly documented as unavailable.
- **Quality:** Relevant Pest, Blade/view compilation, theme build, static analysis, and production gates are run in their documented contexts. Exact failures and skips remain visible.
- **Release decision:** “Production ready” is not used while any required gate is unrun, D-7 is open, or target-environment behavior is unverified.

## Evidence and coordination rules

### Current execution evidence — 12 November 2026

- Production guest smoke checks returned `200` for `/it` and `/it/auth/login`.
- The live axe sweep exposed one serious, shared footer defect: the footer home link used `aria-label="FixCity"` while also containing the visible `FixCity` heading. The shared footer now relies on that visible heading as its accessible name. The deployed site must be rebuilt/deployed before the live check can turn green.
- Historical baseline: the authenticated browser sweep was not certified because no isolated `FIXCITY_STORAGE_STATE` fixture was available and the local application server was not running.
- Owner-scoped tables (`users`, `socialite_users`, `ticket_subscribers`) belong to the `user` connection/database; production migration status and config cache still require a server-side read-only check.

The existing audit story reports earlier URL sweeps, fixes, and test results. Treat those as historical reports until the exact checks are rerun against the current tree. The worktree already contains extensive concurrent modifications; do not overwrite or revert them. Check ownership and locks before touching a shared file, and append verification findings rather than silently rewriting another session's record.

The Ruflo claims service returned `policy-anchor-log-missing` in this session. That is a coordination-tool blocker, not evidence that no other claim exists. Local file-lock checks were free when inspected; repeat them immediately before shared edits.

Use isolated browser state and credentials that are not committed or written into reports. Browser MCPs may be shared; if a session is occupied, use a project-local isolated browser runner and report the exact setup. Do not create or clean databases destructively.

## Session decisions and discussion

- The current pass is documentation-first and reuses the existing audit and Cms stories; it does not create a duplicate audit story.
- The PO selected all eight brainstorming techniques; see the [synthesized report](./front-office-readiness-brainstorming.md).
- The differences and open Head decision are recorded in [GitHub discussion #93](https://github.com/laraxot/theme_sixteen_fila5/discussions/93), linked from the related stories.
- No reported code/test result is re-certified by this plan.

## Owner stories

- [Front-office production-readiness audit](../stories/front-office-production-readiness-audit.story.md)
- [Cms Head migration](../../../../Modules/Cms/docs/stories/head-sixteen-layout-migration.story.md)
- [Head parity closeout and D-7](../../../../Modules/Cms/docs/stories/head-sixteen-parity-closeout.story.md)

## Verification update — 12 November 2026

- `php artisan test Themes/Sixteen/tests/Unit --compact`: rerun after removing the obsolete `ComuneControllerTest`; the current Sixteen contracts are the source of truth for CMS/Folio routes.
- `npm run lint`: passes with no errors after replacing the modal ternary side effect with an explicit branch.
- `npm run build`: passes; Vite emitted the production manifest and assets. The generated asset set still requires deployment and a live cache-busting smoke check.
- The old `ComuneControllerTest` asserted removed `comune.*` routes and non-existent `Modules\\Fixcity\\App\\Models` classes. It was deleted as a stale duplicate of `ComunePagesTest`, not skipped to hide a product failure.
- Remaining release blockers are environmental: authenticated browser state, local app server, deployed asset verification, and the Cms Head D-7 decision.

### Fresh isolated browser verification

The previous authenticated-fixture note is superseded for local evidence by a fresh,
isolated scratch fixture (server `127.0.0.1:8091`, storage state kept outside the
repository). The strict axe suite was rerun serially:

- `45 passed`, `1 skipped`, `0` axe violations, across guest and authenticated pages,
  the ticket wizard and personal area at 375, 768 and 1440 px.
- The single skip is the optional mobile ticket-filter interaction because the current
  page does not render its trigger; opening/focus/Escape behavior therefore remains
  explicitly unverified, not green by implication.
- The authenticated fixture had a verified email, so the ticket wizard reached its
  map and summary steps. This removes the earlier fixture-induced `/auth/verify` and
  `429` noise from the browser evidence; it does not certify production.

The route sweep completed with the rate-limit exception recorded below. Production assets, target
database migrations/config cache and Cms Head D-7 remain release blockers. The local
audit helper was also corrected to derive screenshot output from `__dirname` and to
accept `FIXCITY_BASE_URL`; the stale homepage assertion and non-recursive Folio test
file scan were corrected and their focused suite passes (`16 tests, 175 assertions`).

The completed sweep sampled 248 route/viewport combinations and stopped with eight
`429` responses, all on localized `/auth/verify` pages. Those pages were not measured;
the responses are rate-limiter noise from the shared local fixture, not a functional
pass. A production decision must rerun this subset after the limiter window or with a
dedicated test account/IP and record the result separately.

### Live production check — 12 November 2026

Una verifica guest contro `https://fixcity.eu` ha confermato `200` per home, login,
lista ticket e tracking; `/tickets/create` e `/auth/verify` ridirigono correttamente a
login senza sessione; `/tickets/1` restituisce il 404 applicativo atteso. Tuttavia la
suite axe live ha fallito tutti i 24 casi guest (8 pagine × 3 viewport) per la stessa
violazione serious `label-content-name-mismatch`: il deploy live contiene ancora
`aria-label="FixCity"` sul link che contiene il titolo visibile `FixCity`.

La correzione è già presente nel sorgente del footer e passa sulla fixture locale, ma
non è ancora presente nell'ambiente live. È quindi necessario pubblicare il codice
aggiornato e svuotare/ricompilare le view prima di ripetere la verifica production.

Il controllo asset live è invece verde: il manifest Sixteen, lo sprite Design Comuni,
`assets/xot/header-actions-wrap.css` e `/api/tickets/geojson` hanno restituito `200`
con content type coerente.

L'accesso SSH read-only tentato con l'utente applicativo `ploi` sull'IP risolto da
`fixcity.eu` è stato rifiutato (`Permission denied (publickey)`); nessuna modifica
remota è stata quindi eseguita. Il deploy resta un'azione del gestore con accesso al
server, da verificare poi con il comando Playwright riportato nel runbook Xot.

La sola correzione runtime del footer è stata isolata e pubblicata su `laraxot/dev`
nel commit `3fb6e00b40`. Il controllo live successivo ha ancora rilevato il markup
precedente: il push non attiva un deploy Ploi automatico in questo repository.

### Hardening aggiornato — 12 novembre 2026

- L'audit integrale ha riprodotto un errore applicativo nei flussi di creazione e
  cancellazione ticket: `Ticket` dichiarava `deleted_at` e veniva chiamato con
  `withTrashed()`, ma non usava `SoftDeletes`. Il model ora abilita il trait e i
  test mirati non riproducono più quell'errore.
- L'audit deploy ha trovato una riga distruttiva in `deploy.sh` che cancellava
  `database/migrations/*`; è stata rimossa e la regola è stata annotata nel runbook
  Xot. Il deploy staging/live resta da verificare con accesso al server.
- Il risultato locale resta distinto dal risultato live: il footer corretto, la
  cache delle view e lo schema/configurazione `user` non sono ancora certificati
  sull'ambiente pubblico.
