---
title: "log"
type: note
tags: [documentation]
created: 2026-09-26
updated: 2026-10-26
qmd: "log"
issues: []
discussions: []
status: draft
---

## [2026-10-26] header | slim: "Demo" come regione, avatar come il riferimento (D35)

- Studio del repo italia/design-comuni-pagine-statiche: kit Handlebars, markup fedele e validato, contenuto separato, stile minimo (solo colori); in `cmp-header` solo `logged` e `home` variano. Il nostro equivalente: Blade = vestito, `header.json` = contenuto, `config('ente')` = dato reale che vince.
- Incidente: `header.json` e' tornato con marker `.merge_file_*` alle 20:51 (dopo la risoluzione delle 20:49), JSON non valido e fascia senza regione; risolto di nuovo tenendo il lato "Demo". Verificare sempre `json_decode` dopo un sync.
- Regressione trovata dalle misure: cerchio bianco con icona bianca (invisibile) e hover #00966a con testo 3,8:1. Corretti con le misure del riferimento (24px, icona verde 18px, hover #006342).
- Bug di accessibilita' trovato dal test rosso: con l'ente che vince, `aria-label` e `title` dicevano ancora "Demo" (WCAG 2.5.3). `RegionPortalLinkLabels::resolve` ora segue il nome dell'ente.
- Link: [decision-log D35](../bmad/decision-log.md), [studio e misure](../bmad/header-design-comuni-study.md).

## [2026-10-26] git + fo | audit dei marker di merge nel front office: 5 file, entrambi i lati studiati

- Causa: marker `.merge_file_*` committati nei commit "." dello script di sync (`3d5395e51d`, `732357ada8`). `git status --unmerged` vuoto non prova che i file runtime siano puliti.
- Risoluzione per file: `footer/v1.blade.php` = **terza via** (markup Design Comuni dell'incoming + etichette `contact.labels` di `footer.json` dal current + DPO come "Ruolo (Nome)"); `ticket-list/design-comuni.blade.php` = current (DCP-289: `$phoneItems` non piu' usato); `pages/blog|events/index.json` = current (`ente: toll_free`); `sections/header.json` = current (`region.name/url` vuoti rimossi, poi superato dalla decisione "Demo" nel decision-log).
- Regressione trovata: prendere l'incoming puro del footer faceva leggere `pub_theme::footer.*`, che esiste solo in italiano. In de il footer mostrava "Telefono:" e orari/numero verde senza etichetta. Test rosso poi verde: `FooterV1DesignComuniStructureTest` + `EnteDataFrontOfficeTest` = 58 test, 729 asserzioni.
- Incidente: questo log era stato sovrascritto da una sessione parallela (1422 righe ridotte a 33); ripristinato dal blob di HEAD, in avanti, senza reset.
- Altri marker rimasti nei moduli: solo in file `.old` di backup, non runtime.
- Link: [MERGE_CONFLICT_RESOLUTION_LOG](../merge-conflict-resolution-log.md), [decision-log](../bmad/decision-log.md).

## [2026-10-26] (voce di una sessione parallela, imprecisa: vedi la voce sopra)

Questa voce citava `docs/wiki/collisioni-git-fo-decision.md` nel tema, che esiste solo nel modulo Geo come bozza, e dichiarava risolti file che non aveva verificato. Conservata come cronologia.

### voce originale: git + second-brain | frontoffice collisioni studiate — decisione 3 applicata

- Studio entrambi i lati delle collisioni (current/incoming) su tema Sixteen + moduli Geo/Fixcity/Xot.
- **Tema Sixteen:** `manifest.json` è artifact di build → non deve avere conflitti manuali; documentato in `docs/wiki/collisioni-git-fo-decision.md`. `docs/wiki/index.md` e `docs/wiki/log.md` sincronizzati con log attività.
- **Modulo Geo:** marker rimossi con soluzione 3 (primo lato + `uses()` ai test); file `collisioni-git-fo-decision.md` creato.
- **Fixcity widget:** già risolto (`ticket-create-wizard.blade.php`); 0 residui.
- **Xot docs:** marcati stale (`phpstan-fixes.md`, `filament-4-...`); non cancellati (regola: mai cancellare senza conferma).
- Prossimo: PHPStan L10 + Pest sui test Geo modificati; aggiornare `docs/wiki/index.md` radice.

---

module: "Sixteen"
---

# Sixteen Wiki Activity Log

## [2026-10-26] docs | collisioni frontoffice — studio entrambi i lati (BMAD + second brain)

- `docs/wiki/collisioni-git-fo-decision.md` (radice) documenta la decisione 3 per Geo test, Geo docs, Sixteen manifest, Fixcity widget, Xot docs.
- Nessun residuo marker in `public/manifest.json`; file preservato come build artifact.
- Log sincronizzato con `laravel/Modules/Geo/docs/wiki/log.md` e `docs/project_docs/riepilogo-risoluzione-conflitti.md`.

## [2026-10-22] fo | anti-pattern lock — mai pages/profile/edit (D34)
- knowledge [why-profile-edit-blade-must-not-exist.md](../bmad/knowledge/why-profile-edit-blade-must-not-exist.md); FO = `FrontofficeRoutes::personalAreaImpostazioni()`
- Pest `NoSemanticFolioPageDirectoriesTest` + header forbid `route('profile.edit')`; hook `theme-pages-guard.sh`; blueprint + fo-folio-links aggiornati
- hindsight: initiative kp-2414279f6153450582f67e36216631c2 + correction FO impostazioni

## [2026-10-22] fo | audit UI/UX Design Comuni — data-dc home/track + no gravatar (D33)

- Link: [04-audit-ui-ux-design-comuni.md](../compliance/04-audit-ui-ux-design-comuni.md), [decision-log.md](../bmad/decision-log.md), [filament-blueprint.md](../bmad/filament-blueprint.md).
- User: «analizzare tutta la ui/ux… DesignComuni… bmad + second brain + filament blueprint».
- Smoke `fixcity.eu`: home/track `data-dc=0` → fix; D-03 Gravatar → iniziali; A-14 breadcrumb i18n; D-12 assistenza → tickets/create.

## [2026-10-22] fo | FrontofficeRoutes wave 2 — ticket + area personale

- Supersede: estende D32 (stesso giorno).
- Link: [reusable-components.md](../rules/reusable-components.md), [filament-blueprint.md](../bmad/filament-blueprint.md).
- User: «procedi».
- `FrontofficeRoutes` + helpers `personalArea*`, `faqIndex`, `ticketsShow`; wire ticket-list/detail, header dropdowns, segnalazioni, settings, logout.

## [2026-10-22] fo | reusable Infrastructure/Presentation + Action (D32)

- Link: [reusable-components.md](../rules/reusable-components.md), [filament-blueprint.md](../bmad/filament-blueprint.md), [decision-log.md](../bmad/decision-log.md).
- User: «devi sempre cercare di creare componenti riutilizzabili… bmad + second brain + filament blueprint».
- Estratti: `Infrastructure\Url\FrontofficeContainerAliases` + `FrontofficeRoutes`, `Presentation\Search\FrontofficeSearchSuggestions`, `Actions\Folio\ResolveContainer0IndexAction`.
- Folio `[container0]/index` e search-modal / services / faq consumano i pezzi riusabili; niente alias inline.

## [2026-10-20] ux | /it/auth/register merge 500 + parity DC

- Supersede: estende entry shell+CTA sotto e [register-ux-audit](../auth/register-ux-audit.md).
- Link: [register-ux-audit](../auth/register-ux-audit.md), [prompts/04.md](../prompts/04.md).
- User: «migliora https://fixcity.eu/it/auth/register».
- Causa: marker merge in `auth-register.blade.php` → HTTP 500; breadcrumb DC con `span.separator` → `/ /`.
- Fix: shell ripulita (max 36rem); form colonna unica + * required; copy IT «Dati personali»/«Consensi»/«Indirizzo email».
- Smoke: 200; Playwright card 343/576, CTA 52px, crumb `Home / Crea…`.

## [2026-10-20] ux | /it/auth/register shell + CTA sotto data-dc

- Supersede: estende [register-ux-audit](../auth/register-ux-audit.md); non tocca login.
- Link: [register-ux-audit](../auth/register-ux-audit.md), [prompts/04.md](../prompts/04.md).
- User: «migliora https://fixcity.eu/it/auth/register».
- Causa: isolamento Tailwind/`14-auth-login.css` con `data-dc` → card full-bleed, CTA corta, i18n grezze, breadcrumb `Home / /`.
- Fix: shell inline max 32rem + CTA widget 44px full-width; chiavi `user::*` corrette; un solo link Accedi; padding cookiebar.
- Smoke Playwright: 375/1440 card 343/512, submit h=52, 0 raw keys.

## [2026-10-20] a11y | events feedback vuoto + misure parity prompt 04

- Supersede: solo aggiunge evidenze pomeridiane; non sostituisce la entry DCP-221 sotto.
- Link: [prompts/04.md](../prompts/04.md), [events-public-index](../bmad/events-public-index.md).
- User: «esegui poi migliora il prompt …/prompts/04.md».
- Fix: h2 rating vuoti su `/it/events` → feedback/contacts in JSON + default blade.
- Misure `:8000`: hero Lora 400; mobile gap 0; blog main 74%/80%; events main 54%.
- Decisione: non copiare «Luoghi» DC fake (castelli/Colosseo) nel FO FixCity.

## [2026-10-20] a11y | regressione DCP-221 hero Titillium→Lora + prompt 04

- Sintomo: `.fc-home-hero h1` tornato a Titillium `font-weight:700` (testo «ciccione»).
- Fix: ripristino contratto D18/DCP-221 — Lora Regular 400 + `class="font-serif"` su
  `blocks/home/hero` e `blocks/hero/split`; rebuild Sixteen.
- Prompt: riscrittura strutturata di `docs/prompts/04.md` (filosofia civica, matrice
  stato, decisione Event=Article content_type, gate, DoD).
- Doc: [accessibility](../accessibility.md), [decision-log D18](../bmad/decision-log.md),
  [dcp-221](../bmad/stories/dcp-221-homepage-tipografia-leggibilita-wcag.story.md).

## [2026-10-16] bugfix | /it/blog getAttribute on null

- Causa: exclude featured con `transform→null` + `filter()` non riassegnato
- Fix: `setCollection(reject)` + `$renderCard` tipizzato + `@continue`
- Stesso pattern su `blocks/events/index.blade.php`
- Smoke: `/it/blog` e `/it/events` → 200
- Doc: [blog-public-index.md](../bmad/blog-public-index.md)

## [2026-10-16] architecture | container0.index shell JSON-only (D20)

- Folio `[container0]/index` ridotta a Volt + `<x-layouts.app>` + `<x-page>`
- Rimossi native/utility match e `x-dynamic-component`; chiusura tag coerente
- JSON tenant: `domande-frequenti.index`, `mappa-sito.index`, `services.index`,
  `segnalazioni.index`, `lista-categorie.index`
- Contratto: [container0-index-shell-contract.md](../bmad/container0-index-shell-contract.md)
- Pest: `FolioPageMountContractTest` rafforzato

## [2026-10-15] inbound | blog + eventi (AFC pattern)

- Doc: [inbound-marketing-blog-eventi.md](../inbound-marketing-blog-eventi.md)
- Dossier: [inbound-marketing](../../../../Modules/Fixcity/docs/bmad/inbound-marketing/README.md)
- Pattern: promessa verticale, hub risorse, CTA progressive; no ads/WP copy.

## [2026-10-15] sustainability | modello a strati + docs FO

- Come sostenere FixCity: B2G/co-funding P0; grant+partner P1; no banner ads.
- Doc tema: [project-sustainability-front-office.md](../project-sustainability-front-office.md)
- Dossier: [project-sustainability](../../../../../../bmad-output/project-sustainability/README.md)

## [2026-10-15] marketing | footer partner vs banner (BMAD only)

- Domanda: «Sostieni il progetto» + banner sponsor nel footer?
- Verdict: **partner/sostenitori sì; banner commerciali no** (primo rilascio).
  CTA monetaria solo con governance. Nessuna modifica runtime.
- Dossier: [footer-sponsorship](../../../../../../bmad-output/footer-sponsorship/README.md)
- Concept: [footer-partner-sponsorship-marketing](concepts/footer-partner-sponsorship-marketing.md)
- Story: [footer-sponsor-marketing](../bmad/stories/footer-sponsor-marketing.story.md)

## [2026-10-15] fo | footer JSON SSoT (DCP-222 / DCP-198 A5)

- `<x-section slug="footer">` → `footer.json`: brand FixCity + slogan, solo path
  reali; `social: []`; no logo UE. Blade = vestito + `FrontofficeUrl`, niente
  `<a>` nudi.
- Guard: `FooterJsonBrandAndLinksSsotTest`. Live `/it`: FixCity, 0 bare anchors.
- Story: [dcp-222](../bmad/stories/dcp-222-footer-json-ssot-vestito.story.md)
- Filosofia: [zen-wizard-header-philosophy](concepts/zen-wizard-header-philosophy.md)

## [2026-10-15] ecc | home-page-title + timeline `<time>` (follow-up)

- ECC implement: `homepage.blade.php` → `id="home-page-title"` (no doppio `#main-container`);
  cronologia dettaglio → `occurred_on` + `<time datetime>` (DCP-148).
- **Nota runtime `/it`:** la home viva usa `blocks/home/hero` / `hero/split` con
  `id="fc-home-hero-heading"` (Lora DCP-221); il blocco `blocks/homepage.blade.php`
  resta corretto per path JSON legacy, ma non e' l'H1 servito oggi.
- Live check: un solo `#main-container`, H1 = `fc-home-hero-heading`.
- Docs gia' aggiornati nello ECC: [homepage-guest-visual-fix](concepts/homepage-guest-visual-fix.md),
  [dcp-148](../bmad/stories/dcp-148-dettaglio-pubblico-segnalazione-e-segui.story.md).

## [2026-10-15] a11y | tipografia home /it (DCP-221) + Lora hero

- Problema: titoli «ciccioni» + H1 poco leggibile in Titillium bold.
- Fix: H1 hero in **Lora Regular 400 `!important`** (BI `.font-serif`, scale
  h1 40–48px) per battere `body *` Titillium dello shell Filament FO;
  sans UI a 600; token `--font-serif`/`--dc-font-serif`; self-host Vite (DCP-183).
- Verifica Playwright `/it`: family Lora, weight 400, 48px, lh 1.2;
  `FontsCssGuardTest` 4/4; rebuild `app-BfW1LND_.css`.
- Story: [dcp-221](../bmad/stories/dcp-221-homepage-tipografia-leggibilita-wcag.story.md)
- Concetto: [design-comuni-font-tokens](concepts/design-comuni-font-tokens.md)

