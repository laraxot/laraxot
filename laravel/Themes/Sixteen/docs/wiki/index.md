---
title: "Sixteen Theme Wiki Index"
type: index
tags: [sixteen, theme, wiki, index, frontoffice]
created: 2026-04-15
updated: 2026-07-27
qmd: "sixteen theme wiki index frontoffice folio filament parity docs"
issues:
  - "https://github.com/laraxot/theme_sixteen_fila5/issues/54"
discussions:
  - "https://github.com/laraxot/theme_sixteen_fila5/discussions/55"
related:
  - ../../../../docs/wiki/rules/wiki-markdown-frontmatter-mandatory.md
  - ../../../../docs/wiki/concepts/hackernoon-ai-coding-tips-fixcity-map.md
  - ./concepts/ai-harness-theme-sixteen.md
  - ../../docs/wiki/concepts/ai-harness-theme-discipline.md
---

# Sixteen Theme Wiki

Indice operativo minimo del wiki tema `Sixteen`.

## Scopo

Il tema accumula solo conoscenza locale di presentazione, parity visuale, asset pipeline e boundary tema/modulo.
Le regole generiche di context compression restano nel wiki root e nel modulo AI; qui si documenta solo l'impatto sul corpus del tema. **DB/modelli**: parità migrate/factory/seeder nei moduli — [architecture-module-model-artifact-parity.md](../../../../docs/wiki/bmad/architecture-module-model-artifact-parity.md). **Ogni `.md` nuovo**: [frontmatter + GitHub](../../../../docs/wiki/rules/wiki-markdown-frontmatter-mandatory.md).