## [2026-10-14] docs | studio del tema reale e correzione degli ingressi

- Story: [studio e ingressi](../bmad/stories/docs-studio-stato-e-ingressi.story.md). `README.md` e `00-INDEX.md` riscritti con fatti verificati (stato vivo: `pub_theme = Sixteen` nei 4 `xra.php`; conteggi; build da `package.json`).
- Verificato: controller e rotte del tema esistono ma non sono registrati (`route:list`, 461 rotte, senza `comune/*`, `auth/spid`, `auth/cie`); `INDEX.md` e' la mappa viva, `index.md` e' storico (marcato `stale`).
- Contraddizione aperta con la voce del 2026-10-11 qui sotto: `components/layouts/design-comuni.blade.php`, `-authenticated` e `-bare` esistono (2026-10-13) e `pages/tests/index.blade.php` usa `<x-layouts.design-comuni-bare>`.
- Link: 5 ripuntati, 10 annotati in `wiki/index.md` e `wiki/log.md`. Memoria: `bashscripts/ai/wiki/memories/docs-temi-studio.md`.

## [2026-10-11] architecture | Sixteen è già DesignComuni

- Regola locale: [no-parallel-design-comuni-layout](rules/no-parallel-design-comuni-layout.md)
- Decisione BMAD: [design-comuni-theme-identity-decision](../bmad/design-comuni-theme-identity-decision.md)
- Contratto: nessun `resources/views/layouts/design-comuni.blade.php`, nessun `components/layouts/design-comuni.blade.php`, nessun `<x-layouts.design-comuni>`.
- Shell pubblica canonica: `x-layouts.app` / `resources/views/layouts/app.blade.php`, con `components/layouts/main.blade.php` come base HTML.
- Le vecchie citazioni di `design-comuni.blade.php` sono storico/anti-pattern: non sono istruzioni runtime.

## [2026-07-24] second-brain | max workflow + composer-go concept

- Link da [composer-go-theme-impact](concepts/composer-go-theme-impact.md) a skill second-brain max
- Disciplina: ogni fix tema → update `docs/wiki/` Sixteen + questo log

- 2026-06-10: docs Folio FO — fo-folio-routing-zen, route-not-found-view-cache, header `notifications` vs `area-personale.notifiche`
## [2026-06-09] cta/ticket | NO FrontofficeUrl — LaravelLocalization only

- View: `resources/views/components/blocks/cta/ticket.blade.php`
- URL: `LaravelLocalization::localizeURL($path)` — vietato `FrontofficeUrl` nei blocchi CTA
- Test: `tests/Unit/CtaTicketBlockTest.php` (3 test)
- Doc: `concepts/segnalazioni-elenco-map-integration.md`, `concepts/fo-folio-links-multilingua.md`

## [2026-06-06] architecture | Folio shell — no @props/@extends

- Regola: pagine Folio = Folio+Volt + `<x-layouts.app>`; vietato `@props`, `@extends`, `@php $pageSlug`
- Fix `[container1]/index`: `name('container1.index')`, `@volt('container1.index')`
- Pest 5/5; root memory `folio-page-shell-no-props-extends.md`

## [2026-06-06] architecture | Header → `route('container0.index')`

- `header/v1.blade.php` allineato (non `container0.list`)

## [2026-06-05] architecture | `container0.index` — Filament way, mount lineare

- `[container0]/index`: ripristinato `@volt('container0.index')`/`@endvolt` (mancava nel blade); mount lineare confermato
- `[container0]/index`: `name('container0.index')`, `$pageSlug = $container0.'.index'`; rimosso `container0.list` e match locale/CmsPage nel mount
- Canon: `folio-page-pattern.md`, `folio-route-params-mount.md`, `volt-usage-rule.md`
- Root memoria: `folio-container0-index-filament-way.md`

## [2026-06-05] architecture | Folio @volt statico — no @volt($pageSlug)

- Tabella `name()` ↔ `@volt()` in `folio-page-pattern.md` (`container0.index` / `container0.view` / `container1.index`)
- `folio-route-params-mount.md` + `volt-usage-rule.md` allineati
- Cms: [folio-page-shell-pattern](../../../../Modules/Cms/docs/wiki/concepts/folio-page-shell-pattern.md)

## [2026-06-05] docs | @volt obbligatorio su Folio+Component — canon allineato

- `folio-route-params-mount.md`: `@volt('…')` statico = `name()`; senza → `VoltDirectiveMissingException`
- Root: `no-volt-in-blade-views` eccezione Folio; `volt-route-params-mount-contract` distinzione aggiornata

## [2026-06-05] architecture | Folio params in mount() — fix container0.list

- `[container0]/index.blade.php`: rimosso `@php` + `request()->route()`; title/meta in `mount(string $container0)`
- Rule: `.cursor/rules/folio-route-params-mount.mdc`
- Pest: `FolioPageMountContractTest` esteso a `container0/index`

## [2026-06-05] docs | HackerNoon harness — tips 001-022 in wiki locale

- Stub/checklist: second-brain → canon Xot, ai-harness, [hackernoon map](../../../../../docs/wiki/concepts/hackernoon-ai-coding-tips-fixcity-map.md), [llm-wiki.txt](../../../../../bashscripts/tools/prompts/llm-wiki.txt) _(target mancante, verificato 2026-10-14)_
- GitHub: [#272](https://github.com/laraxot/base_fixcity_fila5/issues/272) / [D#273](https://github.com/laraxot/base_fixcity_fila5/discussions/273)

# Sixteen Wiki Log

## [2026-06-05] architecture | STORY-152 — fromStoredUrl nav + memoria no /it/ no IT Blade

- `FrontofficeUrl::fromStoredUrl()` — normalizza URL CMS `/it/servizi` → locale corrente
- Nav `partials/nav-primary|secondary` + fallback `topics_url` in `v1.blade.php`
- Memory root: `no-hardcoded-locale-italian-in-fo-blade.md` · issue [#288](https://github.com/laraxot/base_fixcity_fila5/issues/288)

## [2026-06-05] architecture | No locale hardcoded + no italiano Blade (header)

- `bootstrap-italia/header` DRY: include `partials/user-dropdown` + `personal-area-guest-cta`
- Memoria root: `fo-folio-localized-urls-no-fake-routes.md`
- Vietato: `href="/it/profilo/servizi"`, `<span>I miei servizi</span>`

## [2026-06-05] architecture | Header menu — `route()` Folio, no `personalArea*`

- Menu utente: `route('services.categories')` … verificato con `folio:list` — **vietato** `FrontofficeUrl::personalArea*`
- `FrontofficeUrl`: solo `fromStoredUrl()` (CMS nav) + `path()` / `testsParity()`
- Doc: [fo-folio-named-routes-header.md](concepts/fo-folio-named-routes-header.md), [fo-header-url-and-translation-contract.md](concepts/fo-header-url-and-translation-contract.md)
- Test: `HeaderAreaPersonaleLinksContractTest` — scan tutti i `.blade.php` (656 assert)
- Root: [folio-frontoffice-navigation-links.md](../../../../../docs/wiki/rules/folio-frontoffice-navigation-links.md)

## [2026-06-05] rule | Header parity religione — link root wiki + index

- Index header: rule/skill/memoria root + STORY-147 UX §11
- `header-authenticated-state.md`: frontmatter + parity doppia
- QMD ingest dopo `qmd update`

## [2026-06-05] ux | STORY-147 header logged — CSS cascade + avatar FO

- Root cause: `11-tailwind-utility-compat` `.btn-primary` blu dopo layer 08; Gravatar identicon in slim bar
- Fix: `13-final-runtime-overrides.css`, `v1.blade.php` solo media avatar, delta doc aggiornato
- Canon: [header-logged-in-parity-delta.md](concepts/header-logged-in-parity-delta.md), memoria root `header-html-visual-parity-rule.md`

## [2026-06-05] docs | AI harness tema + HackerNoon

- [ai-harness-theme-sixteen.md](concepts/ai-harness-theme-sixteen.md) in index
- Canon temi: [ai-harness-theme-discipline.md](../../../Barthelemy/docs/wiki/concepts/ai-harness-theme-discipline.md)

## [2026-06-03] skill | Anthropic frontend-design × civic parity

- Adattamento plugin: [concepts/frontend-design-civic-anthropic-adaptation.md](concepts/frontend-design-civic-anthropic-adaptation.md)
- Memoria: [memories/frontend-design-anthropic-civic-mode.md](memories/frontend-design-anthropic-civic-mode.md)
- Skill bridge: `bashscripts/ai/skills/frontend-design/SKILL.md` · wiki `docs/wiki/skills/frontend-design-civic-pa.md`

## [2026-06-03] skill | Anthropic frontend-design → overlay Fixcity

- Studio: [claude-code/plugins/frontend-design](https://github.com/anthropics/claude-code/tree/main/plugins/frontend-design)
- Skill estesa: `bashscripts/ai/skills/frontend-design/SKILL.md` (PA parity, stack, anti-slop + anti-drift)
- Doc: `concepts/frontend-design-fixcity-overlay.md`, memoria `memories/anthropic-frontend-design-skill-adapted.md`
- Symlink agenti: `bashscripts/ai/.agents/skills/frontend-design` → skill canonica

## [2026-06-03] docs | map-lit marker + popup — ricostruzione e confini tema

- **SSoT modulo:** [geo-map-lit-reconstruction-guide.md](../../../../Modules/Geo/docs/wiki/concepts/geo-map-lit-reconstruction-guide.md) — dimensioni pin, DOM, popup BEM, checklist build
- **Tema:** [geo-map-marker-civic-pin-theme-boundary.md](concepts/geo-map-marker-civic-pin-theme-boundary.md), [geo-map-popup-leaflet-boundary.md](concepts/geo-map-popup-leaflet-boundary.md)
- **Runbook popup vuoto:** [map-popup-header-whitespace-fix.md](../../../../Modules/Geo/docs/wiki/troubleshooting/map-popup-header-whitespace-fix.md) — `<div class="popup__header">` vs `header { min-height: 222px }`
- **Codice:** `marker-config.js` (`__shell`/`__inner`/`__point`), `popup-ticket.js` (STORY-132), `07-map-clusters-and-leaflet.css`

## [2026-06-01] rule | Frontend stack canonico + naming blocchi CMS

- **Regola**: `rules/frontend-stack-canonical.md` — stack Tailwind+Alpine+Lit+DaisyUI+Flowbite+Filament (NO Bootstrap)
- **Regola**: `rules/cms-block-naming-tailwind-flowbite.md` — sottocartelle `blocks/` da Tailwind UI / Flowbite
- **Story**: STORY-112 — `docs/stories/STORY-112-frontend-stack-canonical-rule.md`
- **Issue**: [#197](https://github.com/laraxot/base_fixcity_fila5/issues/197) · **Discussion**: [#198](https://github.com/laraxot/base_fixcity_fila5/discussions/198)

## [2026-05-26] fix | register.blade.php — folio + Volt manuale + template errata

- **Errori**:
  - `<x-layouts.marketing>` non esiste (error 500)
  - `title()` non è funzione Folio (Internal Server Error)
  - Form Volt manuale duplice `RegisterWidget` esistente
  - Traduzioni inesistenti (`user::auth.register.page.*`)
  - Mancanza `Route` facade
- **Fix**: template semplificato con `layouts/app` + `$title` slot + `$this->livewire(RegisterWidget::class)` + chiavi traduzione `user::auth.register-*`
- **Regola second brain**: `filament-auth-widgets-rule.md` — auth pages usano Filament widgets, mai Blade/Volt manuali.
## [2026-05-22] docs | wizard-review-parity — recap Infolist vs input autore/contatti

- [`wizard-review-parity.md`](design/wizard-review-parity.md): chiarito che lo step `form.summary::data::wizard-step` è **misto** (`TextEntry` recap + `TextInput` autore/contatti).

## [2026-05-22] troubleshooting | verificare bundle runtime prima del sorgente

- Aggiornata [concepts/livewire-alpine-esm-order.md](concepts/livewire-alpine-esm-order.md): quando la console cita un hash (`app-BDBkID6g.js`) la diagnosi parte dall'HTML servito, non dal sorgente.
- `public_path()` punta a `public_html`; `laravel/public/themes/Sixteen/manifest.json` puo' essere storico e non autorevole.
- Debugbar su `/it/segnalazione-crea` era iniettata; gli errori `geoMapPickerField`/`headerMobileNav` erano asset/runtime Alpine.
- Issue: [#115](https://github.com/laraxot/base_fixcity_fila5/issues/115).

## [2026-05-22] troubleshooting | alpine init vs vite esm (bootstrap header pre-livewire)

- **Motivo**: gli script tema `@vite(resources/js/app.js)` sono **`type="module"` differiti**, posizionati dopo `@livewireScripts` nei layout pubblici — Alpine parte prima e `Alpine.data` / listener `alpine:init` in `app.js` arrivano troppo tardi → `headerMobileNav` non risolto al primo boot.
- **Artifact**: [concepts/livewire-alpine-esm-order.md](concepts/livewire-alpine-esm-order.md), partial [resources/views/partials/alpine-livewire-bootstrap-header.blade.php](../../resources/views/partials/alpine-livewire-bootstrap-header.blade.php) _(target mancante, verificato 2026-10-14)_, factory [resources/js/theme/header-mobile-nav-scope.js](../../resources/js/theme/header-mobile-nav-scope.js) _(target mancante, verificato 2026-10-14)_.
- **Layout**: include partial all’avvio `<body>` in [components/layouts/main.blade.php](../../resources/views/components/layouts/main.blade.php) e [layouts/main.blade.php](../../resources/views/layouts/main.blade.php).

## [2026-05-15] concept | Tailwind `@apply` come alias preferito

- Documentato in [bootstrap-italia-tailwind-philosophy](concepts/bootstrap-italia-tailwind-philosophy.md), [design-comuni-class-mapping](entities/design-comuni-class-mapping.md); modulo Cms [daisyui-pro-contro-metriche](../../../../Modules/Cms/docs/daisyui-pro-contro-metriche.md); Fixcity `wizard-architecture-filament-theme-boundary`.

## [2026-05-15] reference | DaisyUI pro/contro/percentuali (Cms + Sixteen)

- SSoT: [Modules/Cms/docs/daisyui-pro-contro-metriche](../../../../Modules/Cms/docs/daisyui-pro-contro-metriche.md); sintesi tema [concepts/daisyui-pro-contro-metriche](concepts/daisyui-pro-contro-metriche.md); indici Cms/UI e [daisyui-componenti](../../../../Modules/Cms/docs/daisyui-componenti.md) aggiornati.

## [2026-05-15] wiki | DaisyUI nel stack documentato (tema + moduli)

- Tema: [entities/design-comuni-class-mapping.md](entities/design-comuni-class-mapping.md) (sezione DaisyUI), [concepts/design-comuni-site-wide-component-css-rule.md](concepts/design-comuni-site-wide-component-css-rule.md), [wiki index](index.md).
- Moduli: Fixcity (`fixcity-module`, `wizard-architecture-filament-theme-boundary`), Geo [wiki index](../../../../Modules/Geo/docs/wiki/index.md), Cms `daisyui-componenti.md` + `index`, UI `mcp-ui-ux.md`; root rule [011-blocks-view-convention.md](../../../../../docs/wiki/rules/011-blocks-view-convention.md).

## [2026-05-15] entity | design-comuni-class-mapping — albero upstream `src/stylesheets`

- Documentata in [entities/design-comuni-class-mapping.md](entities/design-comuni-class-mapping.md) la pipeline reale di `bootstrap-italia-comuni.scss` (BS5 + BI + layer comuni + import `cmp-*` da `src/components/`), e il ruolo dei layer Sixteen (Tailwind + parity Filament/Lit senza Bootstrap JS).

## [2026-05-15] Import Geo mappa via `@modules`

- **2026-05-28:** rimosso fork vietato `geo-map-lit-local.js`; mappa elenco solo via `import map-lit.js` in `app.js` — regola [no-theme-map-lit-fork](../../../../../docs/wiki/rules/no-theme-map-lit-fork.md).
- Boundary aggiornato: [concepts/theme-geo-js-boundary.md](concepts/theme-geo-js-boundary.md).

## [2026-05-15] Geo map styles mirror

- Aggiunto mirror `resources/js/components/modules/Geo/map/styles.js` come re-export da `@modules/Geo/...`.
- `coordinate-picker-lit.js` del mirror importa `mapStylesText` da `./map/styles.js`.
- `map/styles.js` resta solo re-export deprecato per compatibilità.

## [2026-05-15] Geo map JS in sottocartella `map/`

- Mirror `resources/js/components/modules/Geo/map/`: `controls`, `events`, `search`, `resize`, `utils`, `layers`.
- Import Lit: `./map/controls.js`, `./map/events.js`, ecc.
- Documentato boundary tema: [concepts/theme-geo-js-boundary.md](concepts/theme-geo-js-boundary.md).
- Regola canonica nel modulo Geo: [map-js-module-naming-rule](../../../../Modules/Geo/docs/wiki/concepts/map-js-module-naming-rule.md).
- Verifica asset: `npm run build` + `npm run copy` in questa cartella tema.

## [2026-05-11] ops | context overflow hardening recepito nel tema


- creati `index.md` e `concepts/context-compression-plugin.md` come bootstrap minimo del wiki tema.
- chiarito il boundary del tema: niente cache o workaround locali nel tema per risolvere overflow; la configurazione resta in `laravel/opencode.json` e negli MCP di progetto.
- recepita la regola pratica: per Sixteen usare wiki/QMD e retrieval mirato, non batch completi di screenshot, HTML dump o audit storici.

## [2026-05-11] prompt governance | structure prompt retains operative instructions

- Correzione di interpretazione: `docs/prompts/structure.txt` non va forzato a bootstrap minimale puro.
- In questo progetto il prompt puo' mantenere istruzioni task-specific e policy operative quando servono al flusso del tema.
- L'entry precedente di cleanup va letta come tentativo superato, non come stato desiderato finale.

## [2026-05-11] prompt governance | structure prompt cleanup

- Ripulito `docs/prompts/structure.txt` da istruzioni utente e testo spurio finiti in coda al prompt.
- Il prompt torna a contenere solo guardrail strutturali del tema, senza task-specific notes embeddate.

## [2026-05-05] prompt governance | wizard prompt enhanced

- Enhanced `docs/prompts/wizard.txt` with critical additions:
  - **Best Practices**: #11-13 (HasWizard trait usage, dual-URL verification, tasto Avanti debugging)
  - **Bad Practices**: Using `pub_theme` in admin, hiding skiplinks, custom Avanti buttons, duplicating HasWizard methods, modifying engine for aesthetic reasons
  - **False Friends**: 6 new entries (tasto Avanti, skiplinks, Safe removal, PHPStan optional)
  - **Checklist**: Enfasi su controlli DOPO OGNI MODIFICA, PHPStan zero errori, zero ignore, zero baseline
  - **Definition of Done**: Esplicitati Safe functions, PHPStan requirements, skiplink verification
- Reinforced: Safe functions (`use function Safe\...`) are project quality contract, not optional.
- Reinforced: PHPStan level 5 is part of Definition of Done - zero tolerance for ignores.

## [2026-05-05] boundary | fixcity ticket infolist ownership

- Added theme-side boundary note: `concepts/fixcity-ticket-infolist-theme-boundary.md`.
- Decision: `TicketInfolist` schema ownership stays in Fixcity module; Sixteen keeps presentation-only responsibilities.
- Cross-link created to Fixcity concept: `ticketinfolist-pattern-reference`.

## [2026-05-05] prompt governance | wizard prompt refactor

- Refactored `docs/prompts/wizard.txt` into operational format with:
  - mandatory sources to study,
  - best practices,
  - bad practices,
  - false friends,
  - execution checklist.
- Reinforced theme philosophy: same wizard engine, different skin (`pub_theme::components.wizard`) without leaking business logic into theme blades.

## [2026-05-04] Story 8-115: XotBaseWizardWidget View Calculation Rule

- **Story**: `_bmad-output/implementation-artifacts/8-115-xotbasewizardwidget-view-calculation-rule.md`
- **Rule**: Widgets che estendono `XotBaseWizardWidget` NON devono definire `$view` property
- **View Resolution**:
  - Admin: `filament/components/wizard` (default Filament)
  - Frontoffice: `pub_theme::components.wizard` (Design Comuni styled - questo tema!)
- **Docs**: `concepts/wizard-custom-view-architecture.md` - documentata view calculation
- **Rule File**: `.windsurf/rules/xotbasewizard-no-view-property.mdc`
- **Status**: ✅ done

## [2026-05-04] Story 8-114: XotBaseWizardWidget vs Filament HasWizard Parity

- **Story**: `_bmad-output/implementation-artifacts/8-114-xotbasewizard-filament-haswizard-parity.md`
- **Problem**: `XotBaseWizardWidget` reinventa logica già presente in Filament Concerns (`HasWizard` Actions + Pages)
- **Visual Issues**: ~~Frontoffice manca tasto "Avanti"~~ ✅ **FIXED** - Architettura corretta:
  - `XotBaseWizardWidget` configura `->view('pub_theme::components.wizard')`
  - View tema renderizza: stepper + contenuto + azioni
  - `create-ticket-wizard.blade.php` wrapper solo titolo/container
- **Architecture Docs**: `concepts/wizard-custom-view-architecture.md` - Filosofia Tema=Vestito, Modulo=Logica
- **URLs**: `/it/tests/segnalazione-crea` vs `/fixcity/admin/tickets/create`
- **Status**: in-progress

## [2026-05-04] Story 8-113: Correct Wizard Implementation Approach
- **Story**: `_bmad-output/implementation-artifacts/8-113-correct-wizard-implementation-approach.md`
- **Fix**: Rimosso `PubThemeWizard` - violava separazione modulo/tema
- **TicketForm**: Ora usa `Wizard::make()` standard (no condizionale, no PubThemeWizard)
- **Philosophy**: Modulo crea componenti standard, Tema gestisce presentazione via CSS/Blade
- **Status**: done

## [2026-05-04] Story 8-112: Wizard Custom View Pattern
- **Pattern**: PubThemeWizard usa view('pub_theme::components.wizard') in frontoffice
- **TicketForm**: Condizionale `inAdmin() ? Wizard : PubThemeWizard`
- **Docs**: Story artifact 8-112 dettaglia il pattern
- **Status**: done

## [2026-05-04] Story 8-111: Wizard Theme Component Architecture (Ready-for-Dev)

- **Story**: `_bmad-output/implementation-artifacts/8-111-fixcity-wizard-theme-component-architecture.md`
- **Fix**: Corregge architettura violata in 8-110 (logica hardcoded nel tema)
- **Pattern**: Tema = Vestito (Blade component), Modulo = Logica (PHP/Filament)
- **New Component**: `pub_theme::wizard` - wrapper Design Comuni per Filament Wizard v5
- **Docs Created**:
  - `concepts/filament-wizard-custom-component.md` - API componente, anti-patterns, testing
  - Links to Fixcity module contract: `Modules/Fixcity/docs/wiki/concepts/wizard-theme-integration.md`
- **Sprint Status**: `ready-for-dev` in epic-8

## [2026-05-04] Story 8-110 Created: Segnalazione-Crea Step 1 Privacy Parity

- **Story**: `_bmad-output/implementation-artifacts/8-110-segnalazione-crea-step1-privacy-design-comuni-parity.md` (`ready-for-dev`)
- **Scope**: Step 1 (privacy) del wizard `/it/tests/segnalazione-crea`
- **Issues**: Stepper visibility, checkbox phrase parity, font Titillium Web, typography
- **Tech Stack**: Tailwind CSS + Alpine.js + Lit, NO Bootstrap
- **Reference**: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html
- **Sprint Status**: Added to epic-8, status `ready-for-dev`
- **Wiki Updated**: `concepts/segnalazione-01-privacy-design-comuni-vs-local.md` with story link

## [2026-05-04] bmad-create-story | 7-110 step1 — stepper, checkbox DC, tipografia

- Artifact: `_bmad-output/implementation-artifacts/7-110-segnalazione-01-privacy-stepper-checkbox-typography-parity.md` (`ready-for-dev`).
- Scope: stepper visibile su `/it/tests/segnalazione-crea`; label checkbox = frase ufficiale Design Comuni; font via `resources/css/` + `npm run build` / `npm run copy`.

## [2026-05-04] Design Comuni source ingestion + header parity documentation

- **Raw sources downloaded**: 
  - `docs/raw/articles/design-comuni/segnalazione-01-privacy.html` (34,566 bytes)
  - `docs/raw/articles/design-comuni/segnalazione-02-dati.html` (49,382 bytes)
  - Handlebars source fetched via raw GitHub URL for `segnalazione-02-dati.hbs` and `segnalazione-01-privacy.hbs`
- **Wiki documentation created**: `docs/wiki/sources/design-comuni-header-reference.md` — complete mapping:
  - 3-tier header structure (slim/center/navbar) from official `cmp-base/base.hbs`
  - Region name link rule: `class="navbar-brand"` mandatory (specificity over `text-white`)
  - CSS cascade analysis: `style-apply.css` sets generic `a { color:#191919 }` inside slim wrapper → `navbar-brand` needed with `!important`
  - Component mapping table: Design Comuni partials → Sixteen Blade components (12 entries)
  - Parity checklist: breadcrumb missing (P1), navscroll da verificare, contacts assente (P1)
- **Index updated**: added `design-comuni-header-source-reference` to sources section with cross-links to concepts
- **Fix confirmed**: `v1.blade.php:71` now uses `navbar-brand`; compiled CSS contains `it-header-slim-wrapper .navbar-brand{color:#fff!important}` — verifica con `grep` su `public/assets/app-Dr4I6Xrd.css`
- **Related**: comparison doc `segnalazione-01-privacy-design-comuni-vs-local-wizard.md` aggiornata con riga 1b (region name link fix)

## [2026-05-04] header visual parity audit + story 8-103 update

- Screenshot comparativo Puppeteer: locale vs Design Comuni `segnalazione-02-dati.html`
- 6 gap confermati: dropdown lingua/utente (data-bs-toggle non funziona su Livewire), classi Tailwind spurie nel dropdown, guest CTA inline style verde, nav active box blu, logo size
- Story `8-103` aggiornata con: delta visivo da screenshot, fix P7 (nav active underline), fix P9 (Tailwind→BootstrapItalia), nota P1 (slim bg già corretto `#00402b`)
- Wiki aggiornati: `concepts/header-slim-dropdown-behavior.md` (direzione Alpine + stato file), `comparisons/header-visual-parity-audit.md` (nuovo)
- Nota importante: il verde `#007A52` su guest CTA è **deliberato** per il branding Fixcity (non un bug rispetto al reference blu Design Comuni)

## [2026-05-04] story 8-103 | header dropdown Livewire fix + parity audit

- Story creata: `_bmad-output/implementation-artifacts/8-103-header-segnalazione-crea-step2-design-comuni-visual-parity.story.md`
- Analisi codice reale condotta su: `app.js`, `header-footer-colors.css`, `design-comuni-header-fix.css`, tutti i partial header
- **Root cause identificata**: `DOMContentLoaded` listener in `app.js` perso dopo Livewire DOM morph — fix: hook `livewire:navigated` + `livewire:update`
- **Branding verde confermato**: `#00402B` slim, `#007A52` center/navbar/CTA — deliberato, non un errore
- Aggiornato `concepts/header-slim-dropdown-behavior.md`: corretto contraddizione Alpine vs data-bs-toggle, aggiunta sezione Livewire hook
- Aggiornato `Fixcity/docs/llm-wiki/concepts/header-green-branding-rule.md`: frontmatter YAML, tabella colori, stato file verificato

## [2026-05-04] concepts | segnalazione-local-html-class-token-table (7-105)
- Generata tabella markdown rigenerabile: `concepts/segnalazione-local-html-class-token-table.md` (metriche 7886 / 636 / 529; script `bashscripts/extract-segnalazione-class-tokens.php`)
- Concept `segnalazione-html-samples-class-token-extraction.md`: fonte `.planning/research`, rimossa tabella inline duplicata; link bidirezionale tabella ↔ entity ↔ story 7-105

## [2026-05-04] entity | bootstrap-italia-class-inventory (story 7-105)
- Creato `docs/wiki/entities/bootstrap-italia-class-inventory.md` — inventario 486 classi BI su 7 pagine DC
- 387 già implementate in `bootstrap-italia-classes.css`, 99 mancanti con mapping Tailwind `@apply`
- Categorie: Spacing(23), Tipografia(16), Display(1), Buttons(3), Header/Nav(6), Cards(2), CMP(11), Modal(9), Icons(6), Accordion(1), Carousel(4), Misc(25)
- Aggiornato `docs/wiki/index.md` con link all'entity

## [2026-05-04] bmad-create-story | 7-105 inventario classi BI (7 pagine DC) → Tailwind in `app.css`

- Story: `_bmad-output/implementation-artifacts/7-105-design-comuni-segnalazione-static-pages-bootstrap-to-tailwind-class-map.md`.
- Scope URL: `segnalazione-dettaglio`, `segnalazione-01`…`04`, `segnalazione-area-personale`, `ticket-list` (Design Comuni pagine statiche).
- Deliverable: elenco deduplicato classi + mapping `@layer` / utilities in `resources/css/app.css`; wiki tabella condivisa con Fixcity; ingest root `docs/wiki/log.md`.
- Allineamento: [segnalazione-visual-parity-correction-plan](concepts/segnalazione-visual-parity-correction-plan.md), story 7-103/7-104.

## [2026-05-04] bmad-create-story | 7-103 audit repo + HTML statico
- Story aggiornata: `_bmad-output/implementation-artifacts/7-103-segnalazione-01-privacy-tailwind-lit-html-audit-correction-plan.md` (sezione repo README, `<head>` pubblicato, delta D0–D8, second brain, task checklist).
- Evidenza modulo: `create-ticket.blade.php` contiene `<style>` con `@apply` non valido in quel contesto — P0 spostamento CSS tema.
- `npm run copy` eseguito in `laravel/Themes/Sixteen` (asset → `public_html/themes/Sixteen/`).

## [2026-05-04] documentation | Segnalazione Visual Parity Correction Plan
- Cross-link from Fixcity module: `../../laravel/Modules/Fixcity/docs/wiki/concepts/segnalazione-design-comuni-comparison.md`
- Created: `concepts/segnalazione-visual-parity-correction-plan.md` (detailed fix plan)
- Sections: Bootstrap→Tailwind mapping, elenco stacked layout, map height 400px, stepper labels, CTAs
- Rules reinforced: NO inline CSS in Blade, CSS in `app.css`, JS in `app.js`, build+copy required
- Quick ref: `mb-3` (Bootstrap) = `mb-4` (Tailwind) since both use 1rem spacing
- Updated: index.md, log.md (this file)


## [2026-05-04] documentation | Segnalazione Visual Parity Correction Plan
- Cross-link from Fixcity module: `../../laravel/Modules/Fixcity/docs/wiki/concepts/segnalazione-design-comuni-comparison.md`
- Created: `concepts/segnalazione-visual-parity-correction-plan.md` (detailed fix plan)
- Sections: Bootstrap→Tailwind mapping, elenco stacked layout, map height 400px, stepper labels, CTAs
- Rules reinforced: NO inline CSS in Blade, CSS in `app.css`, JS in `app.js`, build+copy required
- Quick ref: `mb-3` (Bootstrap) = `mb-4` (Tailwind) since both use 1rem spacing
- Updated: index.md, log.md (this file)



## [2026-05-04] bmad-create-story | 7-110 stepper + checkbox DC + tipografia step1

- Artifact: `_bmad-output/implementation-artifacts/7-110-segnalazione-01-privacy-stepper-checkbox-typography-parity.md`
- Allineamento esplicito a label checkbox ufficiale e visibilità stepper; build/copy dopo CSS.

## [2026-05-04] visual-diff | segnalazione-crea step 1 — screenshot Playwright + story 7-77
- screenshot comparativo Playwright: ref `segnalazione-01-privacy.html` vs locale `segnalazione-crea`.
- identificate 4 DIFF attive: stepper orizzontale, bottone colore/larghezza, checkbox label, bottoni spurii.
- header colori confermati OK (slim `#00402b`, navbar `#007a52`).
- aggiornata `concepts/segnalazione-visual-parity-correction-plan.md` con dati reali e piano fix CSS.
- indice aggiornato con nuova entry correction plan.

## [2026-05-04] wiki | segnalazione-01-privacy vs locale + path relativi
- Creato `comparisons/segnalazione-01-privacy-design-comuni-vs-local-wizard.md` (delta vs [reference Design Comuni](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-01-privacy.html), piano correzione Tailwind+Alpine+Lit, HTML parity senza `<style>` in Blade).
- Allineati link `_bmad-output` e mirror Fixcity: da `comparisons/` servono **6** segmenti `../` fino alla root workspace (`../../../../../_bmad-output/...`).
- Aggiornati `docs/wiki/index.md` (sezione 2026-05-04) con backlink story [7-103](../../../../../_bmad-output/implementation-artifacts/7-103-segnalazione-01-privacy-tailwind-lit-html-audit-correction-plan.md) _(target mancante, verificato 2026-10-14)_.

## [2026-05-04] documentation | Segnalazione Visual Parity Correction Plan
- Cross-link from Fixcity module: `../../laravel/Modules/Fixcity/docs/wiki/concepts/segnalazione-design-comuni-comparison.md`
- Created: `concepts/segnalazione-visual-parity-correction-plan.md` (detailed fix plan)
- Sections: Bootstrap→Tailwind mapping, elenco stacked layout, map height 400px, stepper labels, CTAs
- Rules reinforced: NO inline CSS in Blade, CSS in `app.css`, JS in `app.js`, build+copy required
- Quick ref: `mb-3` (Bootstrap) = `mb-4` (Tailwind) since both use 1rem spacing
- Updated: index.md, log.md (this file)

## [2026-05-04] docs | visual testing frontend + verifica parity
- Creato `concepts/visual-testing-frontend.md` con guida Playwright/Puppeteer per tema:
  - Laravel Headless Browser Tester (`php artisan browser:test`)
  - Screenshot organization (`docs/wiki/assets/screenshots/`)
  - Workflow CI/CD con Playwright + Pest v4
  - Troubleshooting (mappa collassa, screenshot inconsistenti, elementi dinamici)
- Aggiornato `index.md` con nuovi documenti e backlink `visual-parity-verification-rule`
- Collegamento a Playwright/Puppeteer per controlli visuali automatici post-modifica

## [2026-04-30] sync | cluster-group visibile su ticket-list + dataset runtime coerente
- aggiornato blocco test `resources/views/components/blocks/tests/ticket-list.blade.php`: `data-url` mappa allineato a `/data/tickets.json`.
- recepito fix owner-side Geo: cluster rendering reso robusto in init tab/wizard (`removeOutsideVisibleBounds: false` + fallback `addLayers/addLayer`).
- esito condiviso con evidenza runtime: cluster presenti nello shadow DOM e suite Playwright segnalazioni all green (`10 passed`).
- riferimento owner-side: `../../../Modules/Geo/docs/wiki/log.md`.

## [2026-04-30] sync | cluster visibili ripristinati + mappa centrata su posizione corrente
- recepito fix owner-side Geo su `geo-map-lit`: bootstrap cluster `leaflet.markercluster` reso robusto in ESM/Vite con bind globale `L` prima dell'import runtime del plugin.
- recepito hardening icone cluster con classi `marker-cluster-*` compatibili con lo stylesheet plugin (parity visuale farmshops preservata).
- recepita centratura automatica posizione corrente all'avvio; `fitBounds` non overridea la viewport dopo geolocalizzazione riuscita.
- riferimento owner-side: `../../../Modules/Geo/docs/wiki/log.md`.

## [2026-04-30] sync | test map ticket-list all green dopo stabilizzazione zoom
- recepito update owner-side Geo sulla suite Playwright `ticket-list`: scenario zoom stabilizzato con fallback anti-flaky.
- esito condiviso: `10 passed` sulla suite mappa del blocco test segnalazioni.
- riferimento owner-side: `../../../Modules/Geo/docs/wiki/log.md`.

## [2026-04-30] sync | ticket-list resilient map rendering via Geo fallback
- recepito fix owner-side Geo: `geo-map-lit` ora degrada automaticamente a marker plain quando il plugin cluster va in errore runtime.
- obiettivo UX tema preservato: evitare pagina mappa vuota in `/it/tests/ticket-list` mantenendo contenuto visibile anche in modalita` degraded.
- riferimento owner-side: `../../../Modules/Geo/docs/wiki/log.md`.

## [2026-04-30] fix | segnalazioni test block allineato a dataset runtime stabile
- aggiornato `resources/views/components/blocks/tests/ticket-list.blade.php`: `data-url` mappa da `/data/tickets_big.json` a `/data/tickets.json`.
- obiettivo business: ridurre il rischio di pagina vuota in ambienti dove il dataset big non e` garantito o non aggiornato.
- boundary confermato: Sixteen decide il mount del componente e l’URL predefinito, Geo gestisce rendering/fallback marker+cluster.

## [2026-04-30] story | 8-88 marker/cluster visibility parity su ticket-list
- recepita la nuova story `_bmad-output/implementation-artifacts/8-88-ticket-list-marker-cluster-data-loading-farmshops-parity.story.md` per incidente runtime: mappa senza marker/cluster su `/it/tests/ticket-list`.
- focus tema: parity UX/integrazione pagina con dataset numeroso e fallback chiari quando il payload GeoJSON è vuoto o malformato.
- boundary confermato: dato/tickets owner Fixcity, rendering/cluster owner Geo, composizione e parity visuale owner Sixteen.

## [2026-04-30] docs | ticket-list index sync su geo-map-lit
- aggiornato `index.md`: riferimento integrazione mappa allineato da `<ticket-map-lit>` legacy a `<geo-map-lit>`.
- chiarito boundary tecnico: asset Vite dal modulo Geo, tema owner di layout/integrazione.

## [2026-04-30] sync | geo-map-lit search default boundary
- aggiunta pagina `concepts/geo-map-lit-search-default-theme-boundary.md`.
- confermato che il Blade elenco segnalazioni monta `<geo-map-lit>` senza `show-search`.
- boundary: Sixteen monta e dimensiona la mappa, Geo governa search/marker/cluster.

## [2026-04-30] governance | Claude Code theme rules path-scoped
- aggiunta pagina `concepts/claude-code-theme-rules-path-scoping.md`.
- recepita documentazione ufficiale Claude Code: `.claude/rules` senza `paths` entra sempre nel contesto, quindi le regole Sixteen devono essere path-scoped.
- confermato boundary: comportamento tema nei docs Sixteen, `.claude` solo come promemoria operativo leggero.

## [2026-04-29] sync | schema convenzionali senza parametri runtime
- recepita decisione owner-side Fixcity: `TicketForm` può essere SSoT dello schema ticket wizard, ma i metodi convenzionali `get{Name}Schema()` non vanno parametrizzati.
- recepita implementazione: `CreateTicketWizardWidget` delega al provider Fixcity `TicketForm`; Sixteen documenta solo boundary e parity, senza schema PHP o Blade duplicati.
- boundary tema: Sixteen non richiede varianti schema tramite argomenti PHP; renderizza `$this->form` e governa solo parity visuale.
- aggiornata regola locale: `concepts/filament5-schema-form-access-rule.md`.

## [2026-04-29] sync | fixcity location-only persistence for ticket create
- recepito fix backend owner-side: submit admin `/fixcity/admin/tickets/create` non deve mai tentare insert su colonna `address` inesistente.
- boundary tema confermato: nessuna patch CSS/Blade necessaria; il tema consuma lo stesso contratto UI ma il payload persistito e' ora `location`-only + `latitude/longitude`.
- riferimento owner-side: `../../../Modules/Fixcity/docs/wiki/log.md`.

## [2026-04-28] verify | paired screenshots admin vs frontoffice map routes
- acquisito screenshot frontoffice route:
  `http://127.0.0.1:8001/it/tests/segnalazione-crea?step=form.dati-della-segnalazione%3A%3Adata%3A%3Awizard-step`
  -> `../../scripts/fixcity-frontoffice-ticket-create-map.png`
- mantenuto screenshot admin autenticato:
  `http://127.0.0.1:8001/fixcity/admin/tickets/create?step=form.data%3A%3Adata%3A%3Awizard-step`
  -> `../../scripts/fixcity-admin-ticket-create-map.png`
- confronto visuale rapido: frontoffice mostra controlli mappa; admin richiede validazione manuale visuale nel browser utente per conferma finale UX.

## [2026-04-28] ops | admin screenshot script uses laravel .env credentials
- confermato riuso credenziali da `laravel/.env` (`FIXCITY_ADMIN_EMAIL`, `FIXCITY_ADMIN_PASSWORD`) per screenshot admin.
- aggiornato script tema `scripts/inspect-fixcity-admin-ticket-create-map.cjs` con URL target override/env e default su `127.0.0.1:8001`.
- screenshot autenticato generato su:
  `http://127.0.0.1:8001/fixcity/admin/tickets/create?step=form.data%3A%3Adata%3A%3Awizard-step`
- evidenza file: `../../scripts/fixcity-admin-ticket-create-map.png`.

## [2026-04-28] sync | geo admin controls visibility parity hardening
- recepito fix cross-modulo Geo per il caso admin dove i controlli mappa non risultavano visibili.
- boundary tema confermato: fix applicato nel componente JS/CSS owner del modulo Geo, non nel layer tema.
- riferimento owner-side: `../../../Modules/Geo/docs/wiki/log.md`.

## [2026-04-28] sync | fixcity sqlite location-column guard for ticket create
- recepito fix owner-side Fixcity per errore submit wizard/admin: `table tickets has no column named location`.
- boundary confermato: fix nel model backend (`Ticket` mutator + schema guard), nessuna modifica necessaria nel layer tema.
- riferimento owner-side: `../../../Modules/Fixcity/docs/wiki/log.md`.

## [2026-04-28] sync | fixcity admin ticket create unblocked after static closure fix
- recepito fix owner-side Fixcity su errore runtime `/fixcity/admin/tickets/create`: `Using $this when not in object context`.
- allineamento boundary: problema nel resource schema backend (`TicketForm`), non nel layer tema.
- riferimento owner-side: `../../../Modules/Fixcity/docs/wiki/log.md`.

## [2026-04-28] sync | unblock admin topbar da fix config team model (User)
- recepito fix cross-modulo User per errore `/admin`: `TeamModelNotConfigured`.
- allineamento runtime: quando `permission.teams=true`, e' obbligatorio `permission.models.team`.
- riferimento owner-side: `../../../Modules/User/docs/wiki/troubleshooting/spatie-permission-team-model-not-configured.md`.

## [2026-04-28] recheck | screenshot after fix step dati parity
- eseguito recheck visuale post-fix sulla stessa URL/step con evidenza screenshot.
- riferimento screenshot: `../../../Modules/Fixcity/docs/assets/segnalazione-step-dati-after-fix-2026-04-28-full-recheck.png`.
- esito: clipping search risolto, stato mappa stabilizzato, overlay testuale assente; sidebar non ambigua ma ancora visivamente compressa.
- pagina aggiornata: `comparisons/segnalazione-crea-step-dati-screenshot-audit.md`.

## [2026-04-28] fix | wizard step dati parity css (sidebar/search/spacing) + build
- source: `../../../resources/css/app.css`
- applicati fix CSS su wizard: indicatore accordion duplicato rimosso, link sidebar resi piu' leggibili, ridotto spacing verticale, padding input search corretto con `autocomplete-icon`.
- build/copy eseguiti: `npm run build && npm run copy`.
- pagina aggiornata: `comparisons/segnalazione-crea-step-dati-screenshot-audit.md`.

## [2026-04-28] tooling | playwright mcp verification + screenshot audit refinement
- verificata disponibilita' Playwright MCP in runtime locale con `npx -y @playwright/mcp@latest --help`.
- raffinato audit visuale screenshot step `Dati della segnalazione` con evidenza file e piano fix owner-side tema.
- pagina aggiornata: `comparisons/segnalazione-crea-step-dati-screenshot-audit.md`.

## [2026-04-28] audit | screenshot runtime segnalazione-crea step dati
- formalizzato audit visuale da screenshot utente sulla URL `http://127.0.0.1:8001/it/tests/segnalazione-crea?step=form.dati-della-segnalazione%3A%3Adata%3A%3Awizard-step`.
- registrati i sintomi principali: sidebar vuota ma dominante, stato accordion ambiguo, spacing eccessivo, search input con clipping, mappa con testo grezzo sovrapposto.
- nuova pagina: `comparisons/segnalazione-crea-step-dati-screenshot-audit.md`.

## [2026-04-28] governance | boundary tema su reintroduzione pacchetti Laravel 13
- chiarito che la reintroduzione dipendenze post-upgrade resta owner del modulo (`Modules/*/composer.json`), non del tema.
- allineata la conoscenza locale alla decisione root e alla matrice modulo Xot.
- nuova pagina: `concepts/laravel13-package-boundary-for-themes.md`.

## [2026-04-28] ops | context-compression plugin recepito nel tema
- recepita configurazione operativa plugin OpenRouter `context-compression` e MCP `context-mode`.
- aggiornata pagina tema:
  - `concepts/context-compression-plugin.md`
- aggiunti controlli pratici (install, config, verifica) per prevenire errori di context overflow nelle analisi tema.

## [2026-04-28] governance | second brain bootstrap recepito nel tema
- recepito runbook root `../../../../bashscripts/docs/second-brain-session-bootstrap.sh`.
- aggiornato index locale con backlink a `../../../../docs/wiki/concepts/second-brain-session-bootstrap.md`.
- regola confermata: prima retrieval wiki/QMD, poi interventi di parity visuale/catena asset del tema.

## [2026-04-28] governance | hard enforcement PHPMD standalone `.phar`
- recepita la rimozione di `phpmd/phpmd` da Composer nella root Laravel.
- confermata regola operativa tema: quality checks con `php /home/zorin/.local/bin/phpmd.phar`.
- aggiornata memoria locale per prevenire reintroduzione di `vendor/bin/phpmd` nei runbook del tema.

## [2026-04-28] governance | recepita regola root PHPMD standalone `.phar`
- aggiunto backlink nell'indice locale Sixteen verso `docs/wiki/concepts/phpmd-standalone-phar-rule.md`.
- chiarito che i quality gates del tema non devono introdurre PHPMD via Composer: il riferimento resta il `.phar` standalone del workflow progetto.

## [2026-04-28] docs | second brain boundary localized for Sixteen
- created `concepts/second-brain-theme-boundary.md`.
- documented what the theme should accumulate in its second brain: parity rules, ownership boundaries, integration rules, and anti-patterns.
- updated local index with the new boundary page.

## [2026-04-28] docs | boundary theme/modulo su custom field Filament (quality pack)
- aggiornata `concepts/filament-custom-field-binding-modifiers-theme-boundary.md` con quality pack:
  - best practices
  - bad practices
  - false friends
  - link verificati Filament/Livewire
- rafforzato confine DRY+KISS: tema owner della parity visuale, modulo owner del contratto state/binding.
- ingest eseguito in QMD index `fixcity` (collection `sixteen-wiki` aggiornata).

## [2026-04-27] sync | scopo mappa ticket-create recepito dal modulo Fixcity
- recepita analisi business della mappa nello step `data` (`fixcity/admin/tickets/create`): coordinate come dato operativo per smistamento e priorita'.
- confermato boundary tema: Sixteen cura la parity visuale ma non ridefinisce semantica dominio del campo `location`.
- riferimento: `../../../Modules/Fixcity/docs/wiki/concepts/location-capture-map-wizard.md`.

## [2026-04-27] sync | reusable search component discipline from Geo
- recepita regola cross-modulo: pattern search dei picker va estratto in componente riutilizzabile.
- riferimento: `../../../Modules/Geo/docs/wiki/concepts/reusable-search-ui-component-rule.md`.

## [2026-04-27] verifica | story 8-61 — screenshot runtime admin map con blocker 500
- raccolto screenshot contesto route admin: `scripts/admin-map-context.png`.
- diagnostica visuale: la pagina non renderizza il form mappa perche' cade prima in 500 (`Unsupported cipher or incorrect key length`).
- allineato boundary: in questo stato la mappa non e' verificabile, priorita al ripristino bootstrap Laravel.

## [2026-04-27] fix | story 8-59 — script credenziali da .env + placement map_diagnostic
- aggiornato `scripts/inspect-fixcity-admin-ticket-create-map.cjs` per leggere credenziali admin solo da `laravel/.env` (niente hardcoded).
- aggiunto `scripts/map_diagnostic.py` nella cartella `scripts` esistente del tema, conforme alla regola placement.
- formalizzata regola in `concepts/theme-owned-scripts-rule.md` (no credenziali hardcoded, no nuove cartelle root scripts/bashscripts).

## [2026-04-27] sync | geo admin map lens and controls visibility
- recepito fix cross-modulo su route admin ticket create: lente search ridimensionata e controlli mappa Lit resi sempre visibili.
- riferimento: `../../../Modules/Geo/docs/wiki/concepts/admin-map-magnifier-and-controls-visibility.md`.

## [2026-04-27] sync | lit light-dom map controls and reactive state
- recepito aggiornamento modulo Geo su `CoordinatePicker` Lit in Light DOM (iniezione CSS text esplicita).
- recepito fix reattivita stato coordinate lato bridge Alpine/Livewire (pattern immutabile).
- riferimento: `../../../Modules/Geo/docs/wiki/concepts/lit-light-dom-map-controls-and-sync.md`.

## [2026-04-27] sync | geo module build contract and runtime dependency
- recepito fix build modulo Geo (`vite.config.js` entry reali + sintassi `coordinate-picker-lit.js`).
- allineato il tema al principio: stabilita runtime mappa dipende anche dalla salute build del modulo owner Geo.
- riferimento: `../../../Modules/Geo/docs/wiki/concepts/geo-vite-build-contract.md`.

## [2026-04-27] verifica | admin map route visual check
- route admin `fixcity/admin/tickets/create` raggiunta e verificata in step `form.data::data::wizard-step`.
- confermata separazione contesto: runtime admin dipende dalla chain asset Geo (non dal solo CSS frontoffice del tema).
- recepita evidenza cross-modulo: fallback a `themes/Geo/js/map-picker-component.js` con asset mancanti su `/modules/geo/`.
- dopo hardening Geo, verificata chain locale-first: `themes/Geo/js/geo.js` caricato correttamente (HEAD/GET 200) e nessun ricorso primario a `unpkg`.

## [2026-04-27] sync | fixcity admin map asset chain analysis
- recepita analisi runtime della route `fixcity/admin/tickets/create`.
- confermato che il tema non deve sostituire la catena runtime canonica della mappa con fallback legacy.
- riferimento operativo: `../../../Modules/Fixcity/docs/stories/wizard-map-runtime-asset-chain.md`.
- collegamento architetturale: `../../../Modules/Fixcity/docs/wiki/log.md`.

## [2026-04-27] governance | obsidian vault alignment for theme docs
- aggiunta pagina `concepts/obsidian-vault-alignment.md`.
- fissata checklist minima index/log/link cross-modulo per evitare docs non ingestite.
- ingest knowledge base eseguito a livello progetto (`qmd update`) dopo update tema/moduli.

## [2026-04-27] governance | frontoffice vs admin panel style ownership
- aggiunta pagina `concepts/filament-admin-style-ownership-boundary.md`.
- chiarito che Sixteen governa frontoffice; bug visuali mappa in panel admin vanno validati nel contesto Filament admin.

## [2026-04-27] sync | policy matrix awareness in theme docs
- collegata la matrice policy modulo-per-modulo alla documentazione tema.
- ribadito che Sixteen non sceglie la base policy: renderizza capability backend.

## [2026-04-27] governance | policy rendering boundary with backend ACL
- chiarito boundary tema: Sixteen non sceglie tra `UserBasePolicy` e `XotBasePolicy`.
- il tema renderizza solo stati autorizzativi gia' risolti lato backend.
- nuova pagina: `concepts/policy-rendering-boundary.md`.

## [2026-04-27] sync | profiles owner boundary anti-regressione
- recepita regola aggiornata: nessuna migrazione additiva `add_*_to_profiles_table` nei moduli non owner.
- caso reale rimosso dal modulo User: `2026_04_27_000000_add_credits_to_profiles_table.php`.
- owner unico confermato: `Modules/Fixcity/...create_profiles_table.php`.

## [2026-04-27] sync | profiles migration nullable credits contract
- allineata knowledge cross-layer al fix profilo: `credits` e' opzionale e non deve bloccare il flusso UI/registrazione.
- riferimento modulo: `../../../Modules/Fixcity/docs/wiki/concepts/profiles-uuid-contract.md`.
- riferimento root: `../../../../docs/wiki/concepts/profiles-uuid-single-migration-rule.md`.

## [2026-04-23] governance | Route target recheck nel runtime reale del tema
- documentata la regola `route-target-recheck-rule`
- per fix visuali/runtime Sixteen serve recheck della route finale reale dopo build/copy asset, non basta il controllo del componente isolato

## [2026-04-23] governance | Theme bundle integration false friends
- Documentate best practices, bad practices e false friends sull'integrazione bundle del tema con componenti Lit/modulari.
- Regola centrale: il tag custom nel Blade non basta; import in `resources/js/app.js`, build, copy e verifica URL reale sono parte del contratto.
- Nuova pagina: `concepts/theme-bundle-integration-false-friends.md`.

## [2026-04-23] fix | GeopointPicker JS missing from theme bundle
- **problema**: `<geopoint-picker-lit>` non viene riconosciuto dal browser perché il JS non era importato in `app.js`
- **fix**: aggiunto import in `resources/js/app.js` e rebuild tema
- **wiki**: `concepts/geo-lit-components-must-be-imported-rule.md`
- **modules wiki**: `Modules/Geo/docs/wiki/concepts/geopoint-picker-map-invisible-wizard-fix.md`

## [2026-04-23] deletion | Removed redundant Folio page segnalazione-crea.blade.php
- **file removed**: `resources/views/pages/segnalazione-crea.blade.php`
- **reason**: duplicated CMS-driven page (`tests.segnalazione-crea.json` → block view → wizard). Shadowed Folio page with its own route name created confusion; the only production channel is CMS JSON blocks.
- **wiki**: `concepts/no-cms-shadowed-folio-pages-rule.md`

## [2026-04-23] discovery | CMS block architecture for segnalazione-crea
- **sources**: `resources/views/pages/tests/[slug].blade.php`, `config/local/fixcity/database/content/pages/tests.segnalazione-crea.json`, `resources/views/components/blocks/tests/segnalazione-crea.blade.php`
- **decision**: URL /it/tests/segnalazione-crea è CMS-driven (JSON → block view → widget), NON Folio hardcoded. Il tema Sixteen definisce block view come thin wrapper.
- **wiki**: `concepts/theme-cms-block-architecture-segnalazione-crea.md`

## [2026-04-22] contract | public wizard map stability without page css
- **sources:** `Modules/Geo/resources/js/components/coordinate-picker-lit.js`, `docs/wiki/concepts/no-page-specific-css.md`
- **decision:** la stabilita visuale della mappa nei wizard pubblici dipende da box/layout tema stabili e da runtime Geo debounced; il tema non deve introdurre CSS per pagina o workaround JS per compensare flicker runtime.
- **wiki:** `concepts/leaflet-map-flicker-visual-contract.md`

## [2026-04-23] fix | segnalazione-crea map flicker + geolocate when empty
- **sources:** `Modules/Geo/resources/js/components/coordinate-picker-lit.js`, `Modules/Fixcity/app/Filament/Widgets/CreateTicketWizardWidget.php`, `Themes/Sixteen/resources/css/app.css`
- **root cause:** refresh loop troppo aggressivo (invalidate + tile redraw + setView ripetuti) causava lampeggio; coordinate vuote senza `geolocateWhenEmpty()` non centravano sulla posizione corrente.
- **decision:** refresh con invalidate differito, redraw tile solo su `tileerror`/fullscreen; `CoordinatePicker` dello step dati configurato con `->geolocateWhenEmpty()`.

## [2026-04-23] fix | segnalazione-crea navbar green + map fullscreen contracts
- **sources:** `resources/css/app.css`, `resources/views/components/sections/header/v1.blade.php`, `docs/wiki/concepts/segnalazione-crea-navbar-green-contract.md`, `docs/wiki/concepts/coordinate-picker-fullscreen-wizard-contract.md`, `Modules/Geo/docs/wiki/concepts/coordinate-picker-fullscreen-wizard-contract.md`, `Modules/Fixcity/docs/wiki/concepts/segnalazione-crea-map-fullscreen-contract.md`
- **root cause header:** documentazione precedente indicava navbar chiara/theme-light-desk e `app.css` aveva blocchi header duplicati; cambiando un blocco intermedio il blu/bianco poteva vincere ancora.
- **root cause map:** fullscreen solo CSS + `body.overflow=hidden` non bloccava sempre `html`/stacking context wizard; Leaflet richiede refresh dopo transizione.
- **decision:** navbar `segnalazione-crea` verde `#007a52`; coordinate-picker fullscreen usa contratto browser fullscreen + classe document-level e refresh differiti.

## [2026-04-22] governance | No page-specific CSS — Design Comuni principle
- **regola**: vietato `.ticket-wizard-root`, `[data-slug="..."]` o qualsiasi selettore CSS per pagina/widget specifico
- **principio**: Design Comuni ufficiale usa solo selettori di componente (`.it-*`); un wizard è un componente, non "la pagina segnalazione-crea"
- **varianti**: usare props/attributi sul componente, non il nome della pagina come discriminante CSS
- **rule**: `bashscripts/ai/.claude/rules/no-page-specific-css.md`
- **wiki concept**: `laravel/Themes/Sixteen/docs/wiki/concepts/no-page-specific-css.md`
- **memory**: `memory/feedback_no_page_specific_css.md`

## [2026-04-22] ops | context-mode + QMD per docs tema e story BMAD
- **regola root**: `docs/wiki/concepts/context-compression-discipline.md`
- **scope Sixteen**: per story su wizard/header/parity non caricare l'intero corpus tema; usare QMD/context-mode e passare allo skill solo indici, concetti wiki e snippet essenziali.
- **verifica**: context-mode plugin/MCP connessi; QMD aggiorna `theme-sixteen` con 1005 file indicizzati.

## [2026-04-22] governance | getSummarySchema wizard con Infolists
- **regola**: i riepiloghi wizard Sixteen/Fixcity devono usare Filament Infolists in `getSummarySchema()`; `SchemaView` non e' ammesso per summary read-only strutturati.
- **root wiki**: `docs/wiki/concepts/filament-summary-infolist-rule.md`
- **fonte ufficiale**: https://filamentphp.com/docs/5.x/infolists/overview
- **nota DRY + KISS**: linkare la regola root dagli indici locali, non duplicare esempi divergenti.

## [2026-04-21] governance | header segnalazione — una sola fonte CSS + wiki kit
- **sources:** `laravel/Themes/Sixteen/resources/views/components/layouts/main.blade.php`, `laravel/Themes/Sixteen/resources/views/components/sections/header/v1.blade.php`, `laravel/Themes/Sixteen/resources/css/app.css`, `laravel/Themes/Sixteen/docs/wiki/concepts/header-color-parity.md`, `docs/wiki/index.md`, `docs/wiki/concepts/header-section-owner-rule.md`
- **summary:** eliminato `<style>` inline duplicato e link 404 a override esterni; regola Design Comuni (repo + GitHub Pages) e anti-pattern “navbar tutta verde” descritti in `header-color-parity.md`; commento SSoT in `app.css`.
- **riferimento:** [italia/design-comuni-pagine-statiche](https://github.com/italia/design-comuni-pagine-statiche)

## [2026-04-21] story | 8-40 segnalazione-crea — navbar bianca + bundle map
- **sources:** `resources/css/app.css` (`.it-header-wrapper.is-segnalazione-crea …`), `resources/views/components/layouts/main.blade.php` (override fine `<head>` dopo CDN BI 2.18 + Vite), `resources/views/components/sections/header/v1.blade.php` (classe BI **`theme-light-desk`** su `.it-header-navbar-wrapper` quando `tests/segnalazione-crea` — default BI è `background:#06c`), `resources/js/app.js` (rimosso `filament/map-picker.js` duplicato).
- **riferimento visivo:** [Design Comuni segnalazione-02-dati](https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazione-02-dati.html).
- **build:** `npm run build` tema Sixteen dopo modifiche Geo/JS.

## [2026-04-21] fix | header nav links background — design-comuni-global.css

- **sources:** `resources/css/design-comuni-global.css`
- **summary:** `.it-header-center-wrapper` e `.it-header-navbar-wrapper` corretti da `#007a52` (verde) a `#fff`; `.navbar-nav .nav-link` e `.navbar-secondary .nav-link` corretti da `#fff` a `#0066CC` (blu istituzionale). Allineamento visuale con reference Design Comuni `segnalazione-02-dati.html`.

## [2026-04-21] fix | segnalazione-crea header/nav parity reale su section owner

- **sources:** `resources/views/components/sections/header/v1.blade.php`, `resources/css/app.css`, `../../Modules/Fixcity/resources/views/filament/widgets/ticket-create-wizard.blade.php`
- **summary:** applicata classe owner-side `is-segnalazione-crea` direttamente sull'header reale; rimosse override colore fragili nel widget wizard; forzati navbar/slim bar verdi con link trasparenti (niente background blu sui link); allineata posizione hamburger su mobile (sinistra logo, centrato verticalmente); nascosto header wizard Filament duplicato per eliminare linea verde centrale nello stepper.
- **build:** `npm run build && npm run copy` eseguiti con successo.

## [2026-04-21] fix | segnalazione-crea crash su component alias filament non risolto

- **sources:** `../../Modules/Geo/resources/views/filament/forms/components/coordinate-picker.blade.php`
- **summary:** rimossa invocazione legacy `x-filament-forms::field-wrapper.error-message` non disponibile nella versione Filament corrente; il crash bloccava il rendering del wizard in `/it/tests/segnalazione-crea`.
- **artifact:** `../../Modules/Geo/docs/wiki/troubleshooting/filament-field-wrapper-error-message-missing.md`

## [2026-04-21] governance | forbidden folders zero tolerance recepita

- **sources:** `../../../../docs/wiki/concepts/forbidden-folders-zero-tolerance-rule.md`
- **summary:** per tema Sixteen recepita regola strutturale: non devono esistere `docs/archive`, `_docs`, `lang/lang`; mantenere solo percorsi canonici.

## [2026-04-21] story | 8-41 segnalazione-crea css dedup header-stepper-hamburger parity

- **sources:** `resources/css/app.css`, `resources/views/components/sections/header/v1.blade.php`, `../../Modules/Fixcity/resources/views/filament/widgets/ticket-create-wizard.blade.php`
- **summary:** creata story dedicata al cleanup dei blocchi CSS duplicati/conflittuali della pagina `tests/segnalazione-crea`, con focus su header link background, posizionamento hamburger, menu aperto mobile e artifact stepper.
- **artifact:** `../../../../../_bmad-output/implementation-artifacts/8-41-segnalazione-crea-css-dedup-header-stepper-hamburger-parity.md`

## [2026-04-21] audit | parity segnalazione-privacy (header colori, cta, responsive)

- **sources:** `../../Modules/Fixcity/resources/views/filament/widgets/ticket-create-wizard.blade.php`, `resources/css/app.css`
- **summary:** confronto visuale locale vs reference Design Comuni su mobile/tablet/desktop; colori header allineati (`slim` verde scuro, barra nav verde), rimossa CTA duplicata `Successivo`; `Accedi all'area personale` forzato al verde istituzionale.
- **cta rule:** `Avanti` riposizionato sotto checkbox privacy su tutti i breakpoint.
- **artifact:** `concepts/segnalazione-privacy-parity-audit.md`

## [2026-04-21] governance | no docs/archive per nuova documentazione tema

- **summary:** fissata regola locale: niente nuovi file in `Themes/Sixteen/docs/archive/`; nuova conoscenza solo in `docs/wiki/` e `docs/raw/`.
- **artifact:** `concepts/no-docs-archive-rule.md`

## [2026-04-21] governance | struttura wiki tema canonica e sacra

- **summary:** recepita regola root sulla struttura wiki canonica, inclusi `_archive` e `_templates` come parti valide del wiki.
- **artifact:** `../../../../docs/wiki/concepts/wiki-sacred-structure-rule.md`

## [2026-04-21] ui | segnalazione-crea cta parity (avanti unico)

- **sources:** `../../Modules/Fixcity/resources/views/filament/widgets/ticket-create-wizard.blade.php`, `resources/css/app.css`
- **summary:** eliminata la doppia percezione CTA nel wizard (`Successivo` + `Avanti`) mantenendo una sola CTA primaria visibile (`Avanti`) in linea con il pattern Design Comuni; risolti marker di merge residui in `resources/css/app.css`.
- **build:** eseguiti `npm install`, `npm run build`, `npm run copy`.
- **refinement:** allineate classi pulsanti navigazione wizard (`fw-bold`, `btn-next`, `btn-prev`) per miglior parity sui CTA.

## [2026-04-21] fix | livewire queryexception su cache table

- **sources:** `../../database/migrations/2026_04_21_111944_create_cache_table.php`
- **summary:** risolto errore 500 `SQLSTATE[42S02]` su `POST /livewire-*/update` dovuto a tabella `cache` assente; creata anche `cache_locks` con migrazione mirata.
- **guardrail:** per frontoffice con Livewire, garantire disponibilità tabelle cache quando lo store runtime può risolvere su database.
- **artifact:** `concepts/livewire-cache-table-rate-limiter.md`
- **hardening:** resa idempotente la migrazione duplicata `2026_04_21_112114_create_cache_table` per prevenire regressioni su migrate completi.

## [2026-04-21] fix | catena errori 404 + livewire/alpine bootstrap

- **sources:** `resources/views/components/layouts/main.blade.php`, `../../Modules/Geo/resources/views/filament/forms/components/map-picker.blade.php`
- **summary:** rimossi `<link>` hardcoded verso `/themes/Sixteen/css/*` non presenti nel deploy; mantenuto solo bundle Vite.
- **deploy:** pubblicato `themes/Geo/js/geo.js` nel webroot attivo (`public_html/themes/Geo/js/geo.js`).
- **runtime:** riallineati asset Livewire/Filament (`livewire:publish --assets`, `filament:assets`) e pulita cache (`optimize:clear`).
- **resilienza:** `geoMapPickerField` registrato sia in init immediata sia in hook `alpine:init`.
- **artifact:** `concepts/segnalazione-runtime-asset-integrity.md`

## [2026-04-21] refactor | lit component ownership fuori da blade

- **sources:** `../../Modules/Geo/resources/views/filament/forms/components/map-picker.blade.php`, `resources/js/app.js`
- **summary:** eliminata definizione LitElement inline in Blade del map picker; runtime componente demandato al file JS modulo importato nel bundle tema.
- **import:** aggiunto `@modules/Geo/resources/js/filament/map-picker.js` in `resources/js/app.js`.
- **guardrail:** Blade = host/binding; LitElement = solo JS component module.

## [2026-04-21] governance | no absolute paths in git-tracked config (project-wide)

- **sources:** `../../../../../laravel/.mcp.json`, `../../../../../docs/wiki/concepts/no-absolute-paths-in-config.md`
- **summary:** recepita nel tema la regola di portabilita del progetto: file config tracciati da git non devono contenere path assoluti legati a una macchina locale; usare path relativi e variabili env.

## [2026-04-21] governance | Blade extraction generale + header partials

- sources:
  - `../../../../docs/wiki/concepts/blade-component-extraction-governance.md`
  - `../../../../docs/wiki/concepts/sixteen-header-composition-rule.md`
  - `../../../../../memory/feedback_sixteen-header-v1-ssot.md`
- summary:
  - confermata la regola: cercare componenti riusabili in tutte le Blade del tema, non solo in `header/v1.blade.php`
  - per blocchi locali a una section usare `partials/` sotto la directory owner
  - per blocchi locali all'header Sixteen usare `resources/views/components/sections/header/partials/`; `v1.blade.php` resta owner

## [2026-04-21] story | 8-37 Blade reusable extraction e header partials

- sources:
  - `../../../../../_bmad-output/implementation-artifacts/8-37-blade-reusable-components-extraction-and-header-partials-governance.md`
  - `../../../../docs/wiki/concepts/sixteen-header-composition-rule.md`
- summary:
  - fissata la regola generale: cercare componenti riusabili in tutte le Blade del tema
  - se un componente e' locale all'header `v1.blade.php`, va messo sotto `resources/views/components/sections/header/partials/`
  - `v1.blade.php` resta owner/orchestratore; i partial non diventano owner

## [2026-04-21] implement | Story 8-36 — header v1 sottocomponenti DRY/KISS consolidati

- **sources:** `resources/views/components/sections/header/v1.blade.php`, `resources/views/components/sections/header/personal-area-guest-cta.blade.php`, `resources/views/components/sections/header/personal-area-guest-parity.blade.php`, `resources/views/components/sections/header/user-dropdown.blade.php`
- **summary:** consolidati sotto il section owner i blocchi guest CTA, guest parity dropdown e user dropdown autenticato; `v1.blade.php` resta source of truth e orchestration layer dell'header.
- **guardrail:** l'estrazione riduce il rumore ma non cambia l'owner runtime di `<x-section slug="header" />`.

## [2026-04-20] governance | Story 8-35 — SSoT header = `components/sections/header/v1.blade.php`

- **sources:** `../../../../docs/wiki/concepts/header-section-owner-rule.md`, `../../../../../memory/feedback_sixteen-header-v1-ssot.md`, artifact 8-35
- **summary:** regola esplicita per LLM e sviluppatori: `<x-section slug="header" />` → unico blade owner `resources/views/components/sections/header/v1.blade.php`; allineati MEMORY, AGENTS, cursor rules, wiki root; corretto drift su `bootstrap-italia/header` come finta SSoT.
- **index:** [wiki index](./index.md)

## [2026-04-20] implement | Story 7-54 — dropdown slim senza Alpine inline su wizard Livewire

- **sources:** `resources/views/components/sections/header/v1.blade.php`, `resources/js/app.js`, artifact `7-54-segnalazione-crea-header-slim-dropdowns-data-bs-toggle-unification.md`
- **summary:** lingua e utente autenticato usano `data-bs-toggle="dropdown"` (stesso wiring del blocco guest parity e del polyfill `DOMContentLoaded` in `app.js`). Rimosso `x-data`/`x-show` che su `/tests/segnalazione-crea` potevano non inizializzarsi. Rimosso override `background-color: #0066CC` inline sulla slim bar; sfondo da `design-comuni-tokens.css`. Avatar con `img` arrotondato + bordo chiaro se URL presente.
- **wiki:** [header-authenticated-state](./concepts/header-authenticated-state.md), [header-slim-dropdown-behavior](./concepts/header-slim-dropdown-behavior.md)

## [2026-04-20] rule | header SSoT del section layout

- sources:
  - `resources/views/components/sections/header/v1.blade.php`
  - `../../../../docs/wiki/concepts/header-section-owner-rule.md`
- summary:
  - fissata come regola permanente del tema: se il layout usa `<x-section slug="header" />`, l'header owner e fonte di verita e' `components/sections/header/v1.blade.php`
  - `bootstrap-italia/header.blade.php` non va assunto come owner automatico per `segnalazione-crea`

## [2026-04-20] story | 8-36 header subcomponents extraction sotto sections/header

- sources:
  - `resources/views/components/sections/header/v1.blade.php`
  - `../../../../../_bmad-output/implementation-artifacts/8-36-header-section-v1-subcomponents-extraction-dry-kiss.md`
  - `../../../../../.planning/stories/8-36-header-section-v1-subcomponents-extraction-dry-kiss.story.md`
- summary:
  - creata story dedicata per estrarre blocchi riusabili dal section header mantenendo `v1.blade.php` come owner
  - candidati espliciti: language switcher, user dropdown, guest CTA e blocchi presentazionali affini
  - regola DRY + KISS aggiornata da story 8-37: eventuali estrazioni locali all'header solo sotto `resources/views/components/sections/header/partials/`, mai come nuovo header parallelo
  - wiki root: [sixteen-header-composition-rule](../../../../../docs/wiki/concepts/sixteen-header-composition-rule.md); aggiornati README tema, AGENTS, MEMORY, `.cursor/rules/sixteen-header-v1-ssot.mdc`

## [2026-04-20] implement | header slim segnalazione-crea fixato nel section owner reale

- sources:
  - `resources/views/components/sections/header/v1.blade.php`
  - `resources/js/app.js`
  - `resources/css/app.css`
  - `./concepts/header-authenticated-state.md`
- summary:
  - corretto il path owner reale del runtime `segnalazione-crea`: l'header passa da `<x-section slug="header" />` e va fissato in `components/sections/header/v1.blade.php`
  - rimossi i dropdown Alpine inline dal section header; ora lingua e utente usano il wiring unico `data-bs-toggle="dropdown"` gia gestito da `app.js`
  - riallineato il blocco utente al reference `Mario Rossi`: nome in primo piano, avatar secondario, chevron e menu coerenti con slim header
  - build tema eseguita con successo; restano solo warning noti su asset logo risolti a runtime

## [2026-04-20] rule | dropdown slim header runtime-critical e color-parity

- sources:
  - `./concepts/header-authenticated-state.md`
  - `https://italia.github.io/design-comuni-pagine-statiche/servizi/graduatoria-area-personale.html`
  - `_bmad-output/implementation-artifacts/8-33-segnalazione-crea-header-language-and-user-dropdown-functional-color-parity.md`
- summary:
  - fissato il guardrail: dropdown lingua e dropdown utente nel slim header devono funzionare nel runtime reale
  - la parity richiesta include anche i colori e gli stati visuali dei menu aperti/chiusi
  - il quality gate corretto e' screenshot-driven su pagina reale come `segnalazione-crea`

## [2026-04-20] implement | header owner-side con display name e avatar fallback raffinati

- sources:
  - `resources/views/components/bootstrap-italia/header.blade.php`
- summary:
  - raffinata la risoluzione del blocco personale autenticato nel componente owner dell'header
  - display name risolto in ordine `full_name` → `first_name + last_name` → `name` → `email`
  - avatar risolto in ordine `profile_photo_url` → `profile_photo_path` → icona fallback owner-side
  - nickname e chevron del menu utente resi visibili anche fuori dal solo desktop largo

## [2026-04-20] wiki | header guest/auth — fonte di verità e anti-regressione

- sources:
  - `./concepts/header-authenticated-state.md`
  - `../../../../../.planning/stories/5.0-header-logged-in-state.story.md`
  - `../../../../../.cursor/rules/design-comuni-header-auth-state.mdc`
- summary:
  - pagina concetto aggiornata: `bootstrap-italia/header` + `pub_theme::ui.personal_area` / `header_area_personale.*`
  - guest: solo CTA «Accedi all'area personale»; auth: avatar + nickname + dropdown + POST logout
  - screenshot: confrontare guest vs auth su stessa URL (es. `tests/segnalazione-crea`)

## [2026-04-20] ui | header slim area personale se autenticato (Design Comuni)

- sources:
  - `resources/views/components/bootstrap-italia/header.blade.php`
  - `lang/it/ui.php`, `lang/en/ui.php`
- summary:
  - su pagine che usano `<x-bootstrap-italia.header />` il slim header mostra nome utente + dropdown (servizi, pratiche, notifiche, impostazioni, esci) come reference `segnalazione-area-personale.html`
  - ospite: pulsante verso `route('login')` e copy da `pub_theme::ui.personal_area`
  - testi menu da `pub_theme::ui.header_area_personale.*` (struttura a 5 elementi per label)

## [2026-04-20] i18n | login view without italian literal phrases

- sources:
  - `resources/views/pages/auth/login.blade.php`
  - `../../../Modules/User/lang/it/auth.php`
  - `../../../Modules/User/lang/en/auth.php`
- summary:
  - rimosse frasi italiane inline dalla view del tema
  - la view usa solo chiavi traduzione dal modulo `User` (`user::auth.login.page.*`)
  - mantenuta coerenza DRY: copy condivisa cross-theme nel modulo di dominio

## [2026-04-20] ui | login auth riallineata a design comuni con widget filament

- sources:
  - `resources/views/pages/auth/login.blade.php`
  - `resources/views/filament/widgets/auth/login.blade.php`
  - `../../../Modules/User/app/Filament/Widgets/Auth/LoginWidget.php`
- summary:
  - la pagina `/it/auth/login` ora usa un layout visivo coerente con il linguaggio Design Comuni (hero informativa + card servizio)
  - mantenuto il principio architetturale: il form resta nel `LoginWidget` Filament (modulo User), il tema gestisce il vestito
  - semplificato il widget style per coerenza con componenti pubblici del tema

## [2026-04-20] ui | zoom mappa con svg standard e no underline

- sources:
  - `../../Modules/Geo/resources/js/components/coordinate-picker-field.js`
  - `../../Modules/Fixcity/app/Filament/Widgets/CreateTicketWizardWidget.php`
- summary:
  - resa uniforme la UI dei controlli zoom (`+/-`) con icone SVG standard e senza sottolineatura
  - aumentato zoom iniziale del `MapPicker` di test (`location1`) a 15

## [2026-04-20] ui | controlli zoom mappa allineati a toolbar custom

- sources:
  - `../../Modules/Geo/resources/js/components/coordinate-picker-field.js`
- summary:
  - pulsanti `+/-` Leaflet allineati al look&feel dei controlli custom (`layer/fullscreen/current position`)
  - aggiornate icone fullscreen/locate con simboli standard orientati all'abitudine utente

## [2026-04-20] build | rebuild tema dopo marker custom mappicker

- sources:
  - `../../Modules/Geo/resources/js/components/map-picker-marker-config.js`
- summary:
  - recepito marker SVG custom runtime del `MapPicker` nel bundle tema
  - mantenuto fallback marker locale, senza introduzione di dipendenze esterne

## [2026-04-20] governance | mappicker runtime ux recepita nel tema

- sources:
  - `../../Modules/Geo/resources/js/components/coordinate-picker-field.js`
  - `../../Modules/Geo/resources/views/filament/forms/components/coordinate-picker.blade.php`
  - `../../Modules/Geo/docs/wiki/concepts/mappicker-runtime-ux.md`
- summary:
  - recepita nel tema la regola runtime: coordinate iniziali mancanti in `MapPicker` => geolocalizzazione corrente
  - allineata la semantica di stato per evitare mappe inizializzate con coordinate spurie

## [2026-04-20] governance | mappicker xotbasefield recepito nel tema

- sources:
  - `../../Modules/Geo/app/Filament/Forms/Components/MapPicker.php`
  - `../../Modules/Geo/docs/wiki/concepts/mappicker-xotbasefield-rule.md`
- summary:
  - recepita nel tema la regola strutturale: `MapPicker` estende `XotBaseField` e non dipende da sibling field
  - ridotto rischio di regressioni runtime nei wizard con piu varianti mappa

## [2026-04-20] build | rebuild tema dopo fix mappicker (overlay, address readout, marker, fullscreen)

- sources:
  - `../../Modules/Geo/resources/views/filament/forms/components/coordinate-picker.blade.php`
  - `../../Modules/Geo/resources/js/components/coordinate-picker-field.js`
  - `public/assets/app-Cin6PdhR.js`
- summary:
  - rigenerato bundle runtime del tema con fix visuali/funzionali su `MapPicker` (`location1`)
  - eliminato overlap tra layer control e toolbar custom, migliorata gestione fullscreen

## [2026-04-20] build | rebuild tema dopo fix visibilita mappa e fullscreen reattivo

- sources:
  - `Modules/Geo/resources/js/components/geo-latlng-input.js`
  - `public/assets/app-V_uIqEqu.js`
  - `public/manifest.json`
- summary:
  - rigenerato bundle tema dopo fix runtime su `LatitudeLongitudeInput` (tile map visibili)
  - riallineata reattivita pulsante fullscreen nel web component Lit in Light DOM

## [2026-04-20] build | rebuild tema dopo autolocate-on-init per latitudelongitudeinput

- sources:
  - `Modules/Geo/resources/views/filament/forms/components/latitude-longitude-input-lit.blade.php`
  - `Modules/Geo/resources/js/components/geo-latlng-input.js`
  - `public/assets/app-iVHx4Bly.js`
- summary:
  - rigenerato bundle tema con supporto `auto-locate-on-init` quando coordinate iniziali sono null
  - mantenuta visibilità controlli mappa (layer/fullscreen/locate) nel wizard

## [2026-04-20] fix | latitudelongitudeinput runtime ux allineata nel tema

- sources:
  - `../../Modules/Geo/resources/views/filament/forms/components/latitude-longitude-input-lit.blade.php`
  - `../../Modules/Geo/resources/js/components/geo-latlng-input.js`
- summary:
  - recepita nel tema la regola runtime: su coordinate null avvio autolocate automatico
  - confermata necessità di mantenere visibili i controlli layer/fullscreen/locate nel flusso wizard

## [2026-04-20] governance | latitudelongitudeinput allineato a xotbasefield

- sources:
  - `../../Modules/Geo/app/Filament/Forms/Components/LatitudeLongitudeInput.php`
  - `../../Modules/Geo/docs/wiki/concepts/latitudelongitudeinput-xotbasefield-rule.md`
- summary:
  - recepita nel tema la regola architetturale Laraxot: `LatitudeLongitudeInput` non dipende da `CoordinatePicker`
  - ridotto coupling tra picker sibling e migliorata stabilità evolutiva lato UI runtime

## [2026-04-20] build | rebuild tema dopo fix fullscreen/drag in geolatlnginput

- sources:
  - `Modules/Geo/resources/js/components/geo-latlng-input.js`
  - `public/assets/app-bhwe-_Wn.js`
  - `public/manifest.json`
- summary:
  - eseguiti `npm run build` e `npm run copy` per distribuire fix runtime del picker legacy
  - rimane warning ambiente Node (< 20.19) ma build completata con successo

## [2026-04-20] build | rebuild tema dopo dedup input lat/lng nel picker legacy

- sources:
  - `Modules/Geo/resources/js/components/geo-latlng-input.js`
  - `public/assets/app-CXkgYcr7.js`
  - `public/manifest.json`
- summary:
  - rigenerato bundle tema dopo rimozione doppia coppia input coordinate nel web component `geo-latlng-input`
  - eseguito anche `npm run copy` per allineare runtime in `public_html/themes/Sixteen`

## [2026-04-20] governance | allineamento tema a class selector policy per mappe Geo

- sources:
  - `../../Modules/Geo/docs/wiki/concepts/leaflet-class-selector-governance.md`
- summary:
  - recepita nel tema la regola di integrazione runtime: mount mappe via classi locali del componente, mai via id globali
  - ridotto rischio collisioni quando lo stesso step contiene piu picker

## [2026-04-20] audit | tema incluso nello scan selector id-based

- sources:
  - `resources/*`
  - `public/assets/*` (read-only audit)
- summary:
  - completata ricognizione pattern id-based nel tema; classificati i casi non picker-runtime come legacy/docs/assets compilati
  - confermata priorità di enforcement sui componenti mappa runtime del modulo Geo

## [2026-04-20] fix | wizard segnalazione runtime restore after latitudelongitudeinput getter mismatch

- sources:
  - `Modules/Geo/resources/views/filament/forms/components/latitude-longitude-input-lit.blade.php`
- summary:
  - ripristinata compatibilita runtime della pagina `/it/tests/segnalazione-crea` dopo convergenza su `CoordinatePicker`
  - eliminata dipendenza lato view da getter legacy `getDefault*` non presenti nel wrapper attuale

## [2026-04-20] hotfix | ripristino rendering mappa legacy nello step wizard

- sources:
  - `Modules/Geo/resources/views/filament/forms/components/latitude-longitude-input.blade.php`
  - `public/assets/app-CoVltwNO.js`
- summary:
  - fix rapido regressione visuale: `LatitudeLongitudeInput` tornato visibile nello step `/it/tests/segnalazione-crea`
  - preservati i blocchi mappa commentati nel widget Fixcity (nessuna rimozione)

## [2026-04-20] fix | wizard mappa legacy - toolbar layer/geoloc ripristinato

- sources:
  - `Modules/Geo/resources/views/filament/forms/components/latitude-longitude-input.blade.php`
  - `public/assets/app-4WPb1Mkc.js`
- summary:
  - nel flusso `/it/tests/segnalazione-crea` ripristinata inizializzazione runtime del picker legacy (`x-init`)
  - toolbar con layer switch e posizione corrente ora visibile/stabile anche nel layout wizard

## [2026-04-20] refactor | convergenza runtime picker geo nel bundle tema

- sources:
  - `resources/js/app.js`
  - `public/assets/app-4WPb1Mkc.js`
  - `public/manifest.json`
- summary:
  - aggiornato bundle con convergenza eventi e controlli picker (`coords-changed`, locate esplicito, fullscreen coerente)
  - allineata UX mappe nello scenario wizard `/it/tests/segnalazione-crea`

## [2026-04-20] fix | legacy latitudelongitudeinput toolbar e fullscreen runtime

- sources:
  - `Modules/Geo/resources/views/filament/forms/components/latitude-longitude-input.blade.php`
  - `Modules/Geo/lang/it/latitude_longitude_input.php`
- summary:
  - migliorata UX nello step mappa di `/it/tests/segnalazione-crea`: pulsante "usa posizione corrente" sempre visibile nel toolbar
  - fullscreen del picker legacy allineato a copertura completa viewport (edge-to-edge)

## [2026-04-20] dev | coordinatepicker geo - rebuild asset tema

- sources:
  - `resources/js/app.js`
  - `public/manifest.json`
  - `public/assets/app-C-lswTBj.js`
- summary:
  - rebuild Vite dopo consolidamento `CoordinatePicker` (Lit + Leaflet) nel modulo Geo
  - verificata disponibilita runtime del custom element importato nel bundle tema

## [2026-04-20] dev | mappa wizard segnalazione - rebuild asset tema

- sources:
  - `resources/js/app.js`
  - `public/manifest.json`
- summary:
  - rebuild Vite tema Sixteen dopo refactor selector class-based nei picker Geo
  - verificato runtime pagina `/it/tests/segnalazione-crea` con step mappa attivo

## [2026-04-15] init | wiki bootstrap

- Added schema, index, and theme adoption guide.
## [2026-04-21] rule | Header style layer, no inline parity CSS

- Consolidata regola Design Comuni: `v1.blade.php` resta owner markup/composizione; colori e background header vanno nel CSS/token layer.
- Rimosso l'anti-pattern dello `<style>` condizionale per `is-segnalazione-crea`.
- Nuova pagina: `concepts/header-style-layer-rule.md`.
# 2026-04-22

- Ingestita decisione Design Comuni: lo step riepilogo segnalazione resta renderizzato dal widget Fixcity con entry Infolist; il tema governa layout/parity CSS e non reintroduce `SchemaView` come riepilogo primario.
- Ingestita nota `context-compression-plugin-runtime`: Sixteen contiene corpus visuale ampio; consultare wiki/QMD e non caricare batch report completi se non necessario.
- Ingestita regola `theme-css-only-parity-rule`: Sixteen e' owner unico del CSS Design Comuni per `segnalazione-crea`; le Blade dei moduli non devono contenere `<style>` o inline style JS per la parity.
- Ingestita regola `filament5-schema-section-namespace-rule`: il tema non deve forkare la pagina Folio per casi singoli; renderizza lo schema widget corretto e lascia `Section` a Filament Schemas.
- Ingestita regola `filament5-schema-form-access-rule`: il tema renderizza `{{ $this->form }}`; il widget legge stato tramite `$this->form`, non `getForm('form')`.
- Ingestita regola `segnalazione-map-and-section-spacing-parity`: controlli mappa e spacing sezione Disservizio si governano dal CSS tema con build/copy.
- Rafforzata regola CSS Design Comuni: niente selettori per-page tipo `.page-content[data-slug="tests.segnalazione-crea"]` per correzioni riusabili; usare selettori semantici component/site-level.
- Rafforzata regola CSS Design Comuni: niente `.ticket-wizard-root` per comportamenti comuni di wizard; usare hook site/component-level e non specializzare il ticket wizard rispetto agli altri wizard.
- Aggiunto piano header-first per parity dello step riepilogo e submit canonico: `../../Modules/Fixcity/docs/stories/wizard-summary-step-header-and-submit-parity-plan.md`.
# 2026-04-22 - CSS parity wizard owner tema

- Aggiunta `concepts/theme-owned-wizard-css-parity-rule.md`.
- Spostati gli override CSS del wizard segnalazione dal Blade Fixcity a `resources/css/app.css`.
- Regola build: dopo CSS tema eseguire `npm run build` e `npm run copy` da `laravel/Themes/Sixteen`.

# 2026-04-22 - Header navbar green component rule

- Aggiunta `concepts/header-navbar-green-component-rule.md`.
- Documentata la causa ricorsiva dei regressi blu/bianco: Bootstrap Italia ridefinisce colori a livello wrapper, container, navbar, link, stati e media query.
- Regola: correggere sempre il componente header in `resources/css/app.css`, mai tramite selettori per pagina o `.ticket-wizard-root`.

# 2026-04-23 - CSS globale, niente selettori per pagina (wizard parity)

- Nuova pagina: `concepts/global-css-no-page-selectors-wizard-parity.md`.
- Ribadita regola tema: parity via CSS globale/component-level, non via `.page-content[data-slug="..."]` o classi per wizard specifici.
## [2026-04-29] story | coordinate picker fullscreen theme layer
- Collegata story 8-74 al contratto tema Sixteen per fullscreen mappa nel wizard `segnalazione-crea`.
- Confermato CSS owner-side in `resources/css/app.css`, supporto `:fullscreen` + `.is-fullscreen`, niente selector page-scoped, build/copy obbligatorio dopo modifiche.

## [2026-05-04] comparison | segnalazione-01-privacy Design Comuni vs locale — HTML delta audit
- Aggiornato `comparisons/segnalazione-01-privacy-design-comuni-vs-local-wizard.md` con HTML reference completo estratto direttamente da github.io
- Documentati 13 delta visivi/strutturali con mappa classi BI → Tailwind e piano di correzione P0..P4
- Evidenziato problema critico: `create-ticket.blade.php` contiene ~80 righe `<style>` inline (violazione)
- Sezione "Contatta il comune" confermata assente nel locale: raccomandato CMS block
- Larghezza colonna form: reference `col-lg-8` (66%), locale `col-lg-10` (83%) — da correggere
- Story BMAD: 7-103 (ready-for-dev)

## 2026-05-04 (story 7-106 header parity step2)
- **Comparison doc created**: `docs/wiki/comparisons/segnalazione-02-dati-design-comuni-vs-local.md`
  - Full header HTML comparison (Design Comuni vs local wizard step2)
  - All Bootstrap classes mapped: `navbar-brand`, `it-header-slim-wrapper`, `nav-link.active`, etc.
  - Stepper analysis: step2 shows "2/3" with "Dati di segnalazione" active
- **Header v1.blade.php analysis**: 324 lines, Alpine.js mobile nav, all Tailwind mappings in `app.css` (4626 lines)
- **Story 7-106**: depends on 7-104 (step1 done) + 7-105 (class map done)

## 2026-05-04 (visual parity verification)
- **Header color fix**: `--dc-green-dark: #00402b` → `#0066CC` (Design Comuni primary blue)
- **Build + copy**: `npm run build` (5.50s) ✅, `npm run copy` ✅ → `public_html/themes/Sixteen/`
- **Skiplinks ✅**: P1a completed in story 7-104 (already implemented in `layouts/app.blade.php`)
- **Header structure ✅**: 3-tier (slim→center→navbar), all Bootstrap classes mapped to Tailwind in `app.css`
- **Stepper step2**: CSS ready in `app.css` (lines 3278-3350), requires Livewire JS for rendering
- **Story 7-106**: `ready-for-dev`, header analysis complete, comparison doc created
- **Next**: Browser verification at `http://127.0.0.1:8000/it/tests/segnalazione-crea?step=form.data::data::wizard-step`

## 2026-05-04 (header visual parity fix)
- **Header transparency fix**: Removed `text-white` from `navbar-brand` in v1.blade.php
  - Now: `class="d-lg-block navbar-brand"` (matches Design Comuni exactly)
  - Slim background: `#0066CC` blue (`--dc-green-dark`)
  - Brand text: Natural contrast on blue (no forced white)
- **Build**: `npm run build` (4.58s) ✅, `npm run copy` ✅
- **Story 7-106**: Updated with P1a ✅ + P1b ✅ completion
- **Comparison doc**: `segnalazione-02-dati-design-comuni-vs-local.md` created
## [2026-05-04] header-dynamic-navigation + segnalazione-parity
- Created: concepts/header-section-component.md (CMS module wiki)
- Created: concepts/header-dynamic-navigation.md (Sixteen theme wiki)
- Created: concepts/design-comuni-font-tokens.md (Sixteen theme wiki)
- Created: docs/wiki/concepts/segnalazione-privacy-design-comuni-parity.md (root wiki)
- Updated: laravel/Themes/Sixteen/resources/views/components/sections/header/v1.blade.php (fix translation error)
- Created: laravel/Themes/Sixteen/resources/views/components/sections/header/partials/nav-primary.blade.php
- Created: laravel/Themes/Sixteen/resources/views/components/sections/header/partials/nav-secondary.blade.php
- Updated: laravel/Modules/Fixcity/lang/it/create_ticket_wizard.php (exact Design Comuni checkbox text)
- Build: npm run build ✓, npm run copy ✓
- Story: .bmad-output/implementation-artifacts/8-106-header-navigation-json-driven.md
- Status: 8-106 → in-progress
## [2026-05-04] header-dynamic-navigation + segnalazione-parity
- Created: concepts/header-section-component.md (CMS module wiki)
- Created: concepts/header-dynamic-navigation.md (Sixteen theme wiki)
- Created: concepts/design-comuni-font-tokens.md (Sixteen theme wiki)
- Created: ../../../docs/wiki/concepts/segnalazione-privacy-design-comuni-parity.md (root wiki)
- Updated: laravel/Themes/Sixteen/resources/views/components/sections/header/v1.blade.php (fix translation error line 211)
- Created: laravel/Themes/Sixteen/resources/views/components/sections/header/partials/nav-primary.blade.php
- Created: laravel/Themes/Sixteen/resources/views/components/sections/header/partials/nav-secondary.blade.php
- Updated: laravel/Modules/Fixcity/lang/it/create_ticket_wizard.php (exact Design Comuni checkbox text)
- Build: npm run build ✓, npm run copy ✓
- Story: .bmad-output/implementation-artifacts/8-106-header-navigation-json-driven.md
- Status: 8-106 → in-progress

## [2026-06-10] Regola blocks/ Flowbite + Tailwind UI
- **Regola on-demand**: `cms-block-naming-tailwind-flowbite.md` (root + Sixteen wiki)
- **Catalogo**: `laravel/Themes/Sixteen/docs/wiki/how-to/blocks-subfolder-catalog.md`
- **SSoT codice**: `Themes\\Sixteen\\Support\\BlockCategoryRegistry`
- **Test**: `BlockSubfolderNamingTest` — valida sottocartelle ammesse
- **Fix runtime**: slug duplicato `home` in `1.json` → `archive-ticket-detail-layout-v1`

## 2026-06-10 — header FO session

- `route('notifications')` al posto di `area-personale.notifiche`
- Troubleshooting: [route-not-found-view-cache.md](troubleshooting/route-not-found-view-cache.md)

## 2026-06-10 — docs Folio consolidate

- `personal-area-routes.md` allineato al canon (deprecato nomi IT)
- `fo-folio-named-routes.md` sezione Imparato
- INDEX Folio FO sezione unificata

## 2026-10-13 — docs organization + wizard segnalazione parity

- **Riorganizzazione docs** (governance: no-rm, .old suffix, DRY):
  `build/` per i 14 doc Vite (con `build/INDEX.md`); `visual-parity/`
  consolidato (`reports/`, `screenshots/pages/` evidenza per pagina,
  `screenshots/legacy-2026-04/`); bug path annidato
  `visual-parity/scripts/docs/visual-parity/scripts/` eliminato;
  indici root duplicati (`INDEX.md`, `DOCUMENTATION_INDEX.md`,
  `00-index.md`) ritirati con `.old`; `00-INDEX.md` e
  `visual-parity/INDEX.md` aggiornati.
- **Nuova regola** `wiki/rules/cms-content-slug-matches-folio-page-name.md`:
  il JSON di contenuto si chiama come lo slug interrogato (= nome pagina
  Folio). Correzione: `/it/tickets/create` usa `tickets.create.json`
  (famiglia app), NON `tests.tickets-create.json` (famiglia anteprima
  parity `tests.<slug>.json`). Registrata anche nel second brain.
- **Wizard segnalazione** (BMAD + second brain): decisione zen
  `bmad/decisions/segnalazione-flow-parity-zen.md`
  (perché Filament: no-controllers; la parity la fa il tema; la mappa
  localizza il danno); storie DCP-32/33/34/35; heading wizard corretto
  in `create-ticket-wizard.blade.php` (era Tailwind `text-4xl
  font-bold text-slate-900`, ora `title-xxxlarge`/`subtitle-small`);
  `callSchemaComponentMethod` verificato presente su `XotBaseSchemaWidget`
  (via `InteractsWithSchemas`): errore Livewire non riproducibile.
- **Swarm**: 3 subagenti in parallelo su DCP-33 (dati+mappa), DCP-34
  (riepilogo infolist→cmp-info-summary), DCP-35 (conferma).

## 2026-10-14 — P6-DOCS-SIXTEEN: registro story, link relativi, blocco ticket-detail e mappa

Fase BMAD: documentazione/qualita'. Pacchetto P6 dello swarm in [DCP-152](../../../../Modules/Geo/docs/stories/dcp-152-marker-dettaglio-ticket-e-pulsanti-mappa-su-pagine-dc.story.md). Solo documentazione, nessun codice.

- **Registro story**: nuovo [bmad/stories/00-REGISTRO-COMPLETO.md](../bmad/stories/00-registro-completo.md). Di 264 file story (`stories/` 37 + `bmad/stories/` 227, `.lock` esclusi) solo 57 erano citati dagli indici; gli altri 207 sono ora elencati con stato e titolo dichiarati nei file, piu' la tabella dei numeri DCP presenti in due famiglie (datata e senza data, 7..26). Collegato da [00-INDEX.md](../00-INDEX.md) e da [bmad/stories/00-INDEX.md](../bmad/stories/00-INDEX.md), con DCP-148 e DCP-152 (owner Geo). Nessuna story toccata, rinominata o rinumerata.
- **Link relativi** (tool `bashscripts/tools/docs/docs-organizer.py`, funzione `fix_links` con `--by-name`, `prompts/` e `raw/` esclusi, file toccati da meno di 5 minuti saltati): link rotti 1500 -> 1373. Corretti 127 link in 62 file, solo dove il bersaglio esiste ed e' l'unico file con quel nome (cartelle `../`, spostamenti in `build/`, `design-comuni/`, prefisso `laravel/Themes/Sixteen/docs/`). Lasciati volutamente 8 candidati ambigui (`./TESTING.md`, quattro `../rules/on-demand-pattern.md` nei `wiki/*/INDEX.md`, tre `argomenti.md`). Restano 1373 link senza bersaglio sul disco (784 in `design-comuni/`, 256 nei file in radice, 200 in `wiki/`): non indovinati.
- **`wiki/coordinate-picker.md`**: aggiunta la tabella degli attributi di `coordinate-picker-lit` (`readonly`, `marker-icon-url`, `marker-color`, `marker-label`, ...) letta da `static properties`; precisato che `searchAddress` e `reverseGeocode` sono metodi PHP lato server (trait `HasCoordinatePicker`) mentre il Lit interroga Nominatim dal browser; segnato `(not verified)` il percorso di override `resources/views/vendor/geo/...` (Sixteen non ha quel file).
- **`wiki/concepts/coordinate-picker-design-comuni-parity-rule.md`**: aggiornamento DCP-152 (pulsanti opachi garantiti da `.map-container .ctrl-btn.ctrl-btn` nel modulo Geo, sola lettura, test Playwright).
- **Dettaglio ticket**: `filament-first-frontoffice.md`, `mcp-validation-fo-pages.md`, `folio-no-semantic-pages-tickets.md` dicevano che `/it/tickets/{id}` e' il Widget `ViewWidget` con CMS `tickets.view`. Verificato: `tickets.view.json` non esiste in `Modules/Fixcity/config/database/content/pages/`; un id numerico e' servito dal ramo `ticket-detail` di `pages/[container0]/[slug0]/index.blade.php` (`BuildPublicTicketDetailAction`, `data-dc`, blocco `ticket-detail/design-comuni`). Aggiunta una nota datata a ciascun doc; il testo precedente resta come storico.
- Non verificato: specificita' CSS e cause del bug pulsanti (da DCP-152, non rimisurate), precedenza Folio tra `Modules/Fixcity/.../pages/tickets/[id].blade.php` e la pagina del tema (dedotta dal test Pest `TicketPublicDetailPageTest`, non da `route:list`), esistenza di `TicketInfolist` fuori da `Filament/Infolists`.
## [2026-10-14] docs | boundary UI consenso legale

- Aggiunta `concepts/legal-consent-ui-boundary.md` per il contratto UI Sixteen.
- Collegati Gdpr/User e le story BMAD relative a consenso, verifica e legal content.
- La pagina non introduce Bootstrap runtime e non attribuisce al tema decisioni legali.
## Logo FixCity — Heroicons Outline (Blu Navy) — 2026-10-18
- Originale: public_html/images/logo-fixcity.svg (complesso, 200+ path, 8KB)
- Nuova versione (non sovrascritta): public_html/images/logo-fixcity-outline.svg (linee sottili, outline, 2.2 stroke-width, 1.1KB, heroicons-style)
- Header (application-logo.blade.php) punta al nuovo SVG con alt descrittivo e max-height 2.5rem.