**Stack frontoffice (pub)**: Tailwind + [DaisyUI docs](https://daisyui.com/docs/) + Alpine + Lit (Geo) + Filament v5 dove applicabile; **alias stile preferiti con `@apply`** nel CSS tema: [bootstrap-italia-tailwind-philosophy](./concepts/bootstrap-italia-tailwind-philosophy.md); nomi classe Design Comuni / HTML semantico: [design-comuni-class-mapping](./entities/design-comuni-class-mapping.md).

## AI / second brain (root)

- [hackernoon-ai-coding-tips-fixcity-map](../../../../docs/wiki/concepts/hackernoon-ai-coding-tips-fixcity-map.md)
- [ai-harness-theme-sixteen](./concepts/ai-harness-theme-sixteen.md) — harness locale tema
- [second-brain-theme-boundary](./concepts/second-brain-theme-boundary.md) — confine memoria tema
- [llm-wiki prompt](../../../../bashscripts/tools/prompts/llm-wiki.txt) — router agente
- [bmad/architecture](../../../../docs/wiki/bmad/architecture.md)

## Gestionale / moduli (2026-07-27)

Menu admin Filament **non** dipende dal tema — dipende da `config/local/workorder/modules_statuses.json`:

- [tenant-modules-navigation-discipline.md](../../docs/tenant-modules-navigation-discipline.md) — hub Themes
- [runtime-config-religion-hub.md](../../docs/shared-components/runtime-config-religion-hub.md) — permission + config.php + statuses + Dashboard
- [module-dashboard-page-mandatory.md](../../../Modules/Xot/docs/wiki/concepts/module-dashboard-page-mandatory.md) — landing `{modulo}/admin`
- [module-admin-panel-provider-mandatory.md](../../../Modules/Xot/docs/wiki/concepts/module-admin-panel-provider-mandatory.md) — panel `{modulo}/admin`
- [module-filament-panel-triad.md](../../../Modules/Xot/docs/wiki/concepts/module-filament-panel-triad.md) — trinità config + provider + dashboard
- [tenant-module-status-registry](../../../Modules/Tenant/docs/tenant-module-status-registry.md) — canon Tenant

## Testing / PHPStan (2026-06-13)

- [theme-component-test-contract](./concepts/theme-component-test-contract.md) — test UI module → path componenti Sixteen
- [completion-roadmap](./overviews/completion-roadmap.md) — priorità chiusura tema FO
- [phpstan-compliance](./concepts/phpstan-compliance.md) — stato + confine neon `Modules/` only

## Header / auth slim (HTML + visual parity — religione permanente)
- [design-comuni-header-parity](../../../../docs/wiki/rules/design-comuni-header-parity.md) — rule root
- [header-html-visual-parity-rule](../../../../docs/wiki/memories/header-html-visual-parity-rule.md) — memoria
- [header-design-comuni-parity-skill](../../../../docs/wiki/skills/header-design-comuni-parity.md) — workflow agente
- [header-logged-in-parity-delta](./concepts/header-logged-in-parity-delta.md) — delta screenshot STORY-147
- [header-authenticated-state](./concepts/header-authenticated-state.md) — regola guest/auth
- [header-html-parity](../design-comuni/analysis/header-html-parity.md) — cmp-header.hbs vs v1
- UX: [STORY-147-ux-design](../../../../docs/stories/STORY-147-ux-design-header-logged-in.md) §11–13

## Folio / Volt / CMS

- [folio-page-pattern.md](../folio-page-pattern.md) — mount + x-page; lista = `container0.index` (Filament way)
- Root: [folio-container0-index-filament-way.md](../../../../docs/wiki/memories/folio-container0-index-filament-way.md)
- [folio-route-params-mount.md](./concepts/folio-route-params-mount.md) — no request()->route()
- [volt-usage-rule.md](./rules/volt-usage-rule.md) — @volt statico = name()
- Cms: [folio-volt-static-mount-contract](../../../Modules/Cms/docs/wiki/concepts/folio-volt-static-mount-contract.md)

## Folio / Volt (container0 → container1)
- [folio-page-pattern](../folio-page-pattern.md) — `mount()` + `<x-page>` + tabella `@volt` statico
- [folio-route-params-mount](./concepts/folio-route-params-mount.md) — no `request()->route()`, no `@volt($pageSlug)`
- [volt-usage-rule](./rules/volt-usage-rule.md)

## Pagine
- [geo-map-lit-reconstruction-guide.md](../../../Modules/Geo/docs/wiki/concepts/geo-map-lit-reconstruction-guide.md) — ricostruire mappa `/it` da documentazione
- [ticket-list-map-integration.md](./concepts/ticket-list-map-integration.md) — blade, filtri, contratto JSON
- [geo-map-marker-civic-pin-theme-boundary.md](./concepts/geo-map-marker-civic-pin-theme-boundary.md) — override CSS marker
- [geo-map-popup-leaflet-boundary.md](./concepts/geo-map-popup-leaflet-boundary.md) — popup + conflitti CSS tema
- [geo-map-lit-reconstruction-guide.md](../../../Modules/Geo/docs/wiki/concepts/geo-map-lit-reconstruction-guide.md) — ricostruzione marker + popup (SSoT modulo Geo)
- [geo-map-fixes-registry.md](../../../Modules/Geo/docs/wiki/concepts/geo-map-fixes-registry.md) — tabella correzioni INC-1…8
- [global-header-css-leak-leaflet-popup.md](./troubleshooting/global-header-css-leak-leaflet-popup.md) — leak CSS header su popup mappa
- [geo-map-marker-civic-pin-theme-boundary.md](./concepts/geo-map-marker-civic-pin-theme-boundary.md) — confine CSS marker FO
- [geo-map-popup-leaflet-boundary.md](./concepts/geo-map-popup-leaflet-boundary.md) — confine popup + fix header parity
- [marker-cluster-hover-stability.md](./concepts/marker-cluster-hover-stability.md) — STORY-123 cluster hover
- [map-lit-vite-build-troubleshooting.md](./concepts/map-lit-vite-build-troubleshooting.md) — STORY-121 bundle 404
- [map-lit-legend-theme-boundary.md](./concepts/map-lit-legend-theme-boundary.md) — STORY-094 confine tema/Geo
- [map-lit-cluster-type-icons](../../../../../Modules/Geo/docs/wiki/concepts/map-lit-cluster-type-icons.md) — sizing pallini cluster
- [ui-ai-tooling-on-demand.md](./concepts/ui-ai-tooling-on-demand.md) — STORY-126 strumenti UI/AI e MCP
- [frontend-design-fixcity-overlay.md](./concepts/frontend-design-fixcity-overlay.md) — skill Anthropic frontend-design adattata PA/Fixcity

- [map-lit-ticket-api](../concepts/map-lit-ticket-api.md) — consumer API Folio `/api/tickets/geojson` e ticket-details (STORY-052/069)
- [no-italian-component-names](./rules/no-italian-component-names.md) — **REGOLA CRITICA**: nomi file in inglese (CSS, Blade, JS, PHP, JSON)
- [css-filename-english-naming](../architecture/css-filename-english-naming.md) — slug `civic-design-*`, `ticket-parity` (no italiano nel path file)

- [fo-pa-tokens-uniformity](../architecture/fo-pa-tokens-uniformity.md) — token PA + `.fo-filament-form-shell`; no hex per `data-page`
- [auth-login-ux-design-wcag](./design/auth-login-ux-design-wcag.md) — login/register WCAG + Filament primary
- [filament-first-frontoffice](./concepts/filament-first-frontoffice.md) — Filament Blade su FO (`x-filament::tabs` su `/it`, skin Design Comuni)
- [ticket-list-filament-tabs](./concepts/ticket-list-filament-tabs.md) — tab Mappa/Elenco STORY-065
- [wizard-parity-documentation-map](./concepts/wizard-parity-documentation-map.md) — ordine lettura parity segnalazione (dry tema)
- [bootstrap-italia-tailwind-philosophy](./concepts/bootstrap-italia-tailwind-philosophy.md) — `@apply` come alias, HTML semantico
- [daisyui-pro-contro-metriche](./concepts/daisyui-pro-contro-metriche.md) — sintesi tema; SSoT in modulo Cms
- [context-compression-plugin](./concepts/context-compression-plugin.md)
- [design-comuni-class-mapping](./entities/design-comuni-class-mapping.md) — Tailwind, DaisyUI, parity PA, Filament
- [ridondanze-documentazione-wizard](./concepts/ridondanze-documentazione-wizard.md) — quando conviene fusionare slice doc wizard vs tenerle separate

## Collegamenti root

- [context-mode-integration](../../../../../docs/wiki/concepts/context-mode-integration.md)
- [context-compression-plugin-openrouter](../../../../../docs/wiki/concepts/context-compression-plugin-openrouter.md)
- [trigger-map](../../../../../docs/wiki/rules/00-TRIGGER_MAP.md)
